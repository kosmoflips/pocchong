<!DOCTYPE html>
<html>
<head>
<meta content='text/html; charset=UTF-8' http-equiv='Content-Type'/>
<meta content='Method_Kiyoism/.' name='generator'/>
<link href='http://www.pocchong.de/' rel='canonical'/>
<link rel="alternate" type="application/rss+xml" title="音時雨 ～Fairy Aria～ - RSS" href="http://www.pocchong.de/feed.rss" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script src="/elem/js/smiley.js"></script>
<script src="/elem/js/toggle_display.js"></script>
<link rel="stylesheet" type="text/css" href="/elem/css/main.css" />
<link rel="stylesheet" type="text/css" href="/elem/css/mini.css" />
<link rel="stylesheet" type="text/css" href="/elem/css/colour.css" />
<link rel="stylesheet" type="text/css" href="/elem/css/index.css" />
<title>音時雨 ～Fairy Aria～</title>
<script src="/elem/js/fetch_twitter.js"></script>
</head>
<body>
<div id="outlayer">
<div id="header-img"></div>
<div id="header-outer">
<a href="/"><span id="headerlink"></span></a>
<h1><a href="/">音時雨 ～Fairy Aria～</a></h1>
<?php include $_SERVER['DOCUMENT_ROOT'].'/cgi-bin/part_page_menu.html' ?>
</div><!-- #header-outer -->
<div id="mainlayer">
<?php include $_SERVER['DOCUMENT_ROOT'].'/cgi-bin/part_page_searchbox.html' ?>
<div class="post-outer">
<div class="post-inner-shell">
<div class="post-inner">

<div id="top-tagline">Archiving my life with music, colours and code magic.</div>

<div class="line" style="text-align:center">*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*</div>

<div id="feed-show">
<div id="feed-show-inner">
<?php
$timespan=60*24*60*0.5; # 1/2 day in second
$tweetfile=$_SERVER['DOCUMENT_ROOT'].'/cgi-bin/part_index_tweets.html';
if (!empty($_GET['force']) || !file_exists($tweetfile) || ((time() - filemtime($tweetfile)) > $timespan)) { #update json once per x days showing above
	$chunkfile=$_SERVER['DOCUMENT_ROOT'].'/cgi-bin/part_index_tweets_raw.txt';
	$fh=fopen($chunkfile,"w");
	$chunk = file_get_contents('https://syndication.twitter.com/timeline/profile?callback=twitterFetcher.callback&dnt=false&screen_name=kosmoflips&suppress_response_codes=true&lang=en&rnd=1');
	fwrite($fh, $chunk);
	$cmd=sprintf ('perl %s %s %s', $_SERVER['DOCUMENT_ROOT'].'/cgi-bin/process_tweets.pl', $chunkfile, $tweetfile);
	exec($cmd);
}
include ($tweetfile);
?>
</div>
</div>

</div><!-- .post-inner -->
</div><!-- .post-inner-shell -->
</div><!-- .post-outer -->
</div><!-- #mainlayer -->
<div id="footer-outer"></div><!-- .footer-outer -->
<div id="header-img-otherside"></div>
</div><!-- #outlayer -->
<div id="footer-global">
<a href="/about">2006-<script>document.write(new Date().getFullYear())</script> kiyoko@FairyAria</a>
</div>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-56543803-1', 'auto');
  ga('send', 'pageview');
  //since 2014-Apr-27
</script>
</body>
</html>
