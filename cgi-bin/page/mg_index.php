<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/'.'Method_Kiyoism_Remaster.php');
// list index mode.

print_page_mg_index ();

//------------------
function print_page_mg_index_item ($entry=null) {
	if (empty($entry['img_url'])) { #incase stdalone isn't set up, choose a random one
		$entry['img_url']=get_random_img($k,$entry['id']);
	}
	$furl=mk_url_google_img($entry['img_url'],'h300');
?>
<div class="mgarchive-container">
<a href="<?php echo POCCHONG['MYGIRLS']['url'].'/'.$entry['id'] ?>"><img class="mgarchive-image" src="<?php echo $furl ?>" alt="img" /></a>
<div class="mgarchive-overlay">[ <?php echo $entry['vol'] ?> ]<br /><?php echo $entry['title'] ?><br /><?php echo time27($entry['epoch'],5) ?></div>
</div>
<?php
}

function print_page_mg_index () {
	$symbol=rand_deco_symbol();
	$p=new PocPage;
	process_data_mg_index($p,$_GET['page']??null);
	$p->html_open();
	?>
<h2><?php echo $symbol,' ', $p->title,' ', $symbol; ?></h2>
<div class="gallery">
<?php
	foreach ($p->data as $entry) {
		print_page_mg_index_item($entry);
	}
?>
</div><!-- gallery -->
<?php
	$p->html_close();
}

function process_data_mg_index ($pobj=null,$page=0) {
	if (!$pobj) {
		return null;
	}
	$k=new PocDB();
	$pack=POCCHONG['MYGIRLS'];

	$totalrows=$k->countRows($pack['table']);
	$totalpg=calc_total_page($totalrows,$pack['max_gallery']);
	$curr=$page??1;
	$offset=calc_page_offset($curr, $pack['max_gallery']);
	$stat=sprintf ('SELECT %s.id "id",vol,title,epoch,img_url FROM %s join %s on %s.rep_id = %s.id ORDER BY id DESC LIMIT ?,?', $pack['table'], $pack['table'], $pack['table_pcs'], $pack['table'], $pack['table_pcs']);
	$list=$k->getAll($stat, array($offset,$pack['max_gallery']));
	$page_title=sprintf ('%s::%s-%s',$pack['title2'],$list[(count($list)-1)]['vol'],$list[0]['vol']);
	$baseurl=$pack['url'].$pack['url_index_page'];
	$navibar=mk_navi_bar(1,$totalpg,$pack['max_gallery'],$curr,POCCHONG['navi_step'],$baseurl);

	$pobj->title=$page_title;
	$pobj->navi['bar']=$navibar??null;
	$pobj->data=$list;
}

function get_random_img($k,$id=0) {
	global $pack;
	$urls=$k->getAll('select img_url from '.$pack['table_pcs'].' where title_id=?', array($id));
	if (empty($urls)) {
		return '';
	} else {
		shuffle($urls);
		return $urls[0];
	}
}

?>