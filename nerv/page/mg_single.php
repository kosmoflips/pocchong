<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/synapse.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/lib_mg.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/lib_navicalc.php');
// display 1 work only, fetch by id.

print_page_mg_1work();


// --------------------------------

function print_page_mg_1work () {
	$symbol=rand_deco_symbol();
	$p=new PocPage;
	process_data_mg_single($p,$_GET['id']??null);
	$p->html_open();
	?>
<div><!-- artwork block begins -->
<h2><?php echo $symbol,' ', '[',$p->data['main']['vol'],'] ',$p->data['main']['title'], ' ', $symbol; ?></h2>
<?php
	print_mg_blockinfo($p->data['main']);
	print_mg_stdalone($p->data['stds']);
	print_mg_pcs($p->data['pcs']);
	print_edit_button(sprintf ("%s/?id=%s", POC_DB['MYGIRLS']['edit'], $p->data['main']['id']));
?>
</div><!-- closing artwork block -->
<?php
	$p->html_close();
}

function print_mg_blockinfo ($main=null) {
	?>
<blockquote>
<ul>
<li><?php echo clock27($main['epoch'],0,$main['gmt']); ?></li>
<?php
	if ($main['post_id'] and $main['rep_title']) {
	?>
<li>Liner notes: <a href="<?php echo POC_DB['POST']['url'],'/',$main['post_id']; ?>"><?php echo $main['rep_title'] ?></a></li>
<?php
	}
	if ($main['notes']) {
	?>
<li>Inspiration: <?php echo $main['notes']; ?></li>
<?php
	}
	if ($main['remake'] and $main['remake_title']) {
	?>
<li>New Remake: <a href="<?php echo POC_DB['MYGIRLS']['url'], '/', $main['remake']; ?>"><?php echo $main['remake_title']; ?></a></li>
<?php
	}
	if ($main['remade_from'] and $main['remade_from_title']) {
	?>
<li>Remake of: <a href="<?php echo POC_DB['MYGIRLS']['url'], '/', $main['remade_from']; ?>"><?php echo $main['remade_from_title']; ?></a></li>
<?php
	}
	?>
</ul>
</blockquote>
<?php
}

function print_mg_stdalone ($stds=null) {
	foreach ($stds as $pc1) {
		$imgsrc=mk_url_google_img($pc1['img_url'], 's900');
		$daurl=isset($pc1['da_url'])?mk_url_da($pc1['da_url']):'';
		?>
<div class="stdalone">
<?php
	print_stdalone_img($imgsrc, $daurl);
?>
<br />
</div><!-- .stdalone -->
<?php
	}
}

function print_stdalone_img ($imgsrc='', $daurl=null) {
	if ($daurl) {
		echo '<a href="', $daurl, '" target="_blank">';
	}
	?>
<img src="<?php echo $imgsrc; ?>" alt="" />
<?php
	if ($daurl) {
		echo '</a>';
	}
}

function print_mg_pcs ($pcs) {
	if (!empty($pcs)) {
	?>
<div class="mg-h-line"><b>..｡o○☆*:ﾟ･: Variations :･ﾟ:*☆○o｡..</b></div>
<div class="gallery">
<?php
	foreach ($pcs as $pc2) {
	?>
<span class="gallery-img-frame"><a href="<?php echo mk_url_da($pc2['da_url']); ?>"><img src="<?php echo mk_url_google_img($pc2['img_url'],'s640'); ?>" alt="" /></a></span>
<?php
	}
	?>
</div>
<?php
	}
}

function process_data_mg_single($pobj,$id=0) {
	if (!$pobj) {
		return null;
	}

	$page_title=POC_DB['MYGIRLS']['title2'];

	$k=new PocDB();

	if ($id) {
		$entry=$k->getRow('SELECT * FROM '.POC_DB['MYGIRLS']['table'].' WHERE id=?',array($id));
		if (!empty($entry)) {
			$page_title=$entry['title'];
			$navi1=mk_navi_pair($k, POC_DB['MYGIRLS']['table'], $id,POC_DB['MYGIRLS']['url']);
		}
	}

	if (!$entry) {
		jump(POC_DB['MYGIRLS']['url']);
		exit;
	}

	if ($entry['post_id']) {
		$rep_title=$k->getOne('SELECT title FROM '.POC_DB['POST']['table'].' WHERE id=?', array($entry['post_id']));
		if ($rep_title) {
			$entry['rep_title']=$rep_title;
		}
	}

	if ($entry['remake']) {
		$remake=$k->getOne('SELECT title FROM '.POC_DB['MYGIRLS']['table'].' WHERE id=?', array($entry['remake']));
		if ($remake) {
			$entry['remake_title']=$remake;
		}
	}

	if ($entry['remade_from']) {
		$remade=$k->getOne('SELECT title FROM '.POC_DB['MYGIRLS']['table'].' WHERE id=?', array($entry['remade_from']));
		if ($remade) {
			$entry['remade_from_title']=$remade;
		}
	}

	// get all pcs from associated main title id
	$pcs0=$k->getAll('SELECT * FROM '.POC_DB['MYGIRLS']['table_pcs'].' WHERE title_id=?', array($id));
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

	$pobj->title=$page_title;
	$pobj->navi['pair']=$navi1??null;
	$pobj->data['main']=$entry;
	$pobj->data['stds']=$stds;
	$pobj->data['pcs']=$pcs;
}
?>
