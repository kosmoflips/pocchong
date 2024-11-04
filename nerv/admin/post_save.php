<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/synapse.php');

chklogin(1);
$k=new PocDB();

if (isset($_POST['opt'])) { // delete, jump to public link, preview , edit/insert new
	if ($_POST['opt'] == 'DELETE selected entries') { // delete whole record, from list page
		if (isset($_POST['del_id'])) {
			foreach ($_POST['del_id'] as $id) {
				_del_post($k,$id);
			}
		}
		jump(POC_DB_POST['admin_list']);
	}
	elseif ($_POST['opt'] == 'DELETE entry') { // delete whole record, from single work page
		_del_post($k,$_POST['entry']['id']);
		jump(POC_DB_POST['admin_list']);
	}
	elseif ($_POST['opt'] == 'Preview') {
		include(NERV.'/admin/post_preview.php');
		exit;
	}
}

$entry=$_POST['entry'];
$key1=array(); # update
$key2=array(); # insert
$val1=array();
$ph1=array();
$id1=$entry['id'];
unset($entry['id']);
foreach ($entry as $k1=>$v1) {
	$key1[]=sprintf('%s=?', $k1);
	$key2[]=$k1;
	$ph1[]='?';
	$val1[]=trim($v1);
}
$ph1[]='?'; # id
$val1[]=$id1; # id
if (isset($_POST['update'])) { // update existing record
	$stat=sprintf ('UPDATE post SET %s WHERE id=?', implode(',', $key1));
}
else { // insert new record
	$key2[]='id';
	$stat=sprintf ('INSERT INTO post (%s) VALUES(%s)',
		implode(',', $key2), implode(',', $ph1));
}
$k->dosql($stat, $val1);

jump(POC_DB_POST['edit'].'?id='.$id1);


// ------- subs ------------
function _del_post($k=null,$id=0) {
	// $linked=$k->getOne('SELECT id FROM mygirls WHERE post_id=?',array($id));
	// if (!empty($linked)) {
		// $k->dosql('UPDATE mygirls SET post_id=? where id=?',array(null,$linked));
	// }
	$k->dosql('DELETE FROM post WHERE id=?', array($id));
}


?>
