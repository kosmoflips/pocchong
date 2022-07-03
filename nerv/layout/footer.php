<?php # get git version
$gvfile=ROOT.POC_DB['git-version-file'];
if (file_exists($gvfile)) {
	$gver=file_get_contents($gvfile);
	$gver1=substr($gver,0,7);
} else {
	$gver=null;
}
?>
<div class='credit'><a href="/about"><?php echo POC_DB['year-start'] ?>-<?php echo date('Y') ?> <?php echo POC_META['credit']?></a> | <a href="https://github.com/kosmoflips/pocchong/<?php echo $gver?'commit/'.$gver:''; ?>">version: <?php echo $gver?$gver1:'unknown'; ?></a></div>
