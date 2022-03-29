<?php
class PocCalendar {

public function __construct() {
	$this->year=date('Y');
	$this->month=date('n');
	$this->day=date('j');
	$this->def_month_style=array(
		array (	0,
			"睦月/Jan", "如月/Feb", "弥生/Mar", "卯月/Apr",
			"皐月/May", "水無月/Jun", "文月/Jul", "葉月/Aug",
			"長月/Sep", "神無月/Oct", "霜月/Nov", "師走/Dec"
			), //[0]
	);
	$this->def_wkday_style=array(
		array('S','M','T','W','T','F','S'), // [0] style: 1-letter
	);
}
public function set_month ($m=0) {
	if ($m>12 or $m<1) {
		$m=date('n');
	}
	$this->month=$m;
}
public function set_year ($y=0) {
	if ($y<1980 or $y>(date('Y')+30)) {
		$y=date('Y');
	}
	$this->year=$y;
}
public function set_day ($y=0) { // for jump to "today" 1~31
	if ($y<1 or $y>31) {
		$y=date('j');
	}
	$this->year=$y;
}

private function calc_rows($mday=30,$dfirst=0) {
	$r=1;
	$restday=$mday-(7-$dfirst);
	while ($restday>0) {
		$restday-=7;
		$r++;
	}
	return $r;
}
private function month_days () {
	return cal_days_in_month(CAL_GREGORIAN,$this->month,$this->year);
}
public function month_format () { // month formatting data
	$mday=$this->month_days();
	$firstwkday=date('w', strtotime(sprintf ('%d/%d/%d', $this->year, $this->month, 1)));
	return array(
		'days'=>$mday,
		'first'=>$firstwkday,
		'rows'=>$this->calc_rows($mday,$firstwkday),
	);
}
public function month_data ($adjust=0) { // month posting data. must ensure involved tables contain column "epoch"
	$min=mktime(0,0,0,$this->month,1,$this->year); // begin of first day
	$max=(mktime(0,0,0,$this->month,$this->month_days(),$this->year)) + 24*60*60; // end of last day
	if ($adjust) { // adjust by 3 hours for 27hour mode. make sure 3 hour match the hour in 27hour time converter
		$min+=3*60*60;
		$max+=3*60*60;
	}
	$k=new PocDB();
	$list1=$k->getAll('select id,title,epoch,gmt from '.POC_DB['POST']['table'].' where epoch>=? and epoch<?', array($min, $max));
	$list1['type']=1;
	$list2=$k->getAll('select id,title,epoch,gmt from '.POC_DB['MYGIRLS']['table'].' where epoch>=? and epoch<?', array($min,$max));
	$list2['type']=2;
// var_dump($list2);exit;
	$pdata=array();
	foreach (array($list1,$list2) as $l) {
		foreach ($l as $item) {
			if (!isset($item['epoch'])) { // shouldn't happen though
				continue;
			}
			$e0=$item['epoch'];
			$d=date('j',($adjust?clock27($item['epoch'],6,$item['gmt']):$item['epoch']));
			$url=($l['type']==1?POC_DB['POST']['url']:POC_DB['MYGIRLS']['url']);
			$pdata[$d]=array();
			$pdata[$d][]=array(
				// 'id'=>$item['id'],
				'gmt'=>$item['gmt'],
				'epoch'=>$item['epoch'],
				'title'=>$item['title'],
				'url'=>$url.'/'.$item['id'],
			);
		}
		ksort($pdata);
	}
	return $pdata;
}

public function print_month ($style=0) {
	return $this->def_month_style[$style][$this->month];
}
public function print_weekday ($wkday=1,$style=0) {
	return $this->def_wkday_style[$style][$wkday];
}

// print month template. not making one single sub b/c each print involves different styles, blocks
/*
	// weekday header
	for ($w=0; $w<7; $w++) {
		echo '<span>', $calen->print_weekday($w), '</span>',"\n";
	}

	// days
	$d=1;
	for ($r=1;$r<=$mf['rows'];$r++) {
		echo '<div>',"\n";
		for ($w=0; $w<7;$w++) {
			$printday=0;
			if ($r==1 and $w>=$mf['first']) {
				$printday=1;
			}
			elseif ($r>1 and $d<=$mf['days']) {
				$printday=1;
			}

			if ($calen->day==$d) {
				$classp=1;
			} else {
				$classp=0;
			}
			if ($printday==1) {
				if (isset($mdata[$d])) {
					printf ('<span%s><a href="%s" class="day-link" title="%s">%s</a></span>%s',
							($classp?' class="day-today"':''),
							$mdata[$d][0]['url'], // when multiple post/pic exist, print only the first one
							htmlentities($mdata[$d][0]['title']),
							$d,"\n"
						);
				} else {
					echo '<span', ($classp?' class="day-today"':''),'>', $d, '</span>',"\n";
				}
				$d++;
			}
			else {
				echo "<span></span>\n";
			}
		}
		echo "</div>\n";
	}
*/


} // close class

?>