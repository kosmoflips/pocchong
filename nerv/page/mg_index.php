<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/synapse.php');
require_once(NERV.'/lib_mg.php');
require_once(NERV.'/lib_navicalc.php');
// list index mode.

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


//------------------
function process_data_mg_index ($pobj=null,$page=0) {
	if (!$pobj) {
		return null;
	}
	$k=new PocDB();

	$totalrows=$k->countRows(POC_DB['MYGIRLS']['table']);
	$totalpg=calc_total_page($totalrows,POC_DB['MYGIRLS']['max_gallery']);
	$curr=$page??1;
	$offset=calc_page_offset($curr, POC_DB['MYGIRLS']['max_gallery']);
	$stat=sprintf ('SELECT %s.id "id",title,epoch,img_url FROM %s join %s on %s.rep_id = %s.id ORDER BY epoch DESC LIMIT ?,?', POC_DB['MYGIRLS']['table'], POC_DB['MYGIRLS']['table'], POC_DB['MYGIRLS']['table_pcs'], POC_DB['MYGIRLS']['table'], POC_DB['MYGIRLS']['table_pcs']);
	$list=$k->getAll($stat, array($offset,POC_DB['MYGIRLS']['max_gallery']));
	$page_title=sprintf ('%s::%d::',POC_DB['MYGIRLS']['title2'],$curr);
	$baseurl=POC_DB['MYGIRLS']['url'].POC_DB['MYGIRLS']['url_index_page'];
	$navibar=mk_navi_bar(1,$totalpg,POC_DB['MYGIRLS']['max_gallery'],$curr,POC_DB['navi_step'],$baseurl);

	$pobj->title=$page_title;
	$pobj->navi['bar']=$navibar??null;
	$pobj->data=$list;
}

function print_page_mg_index_item ($entry=null) {
	if (empty($entry['img_url'])) { #incase stdalone isn't set up, choose a random one
		$entry['img_url']=get_random_img($k,$entry['id']);
	}
	$furl=mk_url_google_img($entry['img_url'],'h300');
?>
<div class="mgarchive-container">
<a href="<?php echo POC_DB['MYGIRLS']['url'].'/'.$entry['id'] ?>"><img class="mgarchive-image" src="<?php echo $furl ?>" alt="img" /></a>
<div class="mgarchive-overlay"><?php echo $entry['title'] ?><br /><?php echo clock27($entry['epoch'],5) ?></div>
</div>
<?php
}

function get_random_img($k,$id=0) {
	$urls=$k->getAll('select img_url from '.POC_DB['MYGIRLS']['table_pcs'].' where title_id=?', array($id));
	if (empty($urls)) {
		return '';
	} else {
		shuffle($urls);
		return $urls[0];
	}
}

?>