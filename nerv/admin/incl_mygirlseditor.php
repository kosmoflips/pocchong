<?php
// relies on edit_mygirls.php. won't work if used independently
PocPage::html_admin();
?>
<div><a href="<?php echo POC_DB_MG['admin_list']; ?>">go back to list (edits are discarded)</a>
<?php
if (isset($info['update'])) {
?>
|| <a href="<?php echo POC_DB_MG['url'],'?id=',$info['id']; ?>" target="_blank">View</a>
<?php
}
?>
</div>
<hr />

<h4>main info</h4>
<form action="<?php echo POC_DB_MG['save']; ?>" method="post" accept-charset="utf-8" target="">
<?php
if (isset($info['update'])) {
?>
<input type="hidden" name="update" value="1" />
<?php
}
if (isset($info['insert'])) {
?>
<input type="hidden" name="insert" value="1" />
<?php
}
?>

<table><!--info table-->
<tr><td><b>id*</b></td>
	<td><input type="text" name="main[id]" maxlength="11" value="<?php echo $info['id'] ?>" readonly></td></tr>
<tr><td><b>art_id*</b></td>
	<td><input type="number" name="main[art_id]" maxlength="11" value="<?php echo $info['art_id'] ?>" readonly> use SQL if need to change</td></tr>
<tr><td><b>title*</b></td>
	<td><input type="text" name="main[title]" maxlength="255" size="50" value="<?php echo $info['title'] ?>" required pattern="\S.*" /></td></tr>
<tr><td><b>epoch*</b></td>
	<td><input type="number" size="9" name="main[epoch]" maxlength="12" value="<?php echo $info['epoch'] ?>" required /> <?php echo clock27($info['epoch'],0,$info['gmt'],null) ?></td></tr>
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
<tr><td><b>hide entry</b></td>
	<td><select name="main[hide]">
  <option value="0" <?php echo $info['hide']?'':'selected'; ?>>public</option>
  <option value="1" <?php echo $info['hide']?'selected':''; ?>>secret</option>
</select></td></tr>
</table><!--info table ends-->

<hr />

<h4>tags</h4>
<table><!--tag list-->
<tr><td>
<?php
$tagidx=$k->getTags();
foreach ($tagidx as $idx=>$tagid) {
	$chked=0;
	if (!empty($info['tags']) and in_array($idx, $info['tags'])) {
		$chked=1;
	}
?>
<input type="checkbox" name="tags[]" value="<?php echo $idx; ?>" <?php echo $chked?'checked':''; ?> /><?php echo $tagid; ?> ||
<?php
}
?>
</td></tr>
</table><!--tag table ends-->

<hr />

<h4>pieces</h4>
<div>** to completey remove rep_id, select an <u>undefined</u> record and save.</div>
<table>
<tr>
	<th>preview</th>
	<th>rep</th>
	<th>pcs_info</th>
	<th>delete</th>
</tr>
<?php
// append some new lines in case of adding new associated pieces
$mknew=7;
for ($i=1; $i<=$mknew; $i++) {
	$tmpl=$k->prepNew('mygirls_pcs');
	$tmpl['new_pc']=1;
	$tmpl['id']='new'.$i;
	$info['pcs'][]=$tmpl;
}

// write table for pieces
foreach ($info['pcs'] as $pcs) {
	$namepre='pcs[id-'.$pcs['id'].']';
?>
<tr>
<td style="padding:0 !important;">
<?php
if (!empty($pcs['url_preview'])) {
?>
<img src="<?php echo $pcs['url_preview']; ?>" width="120">
<?php
}
?>
</td>
<td>
	<input type="radio" name="set_rep_id" value="<?php echo $pcs['id']; ?>" <?php echo !empty($pcs['is_rep'])?'checked':''; ?> />
</td>
<td>
<b>id:</b><input type="text" name="<?php echo $namepre; ?>[id]" maxlength="11" size="3" value="<?php echo $pcs['id']; ?>" readonly /><br />
<b>img: </b> /img/<input type="text" name="<?php echo $namepre; ?>[img_url]" maxlength="255" size="83" value="<?php echo $pcs['img_url']; ?>" placeholder="subdir/000000_image_file_name.jpg.webp" /><br />
<b>dA: </b> https://www.deviantart.com/kosmoflips/art/<input type="text" name="<?php echo $namepre; ?>[da_url]" maxlength="255" size="45" value="<?php echo $pcs['da_url'] ?>" /><br />
</td>
<td>
<input type="checkbox" name="DEL_pcs[]" value="<?php echo $pcs['id']; ?>" <?php echo isset($pcs['new_pc'])?"disabled":""; ?>/>
</td>
</tr>
<?php
}
?>
</table>
<input type="submit" name="opt" value="Save" onclick="this.form.target='_self'" />
<input type="reset" value="Reset" onclick="return confirm('reset everything?')" />
<?php
if (!isset($info['insert'])) { // this is an existing entry
?>
<input type="submit" name="opt" value="DELETE selected pieces" onclick="return confirm('DELETE selected pieces?') " onclick="this.form.target='_self' " />
<input type="submit" name="opt" value="DELETE entire entry" onclick="return confirm('DELETE entire entry?') " onclick="this.form.target='_self' " />
<?php
} ?>
</form>
<?php
PocPage::html_admin(1);
?>
