<?php #UTF8 anchor (´・ω・｀)
require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/'.'Method_Kiyoism_Remaster.php');
$_URLPRE='/cgi-bin/admin/';
?>
<?php
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

?>
<?php // --------- write html ------------

write_html_admin(0);
if (!$loginflag) { // login failed. show login form
	echo '<div style="text-align: center">', "\n";
	print_errors($error);
	include ($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/admin/incl_loginform.html');
	echo "</div>\n";
} else { // login okay. set up session values
	echo 'logged in as: ', $_SESSION['username'],"<br />\n";
	echo "session timeout: ",time27($_SESSION["time_out"],0,-7,1),"<br />\n";
	echo "<hr />\n";
	include($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/admin/incl_controlpanel.html');
}
write_html_admin(1);
?>
<?php // --------------- sub -------------------
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
		$admin_info=array(
			'kiyoko' => '9077b986873fcd9f5734b15d774a527a' // canari + VANILLA < php version
		);
		if (isset($admin_info[$usr])) {
			$VANILLA='silent hill+biohazard+siren';
			$md5test=md5($pw.$VANILLA);
			if ($admin_info[$usr] == $md5test) {
				global $POCCHONG;
				$_SESSION["POCCHONG_LOGIN_TOKEN"] = true;
				$_SESSION["username"] = $usr;
				$_SESSION["time_out"] = time() + 3600* $POCCHONG['ADMIN']['timeout'];
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