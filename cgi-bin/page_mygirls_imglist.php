<?php #UTF8 anchor (´・ω・｀)
require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/'.'Method_Kiyoism_Remaster.php');
// list index mode.
?>
<?php // ------------- data process -------------
$k=new PocDB();
$table=$POCCHONG['MYGIRLS']['table'];
$max_per_page=$POCCHONG['MYGIRLS']['max_gallery'];
$page_turn=$POCCHONG['GENERAL']['navi_step'];

$totalrows=$k->countRows($table);
$totalpg=calc_total_page($totalrows,$max_per_page);
$cpage=isset($_GET['page'])?$_GET['page']:1;
$curr=verify_current_page( $cpage,$totalpg);
$offset=calc_page_offset($curr, $max_per_page);
$stat='SELECT mygirls.id "id",vol,title,epoch,img_url FROM mygirls join mygirls_pcs on mygirls.rep_id = mygirls_pcs.id ORDER BY id DESC LIMIT ?,?';
$list=$k->getAll($stat, array($offset,$max_per_page));
// $page_title=$POCCHONG['MYGIRLS']['title'].' :: '.$curr;
$page_title=sprintf ('%s::%s-%s',$POCCHONG['MYGIRLS']['title'],$list[(count($list)-1)]['vol'],$list[0]['vol']);
$navi=calc_navi_set(1,$totalpg,$curr,$page_turn);
$baseurl=$POCCHONG['MYGIRLS']['url'].$POCCHONG['MYGIRLS']['url_index_page'];
$naviset=array();
if (!empty($navi)) {
	$naviset=mk_naviset($navi, $page_turn, $curr, $baseurl);
}
?>
<?php // ---------- write html ----------
write_html_open($page_title);
print_post_wrap();
printf ("<h2>%s %s %s</h2>\n",rand_deco_symbol(), $page_title,rand_deco_symbol() ); // h2
echo '<div class="gallery">',"\n";
foreach ($list as $entry) {
	if (empty($entry['img_url'])) { #incase stdalone isn't set up, choose a random one
		$entry['img_url']=get_random_img($k,$entry['id']);
	}
	$furl=mk_url_google_img($entry['img_url'],'h300');
?>
<div class="mgarchive-container">
	<a href="<?php echo $POCCHONG['MYGIRLS']['url'].'/'.$entry['id'] ?>"><img class="mgarchive-image" src="<?php echo $furl ?>" alt="img" /></a>
	<div class="mgarchive-overlay"><?php echo print_label($entry) ?></div>
</div>
<?php
} //close loop
echo '</div><!-- gallery -->',"\n"; #close the last tag
print_post_wrap(1);
write_html_close( $naviset, null);
?>
<?php // ------------ subs --------------
// need $k
function print_label ($entry=null) {
	if (!empty($entry)) {
		printf ('[ %s ]<br />%s<br />%s',
			$entry['vol'],
			$entry['title'],
			time27($entry['epoch'],5)
		);
	}
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
