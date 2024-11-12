<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/synapse.php');

$byid=$_GET['id'] ?? 0;
if ($byid) {
	include('mg_single.php');
	exit();
}

$symbol=rand_deco_symbol();
$p=new PocPage;
$k=new PocDB();

$pcs_per_page=POC_DB_MG['max_gallery'];
$totalrows=$k->countRows('mygirls');
$totalpg=calc_total_page($totalrows,$pcs_per_page);
$curr=$_GET['page']??1;
if ($totalpg<$curr) {
	show_response(404);
}
$offset=calc_page_offset($curr, $pcs_per_page);
$pdata=$k->getAll('SELECT mygirls.id "id",title,epoch,img_url FROM mygirls JOIN mygirls_pcs ON mygirls.rep_id = mygirls_pcs.id ORDER BY epoch DESC LIMIT ?,?', array($offset,$pcs_per_page));

$p->title=sprintf ('%s :: %s',POC_DB_MG['title2'],number2roman($curr));

$p->navi['bar']=mk_navi_bar(1,$totalpg,$pcs_per_page,$curr,POC_NAVI_STEP, 2);

$p->html_open();
?>
<h2><?php echo $symbol,' ', $p->title,' ', $symbol; ?></h2>
<div class="gallery">
<?php
foreach ($pdata as $entry) {
	$furl=mk_mg_img_url($entry['img_url']);
?>
<div class="mgarchive-container">
<a href="<?php echo mk_id_view_url(2,$entry['id']); ?>"><img class="mgarchive-image" src="<?php echo $furl ?>" alt="img" /></a>
<div class="mgarchive-overlay"><?php echo $entry['title'] ?><br /><?php echo clock27($entry['epoch'],3) ?></div>
</div>
<?php
}
?>
</div><!-- gallery -->
<?php
$p->html_close();

?>