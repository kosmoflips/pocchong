</div><!-- #post-list-wrap -->
<?php // <footer-navi>
if (isset($PAGE['navi'])) { ?>
<div id="footer-navi">
<?php include($CGIBIN.'/layout_navi.php'); ?>
</div><!-- .footer-navi -->
<?php } ?>
</div><!-- #mainlayer-->
<?php if (isset($PAGE['side-right'])) { // right sidebar ?>
<div id="sidelayer-right">
<?php include($CGIBIN.'/layout_side_right.php'); ?>
</div><!-- #sidelayer-right -->
<?php } ?>
</div><!-- #content-wrap -->
</div><!-- #master-wrap -->
<footer id="footer-global">
<?php include($CGIBIN.'/layout_footer.php') ?>
</footer>
<?php if (isset($PAGE['page-extra'])) {
	foreach ($PAGE['page-extra'] as $line) {
		echo $line,"\n";
	}
} ?>
</body>
</html>
