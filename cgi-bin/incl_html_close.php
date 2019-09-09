<?php #UTF8 anchor (´・ω・｀)
require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/'.'Method_Kiyoism_Remaster.php');
?>
</div><!-- .post-outer -->
</div><!-- #mainlayer-->
<div id="footer-outer">
<?php
	// NAVI for all pages or 1 single entry	
	if (isset($navi1)) {
		_print_footer_navi($navi1['prev']['title'], $navi1['prev']['url'],1);
		_print_footer_navi($navi1['next']['title'], $navi1['next']['url'],0);
	}
	elseif (isset($naviset)) {
		print_navi_bar($naviset['navi'], $naviset['turn'], $naviset['currpage'],$naviset['baseurl']);
	}
?>
</div><!-- .footer-outer -->
</div><!-- #outlayer -->
<div id="footer-global">
<a href="/about">2006-<?php echo date('Y')?> kiyoko@FairyAria</a>
</div>
</body>
</html>
	