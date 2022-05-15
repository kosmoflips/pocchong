<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/synapse.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/lib_static.php');

$p=new PocPage;
$file=$_SERVER['DOCUMENT_ROOT'].POC_DB['STATIC']['dir'].'/'.$_GET['file'];

include ($file);
echo '</article>',"\n";
$p->html_close();
?>
