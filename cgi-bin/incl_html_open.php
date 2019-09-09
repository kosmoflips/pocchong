<?php #UTF8 anchor (´・ω・｀)
require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/'.'Method_Kiyoism_Remaster.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type"/><meta name="generator" content="Method_Kiyoism_Remaster/." />
<link href="<?php echo $POCCHONG['SITE']['domain'] ?>" rel="canonical" />
<link rel="alternate" type="application/rss+xml" title="<?php echo $POCCHONG['SITE']['maintitle'] ?> - RSS" href="<?php echo $POCCHONG['SITE']['domain'],$POCCHONG['SITE']['rss'] ?>" />
<?php
	if (!empty($jslist)) {
		foreach ($jslist as $jsurl) {
			printf ('<script src="%s"></script>%s', $jsurl,"\n");
		}
	}
	if (!empty($csslist)) {
		foreach ($csslist as $cssurl) {
			printf ('<link rel="stylesheet" type="text/css" href="%s" />%s',$cssurl, "\n");
		}
	}
	if ($title) {
		$title.=' | '.$POCCHONG['SITE']['maintitle'];
	} else {
		$title=$POCCHONG['SITE']['maintitle'];
	}
	printf ('<title>%s</title>%s', $title, "\n");
	if ($extra) { #any code in <head>
		echo $extra, "\n";
	}
?>
</head>
<body>
<div id="outlayer">
<div id="header-img"></div>
<div id="header-outer">
<a href="/"><span id="headerlink"></span></a>
<h1><a href="/"><?php echo $POCCHONG['SITE']['maintitle'] ?></a></h1>
<?php include($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/incl_menu.php'); ?>
</div><!-- #header-outer -->
<div id="mainlayer">
<?php include($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/incl_search.html'); ?>
<div class="post-outer">
