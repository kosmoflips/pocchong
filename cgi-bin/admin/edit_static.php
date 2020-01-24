<?php #UTF8 anchor (´・ω・｀)
require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/'.'Method_Kiyoism_Remaster.php');
?>
<?php //------------- subs ----------------
function _del_page($k=null,$id=0) {
	$k->dosql('DELETE FROM static WHERE id=?', array($id));
}
?>
<?php // ------------ data process --------------
chklogin(1);
$k=new PocDB();
$redirectlist='/a/list_static';
$table=$POCCHONG['STATIC']['table'];

$usrsubmit=0;
$showedit=0;

if (isset($_GET['dst']) and $_GET['dst']==1) {
	print_system_msg('changes made successfully.');
}

// make sure blocks for each purpose properly 'exit' or 'jump'
if (isset($_POST['opt'])) { // delete, jump to public link, preview , edit/insert new
	if ($_POST['opt'] == 'DELETE') {
		if (isset($_POST['del_id'])) {
			foreach ($_POST['del_id'] as $id) {
				_del_page($k,$id);
			}
		}
		if (isset($_POST['id'])) {
			_del_page($k,$_POST['id']);
		}
		$redirectlist.='?dst=3';
		jump($redirectlist);
	}
	elseif ($_POST['opt'] == 'Reorder') {
		foreach ($_POST['num'] as $id=>$neworder) {
			$stat=sprintf ('update %s set num=? where id=?',$table);
			$k->dosql($stat,array($neworder,$id));
		}
		$redirectlist.='?dst=4';
		jump($redirectlist);
	}
	elseif ($_POST['opt'] == 'View') {
		$redirect=$POCCHONG['STATIC']['url'].'/'.(isset($_POST['perma'])?$_POST['perma']:$_POST['id']);
		jump($redirect);
	}
	elseif ($_POST['opt'] == 'Preview') { // quick preview. overall style may differ from current site
		$PAGE=array();
		$PAGE['title']=$_POST['title'];
		$PAGE['head-extra']=array($_POST['extra']);
		include($_SERVER['DOCUMENT_ROOT'].$POCCHONG['TMPL']['site1']);
		include($_SERVER['DOCUMENT_ROOT'].$POCCHONG['TMPL']['entry1']);
		write_preview_sash();
		echo '<div>',"\n";
		printf ('<h2>* %s *</h2>%s', (isset($_POST['title'])? $_POST['title'] : 'No Title'), "\n");
		echo (isset($_POST['content'])?$_POST['content']:''),"\n";
		echo '</div>',"\n";
		include($_SERVER['DOCUMENT_ROOT'].$POCCHONG['TMPL']['entry2']);
		include($_SERVER['DOCUMENT_ROOT'].$POCCHONG['TMPL']['site2']);
		exit;
	}
	elseif ($_POST['opt'] == 'Save') {
		$usrsubmit=1;
	}
	else { // none of above go back to list
		jump($redirectlist);
	}
}
elseif (isset($_GET['new']) or isset($_GET['id'])) { // edit page
	$edit=array();
	if (isset($_GET['id'])) {
		$edit=$k->getRow('SELECT * FROM '.$table.' WHERE id=?', array($_GET['id']));
		if (isset($edit)) {
			$edit['update']=1;
			$edit['id']=$_GET['id'];
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
	include($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/admin/incl_staticeditor.php');
	exit;
}

if ($usrsubmit) { // work on submitted data. from edit current or add new entry
	$keys=array('title','desc','perma','extra','content');
	$pile=array();
	$stat='';
	$id=isset($_POST['id'])?$_POST['id']:0;
	if (isset($_POST['perma'])) { #permalink value must be unique
		$tryid=$k->getOne('select id from static where perma=?', array($_POST['perma']));
		if (isset($tryid) and $tryid!=$id) {
			$edit=$_POST;
			print_system_msg('permalink duplicate as of id='.$tryid);
			include($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/admin/incl_staticeditor.php');
			exit;
		}
	}
	if ($_POST['update']) {
		$s0=array(); #for update
		foreach ($keys as $kk) {
			$s0[]=$kk.'=? '; // need trailing space
			$pile[]=isset($_POST[$kk])?$_POST[$kk]:null;
		}
		$stat='UPDATE '.$table.' SET '.(implode ( ', ' , $s0)).' WHERE id=?';
		$pile[] = $id;
		$k->dosql($stat,$pile);
	}
	elseif ($_POST['insert']) {
		$s1=array(); #for insert
		$s1b=array();
		foreach ($keys as $kk) {
			$s1[]=sprintf ('"%s"', $kk);
			$s1b[]='?';
			$pile[]=isset($_POST[$kk])?$_POST[$kk]:null;
		}
		$id=$k->getOne('SELECT id FROM '.$table.' ORDER BY id DESC LIMIT 1');
		if (!isset($id)) {
			$id=0;
		}
		$id++; // safer
		$s1[] = '"id"';
		$s1b[]='?';
		$pile[] = $id;
		$stat='INSERT INTO '.$table.' ('.(implode (',', $s1)).') VALUES('.(implode(',',$s1b)).')'; //even in case the id is occupied, error should arise since no duplicate in id is allowed
		$k->dosql($stat, $pile);
	}
	$rurl=sprintf ('%s/?id=%s&dst=1',$POCCHONG['STATIC']['edit'],$id);
	jump($rurl);
}
jump($redirectlist);
?>
