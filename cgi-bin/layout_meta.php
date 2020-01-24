<meta content="text/html; charset=UTF-8" http-equiv="Content-Type"/><meta name="generator" content="<?php echo $POCCHONG['engine'] ?>" />
<title><?php echo (isset($PAGE['title'])? ($PAGE['title'].' | '.$POCCHONG['title']) : $POCCHONG['title']) ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo $POCCHONG['css'] ?>" />
<script src="<?php echo $POCCHONG['jquery'] ?>"></script>
<script src="/deco/js/togglediv.js"></script>
<?php // custom head js. if there's special JS need to be inside body, don't put here
if (isset($PAGE['js'])) {//extra js in array
	foreach ($PAGE['js'] as $js) { ?>
<script src="<?php echo $js ?>"></script>
<?php	}
} ?>
<?php if (isset($PAGE['css'])) {//extra css array
	foreach ($PAGE['css'] as $css) { ?>
<link rel="stylesheet" type="text/css" href="<?php echo $css ?>" />
<?php	}
} ?>
<?php if (isset($PAGE['head-extra'])) {//other, inline css/js
	foreach ($PAGE['head-extra'] as $line) {
		echo $line,"\n";
	}
} ?>
