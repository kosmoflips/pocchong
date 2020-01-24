<?php # UTF8 anchor (´・ω・｀)
require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/'.'Method_Kiyoism_Remaster.php');

$pack=$POCCHONG['CALENDAR']; // type, table, title, url, url_page, max, edit
$PAGE=process_data_calendar($_GET['year']??null);

$marray=array(null,
	'睦月/Jan',
	'如月/Feb',
	'弥生/Mar',
	'卯月/Apr',
	'皐月/May',
	'水無月/Jun',
	'文月/Jul',
	'葉月/Aug',
	'長月/Sep',
	'神無月/Oct',
	'霜月/Nov',
'師走/Dec');
$warray=array('S','M','T','W','T','F','S');

?>
<?php // output html
include ($_SERVER['DOCUMENT_ROOT'].$POCCHONG['TMPL']['page1']);
include ($_SERVER['DOCUMENT_ROOT'].$POCCHONG['TMPL']['entry1']);
?>
<h2><?php echo rand_deco_symbol(), ' ', $PAGE['title'], ' ',rand_deco_symbol() ?></h2>
<div class="calendar-wrapper">
<div class="calendar-year-shell">
<?php // left- calendar block
for ($m=1; $m<=12; $m+=$pack['col']) { ?>
<div class="calendar-month-row-shell">
<?php
	for ($i=0; $i<$pack['col']; $i++) { //print one row of XX month blocks
		$m2=$m+$i;
		if ($m2>=1 and $m2<=12) { ?>
<div class="calendar-month-shell-m<?php echo $m2; ?>">
<table>
<tr><th class="month-name" colspan="7"><?php echo $marray[$m2]; ?></th></tr>
<tr><!--wkdayname-->
<?php
	for ($w=0; $w<7; $w++) { ?>
<th class="weekday-name"><?php echo $warray[$w] ?></th>
<?php
	} ?>
</tr>
<?php
	$match=0;
	$d=1;
	for ($r=1;$r<=$PAGE['ydata'][$m2][2];$r++) { ?>
<tr>
<?php
		for ($w=0; $w<7;$w++) {
			$printday=0;
			if ($r==1 and $w>=$PAGE['ydata'][$m2][1]) {
				$printday=1;
			}
			elseif ($r>1 and $d<=$PAGE['ydata'][$m2][0]) {
				$printday=1;
			}

			if ($printday==1) {
				if (isset($PAGE['pdata'][$m2][$d])) { ?>
<td><a href="#p<?php echo $PAGE['pdata'][$m2][$d][0]['epoch'] ?>" class="day-link"><?php echo $d ?></a></td>
<?php
				} else { ?>
<td><?php echo $d ?></td>
<?php
				}
				$d++;
			} else { ?>
<td></td>
<?php
			}
		} ?>
</tr>
<?php
	}
?>
</table>
</div>
<?php
		} else { ?>
<div class="calendar-month-shell"></div>
<?php
		}
	} ?>
</div><!--close calendar-month-row-shell -->
<?php
} ?>
</div><!--close calendar-year-shell -->
<div class="calendar-right-list">
<ul>
<?php //right, list block
foreach ($PAGE['pdata'] as $m=>$ma) {
	foreach ($ma as $d=>$da) {
		foreach ($da as $item) { ?>
<li id="p<?php echo $item['epoch']; ?>" class="day-link-target"><a href="<?php echo $item['url']; ?>">[<?php echo time27($item['epoch'],1,$item['gmt']); ?>] <?php echo $item['title']; ?></a></li>
<?php
		}
	}
} ?>
</ul>
</div>
</div>

<?php
include ($_SERVER['DOCUMENT_ROOT'].$POCCHONG['TMPL']['entry2']);
include ($_SERVER['DOCUMENT_ROOT'].$POCCHONG['TMPL']['page2']);
?>
<?php // subs
function process_data_calendar ($year=null) {
	$k=new PocDB();
	global $POCCHONG;
	global $pack;

	$year_first=$POCCHONG['year-start'];
	$year_last=$k->yearlast(0);
	if (is_int(intval($year))) { // intval used here or php thinks it's a string
		if ($year<$year_first or $year>$year_last) {
			$year = $year_last;
		}
	} else {
			$year = $year_last;
	}
	$title=$pack['title'].'::'.$year;
	$step=$POCCHONG['navi_step'];
	$url=$pack['url'].$pack['url_year'];

	$navibar=mk_navi_bar($year_first,$year_last,1,$year,$step,$url);
	$list1=$k->getAll('select id,title,epoch,gmt from post where year=?', array($year-2000));
	$list1['type']=1;
	$list2=$k->getAll('select id,title,epoch,gmt from mygirls where year=?', array($year-2000));
	$list2['type']=2;

	// map item to data
	$pdata=array();
	foreach (array($list1,$list2) as $l) {
		foreach ($l as $item) {
			if (!isset($item['epoch'])) {
				continue;
			}
			$e0=$item['epoch'];
			$m=date('n',time27($item['epoch'],6,$item['gmt']));
			$d=date('j',time27($item['epoch'],6,$item['gmt']));
			$url=($l['type']==1?$POCCHONG['POST']['url']:$POCCHONG['MYGIRLS']['url']);
			$pdata[$m][$d]=array();
			$pdata[$m][$d][]=array(
				// 'id'=>$item['id'],
				'gmt'=>$item['gmt'],
				'epoch'=>$item['epoch'],
				'title'=>$item['title'],
				'url'=>$url.'/'.$item['id'],
			);
		}
		ksort($pdata);
	}
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
	return array(
		'title'=>$title,
		'js'=>array(
			'/deco/js/calendar_hover_highlight.js', // calendar special
		),
		'navi'=>array(
			'bar'=>$navibar??null,
		),
		'pdata'=>$pdata,
		'ydata'=>$ydata,
	);
}
function monthdays ($year=2006, $month=1) {
	return cal_days_in_month(CAL_GREGORIAN,$month,$year);
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
?>