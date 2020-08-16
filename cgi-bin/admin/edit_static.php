<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/'.'Method_Kiyoism_Remaster.php');

$ENTRY_VAR=POCCHONG['STATIC'];
$TVAR=$ENTRY_VAR['table'];
$DATA_IN=$_POST;

chklogin(1);
$k=new PocDB();
$redirectlist='/a/list_'.$TVAR;

$usrsubmit=0;
$showedit=0;

if (isset($_GET['dst']) and $_GET['dst']==1) {
	print_system_msg('changes made successfully.');
}

// make sure blocks for each purpose properly 'exit' or 'jump'
if (isset($DATA_IN['opt'])) { // delete, jump to public link, preview , edit/insert new
	if ($DATA_IN['opt'] == 'DELETE') {
		if (isset($DATA_IN['del_id'])) {
			foreach ($DATA_IN['del_id'] as $id) {
				_del_page($k,$id);
			}
		}
		if (isset($DATA_IN['id'])) {
			_del_page($k,$DATA_IN['id']);
		}
		$redirectlist.='?dst=3';
		jump($redirectlist);
	}
	elseif ($DATA_IN['opt'] == 'Reorder') {
		foreach ($DATA_IN['num'] as $id=>$neworder) {
			$stat=sprintf ('update %s set num=? where id=?',$TVAR);
			$k->dosql($stat,array($neworder,$id));
		}
		$redirectlist.='?dst=4';
		jump($redirectlist);
	}
	elseif ($DATA_IN['opt'] == 'View') {
		$redirect=$ENTRY_VAR['url'].'/'.(isset($DATA_IN['perma'])?$DATA_IN['perma']:$DATA_IN['id']);
		jump($redirect);
	}
	elseif ($DATA_IN['opt'] == 'Preview') { // quick preview. overall style may differ from current site
		$PAGE=new PocPage;
		$PAGE->title=$DATA_IN['title'];
		$PAGE->head['extra']=array($DATA_IN['extra']);
		$PAGE->html_open();
		write_preview_sash();
		echo '<div>',"\n";
		printf ('<h2>* %s *</h2>%s', (isset($DATA_IN['title'])? $DATA_IN['title'] : 'No Title'), "\n");
		echo (isset($DATA_IN['content'])?$DATA_IN['content']:''),"\n";
		echo '</div>',"\n";
		$PAGE->html_close();
		exit;
	}
	elseif ($DATA_IN['opt'] == 'Save') {
		$usrsubmit=1;
	}
	else { // none of above go back to list
		jump($redirectlist);
	}
}
elseif (isset($_GET['new']) or isset($_GET['id'])) { // edit page
	$edit=array();
	if (isset($_GET['id'])) {
		$edit=$k->getRow('SELECT * FROM '.$TVAR.' WHERE id=?', array($_GET['id']));
		if (isset($edit)) {
			$edit['update']=1;
			$edit['id']=$_GET['id'];
			$edit['content']=htmlentities($edit['content']);
		} else { // specified id doesn't exist. go back to list
			jump($redirectlist);
		}
	} else { // new content
		$edit['insert']=1;
		$edit['id']='';
		$edit['title']='';
		$edit['desc']='';
		$edit['perma']='';
		$edit['extra']='';
		$edit['content']='';
	}
	include($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/admin/incl_'.$TVAR.'editor.php');
	exit;
}

if ($usrsubmit) { // work on submitted data. from edit current or add new entry
	$keys=array('title','desc','perma','extra','content');
	$pile=array();
	$stat='';
	$id=isset($DATA_IN['id'])?$DATA_IN['id']:0;
	if (isset($DATA_IN['perma'])) { #permalink value must be unique
		$tryid=$k->getOne('select id from '.$TVAR.' where perma=?', array($DATA_IN['perma']));
		if (isset($tryid) and $tryid!=$id) {
			$edit=$DATA_IN;
			print_system_msg('permalink duplicate as of id='.$tryid);
			include($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/admin/incl_'.$TVAR.'editor.php');
			exit;
		}
	}
	if (isset($DATA_IN['update'])) {
		$s0=array(); #for update
		foreach ($keys as $kk) {
			$s0[]=$kk.'=? '; // need trailing space
			$pile[]=isset($DATA_IN[$kk])?$DATA_IN[$kk]:null;
		}
		$stat='UPDATE '.$TVAR.' SET '.(implode ( ', ' , $s0)).' WHERE id=?';
		$pile[] = $id;
		$k->dosql($stat,$pile);
	}
	elseif (isset($DATA_IN['insert'])) {
		$s1=array(); #for insert
		$s1b=array();
		foreach ($keys as $kk) {
			$s1[]=sprintf ('"%s"', $kk);
			$s1b[]='?';
			$pile[]=isset($DATA_IN[$kk])?$DATA_IN[$kk]:null;
		}
		$id=$k->nextID($TVAR);
		$s1[] = '"id"';
		$s1b[]='?';
		$pile[] = $id;
		$stat='INSERT INTO '.$TVAR.' ('.(implode (',', $s1)).') VALUES('.(implode(',',$s1b)).')'; //even in case the id is occupied, error should arise since no duplicate in id is allowed
		$k->dosql($stat, $pile);
	}
	$rurl=sprintf ('%s/?id=%s&dst=1',$ENTRY_VAR['edit'],$id);
	jump($rurl);
}
jump($redirectlist);


function _del_page($k=null,$id=0) {
	global $TVAR;
	$k->dosql('DELETE FROM '.$TVAR.' WHERE id=?', array($id));
}

?>
