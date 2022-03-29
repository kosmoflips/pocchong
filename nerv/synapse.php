<?php 	// ------------ system config, keep on top----------------

define ('NERV', $_SERVER['DOCUMENT_ROOT'].'/nerv');

// ------ get site config through ini ------
define('POC_META', readini(NERV.'/meta.ini') );
define('POC_DB', readini(NERV.'/db_config.ini') );
define('POC_DB_FILE', NERV.POC_DB['dbfile'] );
define('POC_LAYOUT', NERV.'/layout' );


// ----- load stuff -----
require_once (NERV.'/lib_oven.php'); // login,cookie,session
require_once (NERV.'/poc_db.php'); // db(sqlite) connection
require_once (NERV.'/poc_layout.php'); // page layout


// ----- add session , if logged on, can see 'edit' button -----
session_start();
if (isset($_SESSION) and isset($_SESSION["time_out"]) and ($_SESSION["time_out"]<time())) {
	session_destroy();
}
chklogin();

?>
<?php // ----- common subs -----
function readini ($file,$soft=true) { # file=ini_file_path; soft=true will NOT return 500 but return null instead
	if (file_exists($file)) {
		$data = parse_ini_file($file,true); # 'true' , parse [sections]
		return ($data);
	} else {
		if (!$soft) {
			http_response_code(500);
			printf ("can't load ini file %s! exit.", $file);
			exit;
		}
		else {
			return null;
		}
	}
}
function rand_deco_symbol($id=0) {
	$set=array( # HTML CODEの形式でより安全だと思う
		'&#10031;', #✯
		'&#10045;', #✽
		'&#10056;', #❈
		'&#10047;', #✿
		'&#10048;', #❀
		'&#10046;', #✾
		'&#9825;', #♡
		'&#10059;', #❋
		'&#10054;', #❆
		'&#10053;', #❅
		'&#9733;', #★
		'&#9734;', #☆
		'&#9825;', #♠
		'&#9826;', #♢
		'&#9828;', #♣
		'&#9831;', #♥
		'&#9834;', #♪
		'&#9835;', #♫
		'&#9836;', #♬
	);
	if ($id) {
		if ($id==-1) {
			return sizeof($set);
		} elseif ($id<sizeof($set)) {
			return $set[$id];
		}
	}
	$randnum=rand(0, (count($set) -1));
	return $set[$randnum];
}
function clock27 ($epoch=null, $format=0, $gmt=-7, $time24=0) { // old name: &time27()
// use either 24 or 27 H mode. process return hash.
# 27-H Mode: time <= 3:00 AM, continue from 24 (0:00 AM=> 24:00, 2:59 AM => 26:59, 3:00 AM => no change)
#as default timezone is -7, when using GMT, e.g. London, need to clearly give "gmt=>0"

	if (isset($epoch)) {
		if ( !preg_match("/^\d+$/", $epoch) ) {
			$epoch=null;
		}
	}
	if (isset($gmt)) {
		preg_match("/^(\s*[+-]?\s*\d+)\s*$/", $gmt, $gmt0);
		if (!isset($gmt0[1]) or ($gmt0[1] < -12 or $gmt0[1] > 12) ) {
			$gmt=null;
		}
	}
	$epoch=isset($epoch)?$epoch:time();
	$gmt=isset($gmt)? $gmt : -7;

// -------------- process time hash ----------------
	$h_offset=date('O'); #server hour offset
	$h_offset=preg_replace('/00$/','',$h_offset);
	$h_offset=preg_replace('/^([+-])0/','$1',$h_offset);
	if ($gmt!=$h_offset) {
		$epoch+=3600*($gmt-$h_offset); #now epoch should be the literal local time
	}
	if ( date('H',$epoch) <=2 and !$time24) { # shift day for 27H if < 3AM and not showing in 24H format
		$shiftday=24;
	} else {
		$shiftday=0;
	}
	$epoch-=$shiftday*60*60;
//------------ output time in format ---------------------------
// NOTE: when writing hour, MUST use {  date('H',$epoch) + $shiftday  }
// test $epoch = 1483607582; actual time = Thu, 05 Jan 2017 02:13:02 -0700 (24H)
	if ($format==1) { return date('M-d', $epoch); } // Jan-04
	elseif ($format==2) { return date('Y',$epoch); } // 2017
	elseif ($format==3) { return date('ymd',$epoch); } // 170104
	elseif ($format==4) { // post format : 2018-Nov-13 (Tue), 26:51@GMT-7
		return sprintf ( "%s, %s:%s@GMT%+02d",
			date("Y-M-d (D)",$epoch),
			(date('H', $epoch) + $shiftday),
			date("i",$epoch),
			$gmt
		);
	}
	elseif ($format==5) { return date('Y/m/d',$epoch); } // YYYY/MM/dd
	elseif ($format==6) { return $epoch; } // epoch
	elseif ($format==7) { // Jan 1, 2020, 25:21
		return (date('M d, Y', $epoch)).sprintf(", %s:%s", (date('H', $epoch) + $shiftday), (date("i", $epoch)));
	}
	else { // default format. January 4, 2017, 26:13 (27H)
		return sprintf ( "%s, %s:%s",
			date('F j, Y', $epoch),
			(date('H', $epoch) + $shiftday),
			date('i', $epoch)
		);
	}
}
function peek($var=null,$stop=0) {
	echo '<pre>';
	var_dump($var);
	echo '</pre>';
	if ($stop) {
		exit;
	}
}
function jump($url='/') { // redirect. saves some writing
	header("Location: ".$url);
	exit;
}
function print_edit_button ($edit_url='') {
	if (chklogin() and $edit_url) {
		echo '<div class="inline-box"><a href="',$edit_url,'">Edit</a></div>',"\n";
	}
}
?>