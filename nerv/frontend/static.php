<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/synapse.php');

$p=new PocPage;
if (array_key_exists('private', $_GET)) {
	$fdir=$_SERVER['DOCUMENT_ROOT'].POC_DB_STATIC['dir2'];
} else {
	$fdir=$_SERVER['DOCUMENT_ROOT'].POC_DB_STATIC['dir'];
}
$file=$fdir.'/'.$_GET['file'];
if (file_exists($file)) {
	include ($file);
} else {
	show_response(404);
}
?>
</article>
<?php
$p->html_close();
?>
