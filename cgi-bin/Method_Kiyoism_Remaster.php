<?php  // LAST MODIFIED : 2020-Jan-23 ?>
<?php 	// ------------ system config, keep on top----------------
// enable extension=mbstring in php.ini
// enable extension=mysqli in php.ini
// enable extension=pdo_sqlite in php.ini
// many subs are inheritated from .pm, so they may not be the best solution in php
// constants , UTF8 anchor (´・ω・｀)
$CGIBIN=$_SERVER['DOCUMENT_ROOT'].'/cgi-bin';
$sysini=$CGIBIN.'/data/pocchong_config.ini';
if (file_exists($sysini)) {
	$POCCHONG= parse_ini_file($sysini,true); # 'true' , parse [sections]
	$POCCHONG['DB']=$_SERVER['DOCUMENT_ROOT'].$POCCHONG['DB'];	
} else {
	die ("the config file pocchong.ini is not found from specified location, fix your code!");
}
session_start();
if (isset($_SESSION) and isset($_SESSION["time_out"]) and ($_SESSION["time_out"]<time())) {
	session_destroy();
}
chklogin();
?>
<?php // ----------- db access. in a class obj ---------------
class PocDB {
public function connect() {
	global $POCCHONG;
	if (file_exists($POCCHONG['DB'])) {
		$dbh = new PDO('sqlite:'.$POCCHONG['DB']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $dbh;
	} else {
		return false;
	}
}
public function dosql($stat,$vars=null) {
	try {
		if (!isset($stat)) { return false; }
		$dbh=$this->connect();
		$sth=$dbh->prepare($stat);
		if (isset($vars) and !empty($vars)) {
			for ($i=0; $i< count($vars); $i++) {
				$sth->bindValue( ($i+1), $vars[$i]);
			}
		}
		$sth->execute();
		return $sth;
	}
	catch(PDOException $e) { echo $e->getMessage(); }
}
public function getAll($stat,$vars=null) { # get an array of all rows, each row is a hash ref
	try {
		$sth=$this->dosql($stat,$vars);
		return $sth->fetchAll();
	} catch(PDOException $e) { echo $e->getMessage(); }
}
public function getRow($stat,$vars=null) { # return H ref from 1 row. stat must match
	try {
		$sth=$this->dosql($stat,$vars);
		$rows=$sth->fetchAll();
		if (isset ($rows[0]) ) {
			return $rows[0];
		} else {
			return null;
		}
	} catch(PDOException $e) { echo $e->getMessage(); }
}
public function getOne($stat, $vars=null) { #get only one single [string] value
	try {
		$sth=$this->dosql($stat,$vars);
		$col=$sth->fetchColumn(); #Fetch the first column from the first row in the result set
		if (isset ($col) ) {
			return $col;
		} else {
			return null;
		}
	} catch(PDOException $e) { echo $e->getMessage(); }
}
public function _getNext($table='',$curr=1,$getprev=0) { # for table [post/mygirls] or any other tables that have both id,title as field, and id/epoch/time should be ordered the same
	if (!$table) { return null; }
	$query=sprintf ('SELECT id,title FROM %s WHERE id %s %s ORDER BY id %s LIMIT 1',
		$table,
		($getprev?'<':'>'),
		$curr,
		($getprev?'desc':'') );
	try {
		return $this->getRow($query);
	} catch(PDOException $e) { echo $e->getMessage(); }
}
public function countRows ($table=null) { # $k is db-obj
	if (!isset($table)) {return 0; }
	return $this->getOne('SELECT COUNT(*) FROM '.$table);
}
public function getTags() { // return $tags->{$tag_id} = $tag_name
	$tags=array();
	$tags0=$this->getAll('SELECT id,name FROM mygirls_tag');
	foreach ($tags0 as $tag1) {
		$tags[$tag1['id']] = $tag1['name'];
	}
	return $tags;
}
public function yearlast ($sel=0) { // return year 4 digits. get years of the most recent post from DB, so $k is required
	// yearfirst is given in ini file
	$yr1b=0;
	$yr2b=0;
	$yr1b=$this->getOne('select year from post order by year desc limit 1') +2000;
	$yr2b=$this->getOne('select year from mygirls order by year desc limit 1') +2000;
	if ($sel==1) { # newest POST
		return $yr1b;
	} elseif ($sel==2) { // newest MG
		return $yr2b;
	} else {
		return (($yr1b>$yr2b)?$yr1b:$yr2b);
	}
}


} // closing class
?>
<?php // ---- time related-------
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
	else { // default format. January 4, 2017, 26:13 (27H)
		return sprintf ( "%s, %s:%s",
			date('F j, Y', $epoch),
			(date('H', $epoch) + $shiftday),
			date('i', $epoch)
		);
	}
}
/*
function get_epoch ($y=2006,$m=12,$d=31,$h27=1) { // get epoches between specified dates. t1/t2=[y,m,d], both at 00:00:00
	$t1=mktime(0,0,0,$m,$d,$y);
	if ($h27) {
		$t1+=24*60*60; #adjust epoch for 27H format
	}
	return $t1;
}
*/
?>
<?php // -------- navi-bar related ---------------
function mk_navi_pair ($k=null,$table='',$cid=1, $url='') {
	// var_dump ($k);
	if (!$k or !$table) { return array(); }
	$n0=$k->_getNext($table,$cid,0);
	$p0=$k->_getNext($table,$cid,1);
	$navi1=array();
	$navi1['next']['url']=sprintf ('%s/%d', $url, $n0['id']);
	$navi1['prev']['url']=sprintf ('%s/%d', $url, $p0['id']);
	$navi1['next']['title']=$n0['title'];
	$navi1['prev']['title']=$p0['title'];
	return $navi1;
}
function mk_navi_bar ($first=1,$last=1,$perpage=1, $curr=1, $step=0, $urlbase='/') { // new version! urlbase has leading but no trailing slash / offset, useful for year as page e.g. 2006..2009
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
			$block[]=array($first,($curr+$step));
			$block[]=array(0,0);
			$block[]=array($last,$last);
		}
		elseif ($end==$last) { //last half
			$block[]=array($first,$first);
			$block[]=array(0,0);
			$block[]=array(($curr-$step),$last);
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
<?php // ------------ mygirls related ----------
function mk_url_google_img ($url='',$size='') { // input  has no https://
	if (!$url) { return ''; }
	$t=explode ('/', $url);
	$fname=array_pop($t);
	array_pop($t); #remove size
	if (!$size or !preg_match('/^[hws]\d+/i', $size)) { #simple check. should work for most cases
		$size='s800';
	}
	return sprintf ('https://%s/%s/%s', (implode ( '/', $t)), $size, $fname);
}
function mk_url_da($url='') { #feed in string after ../art/. uses my dA account
	if ($url) {
		return "http://kosmoflips.deviantart.com/art/".$url;
	} else {
		return '';
	}
}

?>
<?php // ----------------- write html --------------
function write_preview_sash () {
	echo '<div style="z-index:20;position:fixed;background:rgba(0,0,0,0.7);padding:20px 0;text-align:center;display:block;width:100%;left:-100px;top:50px;font-size:30px;font-weight:bold;color:white;transform: rotate(-20deg)">PREVIEW</div>',"\n";
}
function print_edit_button ($edit_url='') {
	if (chklogin() and $edit_url) {
		global $POCCHONG;
		echo '<div class="inline-box"><a href="',$edit_url,'">Edit</a></div>',"\n";
	}
}
function rand_deco_symbol() {
	$set=array( # HTML CODEの形式でより安全だと思う
		'&#10031;', #✯
		'&#10045;', #✽
		'&#10056;', #❈
		'&#10047;', #✿
		'&#10048;', #❀
		'&#10046;', #✾
		// '&#10017;', #✡
		'&#9825;', #♡
		'&#10059;', #❋
		'&#10054;', #❆
		'&#10053;', #❅
		'&#9733;', #★
		'&#9734;', #☆
		// '&#9770;', #☪
		'&#9825;', #♠
		'&#9826;', #♢
		'&#9828;', #♣
		'&#9831;', #♥
		'&#9834;', #♪
		'&#9835;', #♫
		'&#9836;', #♬
	);
	$randnum=rand(0, (count($set) -1));
	return $set[$randnum];
}
function print_system_msg ($msg='') { // admin submit-page edit only
	if (!empty($msg)) {
		echo '<div class="system-msg">system message: '.$msg.'</div>';
	}
}

?>
<?php // ----------- chk login ----------
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

?>
<?php //-------------- misc -------------
function peek($var=null,$stop=0) {
	echo '<pre>';
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
/*
	my ($k, $total)=@_;
	my ($r,$a);
	my $i=0;
	while ($i<$total) {
		my $t=int rand $total;
		if ($r->{$t}) {
			next;
		} else {
			$r->{$t}=1;
			push @$a,$t;
			$i++;
		}
	}
	return $a;
*/
}
?>
