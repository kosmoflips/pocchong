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
	jump ('/a/superzone.php');
} else { # show log in screen
	include('incl_login_screen.php');
}

?>
