#!/usr/bin/perlml
BEGIN { unshift @INC, $ENV{DOCUMENT_ROOT}.'/cgi-bin/nerv'; }
use strict;
use warnings;
use Method_Kiyoism;
use Method_Config;

my $k=Method_Kiyoism->new;
$k->chklogin(1);

my $p=$k->get_param;
#1 -> rss rebuild
#2 -> dump sql
#3 -> backup everything (sql dump + elem + cgi/tmpl)
#444 -> clean up dir [tmp] << everything

if (!$p->{sel}) {
#do nothing
}
elsif ($p->{sel}==1) {
	$k->rss_rebuild;
	$k->rss_write;
}
elsif ($p->{sel}==2) {
	my $outfile;
	if (my $dump=$k->sqldump) {
		my $zippath=$Method_Config::PATH->{TMP}.'/sqldump_'.time;
		if ($outfile=$k->zipfiles($zippath,[$dump])) {
			unlink $dump;
		} else {
			$outfile=$dump;
		}
		$k->downloadfile($outfile,1);
	} else {
		$k->peek('failed to dump sql, go back and check your code.');
	}
	exit;
}
elsif ($p->{sel}==3) {
	#sql dump + entire root dir w/o [tmp] [backyard]
	use File::Spec;
	use File::Temp;
	use File::Copy qw/move/;

	my $bkup=$Method_Config::ROOT;
	my $outdir=$Method_Config::PATH->{REMOTE};
	#i dont know what will happen if iutput file is in a dir to be zipped
	mkpath $outdir if !-d $outdir;
	#dump sql
	my $fh = File::Temp->new(TEMPLATE=>'sqldump_XXXXX',SUFFIX=>'.sql', DIR=>$bkup);
	my $tmpdump = $fh->filename;
	close ($fh);
	move ($k->sqldump,$tmpdump);

	my $outfile=File::Spec->catfile($outdir,'pocchong_'.time);
	my $exclude='*pocchong/tmp*';
	my @exclude=('*pocchong/tmp*', '*backyard/*');
	my $zipped=$k->zipfiles($outfile,[$bkup],$outdir,\@exclude);

	if (-e $zipped and !-z $zipped) {
		$k->downloadfile($zipped,1);
	} else {
		$k->peek('failed to backup site, go back and check your code.');
	}
	exit;
}
elsif ($p->{sel}==444) {
	use File::Path qw/rmtree/;
	rmtree($Method_Config::PATH->{TMP},{keep_root=>1});
	rmtree($Method_Config::PATH->{REMOTE},{keep_root=>1});
}
$k->redirect('/a/admin.cgi');
