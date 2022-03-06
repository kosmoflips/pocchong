<?php 	// ------------ system config, keep on top----------------
/* --------- basic info ------------ UTF8 anchor (´・ω・｀)

LAST MODIFIED : 2020-Jan-23
wrap more things into function, convert global vars to const if possible, to avoid name duplication (although it's rare)

---- enables requirements -----
enable extension=mbstring in php.ini
enable extension=mysqli in php.ini
enable extension=pdo_sqlite in php.ini
many subs are inheritated from .pm, so they may not be the best solution in php

-----------------*/

_initialise();

function _initialise () {
	$sysini=$_SERVER['DOCUMENT_ROOT'].'/cgi-bin/pocchong_config.ini';
	if (file_exists($sysini)) {
		$pdata= parse_ini_file($sysini,true); # 'true' , parse [sections]
	} else {
		die ('the config file '.$sysini.' is not found from specified location, fix your code!');
	}

	foreach ($pdata['PATH'] as $p=>$v) {
		$pdata['PATH'][$p] = $_SERVER['DOCUMENT_ROOT'].'/cgi-bin/'.$v;
	}
	define ('POCCHONG', $pdata);

	session_start();
	if (isset($_SESSION) and isset($_SESSION["time_out"]) and ($_SESSION["time_out"]<time())) {
		session_destroy();
	}
	chklogin();

	return 1;
}

?>
<?php // ----- require mods -----
require_once (POCCHONG['PATH']['mod'].'/poc_db.php');
require_once (POCCHONG['PATH']['mod'].'/poc_pagelayout.php');
require_once (POCCHONG['PATH']['mod'].'/poc_calendar.php');

?>
<?php // -------- navi-bar related ---------------
function mk_navi_pair ($k=null,$table='',$cid=1, $url='') {
	// var_dump ($k);
	if (!$k or !$table) { return array(); }
	$n0=$k->_getNext($table,$cid,0);
	$p0=$k->_getNext($table,$cid,1);
	$navi1=array();
	if (isset($n0)) {
		$navi1['next']['url']=sprintf ('%s/%d', $url, $n0['id']);
		$navi1['next']['title']=$n0['title'];
	}
	if (isset($p0)) {
		$navi1['prev']['url']=sprintf ('%s/%d', $url, $p0['id']);
		$navi1['prev']['title']=$p0['title'];
	}
	return $navi1;
}
function mk_navi_bar ($first=1,$last=1,$perpage=1, $curr=1, $step=0, $urlbase='/') { // new version! urlbase has leading but no trailing slash / offset, useful for year as page e.g. 2006..2009
	$smin=5; // minimal for one side is 5
	if ($perpage>($last-$first+1)) {
		$perpage=5; // randomly given
	}
	if ($curr>$last) {
		$curr=1;
	}
	$block=array();
	$begin=0;
	$end=0;
	if (($curr-$step-1)<=$first) {
		$begin=$first;
	}
	if ($curr+$step>=($last-1)) {
		$end=$last;
	}

	if ($begin==$first and $end==$last) { //whole bar
		$block[]=array($first,$last);
	} else {
		if ($begin==$first) { // first half
			if (($curr+$step-$first+1)<$smin) {
				$block[]=array($first,($smin+$first-1));
			} else {
				$block[]=array($first,($curr+$step));
			}
			$block[]=array(0,0);
			$block[]=array($last,$last);
		}
		elseif ($end==$last) { //last half
			$block[]=array($first,$first);
			$block[]=array(0,0);
			if (($last-$smin)<($curr-$step)) {
				$block[]=array(($last-$smin+1),$last);
			} else {
				$block[]=array(($curr-$step),$last);
			}
		}
		else {//middle
			$block[]=array($first,$first);
			$block[]=array(0,0);
			$block[]=array(($curr-$step),($curr+$step));
			$block[]=array(0,0);
			$block[]=array($last,$last);
		}
	}
	return array(
		'block'=>$block,
		'prev'=>($curr==$first)?0:($curr-1),
		'next'=>($curr==$last)?0:($curr+1),
		'curr'=>$curr,
		'url'=>$urlbase, // ready to be connected with id e.g. "url/id"
	);
}
function calc_total_page($totalrows=1,$max_per_page=1) {
	$pgtotal=intdiv ($totalrows,$max_per_page);
	if ($totalrows%$max_per_page) { $pgtotal++; }
	return $pgtotal;
}
function calc_page_offset($curr_page=1,$max_per_page=0) { #return offset for SQL. MUST ensure $curr (current page) is right . better use after &verify_current_page()
	if ($curr_page <1) {
		$curr_page=1;
	}
	return (($curr_page-1)*$max_per_page);
}

?>
<?php // -------------- print html element --------------
function write_preview_sash () {
	echo '<div style="z-index:20;position:fixed;background:rgba(0,0,0,0.7);padding:20px 0;text-align:center;display:block;width:100%;left:-100px;top:50px;font-size:30px;font-weight:bold;color:white;transform: rotate(-20deg)">PREVIEW</div>',"\n";
}
function print_edit_button ($edit_url='') {
	if (chklogin() and $edit_url) {
		echo '<div class="inline-box"><a href="',$edit_url,'">Edit</a></div>',"\n";
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
function print_system_msg ($msg='') { // admin submit-page edit only
	if (!empty($msg)) {
		echo '<div class="system-msg">system message: '.$msg.'</div>';
	}
}

?>
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
<?php //-------------- misc -------------
function time27 ($epoch=null, $format=0, $gmt=-7, $time24=0) { // use either 24 or 27 H mode. process return hash.
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
function rand_array($total=2) {
	if ($total<2) { return array(0); } // 1 elem
	$numbers = range(0, ($total-1));
	shuffle($numbers);
	return $numbers;
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
function mk_url_da($url='') { #feed in string after ../art/. uses my dA account
	if ($url) {
		// return "http://kosmoflips.deviantart.com/art/".$url; // old url format
		return "https://www.deviantart.com/kosmoflips/art/".$url;
	} else {
		return '';
	}
}
?>
