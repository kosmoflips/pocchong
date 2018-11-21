#!/usr/bin/perlml

use strict;
use warnings;

use File::Temp;
use File::Path;
use File::Copy qw/:DEFAULT move copy/;
use File::Spec;
use Data::Dumper;
use Storable qw/:DEFAULT dclone/;

use lib $ENV{DOCUMENT_ROOT}.'/cgi-bin/';
use Method_Kiyoism_Plus;
my $k=Method_Kiyoism_Plus->new;

$k->chklogin(1);

my $p=$k->param;

my $TMP=$Method_Kiyoism_Plus::POCCHONG->{tmpdir};

if ($p->{dump}) {
	my $cpfile=sqldump();
	return undef if !$cpfile;
	my $zippath=$TMP.'/sqlite_'.time;
	my $outfile;
	if ($outfile=zipfiles($zippath,[$cpfile])) {
		unlink $cpfile;
	} else {
		$outfile=$cpfile;
	}
	downloadfile2($outfile,$k);
}
#old MySql
=pod
if ($p->{dump}) {
	my $outfile;
	if (my $dump=sqldump()) {
		my $zippath=$TMP.'/sqldump_'.time;
		if ($outfile=zipfiles($zippath,[$dump])) {
			unlink $dump;
		} else {
			$outfile=$dump;
		}
		downloadfile($outfile,$k);
	}
}
=cut
elsif ($p->{backup}) {
	#sql dump + entire root dir w/o [tmp] [backyard]
	my $bkup=$ENV{DOCUMENT_ROOT};
	my $outdir='/home2/ghostelf/public_html/squimp/kiyoko/pocchong';
	#i dont know what will happen if iutput file is in a dir to be zipped
	mkpath $outdir if !-d $outdir;
	#dump sql
	my $fh = File::Temp->new(TEMPLATE=>'sqlite_XXXXX',SUFFIX=>'.sqlite', DIR=>$bkup);
	my $tmpdump = $fh->filename;
	close ($fh);
	move (sqldump(),$tmpdump);

	my $fname='pocchong.sqlite.'.time;
	my $outfile=File::Spec->catfile($outdir,$fname);
	my $exclude='*pocchong/tmp*';
	my @exclude=('*pocchong/tmp*');
	my $zipped=zipfiles($outfile,[$bkup],$outdir,\@exclude);
	my $zipped2=File::Spec->catfile($TMP,$fname.'.tgz');
	move ($zipped, $zipped2);
	if (-e $zipped2 and !-z $zipped2) {
		downloadfile2($zipped2,$k);
	}
}
elsif ($p->{cleandir}) {
	use File::Path qw/rmtree/;
	rmtree($TMP,{keep_root=>1});
	rmtree('/home2/ghostelf/public_html/squimp/kiyoko/pocchong',{keep_root=>1});
}

$k->redirect('/a/');

sub downloadfile2 {
	my ($fpath,$k)=@_;
	$k->header();
	my @cc=split /\//, $fpath;
	my $f=0;
	my $path='';
	foreach my $d (@cc) {
	# printf "%s , f=%s<br />", $d,$f;
		if ($d eq 'pocchong' and !$f) {
			$f=1;
		}
		elsif ($f) {
			$path.='/'.$d;
		}
	# printf "%s<br />", $path;
	}
	printf '<a href="%s">%s</a>',$path,$cc[-1];
	exit;
}
sub downloadfile { #specify file(s), gzip them, and send to download popup
	my ($fpath,$k)=@_;
	return undef if (!-e $fpath or !-r $fpath);
	my @t=File::Spec->splitpath($fpath);
	my $fname=pop @t;
	print $k->header(
		{'-Type' => "application/x-download",
		'-Content-Disposition'=>"attachment; filename=$fname"}
	);
	open my $fh, "<", $fpath or die "can't process to download";
	binmode $fh;
	local $/ = \10240; ## 10 k blocks <??????
	while (<$fh>){ print $_; }
	close ($fh);
	return 1;
}
sub zipfiles { #requires tar and gzip on system
#ref: http://ss64.com/bash/tar.html
# tar -zcvf compressFileName folderToCompress
	my ($outfile,$files,$chdir,$exclude)=@_; #self, string (has no ext), A REF
	$chdir=$TMP if (!$chdir or !-d $chdir);
	chdir $chdir;
	my (@cmd,$prog);
	$outfile .= '.tgz';
	@cmd=(
		'/bin/tar',
		'-zcvf', $outfile,
		'-C',$chdir,
	);
	if ($exclude) {
		push @cmd, '--exclude',$_ foreach (@$exclude);
	}
	foreach my $f (@$files) {
		if (-e $f) {
			my $f2=File::Spec->abs2rel($f,$chdir);
			push @cmd, $f2;
		}
	}
	push @cmd,'>',$TMP.'/_dummy.txt';
	my $stat;
	eval { $stat=system("@cmd"); };
	if (!$@ and $stat==0) { return $outfile; }
	else { return 0; }
}
sub sqldump {
	my $cpfile=File::Spec->catfile($TMP,'sqlite_'.time);
	copy ($Method_Kiyoism_Plus::POCCHONG->{dbfile}, $cpfile);
	if (-e $cpfile) {
		return $cpfile;
	} else {
		return undef;
	}
}
# old
=pod
sub sqldump { #return full path of dumped file
	my $prog='/usr/bin/mysqldump';
	my $info={
		db=>"ghostelf_pocchong",
		usr=>"ghostelf_kiyo",
		pw=>"q[/q[bezf7huystdues", #this guy is hopeless, must do sth for him quick
		host=>"localhost"
	};
	my $fname='sqldump_'.time.'.sql';
	my $outfile=File::Spec->catfile($TMP,$fname);
	my @cmd;
	push @cmd,
		# $prog,
		# "--host=".$info->{host},
		# "-u", $info->{usr},
		# "-p".$info->{pw},
		# $info->{db},
		# ">", $outfile;
		$prog,
		"--default-character-set=utf8",
		"--host=".$info->{host},
		"-u", $info->{usr},
		"-p".$info->{pw},
		$info->{db},
		"-r", $outfile;
	my $out;
	eval { $out=system("@cmd"); };
	if (!$@ and $out==0) {
		return $outfile;
	} else {
		return 0;
	}
}
=cut