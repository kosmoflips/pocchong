<?php #UTF8 anchor (´・ω・｀)
require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/'.'Method_Kiyoism_Remaster.php');

$pack=$POCCHONG['POST']; // table, title, url, url_page, max, edit
$PAGE=process_data_post($_GET['id']??null, $_GET['page']??null);
?>
<?php // write html
include ($_SERVER['DOCUMENT_ROOT'].$POCCHONG['TMPL']['page1']);

foreach ($PAGE['data'] as $entry) {
	$posturl=$pack['url'].'/'.$entry['id'];
	include ($_SERVER['DOCUMENT_ROOT'].$POCCHONG['TMPL']['entry1']);
?>
<div class="datetime"><a href="<?php echo $posturl ?>"><?php echo time27( $entry['epoch'],4,$entry['gmt']) ?></a></div>
<h3><a href="<?php echo $posturl ?>"><?php echo rand_deco_symbol(), ' ',$entry['title'], ' ',rand_deco_symbol() ?></a></h3>
<article>
<?php
	echo $entry['content'],"\n";
	print_edit_button(sprintf ("%s/?id=%s", $POCCHONG['POST']['edit'], $entry['id']));
?>
</article>
<?php
	include ($_SERVER['DOCUMENT_ROOT'].$POCCHONG['TMPL']['entry2']);
}

include ($_SERVER['DOCUMENT_ROOT'].$POCCHONG['TMPL']['page2']);

?>
<?php //prepare data
function process_data_post ($id=null,$page=0) {
	global $POCCHONG;
	global $pack;
	$k=new PocDB();
	$step=$POCCHONG['navi_step'];
	$curr=$page??1; #current page index
	$posts=array();
	if ($id) {
		$entry1=$k->getRow('SELECT * FROM post WHERE id=?',array($id));
		if ($entry1) { #id exists
			$posts[]=$entry1;
			$page_title=$entry1['title'];
			#prev/next info when showing only 1 id
			$navipair=mk_navi_pair($k,$pack['table'],$id, $pack['url']);
		}
	}
	if (empty($posts)) { #index mode or id doesn't exist
		$totalrows=$k->countRows($pack['table']);
		$totalpgs=calc_total_page($totalrows,$pack['max']);
		$offset=calc_page_offset($curr,$pack['max']);
		$posts=$k->getAll('SELECT id,title,epoch,gmt,content FROM post ORDER BY id DESC LIMIT ?,?', array($offset,$pack['max']));
		$baseurl_p=$pack['url'].$pack['url_page'];
		$navibar=mk_navi_bar(1,$totalpgs,$pack['max'],$curr,$step,$baseurl_p);
	}

	return array(
		'title'=>$page_title??$pack['title'],
		'navi'=>array(
			'pair'=>$navipair??null,
			'bar'=>$navibar??null,
		),
		'data'=>$posts, // no need to array $posts as it's already an array
	);
}
?>