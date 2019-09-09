<?php #UTF8 anchor (´・ω・｀)
require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/'.'Method_Kiyoism_Remaster.php');
?>
<?php
$extra='<link rel="stylesheet" type="text/css" href="/deco/css/index.css" />
<script src="/deco/js/fetch_twitter.js"></script>
';
write_html_open(null,$extra,$POCCHONG['FILE']['js'],$POCCHONG['FILE']['css']);

?>
<div class="post-inner-shell">
<div class="post-inner">

<div id="top-tagline">Archiving my life with music, colours and code magic.</div>

<div class="line" style="text-align:center">*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*</div>

<div id="feed-show">
<div id="feed-show-inner">
<?php
$timespan=60*24*60*0.5; # 1/2 day in second
$tweetfile=$_SERVER['DOCUMENT_ROOT'].'/cgi-bin/incl_index_tweets.html';
if (!file_exists($tweetfile) or isset($_GET['force']) or ((time() - filemtime($tweetfile)) > $timespan)) { #retrieve twitter data if raw file missing, force update, or since last time is true
	$chunkfile=$_SERVER['DOCUMENT_ROOT'].'/cgi-bin/incl_index_tweets_raw.txt';
	$cmd=sprintf ('perl %s %s %s', $_SERVER['DOCUMENT_ROOT'].'/cgi-bin/process_tweets.pl', $chunkfile, $tweetfile);
	exec($cmd);
}
if (file_exists($tweetfile)) {
	include ($tweetfile);
} else {
	echo "tweet file can't be located ._.";
}
?>
</div><!-- .feed-show-inner -->
</div><!-- .feed-show -->

</div><!-- .post-inner -->
</div><!-- .post-inner-shell -->
<?php
write_html_close();
?>