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

<div class="line" style="text-align:center">*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*</div>

<div style="text-align:center; font-size: 105%">old twitter parser is retired, don't want to use others but also lazy to rewrite.<br />
so the only option left is to go with the dull-looking and lack-of-feature official API <br />
still better than keeping the HP empty, maybe. &#128580;</div>

<div class="line" style="text-align:center">*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*</div>


<div style="text-align:center">
<a class="twitter-timeline" data-width="800" data-height="600" href="https://twitter.com/kosmoflips?ref_src=twsrc%5Etfw">Tweets by kosmoflips</a> <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
</div>

<?php
$p->html_close();
?>
