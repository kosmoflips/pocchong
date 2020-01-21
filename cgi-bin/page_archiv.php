<?php # UTF8 anchor (´・ω・｀)
require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/'.'Method_Kiyoism_Remaster.php');
?>
<?php //prepare data
$k=new PocDB();

$yearmode=0; #mode for showing a list of one certain year. standard time . ALL using 27H mode
$year_first=$POCCHONG['SITE']['year1'];
$year_last=yearlast($k,1);
$page_turn=$POCCHONG['GENERAL']['navi_step'];
$url=$POCCHONG['ARCHIV']['url_archiv'];
$title=$POCCHONG['ARCHIV']['title_archiv'];

# setup yearmode if valid
if (isset($_GET['year']) ) {
	$yearmode=1;
	if ($_GET['year']<$year_first) {
		$yearmode=0;
	} else {
		if ($_GET['year']>$year_last) {
			$yearmode=0; # no new posts created since last post stored in db
		}
	}
}

$cpage=isset($_GET['page'])?$_GET['page']:1; #current offset for page, non-year only
$navi=array();
$listall=array();
$base_stat='SELECT id,title,epoch,gmt FROM post';
if ($yearmode) {
	$cpage=$_GET['year']; // no need to verify year anymore
	$list1=$k->getAll($base_stat.' WHERE year=? ORDER by epoch', array($_GET['year']-2000)); # year mode, order asc
	$listall[$_GET['year']]=$list1;
	$url.=$POCCHONG['ARCHIV']['url_archiv_year'];
	$title.=$POCCHONG['GENERAL']['separator'].$_GET['year'];
	$navi=calc_navi_set($year_first,$year_last,$_GET['year'],$page_turn);
}
else { #non year mode
	$table=$POCCHONG['POST']['table'];
	$max_per_page=$POCCHONG['ARCHIV']['max_archiv'];
	$totalrows=$k->countRows($table);
	$totalpgs=calc_total_page($totalrows,$max_per_page);
	$cpage=verify_current_page($cpage,$totalpgs);
	$offset=calc_page_offset($cpage,$max_per_page);
	$list=$k->getAll($base_stat.' ORDER BY id DESC LIMIT ?,?', array($offset,$max_per_page));
	foreach ($list as $entry) {
		$thisyear=time27($entry['epoch'],2,$entry['gmt']);
		$listall[$thisyear][]=$entry;
	}
	$navi=calc_navi_set(1,$totalpgs,$cpage,$page_turn);
}
$naviset=mk_naviset( $navi, $page_turn, $cpage, $url );

?>
<?php // output html
write_html_open($title);

print_post_wrap(0);
printf ("<h2>%s %s %s</h2>\n",rand_deco_symbol(), $title,rand_deco_symbol() ); // h2
foreach ($listall as $loopyear=>$ylist) {
	echo '<div class="archiv">',"\n";
	echo '<ul>',"\n";
	if (!$yearmode) {
		printf ('<h3><a href="%s%s/%s">%s %04d %s</a></h3>%s',
			$url, $POCCHONG['ARCHIV']['url_archiv_year'],$loopyear,
			rand_deco_symbol(), $loopyear, rand_deco_symbol(),
			"\n");
	}
	foreach ($ylist as $entry) {
		$date=time27($entry['epoch'],1,$entry['gmt']);
		printf ('<li><a href="%s/%s"><span class="archivdate">%s</span> %s</a></li>%s',
			$POCCHONG['POST']['url'],
			$entry['id'],
			$date,
			$entry['title'], // encode('UTF-8',$entry->{title}),
			"\n");
	}
	echo '</ul>',"\n";
	echo '</div><!-- archiv -->',"\n"; #close the last tag
}
print_post_wrap(1);

write_html_close( $naviset );
?>
