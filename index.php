<?php include ($_SERVER['DOCUMENT_ROOT'].'/html/shared/html_begin.tmpl'); ?>
<?php include ($_SERVER['DOCUMENT_ROOT'].'/html/shared/head_meta.tmpl'); ?>
<title>音時雨 ～Fairy Aria～</title>
<?php include ($_SERVER['DOCUMENT_ROOT'].'/html/shared/head_general_js.tmpl'); ?>
<link rel="stylesheet" type="text/css" href="/elem/css/index.css" />
<script src="/elem/js/fetch_twitter.js"></script>
<?php include ($_SERVER['DOCUMENT_ROOT'].'/html/shared/head_close.tmpl'); ?>
<div id="index-toparea">
<h1 style="display:none">音時雨 ～Fairy Aria～</h1>
<?php include ($_SERVER['DOCUMENT_ROOT'].'/html/shared/menu.tmpl'); ?>
</div>
<div id="mainlayer">
<?php include ($_SERVER['DOCUMENT_ROOT'].'/html/shared/search_box.tmpl'); ?>
<?php include ($_SERVER['DOCUMENT_ROOT'].'/html/shared/page_main_begin.tmpl'); ?>
<div class="static">

<div id="top-tagline">Archiving my life with music, colours and code magic.</div>

<div id="twi-box">
<div id="twi-msg"><noscript>This section requires javascript</noscript></div>
</div>

<div class="line" style="text-align:center">*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*</div>

<h2>Recent Updates <a href="/feed.rss"><img src="/elem/img/rss_s15.png" alt="rss feed"></a></h2>
<div id="feed-show">
<?php
	$rss = new DOMDocument();
	$rss->load('http://www.pocchong.de/feed.rss');
	$feed = array();
	foreach ($rss->getElementsByTagName('item') as $node) {
		$item = array ( 
			'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
			'desc' => $node->getElementsByTagName('description')->item(0)->nodeValue,
			'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
			'date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue,
			);
		array_push($feed, $item);
	}
	// $limit = 9;
	echo '<table>';
	echo '<tr>';
	$order=array(0,3,6,9,1,4,7,10,2,5,8,11);
	$col=3;
	for($x=0;$x<count($order);$x++) {
		if ($order[$x]<$col)
			echo '<td>';
		$title = str_replace(' & ', ' &amp; ', $feed[$order[$x]]['title']);
		$link = $feed[$order[$x]]['link'];
		$description = $feed[$order[$x]]['desc'];
		$date = date('Y-F-d', strtotime($feed[$order[$x]]['date']));
			echo '<div class="feed-grid">';
			echo '<div class="feed-post-time">'.$date.'</div>';
			echo '<h4><a href="'.$link.'">'.$title.'</a></h4>';
			echo '<div>'.$description.'</div>';
			echo '</div>';
		if ($order[$x]>(count($order)-$col))
			echo '</td>';
	}
	echo '</tr>';
	echo '</table>';
?>
</div>
</div><!-- .static -->
<?php include ($_SERVER['DOCUMENT_ROOT'].'/html/shared/php_include_bundle3.php'); ?>