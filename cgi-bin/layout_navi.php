<?php if (isset($PAGE['navi']['pair'])) { // next or prev ?>
<div><!-- single navi wrap -->
<?php if (isset($PAGE['navi']['pair']['prev'])) { // &#8678; = left pointing arrow ?>
<div class="navi-prev"><a href="<?php echo $PAGE['navi']['pair']['prev']['url'] ?>">&#8678; <?php echo $PAGE['navi']['pair']['prev']['title'] ?></a></div>
<?php } ?>
<?php if (isset($PAGE['navi']['pair']['next'])) { // &#8680; = right pointing arrow ?>
<div class="navi-next"><a href="<?php echo $PAGE['navi']['pair']['next']['url']?>"><?php echo $PAGE['navi']['pair']['next']['title'] ?> &#8680;</a></div>
<?php } ?>
</div><!-- single navi wrap -->
<?php } ?>
<?php if (isset($PAGE['navi']['bar'])) { // navi bar
	$bar=$PAGE['navi']['bar']; // less typing ?>
<div class="navi-bar">
<?php if ($bar['prev']) { //9664 = left pointing triangle ?>
<span><a href="<?php echo $bar['url'],'/',$bar['prev'] ?>">&#9664;&#9664;</a></span><?php } ?>
<?php // bar block
	foreach ($bar['block'] as $block) {
		if ($block[0]==0) { ?>
<span>..</span>
<?php }
		else {
			for ($i=$block[0];$i<=$block[1];$i++) {
				$navi_class='navi-bar-square';
				if ($i==$bar['curr']) { // self
					$navi_class.='-self';
				} ?>
<span class="<?php echo $navi_class ?>"><a href="<?php echo $bar['url'] ?>/<?php echo $i ?>"><?php echo $i ?></a></span>
<?php }
		}
	} ?>
<?php if ($bar['next']) { //9654 = right pointing triangle ?>
<span><a href="<?php echo $bar['url'],'/',$bar['next'] ?>">&#9654;&#9654;</a></span><?php } ?>
</div><!-- .navi-bar -->
<?php } ?>