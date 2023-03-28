<?php
PocPage::html_admin();
if (!$loginflag) { // login failed. show login form ?>
<div style="text-align: center;">
<?php
	print_errors($error);
	include (NERV.'/admin/incl_loginform.html');
?>
</div>
<?php
}
PocPage::html_admin(1);
?>
