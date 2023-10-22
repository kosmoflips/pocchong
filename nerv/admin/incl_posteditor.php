<?php // ---------- write HTML for post entry editing , relies on $edit generated in edit_post.php. won't work if used independently -----------------
PocPage::html_admin();
?>
<div><a href="<?php echo $redirectlist ?>">discard and go back</a></div>
<hr />
<form action="<?php echo $_SERVER['REDIRECT_URL'] ?>" method="post" accept-charset="utf-8" target="">

<?php
	if (isset($edit['update'])) { ?>
<input type="hidden" name="update" value="1" />
<?php	}
	if (isset($edit['insert'])) { ?>
<input type="hidden" name="insert" value="1" />
<?php	}
?>
<table>
	<tr><td><b>id*</b></td><td><input type="number" name="id" maxlength="11" value="<?php echo $edit['id'] ?>" readonly /></td></tr>
	<tr><td><b>hide*</b></td><td><input type="text" maxlength="11" value="False" readonly /> Rarely, if need to hide this post, use SQL</td></tr>
	<tr><td><b>title*</b></td><td><input type="text" name="title" maxlength="255" size="50" value="<?php echo $edit['title'] ?>" required /></td></tr>
	<tr><td><b>epoch*</b></td><td><input type="number" name="epoch" maxlength="12" value="<?php echo $edit['epoch'] ?>" required /> <?php echo clock27($edit['epoch'],0,$edit['gmt'],0) ?></td></tr>
	<tr><td><b>year*</b></td><td><input type="number" name="year" maxlength="2" value="<?php echo ($edit['year']??(date('Y')-2000)) ?>" readonly /> ("year" - 2000), use SQL if need to change to another year</td></tr>
	<tr><td><b>gmt*</b></td><td><input type="number" min="-12" max="12" name="gmt" maxlength="2" value="<?php echo $edit['gmt'] ?>" required /></td></tr>
	<tr><td><b>content*</b><br /></td><td><textarea class="lined" cols="100" rows="40" name="content" required><?php echo $edit['content'] ?></textarea><br /></td></tr>
</table>
	<input type="submit" name="opt" value="Preview" onclick="this.form.target='_blank'" />
	<input type="submit" name="opt" value="Save" onclick="this.form.target='_self'" />
	<input type="reset" value="Reset" onclick="return confirm('reset everything?')" />
<?php
	if (isset($edit['update'])) {
?>
	<input type="submit" name="opt" value="View" onclick="this.form.target='_blank'" />
	<input type="submit" name="opt" value="DELETE" onclick="return confirm('DELETE?')" onclick="this.form.target='_self'" />
<?php
	}
?>
</form>

<?php
PocPage::html_admin(1);
?>
