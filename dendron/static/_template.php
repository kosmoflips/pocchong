<?php
// ＵＴＦ－８　ＡＮＣＨＯＲ
$title=fname2name($_SERVER['REDIRECT_URL']);
$p->title=$title;
$p->html_open();
static_page_open($title);
?>

<!-- html content code , NO <article> tag! -->