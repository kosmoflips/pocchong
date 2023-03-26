<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/synapse.php');
$DATA_IN=$_POST;

// ------------ data process --------------
chklogin(1);
$k=new PocDB();
$redirectlist='/a/list_table?sel='.POC_DB_POST['table'];

#dst=3: entries deleted in list mode / 2: pieces deleted in page-edit mode / 1: entry updated

if (isset($_GET['dst'])) {
	if ($_GET['dst']==1) {
		print_system_msg('entry updated');
	}
}

$usrsubmit=0;
$showedit=0;
// make sure blocks for each purpose properly 'exit' or 'jump'
if (isset($DATA_IN['opt'])) { // delete, jump to public link, preview , edit/insert new
	if ($DATA_IN['opt'] == 'DELETE') {
		if (isset($DATA_IN['del_id'])) {
			foreach ($DATA_IN['del_id'] as $id) {
				_del_post($k,$id);
			}
		}
		if (isset($DATA_IN['id'])) {
			_del_post($k,$DATA_IN['id']);
		}
		$redirectlist.='&dst=3';
		jump($redirectlist);
	}
	elseif ($DATA_IN['opt'] == 'View') {
		$redirect=sprintf ('%s?id=%s', POC_DB_POST['url'], $DATA_IN['id']);
		jump($redirect);
	}
	elseif ($DATA_IN['opt'] == 'Preview') { // -------- print_post_entry. copied from page_post.php  . may not be synced. purpose is to preview css/style etc.
		$PAGE=new PocPage;
		$PAGE->title=$DATA_IN['title'];
		// $PAGE['head-extra']=array($DATA_IN['extra']);
		$PAGE->html_open();
		write_preview_sash();
		echo '<article>',"\n";
		// timestamp line
		printf ('<div class="datetime">%s</div>%s', clock27( time(),4,-7,0), "\n");
		// title as <h3>
		printf ('<h3>* %s *</h3>%s', (isset($DATA_IN['title'])? $DATA_IN['title'] : 'No Title'), "\n");
		echo (isset($DATA_IN['content'])?$DATA_IN['content']:''),"\n";
		echo '</article>',"\n";
		$PAGE->html_close();
		exit;
	}
	elseif ($DATA_IN['opt'] == 'Save') {
		$usrsubmit=1;
	}
	else { // none of above go back to post-list
		jump($redirectlist);
	}
}
elseif (isset($_GET['new']) or isset($_GET['id'])) { // edit post page
	$edit=array();
	if (isset($_GET['id'])) { // edit existing post
		$edit=$k->getRow('SELECT * FROM '.POC_DB_POST['table'].' WHERE id=?', array($_GET['id']));
		if (isset($edit)) {
			$edit['update']=1;
			$edit['id']=$_GET['id'];
			$edit['content']=htmlentities($edit['content']);
		} else { // specified id doesn't exist. go back to list
			jump($redirectlist);
		}
	}
	else { // new content
		$edit['content']='';
		$edit['gmt']=-7; #default TZ
		$edit['epoch']=time();
		$edit['insert']=1;
		$edit['id']='';
		$edit['title']='';
		$edit['year']=date('Y')-2000;
	}
	include(NERV.'/admin/incl_'.POC_DB_POST['table'].'editor.php');
	exit;
}

if ($usrsubmit) { // work on submitted data. from edit current or add new entry
	$pile=array($DATA_IN['title'],$DATA_IN['epoch'],$DATA_IN['gmt'],$DATA_IN['content']);
	$s0=POC_DB_POST['table'].' SET title=?,epoch=?,gmt=?,content=?';
	$stat='';
	$id=isset($DATA_IN['id'])?$DATA_IN['id']:0;
	if (isset($DATA_IN['update'])) { // update existing record
		$stat=sprintf ('UPDATE %s WHERE id=?',$s0);
		$pile[] = $id;
		$k->dosql($stat,$pile);
	}
	elseif (isset($DATA_IN['insert'])) { // insert new record
		$id=$k->nextID(POC_DB_POST['table']);
		$stat='INSERT INTO '.POC_DB_POST['table'].' ("id","title","epoch","gmt","content","year") VALUES(?,?,?,?,?,?)'; //even in case the id is occupied, error should arise since no duplicate in id is allowed
		array_unshift ($pile, $id);
		$pile[]=$DATA_IN['year']; // year only need for INSERT
		$k->dosql($stat, $pile);
	}
	$rurl=sprintf ('%s?id=%s&dst=1',POC_DB_POST['edit'],$id);
	jump($rurl);
}
jump($redirectlist);


function _del_post($k=null,$id=0) {
	$linked=$k->getOne('SELECT id FROM '.POC_DB_MG['table'].' WHERE post_id=?',array($id));
	if (!empty($linked)) {
		$k->dosql('UPDATE '.POC_DB_MG['table'].' SET post_id=? where id=?',array(null,$linked));
	}
	$k->dosql('DELETE FROM '.POC_DB_POST['table'].' WHERE id=?', array($id));
}


?>
