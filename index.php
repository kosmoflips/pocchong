<?php #UTF8 anchor (´・ω・｀)
require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/'.'Method_Kiyoism_Remaster.php');
?>
<?php
$PAGE=array();
$PAGE['css']=array('/deco/css/index.css');
$PAGE['js']=array('/deco/js/fetch_twitter.js');
?>
<?php // html
include ($_SERVER['DOCUMENT_ROOT'].$POCCHONG['TMPL']['page1']);
include ($_SERVER['DOCUMENT_ROOT'].$POCCHONG['TMPL']['entry1']);
?>
<div id="top-tagline">Archiving my life with music, colours and code magic.</div>

<div class="line" style="text-align:center">*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*</div>

<div id="feed-show">
<div id="feed-show-inner">
<?php
$timespan=60*24*60*0.5; # 1/2 day in second
$tweetfile=$CGIBIN.'/data/incl_index_tweets.html';
if (!file_exists($tweetfile) or isset($_GET['force']) or ((time() - filemtime($tweetfile)) > $timespan)) { #retrieve twitter data if raw file missing, force update, or since last time is true
	$chunkfile=$CGIBIN.'/data/incl_index_tweets_raw.txt';
	$cmd=sprintf ('perl %s %s %s', $CGIBIN.'/process_tweets.pl', $chunkfile, $tweetfile);
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

<?php
include ($_SERVER['DOCUMENT_ROOT'].$POCCHONG['TMPL']['entry2']);
include ($_SERVER['DOCUMENT_ROOT'].$POCCHONG['TMPL']['page2']);
?>
