<?php #UTF8 anchor (´・ω・｀)
require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/'.'Method_Kiyoism_Remaster.php');
// list index mode.

$pack=$POCCHONG['MYGIRLS'];
$PAGE=process_data_mg_index($_GET['page']??null);

?>
<?php // html
include ($_SERVER['DOCUMENT_ROOT'].$POCCHONG['TMPL']['page1']);
include ($_SERVER['DOCUMENT_ROOT'].$POCCHONG['TMPL']['entry1']);
?>
<h2><?php echo rand_deco_symbol(),' ', $PAGE['title'],' ',rand_deco_symbol(); ?></h2>
<div class="gallery">
<?php
foreach ($PAGE['data'] as $entry) {
	if (empty($entry['img_url'])) { #incase stdalone isn't set up, choose a random one
		$entry['img_url']=get_random_img($k,$entry['id']);
	}
	$furl=mk_url_google_img($entry['img_url'],'h300');
?>
<div class="mgarchive-container">
<a href="<?php echo $pack['url'].'/'.$entry['id'] ?>"><img class="mgarchive-image" src="<?php echo $furl ?>" alt="img" /></a>
<div class="mgarchive-overlay">[ <?php echo $entry['vol'] ?> ]<br /><?php echo $entry['title'] ?><br /><?php echo time27($entry['epoch'],5) ?></div>
</div>
<?php } ?>
</div><!-- gallery -->
<?php
include ($_SERVER['DOCUMENT_ROOT'].$POCCHONG['TMPL']['entry2']);
include ($_SERVER['DOCUMENT_ROOT'].$POCCHONG['TMPL']['page2']);
?>
<?php // ------------- data process -------------
function process_data_mg_index ($page=0) {
	global $POCCHONG;
	global $pack;
	$k=new PocDB();

	$step=$POCCHONG['navi_step'];

	$totalrows=$k->countRows($pack['table']);
	$totalpg=calc_total_page($totalrows,$pack['max_gallery']);
	$curr=$page??1;
	$offset=calc_page_offset($curr, $pack['max_gallery']);
	$stat='SELECT mygirls.id "id",vol,title,epoch,img_url FROM mygirls join mygirls_pcs on mygirls.rep_id = mygirls_pcs.id ORDER BY id DESC LIMIT ?,?';
	$list=$k->getAll($stat, array($offset,$pack['max_gallery']));
	$page_title=sprintf ('%s::%s-%s',$pack['title2'],$list[(count($list)-1)]['vol'],$list[0]['vol']);
	$baseurl=$pack['url'].$pack['url_index_page'];
	$navibar=mk_navi_bar(1,$totalpg,$pack['max_gallery'],$curr,$step,$baseurl);
	return array(
		'title'=>$page_title,
		'navi'=>array(
			'bar'=>$navibar??null,
		),
		'data'=>$list,
	);
}
function get_random_img($k,$id=0) {
	$urls=$k->getAll('select img_url from mygirls_pcs where title_id=?', array($id));
	if (empty($urls)) {
		return '';
	} else {
		shuffle($urls);
		return $urls[0];
	}
}

?>