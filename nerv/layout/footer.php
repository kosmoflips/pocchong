<?php # get git version
$headfile=$_SERVER['DOCUMENT_ROOT'].'/.git/HEAD';
$headref=file_get_contents($headfile);
preg_match('/ref:\s*(.+?)$/', $headref,$loc1);
$gvfile=$_SERVER['DOCUMENT_ROOT'].'/.git/'.($loc1[1]??'refs/heads/master'); # current head hash or master (default)
if (file_exists($gvfile)) {
	$gver=file_get_contents($gvfile);
	$gver1=substr($gver,0,7);
} else {
	$gver=null;
}
?>
<div class='credit'><a href="/about"><?php echo POC_YEAR_START; ?>-<?php echo date('Y') ?> <?php echo POC_META['credit']?></a> | <a href="https://github.com/kosmoflips/pocchong/<?php echo $gver ? 'tree/'.$gver : ''; ?>" target="_blank ">version: <?php echo $gver?$gver1:'unknown'; ?></a> | <?php show_theme_selector(); ?></div>
