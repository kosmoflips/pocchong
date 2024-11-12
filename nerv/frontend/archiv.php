<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/synapse.php');

$symbol=rand_deco_symbol();
$p=new PocPage;
$k=new PocDB();

$curr_page=$_GET['page']??1;
$year_newest=$k->get_newest_year();
$total_yrs=$year_newest+2000-POC_YEAR_START+1;
$total_pgs=calc_total_page($total_yrs,POC_DB_ARCHIV['yr_max']);
$curr_yr=$year_newest-calc_page_offset($curr_page,POC_DB_ARCHIV['yr_max']);
$curr_yr2=$curr_yr-POC_DB_ARCHIV['yr_max']+1;
$p->navi['bar']=mk_navi_bar(1,$total_pgs,POC_DB_ARCHIV['yr_max'],$curr_page,POC_NAVI_STEP,3);
$p->title=POC_DB_ARCHIV['title'];

$arv=array();
foreach (array('post','mygirls') as $tb) {
	$basestat=sprintf('SELECT id,title,epoch,gmt,hide FROM %s WHERE year>=? AND year<=? ORDER BY epoch DESC', $tb);
	$get1=$k->getAll($basestat, array($curr_yr2, $curr_yr));
	foreach ($get1 as $x) {
		$yr=clock27($x['epoch'], 2, $x['gmt']);
		$x['table']=$tb=='post'?1:2;
		# would be nearly impossible for my post/art have the same epoch
		$arv[$yr][$x['epoch']]=$x;
	}
}

# order by epoch desc
krsort($arv);

$p->html_open();

printf ('<h2>%s %s %s</h2>%s', $symbol, $p->title, $symbol, "\n");

foreach ($arv as $loopyear=>$ylist) {
?>
<div class="archiv">
<div class="archiv-year"><a href="<?php echo POC_DB_ARCHIV['url'],'?year=',$loopyear ?>"><?php echo $loopyear?></a></div>
<ul>
<?php
	krsort($ylist); # order both post/mg by epoch
	foreach ($ylist as $entry) {
		printf ('<li><a href="%s"><span class="archivdate">%s</span><span class="archivicon">%s%s</span>%s</a></li>%s',
			mk_id_view_url($entry['table'],$entry['id']),
			clock27($entry['epoch'],1,$entry['gmt']),
			($entry['table']==1?"&#128211;":"&#127912;"), # note,art emoji
			($entry['hide']==1?"&#128273;":""),
			$entry['title'],
			"\n"
		);
	}
?>
</ul>
</div><!-- archiv -->
<?php
}
$p->html_close();

?>