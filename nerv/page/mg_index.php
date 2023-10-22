<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/synapse.php');

$byid=$_GET['id'] ?? 0;
if ($byid) {
	include('mg_single.php');
	exit();
}

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

	$dbinfo=POC_DB_MG;

	$totalrows=$k->countRows($dbinfo['table']);
	$totalpg=calc_total_page($totalrows,$dbinfo['max_gallery']);
	$curr=$page??1;
	if ($totalpg<$curr) {
		show_response(404);
	}
	$offset=calc_page_offset($curr, $dbinfo['max_gallery']);
	$stat=sprintf ('SELECT %s.id "id",title,epoch,img_url FROM %s join %s on %s.rep_id = %s.id ORDER BY epoch DESC LIMIT ?,?', $dbinfo['table'], $dbinfo['table'], $dbinfo['table_pcs'], $dbinfo['table'], $dbinfo['table_pcs']);
	$list=$k->getAll($stat, array($offset,$dbinfo['max_gallery']));
	$page_title=sprintf ('%s -%s-',$dbinfo['title2'],number2roman($curr));
	$baseurl=$dbinfo['url'].'?page=';
	$navibar=mk_navi_bar(1,$totalpg,$dbinfo['max_gallery'],$curr,POC_NAVI_STEP,$baseurl);

	$pobj->title=$page_title;
	$pobj->navi['bar']=$navibar??null;
	$pobj->data=$list;
}

function print_page_mg_index_item ($entry=null) {
	if (empty($entry['img_url'])) { #incase stdalone isn't set up, choose a random one
		$entry['img_url']=get_random_img($k,$entry['id']);
	}
	$furl=mk_mg_img_url($entry['img_url']);
?>
<div class="mgarchive-container">
<a href="<?php echo POC_DB_MG['url'].'?id='.$entry['id'] ?>"><img class="mgarchive-image" src="<?php echo $furl ?>" alt="img" /></a>
<div class="mgarchive-overlay"><?php echo $entry['title'] ?><br /><?php echo clock27($entry['epoch'],3) ?></div>
</div>
<?php
}

function get_random_img($k,$id=0) {
	$urls=$k->getAll('select img_url from '.POC_DB_MG['table_pcs'].' where title_id=?', array($id));
	if (empty($urls)) {
		return '';
	} else {
		shuffle($urls);
		return $urls[0];
	}
}

?>