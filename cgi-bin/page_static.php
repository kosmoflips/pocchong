<?php #UTF8 anchor (´・ω・｀)
require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/'.'Method_Kiyoism_Remaster.php');

$pack=$POCCHONG['STATIC']; // type, table, title, url, url_page, max, edit
$PAGE=process_data_static_tag($_GET['tag']??null);

if (!$PAGE) {
	jump($pack['url']);
	exit;
}
?>
<?php // write html
include ($_SERVER['DOCUMENT_ROOT'].$POCCHONG['TMPL']['page1']);
include ($_SERVER['DOCUMENT_ROOT'].$POCCHONG['TMPL']['entry1']);
?>
<h2><?php echo rand_deco_symbol(),' ', $PAGE['title'],' ',rand_deco_symbol() ?></h2>
<article>
<?php
echo $PAGE['data']['content'];
print_edit_button($pack['edit'].'/?id='.$PAGE['data']['id']);
?>
</article>
<?php
include ($_SERVER['DOCUMENT_ROOT'].$POCCHONG['TMPL']['entry2']);
include ($_SERVER['DOCUMENT_ROOT'].$POCCHONG['TMPL']['page2']);
?>
<?php //prepare data
function process_data_static_tag ($tag='') {
	$k=new PocDB();
	global $pack;
	global $POCCHONG;

	if (isset($tag)) {
		$entry=$k->getRow('select * from '.$pack['table'].' where perma=?',array($tag));
		if (empty($entry)) {
			$entry=$k->getRow('select * from '.$pack['table'].' where id=?',array($tag));
		}
	}
	if ($entry) {
		return array(
			'title'=>$entry['title'],
			'data'=>$entry,
			'head-extra'=>array($entry['extra']),
		);
	} else {
		return null;
	}
}
?>
