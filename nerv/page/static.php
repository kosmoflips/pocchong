<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/synapse.php');
require_once(NERV.'/lib_static.php');

$p=new PocPage;
$file=ROOT.POC_DB['STATIC']['dir'].'/'.$_GET['file'];

include ($file);
echo '</article>',"\n";
$p->html_close();
?>
