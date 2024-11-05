<?php
$title=fname2name($_SERVER['REDIRECT_URL']);
$p->title=$title;
$p->html_open();
static_page_open($title);
?>

<div style="width: 90%; text-align: center;margin:auto"><textarea rows="5" cols="13" placeholder="type some kanji so I can see it large!" style="font-size: 50pt"></textarea></div>