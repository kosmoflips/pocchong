<?php #UTF8 anchor (´・ω・｀)
require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/'.'Method_Kiyoism_Remaster.php');
?>
<?php //prepare data
$k=new PocDB();
$baseurl=$POCCHONG['POST']['url'];
$page_title=$POCCHONG['POST']['title'];
$max_per_page=$POCCHONG['POST']['max'];
$page_turn=$POCCHONG['GENERAL']['navi_step'];
$table=$POCCHONG['POST']['table'];

$curr=isset($_GET['page'])?$_GET['page']:1; #current page index
$navi1=array();
$navi=array();

$onentry=0;
$posts=array();
if (isset($_GET['id'])) {
	$entry1=$k->getRow('SELECT * FROM post WHERE id=?',array($_GET['id']));
	if ($entry1) { #id exists
		$onentry=1;
		$posts[]=$entry1;
		$page_title=$entry1['title'];
		#prev/next info when showing only 1 id
		$navi1=mk_navi1($k,$table,$entry1['id'], $baseurl);
	}
}
if (empty($posts)) { #index mode or id doesn't exist
	$totalrows=$k->countRows($table);
	$totalpgs=calc_total_page($totalrows,$max_per_page);
	$curr=verify_current_page($curr,$totalpgs);
	$offset=calc_page_offset($curr,$max_per_page);
	$posts=$k->getAll('SELECT id,title,epoch,gmt, content FROM post ORDER BY id DESC LIMIT ?,?', array($offset,$max_per_page));
	$baseurl_p=$baseurl.$POCCHONG['POST']['url_page'];
	$navi=calc_navi_set(1,$totalpgs,$curr,$page_turn);
	$naviset=mk_naviset($navi,$page_turn,$curr,$baseurl_p);
}
?>
<?php // ---------- write html -------------
write_html_open($page_title);

foreach ($posts as $entry) {
	print_post_wrap(0,1);
	// timestamp line
	printf ('<div class="datetime"><a href="%s/%d">%s</a></div>%s',
			$baseurl, $entry['id'], time27( $entry['epoch'],4,$entry['gmt']), "\n");
	// title as <h3>
	printf ('<h3><a href="%s/%s">%s %s %s</a></h3>%s',
			$baseurl, $entry['id'],
			rand_deco_symbol(), $entry['title'], rand_deco_symbol(),
			"\n");
	echo $entry['content'],"\n";
	print_edit_button(sprintf ("%s/?id=%s", $POCCHONG['POST']['edit'], $entry['id']));
	print_post_wrap(1,1);
}
write_html_close( ($onentry?null:$naviset), ($onentry?$navi1 : null ) );
?>
