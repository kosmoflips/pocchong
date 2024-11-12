<?php 	// ------------ system config, keep on top----------------

// ------ everything starts here -------
define('NERV', $_SERVER['DOCUMENT_ROOT'].'/nerv');

// ------ site constants/configs ------
include(NERV.'/constants.php'); # defined constants

// ----- load db and layout libs -----
require_once (NERV.'/poc_db.php'); // db(sqlite) connection
require_once (NERV.'/poc_page.php'); // page layout

// ----- add session , if logged on, can see 'edit' button -----
session_start();
if (isset($_SESSION) and isset($_SESSION["time_out"]) and ($_SESSION["time_out"]<time())) {
	session_destroy();
}
chklogin();
?>
<?php // ----- server stuff -----
function show_response ($code=200) { # e.g. 404, 500
	http_response_code($code);
	$_GET['code']=$code;
	include(NERV.'/my_httpd.php');
	exit;
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
	if ($number<0) {
		return ($number);
	}
	elseif ($number==0) {
		return 'O';
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
<?php // ----- time work -----
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
	$gmt=isset($gmt)? $gmt : POC_META['default_gmt'];

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
	elseif ($format==3) { return date('Y/m/d',$epoch); } // 2017/01/04
	// elseif ($format==6) { return $epoch; } // epoch
	// elseif ($format==7) { // Jan 1, 2020, 25:21
		// return (date('M d, Y', $epoch)).sprintf(", %s:%s", (date('H', $epoch) + $shiftday), (date("i", $epoch)));
	// }
	else { // default post format : 2018-Nov-13 (Tue), 26:51@GMT-7
		return sprintf ( "%s, %s:%s@GMT%+02d",
			date("Y-M-d (D)",$epoch),
			(date('H', $epoch) + $shiftday),
			date("i",$epoch),
			$gmt
		);
	}
}
?>
<?php // ----- make url -----
function get_const_by_id ($tableid=0) {
	if ($tableid==1) {
		$x=POC_DB_POST;
	}
	elseif ($tableid==2) {
		$x=POC_DB_MG;
	}
	elseif ($tableid==3) {
		$x=POC_DB_ARCHIV;
	}
	else {
		$x=null;
	}
	return $x;
}
function mk_page_view_url ($tableid=1, $page=0, $adminlist=0) {
	if ($adminlist) { # admin's list mode
		$url=sprintf ('/a/list_table?sel=%s&page=%s', $tableid,$page);
	} else {
		$url=get_const_by_id($tableid)['url']??'/';
		$url.=sprintf ('?page=%s',$page);
	}
	return $url;
}
function mk_id_view_url ($tableid=1, $id=0, $edit=0) {
	$x=get_const_by_id($tableid);
	if ($edit) {
		$url=$x['edit']??'/';
	} else {
		$url=$x['url']??'/';
	}
	$url.=sprintf ('?id=%s',$id);
	return $url;
}
?>
<?php // ----- mygirls related -----
function mk_mg_img_url ($path='') { # convert stored in db img path to site-defined img path
# made on 2022-oct-28, since i switch to host image on localhost instead of google
	return ('/img/'.$path);
}
function mk_url_da($url='') { #feed in string after ../art/. uses my dA account
	if ($url) {
		// return "http://kosmoflips.deviantart.com/art/".$url; // old url format
		return "https://www.deviantart.com/kosmoflips/art/".$url;
	} else {
		return '';
	}
}
function cleanimgurl ($url='') {
	$pattern1='/^https?:?/';
	$pattern2='/^[\/:]/';
	while (preg_match($pattern1, $url) or preg_match($pattern2, $url)) {
		$url=preg_replace($pattern1,'',$url);  # remove http(s)
		$url=preg_replace($pattern2,'',$url);  # remove leading slash '/' or ":"
	}
	return $url;
}
?>
<?php // ----- navi-bar related -----
function mk_navi_pair ($k=null, $tableid=0, $cid=1) {
	if (!$k or !$tableid) {
		return array();
	}
	$table=$tableid==1?'post':'mygirls';
	$n0=$k->_getNext($table,$cid,0);
	$p0=$k->_getNext($table,$cid,1);
	$navi1=array();
	# query url here should be universal for all , /pagename?id=xxxx
	if (isset($n0)) {
		$navi1['next']['url']=mk_id_view_url($tableid, $n0['id']);
		$navi1['next']['title']=$n0['title'];
	}
	if (isset($p0)) {
		$navi1['prev']['url']=mk_id_view_url($tableid, $p0['id']);
		$navi1['prev']['title']=$p0['title'];
	}
	return $navi1;
}
function mk_navi_bar ($first=1,$last=1,$perpage=1, $curr=1, $step=0, $tableid=1, $adminlist=0) { // tableid. 1:post, 2:mg, 3: archiv
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
		'tableid'=>$tableid,
		'admin_list_mode'=>$adminlist?1:0,
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
