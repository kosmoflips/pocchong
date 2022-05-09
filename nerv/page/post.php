<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/synapse.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/lib_navicalc.php');

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
echo $entry['content'],"\n";
print_edit_button(POC_DB['POST']['edit'].'/?id='.$entry['id']);
?>
</article>
<?php
	$p->html_close(2);
}
?>