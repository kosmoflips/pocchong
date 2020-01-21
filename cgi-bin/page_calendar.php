<?php # UTF8 anchor (´・ω・｀)
require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/'.'Method_Kiyoism_Remaster.php');
?>
<?php // process year data
$k=new PocDB();

$year = date('Y');
$mrow=3;
/* no custom column number as css is designed to fit the site width
if (isset($_GET['col'])) {
	if ($_GET['col']>0 and $_GET['col']<=12) {
		$mrow=$_GET['col'];
	}
}
*/
if (isset($_GET['year'])) {
	if ($_GET['year']>=2006 and $_GET['year']<=$year) {
		$year=$_GET['year'];
	}
}

$url=$POCCHONG['ARCHIV']['url_calendar'];
$title=$POCCHONG['ARCHIV']['title_calendar'];
if ($year) {
	$title.='::'.$year;
}
$page_turn=$POCCHONG['GENERAL']['navi_step'];
$url.=$POCCHONG['ARCHIV']['url_calendar_year'];
$year_first=$POCCHONG['SITE']['year1'];
$year_last=yearlast($k,0);
$navi=calc_navi_set($year_first,$year_last,$year,$page_turn);
$naviset=mk_naviset( $navi, $page_turn, $year, $url );
$list1=$k->getAll('select id,title,epoch,gmt from post where year=?', array($year-2000));
$list1['type']=1;
$list2=$k->getAll('select id,title,epoch,gmt from mygirls where year=?', array($year-2000));
$list2['type']=2;
// $list=array();
// map item to data
$data=array();
foreach (array($list1,$list2) as $l) {
	foreach ($l as $item) {
		if (!isset($item['epoch'])) {
			continue;
		}
		$e0=$item['epoch'];
		$m=date('n',time27($item['epoch'],6,$item['gmt']));
		$d=date('j',time27($item['epoch'],6,$item['gmt']));
		$url=($l['type']==1?$POCCHONG['POST']['url']:$POCCHONG['MYGIRLS']['url']);
		$data[$m][$d]=array();
		$data[$m][$d][]=array(
			// 'id'=>$item['id'],
			'gmt'=>$item['gmt'],
			'epoch'=>$item['epoch'],
			'title'=>$item['title'],
			'url'=>$url.'/'.$item['id'],
		);
	}
	ksort($data);
	// var_dump($data);
}
?>
<?php // write html
write_html_open($title);

print_post_wrap(0);
printf ("<h2>%s %s %s</h2>\n",rand_deco_symbol(), $title,rand_deco_symbol() ); // h2
?>
<script>
$(document).ready(function() {
	// $(".day-link").on('click mouseover', function() {
	$(".day-link").hover(function() {
		var id = $(this).attr('href');//.replace(/#/, '');
		$('li.day-link-target').css({
			'background': 'none'
		});
		$(id).css({
			'background': 'rgba(201,142,255,0.4)'
		});
	});
});
</script>
<?php
echo '<div class="calendar-wrapper">', "\n";
print_calendar($year, $data, $mrow); // print calendar as table
print_listblock($data);
echo '</div>',"\n";

print_post_wrap(1);
write_html_close( $naviset );
?>
<?php // ----------- calendar style archive --------------
function print_listblock ($data=array()) {
	echo '<div class="calendar-right-list">',"\n";
	echo "<ul>\n";
	foreach ($data as $m=>$ma) {
		foreach ($ma as $d=>$da) {
			foreach ($da as $item) {
				printf ('<li id="p%s" class="day-link-target"><a href="%s">[%s] %s</a></li>%s', $item['epoch'], $item['url'],time27($item['epoch'],1,$item['gmt']),$item['title'],"\n");
			}
		}
	}
	echo "</ul>\n";
	echo "</div>\n";
}
function print_calendar ($year=2006, $data, $col=3) { // full year, in nested <table>
	$ydata=array();
	for ($m=1; $m<=12; $m++) {
		$mday=monthdays($year,$m);
		$firstwkday=date('w', strtotime(sprintf ('%d/%d/%d', $year, $m, 1)));
		$ydata[$m]=array( // day-count, first-weekday, last-weekday, row-count
			$mday,
			$firstwkday,
			calc_rows($mday,$firstwkday),
		);
	}
	echo '<div class="calendar-year-shell">',"\n";
	for ($m=1; $m<=12; $m+=$col) {
		echo '<div class="calendar-month-row-shell">';
		for ($i=0; $i<$col; $i++) { //print one row of XX month blocks
			$m2=$m+$i;
			if ($m2>=1 and $m2<=12) {
				echo '<div class="calendar-month-shell-m',$m2,'">';
				// echo $m2;
				print_mblock($m2,$ydata[$m2], (isset($data[$m2])?$data[$m2]:array()));
				echo "</div>\n";
			} else {
				echo '<div class="calendar-month-shell"></div>',"\n";
			}
		}
		echo "</div><!--close calendar-month-row-shell -->\n";
	}
	echo "</div><!--close calendar-year-shell -->\n";
}
function monthdays ($year=2006, $month=1) {
	return cal_days_in_month(CAL_GREGORIAN,$month,$year);
}
function print_mheader ($m) {
	$marray=array(null,'睦月/Jan','如月/Feb','弥生/Mar','卯月/Apr','皐月/May','水無月/Jun','文月/Jul','葉月/Aug','長月/Sep','神無月/Oct','霜月/Nov','師走/Dec');
	echo '<tr><th class="month-name" colspan="7">',(isset($m)?$marray[$m]:''),"</th></tr>\n";
}
function print_wdayline () {
	$warray=array('S','M','T','W','T','F','S');
	echo "<tr>";
	for ($w=0; $w<7; $w++) {
		echo '<th class="weekday-name">',$warray[$w],"</th>";
	}
	echo "</tr>\n";
}
function calc_rows($mday=30,$dfirst=0) {
	$r=1;
	$restday=$mday-(7-$dfirst);
	while ($restday>0) {
		$restday-=7;
		$r++;
	}
	return $r;
}
function print_days($md, $data) {
	$match=0;
	$d=1;
	for ($r=1;$r<=$md[2];$r++) {
		echo "<tr>";
		for ($w=0; $w<7;$w++) {
			$printday=0;
			if ($r==1 and $w>=$md[1]) {
				$printday=1;
			}
			elseif ($r>1 and $d<=$md[0]) {
				$printday=1;
			}

			if ($printday==1) {
				if (isset($data[$d])) {
					$i0=$data[$d][0]; // in case more than 1 items are there. shouldn't happen ideally
					printf ('<td><a href="#p%s" class="day-link">%s</a></td>', $i0['epoch'], $d);
				} else {
					echo "<td>",$d,"</td>";
				}
				$d++;
			} else {
				echo "<td></td>";
			}
		}
		echo "</tr>\n";
	}
}
function print_mblock ($m=1,$md, $data=array()) { // month block
	echo '<table>',"\n";
	print_mheader($m);
	print_wdayline();
	print_days($md, $data);
	echo "</table>\n";
}
?>
