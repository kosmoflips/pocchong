<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/'.'Method_Kiyoism_Remaster.php');

chklogin(1);
$k=new PocDB;

$TMP=POCCHONG['ADMIN']['tmpdir'];
if (!file_exists($TMP)) {
    mkdir($TMP, 0777, true); // Note that 0777 is already the default mode for directories and may still be modified by the current umask.
}

if (isset($_GET['dump'])) { // dump sql data to file. as sqlite, copy is enough
	$fname='pocchong__backup'.time().'.sqlite';
	$newfile=$_SERVER['DOCUMENT_ROOT'].$TMP.'/'.$fname;
	if (copy(POCCHONG['PATH']['DB'], $newfile)) {
		$zipfile=$newfile.'.tgz';
		$zipfilename=$fname.'.tgz';
		zipfile($newfile,$zipfile);
		if (file_exists($zipfile)) {
			$dfile=$zipfile;
			$dname=$zipfilename;
		} else {
			$dfile=$newfile;
			$dname=$fname;
		}
		download($dfile,$dname);
		exit;
	}
	echo "no file(s) copied/zipped, check source code and try again.\n";
	exit;
}
/*
elseif ($_GET['backup']) {
	$fname='pocchong_site__backup'.time().'tgz';
	$ofile=$_SERVER['DOCUMENT_ROOT'].$TMP.'/'.$fname;
	zipfile($_SERVER['DOCUMENT_ROOT'],$ofile);
	download($ofile,$fname);
}
*/
elseif ($_GET['cleandir']) {
	$pexe=$_SERVER['DOCUMENT_ROOT'].'/cgi-bin/admin/sub_cleandir.pl';
	doperl($pexe, $_SERVER['DOCUMENT_ROOT'].$TMP );
	echo 'dir ',$TMP,' is empty now.';
	exit;
}

header("Location: /a");

// ---------------------- subs --------------------
function download($file=null, $filename=null) {
	if (file_exists($file) and isset($filename)) {
		global $TMP;
		printf ('<a href="%s/%s">download file: %s</a>', 
			$TMP, $filename, $filename);
	} else {
		echo 'no file specified to be downloaded';
	}
	exit;
}
function zipfile($infile=null,$outfile=null) { #requires tar and gzip on system
#ref: http://ss64.com/bash/tar.html
# tar -zcvf compressFileName folderToCompress
	if (!$infile or !$outfile or !file_exists($infile)) {
		return 0;
	}
	if (file_exists($outfile)) {
		unlink($outfile);
	}
	$pexe=$_SERVER['DOCUMENT_ROOT'].'cgi-bin/admin/sub_zipfile.pl';
	doperl($pexe, sprintf ('%s %s', $infile, $outfile) );
	if (file_exists($outfile)) {
		return 1;
	}
	return 0;
}
function doperl($perl='',$args='') { // give args as a plain string "-t xxx yyy ..."
	if (!$perl or !file_exists($perl)) { return 0; }
	$cmd=sprintf ('perl %s %s', $perl, $args);
	exec($cmd);
	1;
}

?>