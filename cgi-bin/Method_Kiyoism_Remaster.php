<?php 	// ------------ system config, keep on top----------------
// enable extension=mbstring in php.ini
// enable extension=mysqli in php.ini
// enable extension=pdo_sqlite in php.ini
// many subs are inheritated from .pm, so they may not be the best solution in php
// constants , UTF8 anchor (´・ω・｀)
$sysini=$_SERVER['DOCUMENT_ROOT'].'/cgi-bin/pocchong.ini';
if (!file_exists($sysini)) {
	die ("the config ini file is not found from specified location, fix your code!");
} else {
	$POCCHONG= parse_ini_file($sysini,true); # 'true' , parse [sections]
	$POCCHONG['FILE']['sqlite']=$_SERVER['DOCUMENT_ROOT'].$POCCHONG['FILE']['sqlite'];	
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
	if (file_exists($POCCHONG['FILE']['sqlite'])) {
		$dbh = new PDO('sqlite:'.$POCCHONG['FILE']['sqlite']);
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
	try {
		$query=sprintf ('SELECT id,title FROM %s WHERE id %s %s ORDER BY id %s LIMIT 1',
			$table,
			($getprev?'<':'>'),
			$curr,
			($getprev?'desc':'') );
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


} // closing class
?>
<?php // --------------- navi-bar related ---------------
function mk_navi1 ($k=null,$table='',$cid=1, $url='') {
	if (!$k or !$table) { return 0; }
	$n0=$k->_getNext($table,$cid,0);
	$p0=$k->_getNext($table,$cid,1);
	$navi1=array();
	$navi1['next']['url']=sprintf ('%s/%d', $url, $n0['id']);
	$navi1['prev']['url']=sprintf ('%s/%d', $url, $p0['id']);
	$navi1['next']['title']=$n0['title'];
	$navi1['prev']['title']=$p0['title'];
	return $navi1;
}
function mk_naviset($navi=null,$page_turn=1,$curr=1,$baseurl='') { // for write_html_close
	if (!isset($navi)) { return null; }
	$naviset=array();
	$naviset['navi']=$navi;
	$naviset['turn']=$page_turn;
	$naviset['currpage']=$curr;
	$naviset['baseurl']=$baseurl;
	return $naviset;
}
function calc_navi_set($first=1,$last=1,$curr=1,$turn=0) { #process set for input of &print_navi_bar
# b0: fixed, first page
# b1: optional. page 1,2,3,4,5 ... 10, b1=5
# e0: optional. page 1 ... 6,7,8,9,10 , e0 = 6
# e1: last page, fixed. depending on the last record of the db
# mid: optional. 1 ... 5, 6,7,8, 9 ... 15 , mid=6, currpage =6
	$totalcont=$last-$first+1;
	#initial values
	$navi=array (
		'begin0'=>$first,
		'begin1'=>0,
		'end0'=>0,
		'end1'=>$last,
		'mid'=>0,
		'prev'=>0,
		'next'=>0
	);
	if (($turn*2+1)>=$totalcont) { #no need to break // 1,2,3 end
		$navi['begin1']=$navi['end1'];
		$navi['end1']=0;
	}
	elseif ($curr<=(1+$turn+$navi['begin0'])) { # 1,2,3,4,5 // 10
		$navi['begin1']=$navi['begin0']+2*$turn;
		if ($curr==($turn+1+$navi['begin0'])) {
			$navi['begin1']+=1;
		}
	}
	elseif ($curr>=($navi['end1']-$turn-1)) { # 1 // 6,7,8,9,10
		$navi['end0']=$curr-$turn;
		if ($curr>=($navi['end1']-$turn)) {
			$navi['end0']=$navi['end1']-$turn*2;
		}
	}
	else {
		$navi['mid']=$curr; #+-2 each side
	}

	$navi['prev']=$curr-1;
	$navi['next']=$curr+1;
	if ($navi['prev']<$navi['begin0']) {
		$navi['prev']=0; #don't show "prev" if reaching first page
	}
	if (($navi['next']>$navi['end1'] and $navi['end1']>0) or
		($navi['next']>$navi['begin1'] and $navi['end1']==0)) {
		$navi['next']=0; # don't show "next" if reaching last page
	}
	return $navi;
}
function verify_current_page($curr=1,$pgtotal=1) {
	if ($curr>$pgtotal) {
		$curr=$pgtotal;
	}
	elseif ($curr<1) {
		$curr=1;
	}
	return $curr;
}
function calc_total_page($totalrows=1,$max_per_page=1) {
	$pgtotal=sprintf ("%d", ($totalrows/$max_per_page) );
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
<?php // ----------------- write html --------------
function write_html_open($title='',$extra='',$defaultmeta=0) {
	global $POCCHONG;
	$jslist=array();
	$csslist=array();
	if ($defaultmeta) {
		$jslist=$POCCHONG['FILE']['js'];
		$csslist=$POCCHONG['FILE']['css'];
	}
	include ($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/incl_html_open.php');
}
function write_html_close($naviset=null,$navi1=null) { // if both are given, only use navi1 (for single page)
	include ($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/incl_html_close.php');
}

function print_static_title($title='') {
	if ($title) {
		printf ("<h2>%s %s %s</h2>\n", rand_deco_symbol(), $title, rand_deco_symbol() );
	}
}
function write_html_admin($end=0) {
	if (!$end) {
		include ($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/admin/incl_admin_head.html');
	} else {
		include ($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/admin/incl_admin_tail.html');
	}
}
function write_preview_sash () {
	echo '<div style="z-index:20;position:fixed;background:rgba(0,0,0,0.7);padding:20px 0;text-align:center;display:block;width:100%;left:-100px;top:50px;font-size:30px;font-weight:bold;color:white;transform: rotate(-20deg)">PREVIEW</div>',"\n";
}
function print_post_wrap($do_end=0) { #for .post-inner-shell = ONE individual entry
	if (!$do_end) { #<div> opens
		echo '<div class="post-inner-shell">',"\n";
		echo '<div class="post-inner">',"\n";
	} else { #<div> closes
		echo '</div><!-- .post-inner -->',"\n";
		echo '</div><!-- .post-inner-shell -->',"\n";
	}
	
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
		'&#10017;', #✡
		'&#9825;', #♡
		'&#10059;', #❋
		'&#10054;', #❆
		'&#10053;', #❅
		'&#9733;', #★
		'&#9734;', #☆
		'&#9770;', #☪
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
function _print_navi_square($baseurl='', $page=1,$cpage=1) {
	$class='navi-bar-square';
	if ($cpage == $page) {
		$class.='-self';
	}
	printf ('<span class="%s"><a href="%s/%s">%s</a></span>%s',
		$class,
		$baseurl,
		$page,
		$page,
		"\n");
}
function print_spacer_dots() {
	echo '<span>..</span>',"\n";
}
function print_navi_bar($navi=null, $turn=1, $curr=1, $baseurl='') {
	if (!$navi) { return 0; }

	echo '<div class="navi-bar">',"\n";
	if ($navi['prev']) {
		printf ('<span><a href="%s/%s">◀◀</a></span>%s', $baseurl, ($curr-1),"\n");
	}
	if ($navi['begin0'] and $navi['begin1']) {
		for ($i=$navi['begin0']; $i<=$navi['begin1']; $i++) {
			_print_navi_square($baseurl, $i,$curr);
		}
	} elseif ($navi['begin0']) {
		_print_navi_square($baseurl, $navi['begin0'],$curr);
	}
	if ($navi['mid']) {
		print_spacer_dots();
		for ($i=($navi['mid']-$turn); $i<=($navi['mid']+$turn); $i++) {
			_print_navi_square($baseurl, $i,$curr);
		}
	}
	if ($navi['end0'] and $navi['end1']) {
		print_spacer_dots();
		for ($i=$navi['end0']; $i<=$navi['end1']; $i++) {
			_print_navi_square($baseurl, $i,$curr);
		}
	}
	elseif ($navi['end1']) {
		print_spacer_dots();
		_print_navi_square($baseurl, $navi['end1'],$curr);
	}
	if ($navi['next']) {
		printf ('<span><a href="%s/%s">▶▶</a></span>%s', $baseurl, ($curr+1), "\n");
	}
	echo '</div><!-- .navi-bar ends -->',"\n";
}
function _print_footer_navi ($title='',$url='',$prev=0) { //footer navi <div> for next/prev entry . url should be ABS path to root.
	if (!$title or !$url) {
		return 0;
	}
	if ($prev) {
		$titlefmt='⇦ '.$title;
		$prev='prev';
	} else {
		$titlefmt=$title.' ⇨';
		$prev='next';
	}
	echo '<div class="navi-',$prev,'"><a href="',$url,'">', $titlefmt,'</a></div>',"\n";
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
	else { // default format. January 4, 2017, 26:13 (27H)
		return sprintf ( "%s, %s:%s",
			date('F j, Y', $epoch),
			(date('H', $epoch) + $shiftday),
			date('i', $epoch)
		);
	}
}
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
function print_line_seperator() {
	echo '<div style="margin-top: 25px; border-bottom: 3px double #d8afe2; display:block; text-align:center; width: 90%; font-size: 120%; text-shadow: 2px 2px 3px #bd8db4; margin: auto; "><b>..｡o○☆*:ﾟ･: Variations :･ﾟ:*☆○o｡..</b></div>',"\n";
}
?>
