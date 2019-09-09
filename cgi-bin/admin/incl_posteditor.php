<?php #UTF8 anchor (´・ω・｀)
require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/'.'Method_Kiyoism_Remaster.php');
?>
<?php // ---------- write HTML for post entry editing , relies on $edit generated in edit_post.php. won't work if used independently -----------------
	write_html_admin(0);
?>
<div><a href="<?php echo $redirectlist ?>">discard and go back</a></div>
<hr />
<form action="<?php echo $POCCHONG['POST']['edit'] ?>" method="post" accept-charset="utf-8" target="">

<?php
	if (isset($edit['update'])) {
		echo '<input type="hidden" name="update" value="1" />', "\n";
	}
	if (isset($edit['insert'])) {
		echo '<input type="hidden" name="insert" value="1" />', "\n";
	}
?>
<table>
	<tr><td><b>id*</b></td><td><input type="number" name="id" maxlength="11" value="<?php echo $edit['id'] ?>" readonly /></td></tr>
	<tr><td><b>title*</b></td><td><input type="text" name="title" maxlength="255" size="50" value="<?php echo $edit['title'] ?>" required /></td></tr>
	<tr><td><b>epoch*</b></td><td><input type="number" name="epoch" maxlength="12" value="<?php echo $edit['epoch'] ?>" required /> <?php echo time27($edit['epoch'],4,$edit['gmt'],0) ?></td></tr>
	<tr><td><b>gmt*</b></td><td><input type="number" min="-12" max="12" name="gmt" maxlength="2" value="<?php echo $edit['gmt'] ?>" required /></td></tr>
	<tr><td><b>content*</b><br /></td><td><textarea rows="40" cols="80" name="content" required><?php echo $edit['content'] ?></textarea><br /></td></tr>
</table>
	<input type="submit" name="opt" value="Preview" onclick="this.form.target='_blank'" />
	<input type="reset" value="Reset" onclick="return confirm('reset everything?')" />
	<input type="submit" name="opt" value="Submit" onclick="this.form.target='_self'" />
<?php
	if (isset($edit['update'])) {
?>
	<input type="submit" name="opt" value="View" onclick="this.form.target='_blank'" />
	<input type="submit" name="opt" value="DELETE" onclick="return confirm('DELETE?')" onclick="this.form.target='_self'" />
<?php
	}
	echo "</form>\n";
	write_html_admin(1);
?>
