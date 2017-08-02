#!/usr/bin/perlml

use strict;
use warnings;

use File::Temp;
use File::Path;
use File::Copy qw/:DEFAULT move/;
use File::Spec;
use Data::Dumper;
use Storable qw/:DEFAULT dclone/;

use Method_Kiyoism_Plus;
my $k=Method_Kiyoism_Plus->new;

$k->chklogin(1);

my $p=$k->param;

my $TMP=$Method_Kiyoism_Plus::POCCHONG->{tmpdir};

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
elsif ($p->{backup}) {
	#sql dump + entire root dir w/o [tmp] [backyard]
	my $bkup=$ENV{DOCUMENT_ROOT};
	my $outdir='/home2/ghostelf/public_html/squimp/kiyoko/pocchong';
	#i dont know what will happen if iutput file is in a dir to be zipped
	mkpath $outdir if !-d $outdir;
	#dump sql
	my $fh = File::Temp->new(TEMPLATE=>'sqldump_XXXXX',SUFFIX=>'.sql', DIR=>$bkup);
	my $tmpdump = $fh->filename;
	close ($fh);
	move (sqldump(),$tmpdump);

	my $outfile=File::Spec->catfile($outdir,'pocchong_'.time);
	my $exclude='*pocchong/tmp*';
	my @exclude=('*pocchong/tmp*');
	my $zipped=zipfiles($outfile,[$bkup],$outdir,\@exclude);

	if (-e $zipped and !-z $zipped) {
		downloadfile($zipped,$k);
	}
}
elsif ($p->{cleandir}) {
	use File::Path qw/rmtree/;
	rmtree($TMP,{keep_root=>1});
	rmtree('/home2/ghostelf/public_html/squimp/kiyoko/pocchong',{keep_root=>1});
}

$k->redirect('/a/');

sub downloadfile { #specify file(s), gzip them, and send to download popup
	my ($fpath,$CGI)=@_;
	return undef if (!-e $fpath or !-r $fpath);
	my @t=File::Spec->splitpath($fpath);
	my $fname=pop @t;
	print $CGI->header(
		'-Type' => "application/x-download",
		'-Content-Disposition'=>"attachment; filename=\"$fname\""
	);
	open my $fh, "<", $fpath or die "can't process to download";
	binmode $fh;
	local $/ = \10240; ## 10 k blocks <??????
	while (<$fh>){ print $_; }
	close ($fh);
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

