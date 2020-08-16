<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/'.'Method_Kiyoism_Remaster.php');

print_page_calendar();


// ----------------
function print_calendar_month_row ($pdata,$y=0, $mstart=0) {
	// $MONTH=def_calendar_month();
	$calen=new PocCalendar;
	$calen->set_year($y);
	?>
<div class="calendar-month-row-shell">
<?php
	for ($i=0; $i<POCCHONG['CALENDAR']['col']; $i++) { //print one row of XX month blocks
		$m2=$mstart+$i;
		if ($m2>=1 and $m2<=12) {
			$calen->set_month($m2);
	?>
<div class="calendar-month-shell">
<div class="calendar-month-wrap">
<div class="month-name"><?php echo $calen->print_month() ?></div>
<?php
			print_calendar_this_month($pdata,$calen);
?>
</div><!-- .calendar-month-wrap -->
</div><!-- .calendar-month-shell -->
<?php
		}
		else {
		?>
<div class="calendar-month-wrap"></div>
<?php
		}
	} ?>
</div><!--close calendar-month-row-shell -->
<?php
}

function print_calendar_rightbox ($pdata=null) {
	?>
<div class="calendar-right-list">
<ul>
<?php //right, list block
	foreach ($pdata as $m=>$ma) {
		foreach ($ma as $d=>$da) {
			foreach ($da as $item) {
			?>
<li id="p<?php echo $item['epoch']; ?>" class="day-link-target"><a href="<?php echo $item['url']; ?>">[<?php echo time27($item['epoch'],1,$item['gmt']); ?>] <?php echo $item['title']; ?></a></li>
	<?php
			}
		}
	} ?>
</ul>
</div>
<?php
}

function print_calendar_yearshell ($pdata,$y=0) {
	$col=POCCHONG['CALENDAR']['col'];
	?>
<div class="calendar-year-shell">
<?php // left- calendar block
	for ($m=1; $m<=12; $m+=$col) {
		print_calendar_month_row($pdata,$y,$m);
	}
	?>
</div><!--close calendar-year-shell -->
<?php
}

function print_page_calendar () {
	$symbol=rand_deco_symbol();
	$yr=$_GET['year']??date('Y');
	$p=new PocPage;
	process_data_calendar($p,$yr);
	$p->html_open();
	?>
<h2><?php echo $symbol, ' ', $p->title, ' ', $symbol ?></h2>
<div class="calendar-wrapper">
<?php
	print_calendar_yearshell($p->data,$yr);
	print_calendar_rightbox($p->data);
?>
</div>
<?php
	$p->html_close();
}

function process_data_calendar ($pobj=null,$year=0) {
	if (!$pobj) {
		return null;
	}
	$k=new PocDB();
	$pack=POCCHONG['CALENDAR'];
	$year_first=POCCHONG['year-start'];
	$year_last=$k->yearlast(0);
	if (is_int(intval($year))) { // intval used here or php thinks it's a string
		if ($year<$year_first or $year>$year_last) {
			$year = $year_last;
		}
	} else {
			$year = $year_last;
	}

	$navibar=mk_navi_bar($year_first,$year_last,1,$year,POCCHONG['navi_step'],$pack['url'].$pack['url_year']);
	// $ydata=calendar_mk_ydata($year);
	// $pdata=calendar_mk_pdata($year);

	$pobj->title=$pack['title'].'::'.$year;
	$pobj->head['js']=array(
			'/deco/js/calendar_hover_highlight.js', // calendar special
		);
	$pobj->navi['bar']=$navibar??null;
	$cobj=new PocCalendar;
	for ($m=1;$m<=12;$m++) {
		$cobj->set_year($year);
		$cobj->set_month($m);
		$pobj->data[$m]=$cobj->month_data();
	}
}

function print_calendar_this_month ($pdata=null,$calen=null) {
	?>
<?php
	echo '<div class="weekday-name">', "\n";
	for ($w=0; $w<7; $w++) {
		echo '<span>', $calen->print_weekday($w), '</span>',"\n";
	}
	echo '</div><!--wkdayname-->',"\n";

	$d=1;
	$mf=$calen->month_format();
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

			$md=$pdata[$calen->month];
			if ($printday==1) {
				if (isset($md[$d])) {
					printf ('<span><a href="%s" class="day-link" title="%s">%s</a></span>%s',
							// ($hoverup? $pdata[$m2][$d][0]['url'] : '#p'.$pdata[$m2][$d][0]['epoch']),
							('#p'.$md[$d][0]['epoch']),
							htmlentities($md[$d][0]['title']),
							$d,"\n"
					);
				} else {
					echo '<span>', $d, '</span>',"\n";
				}
				$d++;
			}
			else {
				echo "<span></span>\n";
			}
		}
		echo "</div>\n";
	}

}
?>
