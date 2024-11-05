<?php // ---------- write HTML for post entry editing , relies on $edit generated in edit_post.php. won't work if used independently -----------------
PocPage::html_admin();
?>
<div><a href="<?php echo POC_DB_POST['admin_list']; ?>">go back to list (edits are discarded)</a>
<?php
if (isset($edit['update'])) {
?>
|| <a href="<?php echo POC_DB_POST['url'],'?id=',$edit['id']; ?>" target="_blank">View</a></div>
<?php
}
?>

<hr />
<form action="<?php echo POC_DB_POST['save']; ?>" method="post" accept-charset="utf-8" target="">

<?php
	if (isset($edit['update'])) { ?>
<input type="hidden" name="update" value="1" />
<?php	}
	if (isset($edit['insert'])) { ?>
<input type="hidden" name="insert" value="1" />
<?php	}
?>
<table>
<tr><td><b>id*</b></td><td><input type="number" name="entry[id]" maxlength="11" value="<?php echo $edit['id'] ?>" readonly /></td></tr>
<tr><td><b>title*</b></td><td><input type="text" name="entry[title]" maxlength="255" size="50" value="<?php echo $edit['title'] ?>" required /></td></tr>
<tr><td><b>epoch*</b></td><td><input type="number" name="entry[epoch]" maxlength="12" value="<?php echo $edit['epoch'] ?>" required /> <?php echo clock27($edit['epoch'],0,$edit['gmt'],0) ?></td></tr>
<tr><td><b>year*</b></td><td>20<input type="number" name="entry[year]" maxlength="2" value="<?php echo ($edit['year']??(date('Y')-2000)) ?>" readonly /> use SQL if need to change to another year</td></tr>
<tr><td><b>gmt*</b></td><td><input type="number" min="-12" max="12" name="entry[gmt]" maxlength="2" value="<?php echo $edit['gmt'] ?>" required /></td></tr>
<tr><td><b>hide entry</b></td>
<td><select name="entry[hide]">
<option value="0" <?php echo $edit['hide']?'':'selected'; ?>>public</option>
<option value="1" <?php echo $edit['hide']?'selected':''; ?>>secret</option>
</select></td></tr>


<tr><td><b>content*</b><br /></td><td><textarea class="lined" cols="100" rows="40" name="entry[content]" required><?php echo $edit['content'] ?></textarea><br /></td></tr>
</table>
<input type="submit" name="opt" value="Preview" onclick="this.form.target='_blank'" />
<input type="submit" name="opt" value="Save" onclick="this.form.target='_self'" />
<input type="reset" value="Reset" onclick="return confirm('reset everything?')" />
<?php
if (isset($edit['update'])) {
?>
<input type="submit" name="opt" value="DELETE entry" onclick="return confirm('DELETE this entry?')" onclick="this.form.target='_self'" />
<?php
}
?>
</form>

<?php
PocPage::html_admin(1);
?>
