<?php #UTF8 anchor (´・ω・｀)
require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/'.'Method_Kiyoism_Remaster.php');
// display 1 work only, fetch by id.
$pack=$POCCHONG['MYGIRLS'];
$PAGE=process_data_mg_single($_GET['id']??null);

if (!$PAGE) {
	jump($pack['url']);
	exit;
}
?>
<?php //html
include ($_SERVER['DOCUMENT_ROOT'].$POCCHONG['TMPL']['page1']);
include ($_SERVER['DOCUMENT_ROOT'].$POCCHONG['TMPL']['entry1']);
?>
<div><!-- artwork block begins -->
<h2><?php echo rand_deco_symbol(),' ', $PAGE['main']['title'], ' ', rand_deco_symbol(); ?></h2>
<blockquote>
<ul>
<li>ID: <?php echo $PAGE['main']['vol']; ?></li>
<li><?php echo time27($PAGE['main']['epoch'],0,$PAGE['main']['gmt']); ?></li>
<?php
if ($PAGE['main']['post_id'] and $PAGE['main']['rep_title']) { ?>
<li>Liner notes: <a href="<?php echo $POCCHONG['POST']['url'],'/',$PAGE['main']['post_id']; ?>"><?php echo $PAGE['main']['rep_title'] ?></a></li>
<?php
}
if ($PAGE['main']['notes']) { ?>
<li>Inspiration: <?php echo $PAGE['main']['notes']; ?></li>
<?php 
}
if ($PAGE['main']['remake'] and $PAGE['main']['remake_title']) { ?>
<li>New Remake: <a href="<?php echo $POCCHONG['MYGIRLS']['url'], '/', $PAGE['main']['remake']; ?>"><?php echo $PAGE['main']['remake_title']; ?></a></li>
<?php
}
if ($PAGE['main']['remade_from'] and $PAGE['main']['remade_from_title']) { ?>
<li>Remake of: <a href="<?php echo $POCCHONG['MYGIRLS']['url'], '/', $PAGE['main']['remade_from']; ?>"><?php echo $PAGE['main']['remade_from_title']; ?></a></li>
<?php
} ?>
</ul>
</blockquote>
<?php // std alone
foreach ($PAGE['stds'] as $pc1) {
	$imgsrc=mk_url_google_img($pc1['img_url'], 's900');
	$daurl=isset($pc1['da_url'])?mk_url_da($pc1['da_url']):''; ?>
<div class="stdalone">
<?php if ($daurl) { ?><a href="<?php echo $daurl; ?>" target="_blank"><?php } ?><img src="<?php echo $imgsrc; ?>" alt="" /><?php if ($daurl) { ?></a><?php } ?><br />
</div><!-- .stdalone -->
<?php
}
if (!empty($PAGE['pcs'])) { ?>

<div class="mg-h-line"><b>..｡o○☆*:ﾟ･: Variations :･ﾟ:*☆○o｡..</b></div>

<div class="gallery">
<?php
	foreach ($PAGE['pcs'] as $pc2) { ?>
<span class="gallery-img-frame"><a href="<?php echo mk_url_da($pc2['da_url']); ?>"><img src="<?php echo mk_url_google_img($pc2['img_url'],'s640'); ?>" alt="" /></a></span>
<?php
	} ?>
</div>
<?php
}
print_edit_button(sprintf ("%s/?id=%s", $POCCHONG['MYGIRLS']['edit'], $PAGE['main']['id']));
?>
</div><!-- closing artwork block -->

<?php
include ($_SERVER['DOCUMENT_ROOT'].$POCCHONG['TMPL']['entry2']);
include ($_SERVER['DOCUMENT_ROOT'].$POCCHONG['TMPL']['page2']);
?>
<?php // ------------- data process -------------
function process_data_mg_single($id=0) {
	global $POCCHONG;
	global $pack;
	$k=new PocDB();
	$page_title=$pack['title2'];

	if ($id) {
		$entry=$k->getRow('SELECT * FROM mygirls WHERE id=?',array($id));
		if (!empty($entry)) {
			$page_title=$entry['title'];
			$navi1=mk_navi_pair($k, $pack['table'], $id,$pack['url']);
		}
	}

	if (!$entry) {
		return null;
	}

	if ($entry['post_id']) {
		$rep_title=$k->getOne('SELECT title FROM post WHERE id=?', array($entry['post_id']));
		if ($rep_title) {
			$entry['rep_title']=$rep_title;
		}
	}

	if ($entry['remake']) {
		$remake=$k->getOne('SELECT title FROM mygirls WHERE id=?', array($entry['remake']));
		if ($remake) {
			$entry['remake_title']=$remake;
		}
	}

	if ($entry['remade_from']) {
		$remade=$k->getOne('SELECT title FROM mygirls WHERE id=?', array($entry['remade_from']));
		if ($remade) {
			$entry['remade_from_title']=$remade;
		}
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

	return array(
		'title'=>$page_title,
		'navi'=>array(
			'pair'=>$navi1??null,
		),
		'main'=>$entry,
		'stds'=>$stds,
		'pcs'=>$pcs,
	);
}
?>
