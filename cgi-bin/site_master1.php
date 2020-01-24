<!DOCTYPE html>
<?php #UTF8 anchor (´・ω・｀) + data process
require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/'.'Method_Kiyoism_Remaster.php');
global $POCCHONG;
global $CGIBIN;
?>
<html lang="en">
<head>
<?php include($CGIBIN.'/layout_meta.php'); ?>
</head>
<body>
<div id="master-wrap">
<header id="header-outer">
<?php include($CGIBIN.'/layout_header.php'); ?>
<?php include($CGIBIN.'/layout_menu.php'); ?>
</header>
<div id="content-wrap">
<?php if (isset($PAGE['side-left'])) { // left sidebar ?>
<div id="sidelayer-left">
<?php include($CGIBIN.'/layout_side_left.php'); ?>
</div><!-- #sidelayer-right -->
<?php } ?>
<div id="mainlayer">
<?php include($CGIBIN.'/incl_search.html'); ?>
<div id="post-list-wrap">
