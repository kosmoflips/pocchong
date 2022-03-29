<?php // ----------- chk login / password related----------
function chklogin($retreat=0) {
	if (isset($_SESSION['POCCHONG_LOGIN_TOKEN'])) {
		return 1;
	} else {
		if ($retreat) { // if given, redirect to login page when login fails
			header("Location: /a/");
		}
		return 0;
	}
}
function pass2array ($pass='') {
	if (empty($pass)) {
		return false;
	}
	$plen=strlen($pass);
	$parr=array();
	for ($i=0; $i<$plen; $i++) {
		$c=substr ($pass, $i, 1);
		$parr[]=ord($c);
	}
	return $parr;
}
function pass_conv ($str='', $pass='', $passhash='', $encipher=0) {
	if (empty($str) or empty($pass) or empty($passhash)) {
		return false;
	}
	if (!password_verify ($pass, $passhash)) { #hash should be from password_hash($pass, PASSWORD_DEFAULT)
		return false;
	}
	$parr=pass2array($pass);
	$plen=sizeof($parr);
	$cs=0;
	$str2='';
	// $str2o='';
	for ($i=0; $i<strlen($str); $i++) {
		if ($cs==$plen) {
			$cs=0;
		}
		$c=substr ($str, $i, 1);
		if ($encipher) { // normal to digit, addition
			$c2=ord($c) + $parr[$cs];
			// $str2o.=$c2.' ';
		} else { // digit to normal, minus
			$c2=ord($c) - $parr[$cs];
		}
		$str2.=chr($c2);
		$cs++;
	}
	return $str2;
}
?>