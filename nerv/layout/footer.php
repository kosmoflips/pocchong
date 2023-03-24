<?php # get git version
$gvfile=$_SERVER['DOCUMENT_ROOT'].'/.git/refs/heads/master'; # master only, doesn't consider if on a branch
if (file_exists($gvfile)) {
	$gver=file_get_contents($gvfile);
	$gver1=substr($gver,0,7);
} else {
	$gver=null;
}
?>
<div class='credit'><a href="/about"><?php echo POC_YEAR_START; ?>-<?php echo date('Y') ?> <?php echo POC_META['credit']?></a> | <a href="https://github.com/kosmoflips/pocchong/<?php echo $gver ? 'tree/'.$gver : ''; ?>" target="_blank ">version: <?php echo $gver?$gver1:'unknown'; ?></a> | <?php show_theme_selector(); ?></div>
