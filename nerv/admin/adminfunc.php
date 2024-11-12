<?php
function login($usr='', $pw='') {
	if ($usr and $pw) {
		define('TMP_ADMIN_USER_DATA','/binary/userdata.ini');
		$usr=strtolower($usr);
		$admin_info=readini($_SERVER['DOCUMENT_ROOT'].TMP_ADMIN_USER_DATA);
		if (isset($admin_info[$usr])) {
			if (password_verify ( ($usr.$pw), $admin_info[$usr]) ) {
				# generate pass by running password_hash( (lowercase_user+pass ), PASSWORD_BCRYPT )
				$_SESSION["POCCHONG_LOGIN_TOKEN"] = true;
				$_SESSION["username"] = $usr;
				$_SESSION["time_out"] = time() + 3600 * 2.5;
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
