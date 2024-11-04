<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/synapse.php');
chklogin(1);
$k=new PocDB();

$info=$k->getRow('SELECT * FROM mygirls WHERE id=?',array($_GET['id']??0));

if (empty($info)) { // given id is false , redirect to list
	jump(POC_DB_MG['admin_list']);
	exit;
}

$info['update']=1;

# current tags
$tags=$k->getAll('SELECT tag_id FROM mygirls_link WHERE title_id=?',array($info['id']));
foreach ($tags as $tagid) {
	$info['tags'][]=$tagid['tag_id'];
}

# all pieces
$sth=$k->getAll('SELECT * FROM mygirls_pcs WHERE title_id=?',[$_GET['id']]);
foreach ($sth as $r) { // loop through each [pcs]. set up pic preview url and label representative pcs
	$url='';
	$url_preview='';
	if (!empty($r['img_url'])) {
		$r['img_url']=cleanimgurl($r['img_url']);
		$r['url_preview']=mk_mg_img_url($r['img_url']);
	} else {
		$r['url_preview']='';
	}
	if (isset($info['rep_id']) and $info['rep_id']==$r['id']) {
		$r['is_rep']=1;
	}
	// all done, push this pcs into info
	$info['pcs'][]=$r;
}

include(NERV.'/admin/incl_mygirlseditor.php');

?>