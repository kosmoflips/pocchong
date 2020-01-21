<?php #UTF8 anchor (´・ω・｀)
require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/'.'Method_Kiyoism_Remaster.php');
// display 1 work only, fetch by id.
?>
<?php // ------------- data process -------------
$k=new PocDB();
$table=$POCCHONG['MYGIRLS']['table'];
$page_title=$POCCHONG['MYGIRLS']['title'];
$baseurl=$POCCHONG['MYGIRLS']['url'];

$navi1=array();
if (isset($_GET['id'])) {
	$entry=$k->getRow('SELECT * FROM mygirls WHERE id=?',array($_GET['id']));
	if (!empty($entry)) {
		$page_title=$entry['title'];
		$navi1=mk_navi1($k, $table, $_GET['id'],$baseurl);
	} else {
		jump ($baseurl);
	}
}
?>
<?php // ---------- write html ----------
write_html_open($page_title);

// get all pcs from associated main title id
$pcs0=$k->getAll('SELECT * FROM mygirls_pcs WHERE title_id=?', array($entry['id']));
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

print_post_wrap();
echo '<div><!-- artwork block begins -->',"\n";
print_mygirls_h3($entry);
print_info_block($k, $entry);
foreach ($stds as $pc1) {
	print_stdalone($pc1, 0);
}
if (!empty($pcs)) {
	print_line_seperator();
	echo '<div class="gallery">', "\n";
	foreach ($pcs as $row) {
		print_pcs_vars($row);
	}
	echo '</div>',"\n";
}

print_edit_button(sprintf ("%s/?id=%s", $POCCHONG['MYGIRLS']['edit'], $entry['id']));
echo '</div><!-- closing artwork block -->',"\n";
print_post_wrap(1);

write_html_close( null, $navi1);
?>
<?php // ------------ subs --------------
// this function needs $k
function print_info_block ($k=null, $entry=null) {
	if (!$k or !$entry) { return 0; }
	global $POCCHONG;
	// $widthsize=$idx ? ' style="width: 80%"' : ''; # no more $idx thing. this was only used in old-style main indexing page
	// echo '<blockquote',$widthsize,'>',"\n";
	echo '<blockquote>',"\n";
	echo '<ul>',"\n";
	echo '<li>ID: ', $entry['vol'],'</li>',"\n";
	echo '<li>', time27($entry['epoch'],0,$entry['gmt']) ,'</li>',"\n";
	#get rep title
	if (isset($entry['post_id'])) {
		$rep_title=$k->getOne('SELECT title FROM post WHERE id=?', array($entry['post_id']));
		if ($rep_title) {
			$rep_url=sprintf ('%s/%s',$POCCHONG['POST']['url'],$entry['post_id']);
			printf ('<li>Liner notes: <a href="%s">%s</a></li>%s',$rep_url,$rep_title,"\n");
		}
	}
	if (!empty($entry['notes'])) {
		echo '<li>Inspiration: ',$entry['notes'],'</li>',"\n";
	}
	if (isset($entry['remake'])) {
		$remake=$k->getOne('SELECT title FROM mygirls WHERE id=?', array($entry['remake']));
		if (!empty($remake)) {
		printf ('<li>New Remake: <a href="%s/%s">%s</a></li>%s',
			$POCCHONG['MYGIRLS']['url'], $entry['remake'],
			$remake,
			"\n");
		}
	}
	if (isset($entry['remade_from'])) {
		$remade=$k->getOne('SELECT title FROM mygirls WHERE id=?', array($entry['remade_from']));
		if (!empty($remade)) {
			printf ('<li>Remake of: <a href="%s/%s">%s</a></li>%s',
				$POCCHONG['MYGIRLS']['url'], $entry['remade_from'],
				$remade,
				"\n");
		}
	}
	#---------------name tags---------------
/*
	if (!$idx) { // get tags linked to title-id
		$tags=$k->getAll('SELECT tag_id FROM mygirls_link WHERE title_id=?', array($entry['id']));
		if (isset($tags)) { // this title is tagged
			$nhash=$k->getTags();
			shuffle($tags);
			echo '<li>Name tags: ',"\n";
			foreach ($tags as $idx0=>$tagid) {
				printf ('%s<a href="%s%s/%s">%s</a>',
					rand_deco_symbol(),
					$POCCHONG['MYGIRLS']['url'],
					$POCCHONG['MYGIRLS']['url_tag'],
					$tagid['tag_id'],
					$nhash[ $tagid['tag_id'] ] );
			}
			echo rand_deco_symbol(), '</li>', "\n"; #finish the line-deco
		}
	}
*/
	echo '</ul>',"\n";
	echo '</blockquote>',"\n";
}
//no $k
function print_stdalone($pc1=null) {
	if (!$pc1) { return 0; }
	$pcsize='s900';
	$imgsrc=mk_url_google_img($pc1['img_url'], $pcsize);
	$imglink=sprintf ('<img src="%s" alt="" />', $imgsrc);
	$daurl='';
	if (isset($pc1['da_url'])) {
		$daurl=mk_url_da($pc1['da_url']);
		$imglink=sprintf ('<a href="%s">%s</a>', $daurl, $imglink);
	}
	echo '<div class="stdalone">',"\n";
	echo $imglink,'<br />',"\n";
	echo '</div><!-- .stdalone -->',"\n";
}
function print_pcs_vars($pc2=null) {
	if (!$pc2) { return 0; }
	global $POCCHONG;
	$picsize='s640';
	$class='gallery-img-frame';
	$picurl=mk_url_da($pc2['da_url']);
	printf ('<span class="%s"><a href="%s"><img src="%s" alt="" /></a></span>',
		$class,
		$picurl,
		mk_url_google_img($pc2['img_url'],$picsize)
	);
}
?>
