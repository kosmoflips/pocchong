<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>Super Zone</title>
<?php
	global $POCCHONG;
	print_link_in_head(null,$POCCHONG['FILE']['css_admin'],1);
	if (isset($linedjs)) {
		use_lined_textarea();
	}
?>
</head>
<body>
<div class="main">
