<div id="menu-out">
<ul id="menu">
<li><a href="<?php echo $POCCHONG['POST']['url'] ?>">Days</a></li>
<li><a href="<?php echo $POCCHONG['ARCHIV']['url_archiv'] ?>">Archiv</a>
	<ul class="sub-menu">
		<li><a href="<?php echo $POCCHONG['ARCHIV']['url_calendar']?>">Calendar</a>
		</li></ul>
</li>
<li><a href="<?php echo $POCCHONG['MYGIRLS']['url'] ?>">MyGirls</a></li>
<li><a href="/backyard">Backyard</a>
	<ul class="sub-menu">
<?php // links here open in new window
	foreach ($POCCHONG['STATIC']['set'] as $ff => $tt) {
		printf ('<li><a href="%s" target="_blank">%s</a></li>%s',
				$ff, $tt, "\n" );
	}
?>
	</ul>
</li>
<li><a href="/about">About</a></li>
<li style="background: none;min-width:20px;border:none"><a href="javascript:toggleDiv('g-search-area');"><img src="/deco/img/search_icon.png" width="17" alt="" /></a></li>
</ul>
</div><!-- menu-out -->
