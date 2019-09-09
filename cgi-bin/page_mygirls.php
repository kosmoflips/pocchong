<?php #UTF8 anchor (´・ω・｀)
require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/'.'Method_Kiyoism_Remaster.php');
// NOTE . this page is reserved from public
?>
<?php // ------------- data process -------------
$k=new PocDB();
$table=$POCCHONG['MYGIRLS']['table'];
$page_title=$POCCHONG['MYGIRLS']['title'];
$baseurl=$POCCHONG['MYGIRLS']['url'];
$max_per_page=$POCCHONG['MYGIRLS']['max'];
$page_turn=$POCCHONG['GENERAL']['navi_step'];

$posts;
$navi1=array();
$navi=array();
$naviset=array();
$curr=0;

$mode=1; #show all.  =2, single id , =3 tag
if (isset($_GET['id'])) { // if id okay, mode=2 . need navi1
	$entry1=$k->getRow('SELECT * FROM mygirls WHERE id=?',array($_GET['id']));
	if (!empty($entry1)) {
		$mode=2;
		$page_title=$entry1['title'];
		$posts[]=$entry1;
		$navi1=mk_navi1($k, $table, $_GET['id'],$baseurl);
	}
}
/*
elseif (isset($_GET['tag'])) { // if nametag okay, mode = 3 . need naviset
	$tags=$k->getTags();	
	if (isset ($tags[$_GET['tag']]) ){#tag id exists
		$mode=3;
		$max_per_page = 17; // 適当
		// count all titles with matched tag
		$totalrow=$k->getOne('SELECT COUNT(*) FROM mygirls JOIN mygirls_link ON mygirls.id=mygirls_link.title_id WHERE mygirls_link.tag_id=?', array($_GET['tag']) );
		$pgtotal=calc_total_page($totalrow,$max_per_page);
		$curr=verify_current_page( ( isset($_GET['offset'])?$_GET['offset']:1 ), $pgtotal); #use "offset" instead of "page" to make it specific to 'tag'
		$offset=calc_page_offset($curr,$max_per_page);
		$posts=$k->getAll('SELECT * FROM mygirls JOIN mygirls_link ON mygirls.id=mygirls_link.title_id WHERE mygirls_link.tag_id=? ORDER BY mygirls.id DESC LIMIT ?,?', array($_GET['tag'], $offset,$max_per_page));
		$baseurl.= $POCCHONG['MYGIRLS']['url_tag'].'/'.$_GET['tag'];
		$navi=calc_navi_set(1, $pgtotal,$curr, $page_turn);
	}
}
*/
if ($mode==1) { // need naviset
	$totalrows=$k->countRows($table);
	$totalpg=calc_total_page($totalrows,$max_per_page);
	$curr=verify_current_page( (isset ($_GET['page'])?$_GET['page']:1),$totalpg);
	$offset=calc_page_offset($curr, $max_per_page);
	$posts=$k->getAll('SELECT * FROM mygirls ORDER BY id DESC LIMIT ?,?', array($offset,$max_per_page));
	$navi=calc_navi_set(1,$totalpg,$curr,$page_turn);
	$baseurl.=$POCCHONG['MYGIRLS']['url_sub'];
}
if (!empty($navi)) {
	$naviset=mk_naviset($navi, $page_turn, $curr, $baseurl);
}
?>
<?php // ---------- write html ----------
write_html_open($page_title,null, 1);

if ($mode==2) { # 1 entry
	print_art_entry($k,$posts[0],0,0);
}
elseif ($mode==1) { # all entries, index mode
	foreach ($posts as $entry) {
		print_art_entry($k, $entry,1);
	}
} elseif ($mode==3) { #no "index" view . for tagged entries
	print_art_entry_tag($k, $posts,$_GET['tag']);
}

write_html_close( ($mode!=2? $naviset:null), ($mode==2? $navi1:null) );
?>
<?php // ------------ subs --------------
// need $k
function print_art_entry($k=null,$entry=null,$idx=0) { #idx=1, on index page, w/o arrange // <div class="post-inner-shell">....</div>
	global $POCCHONG;
	if (!$k or !$entry) { return 0; }
	// get all pcs from associated main title id
	$pcs0=$k->getAll('SELECT * FROM mygirls_pcs WHERE title_id=?', array($entry['id']));
	$stds=array();
	$pcs=array();
	foreach ($pcs0 as $row) {
		if ($row['stdalone']) {
			if (empty($stds) or !$idx) {
				$stds[]=$row;
			} else {
				$pcs[]=$row;
			}
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
	print_mygirls_h3($entry,$idx);
	if (!$idx) { #print stdalone+variations
		print_art_entry_single($k,$entry,$stds,$pcs);
	} else { #print only 1 stdalone
		print_art_entry_index($k,$entry,$stds,$pcs);
	}
	print_edit_button(sprintf ("%s/?id=%s", $POCCHONG['MYGIRLS']['edit'], $entry['id']));
	echo '</div><!-- closing artwork block -->',"\n";
	print_post_wrap(1);
}
function print_art_entry_single ($k=null, $entry=null,$stds=null,$pcs=null) {
	if (!$k or !$entry or !$stds) { return 0; }
	print_info_block($k, $entry, 0);
	foreach ($stds as $pc1) {
		print_stdalone($pc1, 0);
	}
	if (!empty($pcs)) {
		print_line_seperator();
		echo '<div class="gallery">', "\n";
		foreach ($pcs as $row) {
			print_pcs_vars($row,0,0);
		}
		echo '</div>',"\n";
	}
}
function print_info_block ($k=null, $entry=null,$idx=0) { // indiv page only
	if (!$k or !$entry) { return 0; }
	global $POCCHONG;
	$widthsize=$idx ? ' style="width: 80%"' : '';
	echo '<blockquote',$widthsize,'>',"\n";
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
function print_art_entry_index($k=null, $entry=null, $stds=null, $pcs=null) {
	if (!$k or !$entry or !$stds) { return 0; }
	global $POCCHONG;
	echo '<table style="width: 97%">',"\n";
	echo '<tr>',"\n";
	echo '<td style="width: 42%;">',"\n";
	print_stdalone($stds[0],$entry,1);
	echo '</td>',"\n";
	echo '<td style="vertical-align:top;">',"\n";
	print_info_block($k, $entry,1);
	if (!empty($pcs)) {
		echo '<div class="gallery-mini">',"\n";
		$i=0;
		$rlist=rand_array(count($pcs));
		foreach ($rlist as $j) {
			print_pcs_vars($pcs[$j],1,1);
			$i++;
			if ($i>=$POCCHONG['MYGIRLS']['mini']) {
				break;
			}
		}
		echo "\n";
		echo '</div>','<!-- .gallery-mini end -->',"\n";
	}
	echo '</td>',"\n";
	echo '</tr>',"\n",'</table>',"\n";
}
function print_art_entry_tag($k=null, $posts=null,$tag='') {
	if (!$k or !$posts or !$tag) { return 0; }
	global $POCCHONG;
	$nhash=$k->getTags();

	print_post_wrap();
	echo '<div>',"\n";
	print_mygirls_h3( array( 'title' =>'NameTag'.$POCCHONG['GENERAL']['separator'].$nhash[$tag] ) , 0);
	echo '<div class="gallery">',"\n";
	foreach ($posts as $entry) {
		// print title in a rand colour block
		// under tag mode, no img url , etc from _pcs are selected yet. so get the rep one
		$pc0=$k->getRow('SELECT da_url,img_url FROM mygirls_pcs WHERE id=?',array($entry['rep_id']));
		$entry['img_url']=$pc0['img_url'];
		$entry['da_url']=$pc0['da_url'];
		printf ('<div class="gallery-img-frame-mid" style="background: #%s; width: 85px">%s</div>%s', rand_bg_colour(), $entry['title'], "\n" );
		print_pcs_vars($entry,0,1);
		echo "\n";
	}
	echo "</div><!-- .gallery -->\n";
	echo "</div>\n";
	print_post_wrap(1);
}
//no $k
function print_mygirls_h3 ($entry=null,$idx=0) {
	if ($entry) {
		global $POCCHONG;
		$title_h2=sprintf ('%s %s %s', rand_deco_symbol(), $entry['title'], rand_deco_symbol() );
		if ($idx) { #include link on h3
			$title_h2=sprintf ('<a href="%s/%s">%s</a>', $POCCHONG['MYGIRLS']['url'], $entry['id'], $title_h2 );
		}
		echo '<h2>',$title_h2,'</h2>',"\n";
	}
}
function print_stdalone($pc1=null,$idx=0) {
	if (!$pc1) { return 0; }
	$pcsize=$idx?'s400':'s900';
	$imgsrc=mk_url_google_img($pc1['img_url'], $pcsize);
	$daurl='';
	$imglink=sprintf ('<img src="%s" alt="" />', $imgsrc);
	if (isset($pc1['da_url'])) {
		$daurl=mk_url_da($pc1['da_url']);
		$imglink=sprintf ('<a href="%s">%s</a>', $daurl, $imglink);
	}

	echo '<div class="stdalone">',"\n";
	echo $imglink,'<br />',"\n";
	if (!$idx and $daurl) {
		printf ('<a href="%s">deviantART</a>', $daurl);
	}
	echo '</div><!-- .stdalone -->',"\n";
}
function mk_url_da($url='') { #feed in string after ../art/. uses my dA account
	if ($url) {
		return "http://kosmoflips.deviantart.com/art/".$url;
	} else {
		return '';
	}
}
function print_pcs_vars($pc2=null,$idx=0,$mid=0) { // mid is shown on indiv page
	if (!$pc2) { return 0; }
	global $POCCHONG;
	$picsize=$idx?'120':'s640';
	$class='gallery-img-frame';
	$picurl=($idx or $mid)?  sprintf ('%s/%s', $POCCHONG['MYGIRLS']['url'], $pc2['title_id']) : mk_url_da($pc2['da_url'])  ;
	if ($idx) {
		$class.='-mini';
	} elseif ($mid) {
		$class.='-mid';
	}
	printf ('<span class="%s"><a href="%s"><img src="%s" alt="" /></a></span>',
		$class,
		$picurl,
		mk_url_google_img($pc2['img_url'],$picsize)  );
}
function rand_bg_colour() {
	$cols=array('fa896a','ffa729','fff829','61cf1d','10c554','849fca','0bd3ac','19c9f1','1184e5','dd8067','99efdf','5acdf1','4466e1','7c4ef8','d561cc','f6476d');
	return $cols[rand(0,(count($cols)-1))];
}
?>
