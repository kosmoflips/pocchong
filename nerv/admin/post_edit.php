<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/synapse.php');
chklogin(1);
$k=new PocDB();

$edit=$k->getRow('SELECT * FROM post WHERE id=?', array($_GET['id']??0));

if (empty($edit)) {
	jump(POC_DB_POST['admin_list']);
	exit;
}

$edit['update']=1;
$edit['content']=htmlentities($edit['content']);

include(NERV.'/admin/incl_posteditor.php');
?>
