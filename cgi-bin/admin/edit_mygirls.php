<?php #UTF8 anchor (´・ω・｀)
require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/'.'Method_Kiyoism_Remaster.php');
?>
<?php // ----- subs ------------
function _del_mygirls($k=null,$id=0) { // delete entire entry
	$stat=array();
	$stat[]='DELETE FROM mygirls_link WHERE title_id=?';
	$stat[]='DELETE FROM mygirls_pcs WHERE title_id=?';
	$stat[]='DELETE FROM mygirls WHERE id=?';
	$k->dosql('UPDATE mygirls SET rep_id=? where id=?',array(null,$id)); // avoid interal relationship
	foreach ($stat as $s) {
		$k->dosql($s,array($id));
	}
}
function cleanimgurl ($url='') { #better to separate since it's easier to choose your output img dimension
#old type url: http://lh4.googleusercontent.com/-a-tSvDZLVhU/VndOs2XjNuI/AAAAAAAAi70/8xr7M0mbG8I/s500/ ===> "s500/" can be omitted
	$pattern1='/^https?:?/';
	$pattern2='/^[\/:]/';
	while (preg_match($pattern1, $url) or preg_match($pattern2, $url)) {
		$url=preg_replace($pattern1,'',$url);  #remove https
		$url=preg_replace($pattern2,'',$url);  # remove leading slash '/'
	}
	return $url;
}
function add_tag($k=null,$titleid=0,$tagid=0) {
	if ($k and $titleid and $tagid) {
		$lastid=$k->getOne('SELECT id FROM mygirls_link ORDER BY id DESC LIMIT 1');
		$lastid++;
		$k->dosql('INSERT INTO mygirls_link ("id","title_id","tag_id") VALUES(?,?,?)', array($lastid,$titleid, $tagid));
	}
}
?>
<?php
chklogin(1);
$k=new PocDB();
$redirectlist='/a/list_table?sel=mygirls';

#dst=3: entries deleted in list mode / 2: pieces deleted in page-edit mode / 1: entry updated

if (isset($_GET['dst'])) {
	if ($_GET['dst']==2) {
		print_system_msg('selected pieces deleted');
	}
	else {
		print_system_msg('entry updated');
	}
}

$usrsubmit=0;
if (isset($_POST['opt'])) { //submit,preview,delete
	if ($_POST['opt'] == 'DELETE') {
		if (isset($_POST['del_id'])) {
			foreach ($_POST['del_id'] as $id) {
				_del_mygirls($k,$id);
			}
		}
		if (isset($_POST['main']['id'])) { // submitted from edit interface
			_del_mygirls($k,$_POST['main']['id']);
		}
		$redirectlist.='&dst=3';
		jump($redirectlist);
	}
	elseif ($_POST['opt'] == 'DELselected') {
		if (isset ($_POST['DEL_pcs']) and !empty($_POST['main']['id'])) {
			$rep=$k->getRow('SELECT id as "rep_id",title_id FROM mygirls_pcs WHERE id=?', array($_POST['main']['id']));
			foreach ($_POST['DEL_pcs'] as $pid) {
				if (preg_match('/^new/i', $pid)) { // no need to delete "new" entries
					continue;
				}
				if ($rep['rep_id'] == $pid) {
					$k->dosql('UPDATE mygirls SET rep_id=? where id=?',array(null,$rep['title_id']));
				}
				$k->dosql('DELETE FROM mygirls_pcs WHERE id=?',array($pid));
			}
		}
		$rurl=sprintf ('%s/?id=%s&dst=2',$POCCHONG['MYGIRLS']['edit'],$_POST['main']['id'] );
		jump($rurl);
		exit;
	}
	elseif ($_POST['opt'] == 'View') {
		$reurl=sprintf ('%s/%s', $POCCHONG['MYGIRLS']['url'],$_POST['main']['id']);
		jump($reurl);
	}
	elseif ($_POST['opt'] == 'Save') { // set flag, do later
		$usrsubmit=1;
	}
	else {
		jump($redirectlist);
	}
}
elseif (isset($_GET['new']) or isset($_GET['id'])) { #load page to edit
	$info=array();
	$tagidx=$k->getTags();

	if (isset($_GET['id'])) { // edit existing entry
		$info=$k->getRow('SELECT * FROM mygirls WHERE id=?',array($_GET['id']));
		if (empty($info)) { // given id is false , redirect to list
			jump($redirectlist);
		}
		// ---- below, given id is true. get all pcs , link tags-----------
		$info['update']=1;
		$tags=$k->getAll('SELECT tag_id FROM mygirls_link WHERE title_id=?',array($info['id']));
		foreach ($tags as $tagid) {
			$info['tags'][]=$tagid['tag_id'];
		}
		$stat='SELECT * FROM mygirls_pcs WHERE title_id=?';
		$sth=$k->getAll($stat,[$_GET['id']]);
		foreach ($sth as $r) { // loop through each [pcs]. set up pic preview url and label representative pcs
			$url='';
			$url_preview='';
			if (!empty($r['img_url'])) {
				$r['img_url']=cleanimgurl($r['img_url']);
				$r['url_preview']=mk_url_google_img($r['img_url'],'s120');
				// $r['img_url']='https://'.$r['img_url']; // data stored in db has no leading 'http://'
			} else {
				$r['url_preview']='';
			}
			if (isset($info['rep_id']) and $info['rep_id']==$r['id']) {
				$r['is_rep']=1;
			}
			// all done, push this pcs into info
			$info['pcs'][]=$r;
		}
	}
	elseif (isset($_GET['new'])) { // make a new entry
		$info['insert']=1;
		$info['id']='';
		$info['vol']='FACL000';
		$info['title']='';
		$info['year']=date('Y')-2000;
		$info['epoch']=time();
		$info['gmt']=-7;
		$info['post_id']='';
		$info['rep_id']='';
		$info['notes']='';
		$info['remake']='';
		$info['remade_from']='';
	}

	// append some new lines in case of adding new associated pieces
	$mknew=5;
	for ($i=1; $i<=$mknew; $i++) {
		$info['pcs'][]=array(
			'id'=>'new'.$i,
			'stdalone'=>0,
			'img_url'=>'',
			'da_url'=>'',
			'url_preview'=>'',
		);
	}
	// ---- write editor HTML -----------
	include($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/admin/incl_mygirlseditor.php');
	exit;
}

if ($usrsubmit) { // edit/update from $_POST
	$main=isset($_POST['main'])?$_POST['main']:null;
	$tags=isset($_POST['tags'])?$_POST['tags']:null;
	$pcs=isset($_POST['pcs'])?$_POST['pcs']:null;
	if (empty($main)) {
		jump($redirectlist);
	}

	if (!empty($_POST['update']) ) {
		$update=1;
	} else {
		$update=0;
	}
	// ---------update/insert main block, and tags-----------
	if ($update) {
		// --- main block , except rep_id ---
		$mainblock=$k->getRow('SELECT * FROM mygirls where id=?',array($main['id']));
		$s0=array();
		$pile=array();
		foreach ($main as $mkey=>$mval ) {
			if ( $mainblock[$mkey] != $mval) { // only update if not the same as old db data
				$s0[]=$mkey.'=? '; // need trailing space
				$pile[]=empty($mval)?null:$mval;
			}
		}
		if (!empty($s0)) { // one or more columns need update
			$stat='UPDATE mygirls SET '.(implode ( ', ' , $s0)).' WHERE id=?';
			$pile[]=$main['id'];
			$k->dosql($stat,$pile);
		}
		// --- tags ---
		$rmlist=array();
		$addlist=array();
		$ctags0=$k->getAll('SELECT id,tag_id from mygirls_link where title_id=?',array($main['id'])); // all current tags
		$ctags=array();
		foreach ($ctags0 as $cc) {
			$ctags[]=$cc['tag_id'];
		}
		$alltags=$k->getTags();
		if (!empty($tags) and !empty($ctags)) {
			foreach ($alltags as $tid=>$tname) {
				if (in_array($tid, $tags) and !in_array($tid, $ctags)) {
					$addlist[]=$tid;
				}
				elseif (!in_array($tid, $tags) and in_array($tid, $ctags)) {
					$rmlist[]=$tid;
				}
			}
		} elseif (empty($tags) and !empty($ctags)) {
			$rmlist=$ctags;
		} elseif (!empty($tags) and empty($ctags)) {
			$addlist=$tags;
		}
		foreach ($rmlist as $rm) {
			$k->dosql('DELETE FROM mygirls_link WHERE (title_id=? and tag_id=?)', array($main['id'], $rm));
		}
		foreach ($addlist as $add) {
			add_tag($k, $main['id'],$add);
		}
	}
	else { //insert
		$lastid=$k->getOne('SELECT id FROM mygirls ORDER BY id DESC LIMIT 1');
		$lastid++;
		$s0=array();
		$pile=array();
		$s0[]='"id"';
		$pile[]=$lastid;
		$vals='?';
		foreach ($main as $akey=>$aval) {
			if ($akey == 'id') { // id is processed above
				continue;
			}
			$s0[]=sprintf ('"%s"',$akey);
			$pile[]=$aval;
			$vals.=',?';
		}
		$stat='INSERT INTO mygirls ('; // INSERT INTO mygirls ("id","title","epoch") VALUES(?,?,?)';
		$stat.=implode(',',$s0);
		$stat.=') VALUES(';
		$stat.=$vals;
		$stat.=')';
		$k->dosql($stat,$pile);
		$main['id']=$lastid; // do this last
		// --- tags ---
		foreach ($tags as $tid) {
			add_tag($k, $main['id'],$tid);
		}
	}

	// ----------- update/insert pcs block ---------
	$crep=$k->getOne('SELECT rep_id FROM mygirls WHERE id=?', array($main['id']));
	$newrep=!empty($_POST['set_rep_id'])?$_POST['set_rep_id']:0;
	foreach ($pcs as $pc1) {
		$pid=$pc1['id'];
		$new=0;
		if (preg_match('/new(\d+)/i',$pc1['id'],$tmp)) {
			$pid=$tmp[1];
			$new=1;
		}
		if (empty($pc1['img_url'])) {
			if ($newrep==$pc1['id']) {
				$newrep=$crep;
			}
			continue;
		}
		// update existing pcs
		$stat='';
		$pile=array();
		if (!$new) {
			$stat='UPDATE mygirls_pcs SET title_id=?, stdalone=?, img_url=?, da_url=? WHERE id=?';
			$p2=$pid;
		}
		else { // insert new pcs
			$lastpid=$k->getOne('SELECT id FROM mygirls_pcs ORDER BY id DESC LIMIT 1');
			$lastpid++;
			$p2=$lastpid;
			$stat='INSERT INTO mygirls_pcs ("title_id","stdalone","img_url","da_url","id") VALUES(?,?,?,?,?)';
			if ($newrep == $pc1['id']) { // this newly inserted pc will be used as rep
				$newrep=$lastpid;
			}
		}
		$pile=array(
				$main['id'],
				(empty($pc1['stdalone'])?0:1),
				(empty($pc1['img_url'])?null:cleanimgurl($pc1['img_url'])),
				(empty($pc1['da_url'])?null:$pc1['da_url']),
				$p2  );
		$k->dosql($stat,$pile);
	}
	if ( $newrep != $crep ) { // -- update rep_id ---
		$k->dosql('UPDATE mygirls SET rep_id=? where id=?', array($newrep,$main['id']));
	}
	$rurl=sprintf ('%s/?id=%s&dst=1',$POCCHONG['MYGIRLS']['edit'],$main['id'] );
	jump($rurl);
}

jump($redirectlist);

?>