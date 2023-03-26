<?php
// relies on edit_mygirls.php. won't work if used independently
PocPage::html_admin();
?>
<div><a href="<?php echo $redirectlist ?>">discard and go back</a></div>
<hr />

<form action="<?php echo $_SERVER['REDIRECT_URL'] ?>" method="post" accept-charset="utf-8" target="">
<?php
	if (isset($info['update'])) { ?>
<input type="hidden" name="update" value="1" />
<?php	}
	if (isset($info['insert'])) { ?>
<input type="hidden" name="insert" value="1" />
<?php	}
?>
<table><!--info table-->
<tr><td><b>id*</b></td>
	<td><input type="text" name="main[id]" maxlength="11" value="<?php echo $info['id'] ?>" readonly></td></tr>
<tr><td><b>title*</b></td>
	<td><input type="text" name="main[title]" maxlength="255" size="50" value="<?php echo $info['title'] ?>" required /></td></tr>
<tr><td><b>epoch*</b></td>
	<td><input type="number" size="9" name="main[epoch]" maxlength="12" value="<?php echo $info['epoch'] ?>" required /> <?php echo clock27($info['epoch'],4,$info['gmt'],null) ?></td></tr>
<tr><td><b>gmt*</b></td>
	<td><input type="number" min="-12" max="12" size="4" name="main[gmt]" maxlength="2" value="<?php echo $info['gmt'] ?>" required /></td></tr>
<tr><td><b>year*</b></td>
	<td><input type="number" name="main[year]" maxlength="2" value="<?php echo ($info['year']??(date('Y')-2000)) ?>" readonly /> (shown as year-2000) if really want to change, use SQL</td></tr>
<tr><td><b>rep_id (current)</b></td>
	<td><input type="number" size="4" name="curr_rep_id" maxlength="11" value="<?php echo $info['rep_id'] ?>" readonly></td></tr>
<tr><td><b>post_id (for liner notes)</b></td>
	<td><input type="number" size="4" name="main[post_id]" maxlength="11" value="<?php echo $info['post_id'] ?>"></td></tr>
<tr><td><b>inspiration</b></td>
	<td><input type="text" name="main[notes]" maxlength="255" size="50" value="<?php echo $info['notes'] ?>"></td></tr>
<tr><td><b>remade from</b></td>
	<td><input type="number" size="4" name="main[remade_from]" maxlength="11" value="<?php echo $info['remade_from'] ?>"> this is a new remake</td></tr>
<tr><td><b>new remake</b></td>
	<td><input type="number" size="4" name="main[remake]" maxlength="11" value="<?php echo $info['remake'] ?>"> this is an old work</td></tr>
</table><!--info table ends-->
<hr />
<table><tr>
<td><!--tag list-->
<?php
		$x=0;
		ksort($tagidx); # sort by tag id
		foreach ($tagidx as $idx=>$tagid) {
			$x++;
			$chked=0;
			if (!empty($info['tags']) and in_array($idx, $info['tags'])) {
				$chked=1;
			}
			printf ('<input type="checkbox" name="tags[]" value="%s" %s />%s%s',
				$idx,
				($chked?'checked':''),
				$tagid,
				($x%8==0?"<br />\n":' || ')  );
		}
?>
</td></tr></table><!--shell table ends-->
<hr />
<table>
	<tr>
		<th>preview</th>
		<th>pcs_info</th>
	</tr>
<?php
	foreach ($info['pcs'] as $pcs) {
		$namepre='pcs[id-'.$pcs['id'].']';
?>
	<tr>
	<td style="padding:0 !important;">
		<?php if ($pcs['url_preview']) {
			echo '<img src="', $pcs['url_preview'], '" width="120">';
			}
			?>
	</td>
	<td>
		<b>id:</b><input type="text" name="<?php echo $namepre ?>[id]" maxlength="11" size="3" value="<?php echo $pcs['id'] ?>" readonly /> || 
		<b>std:</b><input type="checkbox" name="<?php echo $namepre ?>[stdalone]" value="1" <?php echo !empty($pcs['stdalone'])?'checked':'' ?>/> | 
		<b>rep:</b><input type="radio" name="set_rep_id" value="<?php echo $pcs['id'] ?>" <?php echo !empty($pcs['is_rep'])?'checked':'' ?> /> || 
		<b>DELETE this?</b> <input type="checkbox" name="DEL_pcs[]" value="<?php echo $pcs['id'] ?>" /><br />
		<b>da_url: </b>
			https://www.deviantart.com/kosmoflips/art/<input type="text" size="45" name="<?php echo $namepre ?>[da_url]" maxlength="255" size="50" value="<?php echo $pcs['da_url'] ?>" /><br />
		<b>img_url: </b>/img/<input type="text" size="75" name="<?php echo $namepre ?>[img_url]" maxlength="255" size="120" value="<?php echo $pcs['img_url'] ?>" placeholder="g00/000000_image.jpg.webp" />
	</td>
	</tr>
<?php
	}
?>
</table>
<input type="submit" name="opt" value="Save" onclick="this.form.target='_self'" />
<?php
	if (!isset($info['insert'])) { // this is an existing entry
?>
<input type="submit" name="opt" value="View" onclick="this.form.target='_blank' " />
<input type="reset" value="Reset" onclick="return confirm('reset everything?')" />
<input type="submit" name="opt" value="DELETE selected" onclick="return confirm('DELETE selected pieces?') " onclick="this.form.target='_self' " />
<input type="submit" name="opt" value="DELETE" onclick="return confirm('DELETE entire entry?') " onclick="this.form.target='_self' " />
<?php
	} ?>
</form>
<?php
PocPage::html_admin(1);
?>
