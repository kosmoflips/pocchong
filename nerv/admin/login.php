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
	if (!$loginflag) { // login failed. show login form
		echo '<div style="text-align: center;">',"\n";
		if (isset($error)) {
			echo "<div>\n";
			foreach ($error as $line) {
				echo $line, "<br />\n";
			}
			echo "</div>\n";
		}
		include (NERV.'/admin/incl_loginform.html');
		echo '</div>',"\n";
	}
	PocPage::html_admin(1);
}

?>
