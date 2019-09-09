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
$process_all=1;
$posts=array();
$navi1=array();
$navi=array();

if (isset($_GET['id'])) {
	$entry1=$k->getRow('SELECT * FROM post WHERE id=?',array($_GET['id']));
	if ($entry1) { #id exists
		$process_all=0;
		$posts[]=$entry1;
		$page_title=$entry1['title'];
		#prev/next info when showing only 1 id
		$navi1=mk_navi1($k,$table,$entry1['id'], $baseurl);
	}
}

if ($process_all) {
	$totalrows=$k->countRows($table);
	$totalpgs=calc_total_page($totalrows,$max_per_page);
	$curr=verify_current_page($curr,$totalpgs);
	$offset=calc_page_offset($curr,$max_per_page);
	$posts=$k->getAll('SELECT id,title,epoch,gmt, content FROM post ORDER BY id DESC LIMIT ?,?', array($offset,$max_per_page));
	$baseurl.=$POCCHONG['POST']['url_page'];
	$navi=calc_navi_set(1,$totalpgs,$curr,$page_turn);
	$naviset=mk_naviset($navi,$page_turn,$curr,$baseurl);
}
?>
<?php // ---------- write html -------------
write_html_open($page_title,null,1);

if (!$process_all) { # single entry
	print_post_entry($posts[0],$POCCHONG['POST']['url'],0);
} else {
	foreach ($posts as $entry) {
		print_post_entry($entry,$POCCHONG['POST']['url'],1);
	}
}

write_html_close( ($process_all? $naviset : null ), (!$process_all? $navi1 : null ) );
?>
<?php // ------------ sub print post entry -----------
function print_post_entry ($entry=null,$baseurl='/',$idx=0) { # <div class="post-inner-shell">....</div> .// $baseurl is for single entry
	global $POCCHONG;
	if (!isset($entry)) { return 0; }
	$tag=$idx?'div':'article';
	print_post_wrap();
	echo '<',$tag,'>',"\n";
	// timestamp line
	printf ('<div class="datetime"><a href="%s/%d">%s</a></div>%s',
			$baseurl, $entry['id'], time27( $entry['epoch'],4,$entry['gmt']), "\n");
	// title as <h3>
	if ($idx) {
		printf ('<h3><a href="%s/%s">%s %s %s</a></h3>%s',
			$baseurl, $entry['id'],
			rand_deco_symbol(), $entry['title'], rand_deco_symbol(),
			"\n");
	} else {
		printf ('<h3>%s %s %s</h3>%s',
			rand_deco_symbol(), $entry['title'], rand_deco_symbol(),
			"\n");
	}

	if ($idx) { # short content for index page, remove html tags
		$imgcatch='';
		if ( preg_match('/<img\s+src="(.+?)"/', $entry['content'], $match) ) {
			$imgcatch=$match[1];
		}
		elseif ( preg_match( '@\s+src="https?://www.youtube.com/embed/(\w+)@i' , $entry['content'] , $match) ) {
			$imgcatch=sprintf ("https://img.youtube.com/vi/%s/0.jpg", $match[1]);
		}
		// remove html tags, replace '.line' to '-----', add <br />for "\n"
		$idxlimit=444; #max chars to show on post-listing page
		$entry['content']=preg_replace('/<div class="line".+?<\/div>/i', "* * *\n", $entry['content']);
		$entry['content']=strip_tags($entry['content']);
		$entry['content']=mb_substr($entry['content'], 0, $idxlimit); // mb_substr instand of substr . multi-byte safe
		$entry['content']=preg_replace('/[\r\n]+/','<br />', $entry['content']);
		echo '<div class="idx-txt">', "\n";
		if ($imgcatch) {
			echo '<div class="idx-preview"><img src="',$imgcatch,'" alt="" /></div>', "\n";
		}
		printf ('%s. . . <a href="%s/%s" style="color: green; font-weight:bold">&gt;&gt;&gt;</a></div>%s',
			$entry['content'], $baseurl, $entry['id'], "\n");
	} else {
		echo $entry['content'],"\n";
	}
	print_edit_button(sprintf ("%s/?id=%s", $POCCHONG['POST']['edit'], $entry['id']));
	echo '</',$tag,'>',"\n";
	print_post_wrap(1);
}
?>
