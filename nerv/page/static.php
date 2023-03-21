<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/synapse.php');
require_once(NERV.'/lib_static.php');

$p=new PocPage;
if (array_key_exists('private', $_GET)) {
	$fdir=ROOT.POC_DB['STATIC']['dir2'];
} else {
	$fdir=ROOT.POC_DB['STATIC']['dir'];
}
$file=$fdir.'/'.$_GET['file'];
if (file_exists($file)) {
	include ($file);
} else {
	show_response(404);
}
echo '</article>',"\n";
$p->html_close();
?>
