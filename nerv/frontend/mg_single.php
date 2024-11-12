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
$repid=$k->getOne('SELECT rep_id FROM mygirls WHERE id=?', array($id))??0;
$pcs=array();
foreach ($pcs0 as $row) {
	if ($repid==$row['id']) {
		$std=$row;
	} else {
		$pcs[]=$row;
	}
}
shuffle($pcs);
if (empty($std)) {
	if (!empty($pcs)) { // if no given stdalone, get 1 random pcs from shuffed $pcs
		$std=array_pop($pcs);
	} else {
		$std['img_url']=POC_IMG_PLACEHOLDER;
	}
}

$p->navi['pair']=mk_navi_pair($k, 2, $id);
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
<li><b>Liner notes:</b> <a href="<?php echo mk_id_view_url(1,$entry['post_id']); ?>"><?php echo $k->getOne('SELECT title FROM post WHERE id=?', array($entry['post_id'])); ?></a></li>
<?php
}
if ($entry['notes']) {
?>
<li><b>Inspiration:</b> <?php echo $entry['notes']; ?></li>
<?php
}
if ($entry['remake']) {
?>
<li><b>New Remake:</b> <a href="<?php echo mk_id_view_url(2,$entry['remake']); ?>"><?php echo $k->getOne('SELECT title FROM mygirls WHERE id=?', array($entry['remake'])); ?></a></li>
<?php
}
if ($entry['remade_from']) {
?>
<li><b>Remake of:</b> <a href="<?php echo mk_id_view_url(2,$entry['remade_from']); ?>"><?php echo $k->getOne('SELECT title FROM mygirls WHERE id=?', array($entry['remade_from'])); ?></a></li>
<?php
}
?>
</ul>
</blockquote>

<?php
// ----- rep -----
$imgsrc=mk_mg_img_url($std['img_url']);
$daurl=isset($std['da_url'])?mk_url_da($std['da_url'], $entry['epoch']):'';
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

print_edit_button(mk_id_view_url(2, $entry['id']));
?>
</div><!-- closing artwork block -->
<?php
$p->html_close();

?>
