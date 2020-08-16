<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/'.'Method_Kiyoism_Remaster.php');

$p=new PocPage;
$p->head(
	array (
		'css' => array(
					'/deco/css/index.css',
				),
		'js' => array(
					'/deco/js/fetch_twitter.js',
				),
		'extra' => array('<link href="https://fonts.googleapis.com/css?family=Black+Ops+One&display=swap" rel="stylesheet">')
	)
);

$p->html_open();
?>

<?php
//<div id="top-tagline">< ?php include(POCCHONG['PATH']['widget'].'/oneliner.php'); ? ></div>
?>

<div class="line" style="text-align:center">*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*</div>

<div id="left-half">

<div class="side-square">
<div class="recentpost">
<?php include(POCCHONG['PATH']['widget'].'/randomdays.php'); ?>
</div>
</div>

<div class="side-square">
<?php include (POCCHONG['PATH']['widget'].'/twitter_feed.php');?>
</div>

</div><!-- LEFT HALF-->

<div id="right-half">

<div class="side-square">
<?php include (POCCHONG['PATH']['widget'].'/calendar_thismonth.php'); ?>
</div>

<div class="side-square">
<?php include (POCCHONG['PATH']['widget'].'/randomgirls.php') ?>
</div>

<div class="side-square">
<?php include (POCCHONG['PATH']['widget'].'/lastfmtrack.php') ?>
</div>

<div class="side-square">
<?php include (POCCHONG['PATH']['widget'].'/search.php') ?>
</div>

<div class="side-square">
<?php include (POCCHONG['PATH']['widget'].'/themeinfo.php') ?>
</div>

</div><!-- RIGHT HALF-->

<?php
$p->html_close();

?>
