<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/synapse.php');

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


// --------- write html ------------
PocPage::html_admin();
if (!$loginflag) { // login failed. show login form ?>
<div style="text-align: center;">
<?php
	print_errors($error);
	include (NERV.'/admin/incl_loginform.html');
?>
</div>
<?php
} else { // login okay. set up session values
?>
logged in as: <?php echo $_SESSION['username'] ?><br />
session timeout: <?php echo clock27($_SESSION["time_out"],0,-7,1) ?><br />
<hr />
<?php
include(NERV.'/admin/incl_controlpanel.html');
}
PocPage::html_admin(1);


// --------------- sub -------------------
function print_errors($error=null) {
	if (isset($error)) {
		echo "<div>\n";
		foreach ($error as $line) {
			echo $line, "<br />\n";
		}
		echo "</div>\n";
	}
}
function login($usr='', $pw='') {
	if ($usr and $pw) {
		$usr=strtolower($usr);
		$admin_info=readini(ROOT.POC_DB['ADMIN']['userdata']);
		if (isset($admin_info[$usr])) {
			if (password_verify ( ($usr.$pw), $admin_info[$usr]) ) {
				# generate pass by running password_hash( (lowercase_user+pass ), PASSWORD_BCRYPT )
				$_SESSION["POCCHONG_LOGIN_TOKEN"] = true;
				$_SESSION["username"] = $usr;
				$_SESSION["time_out"] = time() + 3600* POC_DB['ADMIN']['timeout'];
				return 1;
			}
		}
	}
	return 0;
}
function logout() {
	session_destroy();
	header("Location: /a/");
}
?>
