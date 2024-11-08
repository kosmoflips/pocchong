<!-- google fonts -->
<link href="https://fonts.googleapis.com/css?family=Milonga&display=swap&subset=latin-ext" rel="stylesheet" /><!-- font-family: 'Milonga', cursive; -->
<link href="https://fonts.googleapis.com/css2?family=Exo&display=swap" rel="stylesheet" />
<!-- javascript -->
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
<script src="/deco/js/lightbox/lightbox.js"></script>
<script src="/deco/js/change_css_in_situ.js"></script>
<!-- theme css -->
<link rel="stylesheet" type="text/css" href="<?php
$cssfile='/deco/css/theme_'.($_COOKIE['theme']??'').'.css';
$cssfile2=$_SERVER['DOCUMENT_ROOT'].$cssfile; // real path on disk
if (file_exists($cssfile2)) {
	echo $cssfile;
} else {
	echo '/deco/css/theme_'.POC_THEME[0].'.css';
}
?>" />
<!-- layout css -->
<link rel="stylesheet" type="text/css" href="/deco/js/lightbox/lightbox.css" />
<link rel="stylesheet" type="text/css" href="/deco/css/site_base.css" />
