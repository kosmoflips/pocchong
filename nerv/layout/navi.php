<?php
if ($this->navi['pair']) { // next or prev
	print_navi_pair($this->navi['pair']);
}
if (isset($this->navi['bar'])) { // navi bar 1 2 3 ... last
	print_navi_bar($this->navi['bar']);
}

// ---------print_navi_pair -------
function print_navi_pair ($npair=null) {
	if (!isset($npair)) {
		return null;
	}
	?>
<div class="navi-single"><!-- single navi wrap -->
<?php
	if (isset($npair['prev'])) { // &#8678; = left pointing arrow
	?>
<div class="navi-prev"><a href="<?php echo $npair['prev']['url'] ?>">&#8678; <?php echo $npair['prev']['title'] ?></a></div>
<?php
	}
	if (isset($npair['next'])) { // &#8680; = right pointing arrow
	?>
<div class="navi-next"><a href="<?php echo $npair['next']['url']?>"><?php echo $npair['next']['title'] ?> &#8680;</a></div>
<?php
	}
	?>
</div><!-- single navi wrap -->
<?php
}

// ---------print_navi_bar -------
function print_navi_bar($bar=null) {
	if (!isset($bar)) {
		return null;
	}
	?>
<div class="navi-bar">
<?php
	if ($bar['prev']) { //9664 = left pointing triangle
	?>
<span><a href="<?php echo $bar['url'],$bar['prev'] ?>">&#9664;&#9664;</a> </span><?php } ?>
<?php // bar block
	foreach ($bar['block'] as $block) {
		if ($block[0]==0) { ?>
<span>&#65381;&#65381;</span>
<?php
		} else {
			for ($i=$block[0];$i<=$block[1];$i++) {
				$navi_class='navi-bar-square';
				if ($i==$bar['curr']) { // self
					$navi_class.='-self';
				}
			?>
<span class="<?php echo $navi_class ?>"><a href="<?php echo $bar['url'], $i ?>"><?php echo $i ?></a></span>
<?php
			}
		}
	}
	if ($bar['next']) { //9654 = right pointing triangle
	?>
<span> <a href="<?php echo $bar['url'],$bar['next'] ?>">&#9654;&#9654;</a></span>
<?php
	}
	?>
</div><!-- .navi-bar -->
<?php
}
?>