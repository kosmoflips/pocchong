<?php #UTF8 anchor (´・ω・｀)
require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/'.'Method_Kiyoism_Remaster.php');
?>
<?php //------------- subs ----------------
function _del_post($k=null,$id=0) {
	$linked=$k->getOne('SELECT id FROM mygirls WHERE post_id=?',array($id));
	if (!empty($linked)) {
		$k->dosql('UPDATE mygirls SET post_id=? where id=?',array(null,$linked));
	}
	$k->dosql('DELETE FROM post WHERE id=?', array($id));
}
?>
<?php // ------------ data process --------------
chklogin(1);
$k=new PocDB();
$redirectlist='/a/list_table?sel=post';

$usrsubmit=0;
$showedit=0;
// make sure blocks for each purpose properly 'exit' or 'jump'
if (isset($_POST['opt'])) { // delete, jump to public link, preview , edit/insert new
	if ($_POST['opt'] == 'DELETE') {
		if (isset($_POST['del_id'])) {
			foreach ($_POST['del_id'] as $id) {
				_del_post($k,$id);
			}
		}
		if (isset($_POST['id'])) {
			_del_post($k,$_POST['id']);
		}
		jump($redirectlist);
	}
	elseif ($_POST['opt'] == 'View') {
		$redirect=$POCCHONG['POST']['url'].'/'.$_POST['id'];
		jump($redirect);
	}
	elseif ($_POST['opt'] == 'Preview') { // -------- print_post_entry. copied from page_post.php  . may not be synced. purpose is to preview css/style etc.
		write_html_open(null,null,$POCCHONG['FILE']['js'],$POCCHONG['FILE']['css']);
		write_preview_sash();
		 $tag='article';
		print_post_wrap();
		echo '<',$tag,'>',"\n";
		// timestamp line
		printf ('<div class="datetime">%s</div>%s', time27( time(),4,-7,0), "\n");
		// title as <h3>
		printf ('<h3>* %s *</h3>%s', (isset($_POST['title'])? $_POST['title'] : 'No Title'), "\n");
		echo (isset($_POST['content'])?$_POST['content']:''),"\n";
		echo '</',$tag,'>',"\n";
		print_post_wrap(1);
		write_html_close();
		exit;
	}
	elseif ($_POST['opt'] == 'Submit') {
		$usrsubmit=1;
	}
	else { // none of above go back to post-list
		jump($redirectlist);
	}
}
elseif (isset($_GET['new']) or isset($_GET['id'])) { // edit post page
	$edit=array();
	if (isset($_GET['id'])) {
		$edit=$k->getRow('SELECT * FROM post WHERE id=?', array($_GET['id']));
		if (isset($edit)) {
			$edit['update']=1;
			$edit['id']=$_GET['id'];
		} else { // specified id doesn't exist. go back to list
			jump($redirectlist);
		}
	} else { // new content
		$edit['content']='';
		$edit['gmt']=-7; #default TZ
		$edit['epoch']=time();
		$edit['insert']=1;
		$edit['id']='';
		$edit['title']='';
	}
	include($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/admin/incl_posteditor.php');
	exit;
}

if ($usrsubmit) { // work on submitted data. from edit current or add new entry
	$pile=array($_POST['title'],$_POST['epoch'],$_POST['gmt'],$_POST['content']);
	$s0='post SET title=?,epoch=?,gmt=?,content=?';
	$stat='';
	$id=isset($_POST['id'])?$_POST['id']:0;
	if ($_POST['update']) {
		$stat=sprintf ('UPDATE %s WHERE id=?',$s0);
		$pile[] = $id;
		$k->dosql($stat,$pile);
	}
	elseif ($_POST['insert']) {
		$id=$k->getOne('SELECT id FROM post ORDER BY id DESC LIMIT 1');
		$id++; // safer. even with auto-increase id for 'post', sometimes it complains id can't be null
		$stat='INSERT INTO post ("id","title","epoch","gmt","content") VALUES(?,?,?,?,?)'; //even in case the id is occupied, error should arise since no duplicate in id is allowed
		array_unshift ($pile, $id);
		$k->dosql($stat, $pile);
	}
	$rurl=sprintf ('%s/?id=%s',$POCCHONG['POST']['edit'],$id);
	jump($rurl);
}
jump($redirectlist);
?>
