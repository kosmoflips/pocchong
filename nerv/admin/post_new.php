<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/synapse.php');

// ------------ data process --------------
chklogin(1);
$k=new PocDB();

$edit=$k->prepNew('post');
$edit['insert']=1;
$edit['id']=$k->nextID('post');
$edit['epoch']=time();
$edit['year']=date('Y', $edit['epoch'])-2000;
$edit['gmt']=POC_META['default_gmt'];

include(NERV.'/admin/incl_posteditor.php');

?>
