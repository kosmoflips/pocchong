<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/synapse.php');
require_once(NERV.'/lib_navicalc.php');

$p=new PocPage;
process_data_post($p,$_GET['id']??null, $_GET['page']??null);
$p->html_open(1);
foreach ($p->data as $entry) {
	print_post_single($p,$entry);
}
$p->html_close(1);

//-------------------------
function process_data_post ($pobj=null,$id=null,$page=0) {
	if (!$pobj) {
		return null;
	}
	$pack=POC_DB['POST'];
	$k=new PocDB();
	$step=POC_DB['navi_step'];
	$curr=$page??1; #current page index
	$posts=array();
	if ($id) {
		$entry1=$k->getRow('SELECT * FROM '.$pack['table'].' WHERE id=?',array($id));
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
		$posts=$k->getAll('SELECT id,title,epoch,gmt,content FROM '.$pack['table'].' ORDER BY id DESC LIMIT ?,?', array($offset,$pack['max']));
		$baseurl_p=$pack['url'].$pack['url_page'];
		$navibar=mk_navi_bar(1,$totalpgs,$pack['max'],$curr,$step,$baseurl_p);
	}

	$pobj->title=$page_title??$pack['title'];
	$pobj->navi['pair']=$navipair??null;
	$pobj->navi['bar']=$navibar??null;
	$pobj->data=$posts; // is array()
}
function print_post_single($p,$entry) {
	$posturl=POC_DB['POST']['url'].'/'.$entry['id'];
	$p->html_open(2);
	?>
<div class="datetime"><a href="<?php echo $posturl ?>"><?php echo clock27( $entry['epoch'],4,$entry['gmt']) ?></a></div>
<h3><a href="<?php echo $posturl ?>"><?php echo rand_deco_symbol(), ' ',$entry['title']; ?></a></h3>
<article>
<?php
# convert for lightbox. need custom flag if not want to use it?
$entry2=add_lightbox_tag($entry['content'], $entry['id']);
echo $entry2,"\n";
print_edit_button(POC_DB['POST']['edit'].'/?id='.$entry['id']);
?>
</article>
<?php
	$p->html_close(2);
} // close print_post_single()
function add_lightbox_tag ($content='', $tag="uniq_string") { # convert <img ...> not wrapped by <a> with lightbox tag. treat all images in one post as a set
	# find pattern as: <a href=...><img src...></a>; <a> pair is optional
	$content = preg_replace_callback ('/(<a href=.+?>)?<img\s+src="(.+?)"(.*?)>(<\/a\s*>)?/', function ($matches) use ($tag) {
		if (!preg_match('/<a/', $matches[1], $yy)) { # only add lightbox if no <a href...> defined outside
			$x=sprintf ('<a href="%s" data-lightbox="pid%s"><img src="%s"%s></a>', $matches[2], $tag, $matches[2], $matches[3]);
			return ($x);
		} else {
			return $matches[0];
		}
	}, $content);
	return ($content);
}

?>