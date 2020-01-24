<div id="menu-out">
<ul id="menu">
<li><a href="<?php echo $POCCHONG['POST']['url'] ?>"><?php echo $POCCHONG['POST']['title'] ?></a></li>
<li><a href="<?php echo $POCCHONG['ARCHIV']['url'] ?>"><?php echo $POCCHONG['ARCHIV']['title'] ?></a>
	<ul class="sub-menu">
		<li><a href="<?php echo $POCCHONG['CALENDAR']['url']?>"><?php echo $POCCHONG['CALENDAR']['title'] ?></a></li>
	</ul>
</li>
<li><a href="<?php echo $POCCHONG['MYGIRLS']['url'] ?>"><?php echo $POCCHONG['MYGIRLS']['title'] ?></a></li>
<li><a href="<?php echo $POCCHONG['STATIC']['url_index'] ?>"><?php echo $POCCHONG['STATIC']['title'] ?></a>
	<ul class="sub-menu">
<?php // links here open in new window
	foreach ($POCCHONG['STATIC']['set'] as $ff => $tt) { ?>
		<li><a href="<?php echo $ff ?>" target="_blank"><?php echo $tt ?></a></li>
<?php	}
?>
	</ul>
</li>
<li><a href="/about">About</a></li>
<!-- may be removed/replaced in next design -->
<li style="background: none;min-width:20px;border:none"><a href="javascript:toggleDiv('g-search-area');"><img src="/deco/img/search_icon.png" width="17" alt="" /></a></li>
</ul>
</div><!-- id="menu-out" -->
