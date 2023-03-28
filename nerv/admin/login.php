<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/synapse.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/admin/adminfunc.php');

$error=array();
$loginflag=0;
if (isset($_POST['logout'])) { # log out
	logout();
	$error[]='log out successful';
}
elseif (isset($_POST['login'])) {
	$cuser=$_POST['user'];
	$cpw=$_POST['pw'];
	$loginflag=login($cuser,$cpw);
	if (!$loginflag) {
		$error[]='wrong login info';
	}
} else {
	$loginflag=chklogin();
}

if ($loginflag) {
	include ('superzone.php');
} else { # show log in screen
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
}

?>
