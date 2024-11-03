<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/synapse.php');
// display 1 work only, fetch by id.

$symbol=rand_deco_symbol();
$p=new PocPage;
$k=new PocDB();

$id=$_GET['id']??0;
if ($id) {
	$entry=$k->getRow('SELECT * FROM mygirls WHERE id=?',array($id));
	if (!empty($entry)) {
		$p->title=$entry['title'];
	} else {
		show_response(404);
	}
} else {
	show_response(404);
}

// get all pcs from associated main title id
$pcs0=$k->getAll('SELECT * FROM mygirls_pcs WHERE title_id=?', array($id));
$stds=array();
$pcs=array();
foreach ($pcs0 as $row) {
	if ($row['stdalone']) {
		$stds[]=$row;
	} else {
		$pcs[]=$row;
	}
}
shuffle($pcs);
if (empty($stds)) { // if no given stdalone, get 1 random pcs from shuffed $pcs
	$stds[]=array_pop($pcs);
}

$p->navi['pair']=mk_navi_pair($k, POC_DB_MG['table'], $id,POC_DB_MG['url']);
$p->html_open();
?>
<div><!-- artwork block begins -->
<h2><?php echo $symbol,' ', $entry['title'], ' ', $symbol; ?></h2>
<blockquote>
<ul>
<li><b>Finished on:</b> <?php echo clock27($entry['epoch'],3,$entry['gmt']); ?></li>
<?php
if ($entry['post_id']) {
?>
<li><b>Liner notes:</b> <a href="<?php echo POC_DB_POST['url'],'?id=',$entry['post_id']; ?>"><?php echo $k->getOne('SELECT title FROM post WHERE id=?', array($entry['post_id'])); ?></a></li>
<?php
}
if ($entry['notes']) {
?>
<li><b>Inspiration:</b> <?php echo $entry['notes']; ?></li>
<?php
}
if ($entry['remake']) {
?>
<li><b>New Remake:</b> <a href="<?php echo POC_DB_MG['url'], '?id=', $entry['remake']; ?>"><?php echo $k->getOne('SELECT title FROM mygirls WHERE id=?', array($entry['remake'])); ?></a></li>
<?php
}
if ($entry['remade_from']) {
?>
<li><b>Remake of:</b> <a href="<?php echo POC_DB_MG['url'], '?id=', $entry['remade_from']; ?>"><?php echo $k->getOne('SELECT title FROM mygirls WHERE id=?', array($entry['remade_from'])); ?></a></li>
<?php
}
?>
</ul>
</blockquote>
<?php
// ----- stdalone -----
foreach ($stds as $pc1) {
	$imgsrc=mk_mg_img_url($pc1['img_url']);
	$daurl=isset($pc1['da_url'])?mk_url_da($pc1['da_url'], $entry['epoch']):'';
?>
<div class="stdalone">
<?php
if ($daurl) {
?>
<a href="<?php echo $daurl ?>" target="_blank">
<?php
} ?>
<img src="<?php echo $imgsrc; ?>" alt="" />
<?php
if ($daurl) {
?>
</a>
<?php
}
?>
<br />
</div><!-- .stdalone -->
<?php
}

// ----- pcs -----
if (!empty($pcs)) {
?>
<div class="mg-h-line"><b>..｡o○☆*:ﾟ･: Variations :･ﾟ:*☆○o｡..</b></div>
<div class="gallery">
<?php
foreach ($pcs as $pc2) {
?>
<span class="gallery-img-frame"><a href="<?php echo mk_url_da($pc2['da_url']); ?>"><img src="<?php echo mk_mg_img_url($pc2['img_url']); ?>" alt="" /></a></span>
<?php
}
?>
</div>
<?php
}

print_edit_button(sprintf ("%s?id=%s", POC_DB_MG['edit'], $entry['id']));
?>
</div><!-- closing artwork block -->
<?php
$p->html_close();

?>
