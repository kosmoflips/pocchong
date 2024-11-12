<div id="footer-navi">
<?php
if ($this->navi['pair']) { // next or prev
	print_navi_pair($this->navi['pair']);
}
if (isset($this->navi['bar'])) { // navi bar 1 2 3 ... last
	print_navi_bar($this->navi['bar']);
}
?>
</div><!-- .footer-navi -->

<?php // ------------- subs -----------
function print_navi_pair ($npair=null) {
	if (!isset($npair)) {
		return null;
	}
	echo '<div class="navi-single"><!-- single navi wrap -->',"\n";
	if (isset($npair['prev'])) { // &#8678; = left pointing arrow
		printf ('<div class="navi-prev"><a href="%s">&#8678; %s</a></div>%s', $npair['prev']['url'], $npair['prev']['title'], "\n");
	}
	if (isset($npair['next'])) { // &#8680; = right pointing arrow
		printf ('<div class="navi-next"><a href="%s">%s &#8680;</a></div>%s', $npair['next']['url'], $npair['next']['title'], "\n");
	}
	echo '</div><!-- single navi wrap -->',"\n";
}

function print_navi_bar($bar=null) {
	if (!isset($bar)) {
		return null;
	}
	echo '<div class="navi-bar">',"\n";
	if ($bar['prev']) { //9664 = left pointing triangle
		printf ('<span><a href="%s">&#9664;&#9664;</a></span>', mk_page_view_url($bar['tableid'],$bar['prev'],$bar['admin_list_mode']));
	}
	foreach ($bar['block'] as $block) {
		if ($block[0]==0) {
			echo '<span>&#65381;&#65381;</span>';
		} else {
			for ($i=$block[0];$i<=$block[1];$i++) {
				$navi_class='navi-bar-square';
				if ($i==$bar['curr']) { // self
					$navi_class.='-self';
				}
				printf ('<span class="%s"><a href="%s">%d</a></span>', $navi_class, mk_page_view_url($bar['tableid'], $i,$bar['admin_list_mode']), $i,);
			}
		}
	}
	if ($bar['next']) { //9654 = right pointing triangle
		printf ('<span><a href="%s">&#9654;&#9654;</a></span>', mk_page_view_url($bar['tableid'],$bar['next'],$bar['admin_list_mode']));
	}
	echo '</div><!-- .navi-bar -->',"\n";
}
?>
