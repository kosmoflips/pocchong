<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/synapse.php');

chklogin(1);
$k=new PocDB();

$editedurl=POC_DB_MG['edit'].'?id='.($_POST['main']['id']??0);

// ---- delete -----
if (isset($_POST['opt'])) {
	if ($_POST['opt'] == 'DELETE selected entries') { // delete whole record, from list page
		if (isset($_POST['del_id'])) {
			foreach ($_POST['del_id'] as $id) {
				_del_mygirls($k,$id);
			}
		}
		jump(POC_DB_MG['admin_list']);
	}
	elseif ($_POST['opt'] == 'DELETE entire entry') { // delete whole record, from single work page
		_del_mygirls($k,$_POST['main']['id']);
		jump(POC_DB_MG['admin_list']);
	}
	elseif ($_POST['opt'] == 'DELETE selected pieces') { // delete some pieces
		if (isset ($_POST['DEL_pcs'])) {
			foreach ($_POST['DEL_pcs'] as $pid) {
				$k->dosql('DELETE FROM mygirls_pcs WHERE id=?',array($pid));
			}
		}
		jump($editedurl);
	}
}

$main=isset($_POST['main'])?$_POST['main']:null;
$tags=isset($_POST['tags'])?$_POST['tags']:array();
$pcs=isset($_POST['pcs'])?$_POST['pcs']:array();

// ------------ main block ----------------
$id1=$main['id'];
unset($main['id']);
$key1=array(); # for insert
$key2=array(); # for update
$val1=array();
$ph1=array();
foreach ($main as $akey=>$aval) {
	$key1[]=$akey;
	$key2[]=sprintf ('%s=?',$akey);
	$val1[]=trim($aval);
	$ph1[]='?';
}
# put id at last
$val1[]=$id1;
$ph1[]='?';
if (empty($_POST['update'])) { // insert new
	$key1[]='id';
	// INSERT INTO mygirls (id,title,epoch) VALUES(?,?,?)';
	$stat=sprintf ("INSERT INTO mygirls (%s) VALUES (%s)",
		implode(',',$key1), implode(',',$ph1) );
} else { // update
	// UPDATE mygirls SET title=?,etc=? WHERE id=?';
	$stat=sprintf ("UPDATE mygirls SET %s WHERE id=?",
		implode(',',$key2) );
}
$k->dosql($stat,$val1);


// -------------- tags -----------------
$oldtags0=$k->getAll('SELECT id,tag_id from mygirls_link where title_id=?',array($id1)); // all current tags
$rm_tags=array();
$add_tags=array();
if (!empty($oldtags0)) { # compare and remove old tags if exist
	$oldtags=array();
	# existing tags
	foreach ($oldtags0 as $t0) {
		$oldtags[$t0['tag_id']]=$t0['id'];
	}
	foreach ($tags as $t1) { # cmp new set vs old set
		if (!array_key_exists($t1, $oldtags)) { # tag in new-set, not in old-set. add it
			$add_tags[]=$t1;
		}
	}
	foreach ($oldtags as $t2=>$tableid) { # cmp old vs new
		if (!in_array($t2, $tags)) { # tag in old but not in new. rm it
			$rm_tags[]=$tableid;
		}
	}
}
else { # no saved tags, add all
	$add_tags=$tags;
}

if (!empty($rm_tags)) {
	foreach ($rm_tags as $rmtableid) {
		$k->dosql('DELETE FROM mygirls_link WHERE id=?', array($rmtableid));
	}
}
if (!empty($add_tags)) {
	foreach ($add_tags as $add_tag_id) {
		$lastid=$k->nextID('mygirls_link');
		$k->dosql('INSERT INTO mygirls_link ("id","title_id","tag_id") VALUES(?,?,?)', array($lastid,$id1,$add_tag_id));
	}
}



// ----------- pieces ---------------

# current rep_id if set
$curr_rep=$k->getOne('SELECT rep_id FROM mygirls WHERE id=?', array($id1));
$new_rep=$_POST['set_rep_id']??0;

foreach ($pcs as $pc1) {
	if (empty($pc1['img_url'])) {
		continue;
	}
	$pid=$pc1['id'];
	$new=0;
	if (preg_match('/new(\d+)/i',$pc1['id'],$tmp)) {
		$new=1;
	}
	// update existing pcs
	$set_key1=array(); # insert
	$set_key2=array(); # update
	$set_val=array();
	$set_ph=array();
	$pid1=$pc1['id'];
	unset($pc1['id']);
	foreach ($pc1 as $key1=>$val1) {
		$set_key1[]=sprintf ('"%s"', $key1);
		$set_key2[]=sprintf ('%s=?', $key1);
		$tmp1=trim($val1);
		if ($key1=='img_url') {
			$tmp1=cleanimgurl($tmp1);
		}
		$set_val[]=$tmp1;
		$set_ph[]='?';
	}
	$set_ph[]='?';
	if (!$new) { # update
		$set_val[]=$pid1;
		$stat=sprintf ('UPDATE mygirls_pcs SET %s WHERE id=?',
			implode(',', $set_key2) );
	} else { // insert new pcs
		# piece id
		$set_key1[]='id';
		$newid=$k->nextID('mygirls_pcs');
		$set_val[]=$newid;
		# title_id
		$set_key1[]='title_id';
		$set_val[]=$id1;
		$set_ph[]='?';
		$stat=sprintf ('INSERT INTO mygirls_pcs (%s) VALUES(%s)',
			implode(',', $set_key1), implode(',', $set_ph) );
		if ($new_rep == $pid1) { // this newly inserted pc will be used as rep
			$new_rep=$newid;
		}
	}

	$k->dosql($stat,$set_val);
}

// -- update rep_id ---
if ( $new_rep != $curr_rep ) {
	$k->dosql('UPDATE mygirls SET rep_id=? where id=?', array($new_rep,$id1));
}

jump($editedurl);

// ----------------- subs ---------------------
function _del_mygirls($k=null,$id=0) { // delete entire entry
	$k->dosql('UPDATE mygirls SET rep_id=? where id=?',array(null,$id)); // avoid interal relationship
	$k->dosql('DELETE FROM mygirls_link WHERE title_id=?',array($id));
	$k->dosql('DELETE FROM mygirls_pcs WHERE title_id=?',array($id));
	$k->dosql('DELETE FROM mygirls WHERE id=?',array($id));
}
?>