<?php 	// ------------ system config, keep on top----------------

# SUPER IMPORTANT INFO:
# TO MAKE SURE JUMP WORKS PROPERLY, THERE SHOULD NOT BE ANY EXTRA WHITE SPACE OUTSIDE OF ANY <php> TAGS!

define ('NERV', $_SERVER['DOCUMENT_ROOT'].'/nerv');

// ----- load db and layout libs -----
require_once (NERV.'/poc_db.php'); // db(sqlite) connection
require_once (NERV.'/poc_page.php'); // page layout

// ------ get site config through ini ------
define('POC_DB', readini(NERV.'/db_config.ini') );
define('POC_META', POC_DB['META'] );
define('POC_LAYOUT', NERV.'/layout' );

// ----- other internal constants -----
define('POC_YEAR_START', 2006); # first posted year

// ----- add session , if logged on, can see 'edit' button -----
session_start();
if (isset($_SESSION) and isset($_SESSION["time_out"]) and ($_SESSION["time_out"]<time())) {
	session_destroy();
}
chklogin();
?>
<?php // ----- system stuff -----
function show_response ($code=200) { # e.g. 404, 500
	http_response_code($code);
	echo "<h1>HTTP Resonse Code: ", $code, '<hr />┐(・ω・)┌</h1>';
	die();
}
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
function number2roman ($number=0) {
	# code from: https://stackoverflow.com/a/15023547/3566819
	if ($number<=0) {
		return ($number);
	}
	$map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
    $returnValue = '';
    while ($number > 0) {
        foreach ($map as $roman => $int) {
            if($number >= $int) {
                $number -= $int;
                $returnValue .= $roman;
                break;
            }
        }
    }
    return $returnValue;
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
		// '&#9834;', #♪
		// '&#9835;', #♫
		// '&#9836;', #♬
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
function chklogin($redirect_to_login_page=0) {
	if (isset($_SESSION['POCCHONG_LOGIN_TOKEN'])) {
		return 1;
	} else {
		if ($redirect_to_login_page) { // if given, redirect to login page when login fails
			jump('/a/');
			die ('log in is required, click <a href="/a/">here</a> to log in.'); # put a die here just in case so following contents won't be shown
		}
		return 0;
	}
}
function print_edit_button ($edit_url='') {
	if (chklogin() and $edit_url) {
		echo '<div class="inline-box"><a href="',$edit_url,'">Edit</a></div>',"\n";
	}
}
?>
<?php // ----- mygirls related -----
function mk_mg_img_url ($path='') { # convert stored in db img path to site-defined img path
# made on 2022-oct-28, since i switch to host image on localhost instead of google
	return ('/img/'.$path);
}
function mk_url_google_img ($url='',$size='') { // input  has no https://
# as of 2022-Mar-5 , new url format on blogger: https://blogger.googleusercontent.com/img/a/a_super_long_string=s320
# for old googleusercontent link [https://lh4.googleusercontent.com/string-for-this-img/may-contain-multiple-slashes/s500/], keep as is unless they stop working.
	if (!$url) { return ''; }
	if (!$size) {
		$size='s800';
	}
	if (preg_match('/blogger.googleusercontent.com/',$url)) {
		$url=preg_replace('/=[swh]\d+\/?$/i', '', $url);
		$url2=sprintf ('https://%s=%s', $url,$size);
	}
	else { # old url style
		$t=explode ('/', $url);
		$fname=array_pop($t);
		array_pop($t); #remove size << supposed to always be there, didn't tested!
		// if (!$size or !preg_match('/^[hws]\d+/i', $size)) { #simple check. should work for most cases
		# url has no defined size. OR user doesn't give new size
			// $size='s800';
		// }
		$url2=sprintf ('https://%s/%s/%s', (implode ( '/', $t)), $size, $fname);
	}
	return $url2;
}
function mk_url_da($url='', $epoch=0) { #feed in string after ../art/. uses my dA account
	if ($url) {
		// return "http://kosmoflips.deviantart.com/art/".$url; // old url format
		return "https://www.deviantart.com/kosmoflips/art/".$url;
	} else {
		return '';
	}
}

?>
<?php // ----- static page related -----
function fname2name ($file='') { # split filename by "_" and return name by uc first letter -- unless define a new name later
	# remove last elem: php OR other extension
	$file=basename($file);
	if (preg_match('/\./', $file )) {
		$x0=preg_split('/\./', $file);
		array_pop($x0);
		$x1=$x0[0];
	} else {
		$x1=$file;
	}
	$fsub=preg_split('/_/', $x1);
	foreach (array_keys($fsub) as $i) {
		$fsub[$i]=ucfirst($fsub[$i]);
	}
	return (implode(' ',$fsub) );
}
function static_page_open ($title='No Title') {
	$symbol=rand_deco_symbol();
	echo '<h2>', $symbol,' ', $title,' ',$symbol, '</h2>', "\n";
	echo '<article>', "\n";
}
?>
<?php // -------------- admin edit page related --------------
function write_preview_sash () {
	?>
<div style="z-index:20;position:fixed;background:rgba(0,0,0,0.7);padding:20px 0;text-align:center;display:block;width:100%;left:-100px;top:50px;font-size:30px;font-weight:bold;color:white;transform: rotate(-20deg)">PREVIEW</div>
<?php
}
function print_system_msg ($msg='') { // admin submit-page edit only
	if (!empty($msg)) {
		?>
<div class="system-msg">system message: <?php echo $msg ?></div>
<?php
	}
}

?>
