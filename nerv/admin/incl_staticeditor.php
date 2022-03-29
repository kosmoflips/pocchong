<?php // ---------- write HTML for page entry editing , relies on $edit generated in edit_post.php. won't work if used independently -----------------
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
	<tr><td><b>title*</b></td><td><input type="text" name="title" maxlength="255" size="50" value="<?php echo $edit['title'] ?>" placeholder="page title" required /></td></tr>
	<tr><td><b>description</b></td><td><input type="text" name="desc" maxlength="255" size="50" value="<?php echo $edit['desc'] ?>" placeholder="brief page description" /></td></tr>
	<tr><td><b>permalink*</b></td><td><input type="text" name="perma" maxlength="25" value="<?php echo $edit['perma'] ?>" placeholder="page_tag1" /> !! use a-z, dash, underscore only !!</td></tr>
	<tr><td><b>extra css/js/link</b><br /></td><td><textarea class="lined" cols="100" rows="10" name="extra"><?php echo $edit['extra'] ?></textarea><br /></td></tr>
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
	} ?>
</form>
<?php
PocPage::html_admin(1);
?>
