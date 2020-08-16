<?php
print_calendar_this_month ();

//----------
function print_calendar_this_month () {
	$calen=new PocCalendar; #default, show current month on HP
	$mdata=$calen->month_data(1);
	$mf=$calen->month_format();
?>
<h4><?php rand_deco_symbol(); ?> <a href="/calendar"><?php echo $calen->year,'::', $calen->print_month(); ?></a></h4>
<div class="calendar-side">
<div class="weekday-name">
<?php
	// weekday header
	for ($w=0; $w<7; $w++) {
		echo '<span>', $calen->print_weekday($w), '</span>',"\n";
	}
?>
</div><!--wkdayname-->
<?php
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
?>
</div>
<?php
}
?>