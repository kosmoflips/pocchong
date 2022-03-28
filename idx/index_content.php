<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/synapse.php');

$p=new PocPage;
$p->head( array( // index specific <head> stuff
		'css'=>array('/deco/css/index.css'),
		'extra'=>array('<link href="https://fonts.googleapis.com/css?family=Black+Ops+One&display=swap" rel="stylesheet">')
	)
);
$p->html_open();
?>

<div id="top-tagline"><?php include('oneliner.php'); ?></div>

<div class="line" style="text-align:center">*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*</div>

<div id="left-half">

<div class="side-square">
<div class="recentpost">
<?php include('randomdays.php'); ?>
</div>
</div>

</div><!-- LEFT HALF-->

<div id="right-half">


<div class="side-square">
<?php include ('randomgirls.php') ?>
</div>

<div class="side-square">
<?php // include ('search.php') ?>
</div>

<div class="side-square">
<?php include ('themeinfo.php') ?>
</div>

</div><!-- RIGHT HALF-->

<?php
$p->html_close();

?>
