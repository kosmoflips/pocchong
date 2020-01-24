<?php # UTF8 anchor (´・ω・｀)
require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/'.'Method_Kiyoism_Remaster.php');

$pack=$POCCHONG['ARCHIV']; // type, table, title, url, url_page, max, edit
$PAGE=process_data_archiv($_GET['year']??null,$_GET['page']??null);

?>
<?php // write html
include ($_SERVER['DOCUMENT_ROOT'].$POCCHONG['TMPL']['page1']);
include ($_SERVER['DOCUMENT_ROOT'].$POCCHONG['TMPL']['entry1']);
?>
<h2><?php echo rand_deco_symbol(),' ', $PAGE['title'],' ',rand_deco_symbol() ?></h2>
<?php
foreach ($PAGE['data'] as $loopyear=>$ylist) { ?>
<div class="archiv">
<ul>
<?php
	if (!$PAGE['yearmode']) { ?>
<h3><a href="<?php echo $pack['url'], $pack['url_year'],'/',$loopyear ?>"><?php echo rand_deco_symbol(), ' ', $loopyear, ' ', rand_deco_symbol() ?></a></h3>
<?php
	}
	foreach ($ylist as $entry) {
		$date=time27($entry['epoch'],1,$entry['gmt']);
?>
<li><a href="<?php echo $POCCHONG['POST']['url'],'/',$entry['id'] ?>"><span class="archivdate"><?php echo $date ?></span> <?php echo $entry['title'] ?></a></li>
<?php } ?>
</ul>
</div><!-- archiv -->
<?php
}

include ($_SERVER['DOCUMENT_ROOT'].$POCCHONG['TMPL']['entry2']);
include ($_SERVER['DOCUMENT_ROOT'].$POCCHONG['TMPL']['page2']);
?>
<?php //prepare data
function process_data_archiv ($year=null, $page=0) {
	$k=new PocDB();
	global $pack;
	global $POCCHONG;
	$year_first=$POCCHONG['year-start'];
	$year_last=$k->yearlast(1);
	$step=$POCCHONG['navi_step'];
	$title=$pack['title'];

	# setup yearmode if valid
	$yearmode=0;
	if ($year) {
		$yearmode=1;
		if ($year<$year_first) {
			$yearmode=0;
		} else {
			if ($year>$year_last) {
				$yearmode=0; # no new posts created since last post stored in db
			}
		}
	}

	$cpage=$page??1; #current offset for page, non-year only
	$listall=array();
	$base_stat='SELECT id,title,epoch,gmt FROM post';
	if ($yearmode) {
		$cpage=$year; // no need to verify year anymore
		$list1=$k->getAll($base_stat.' WHERE year=? ORDER by epoch', array($_GET['year']-2000)); # year mode, order asc
		$listall[$year]=$list1;
		$url_yr=$pack['url'].$pack['url_year'];
		$title.=$POCCHONG['separator'].$year;
		$navibar=mk_navi_bar($year_first, $year_last,1,$cpage,$step,$url_yr);
	}
	else { #non year mode
		$totalrows=$k->countRows($pack['table']);
		$totalpgs=calc_total_page($totalrows,$pack['max']);
		$offset=calc_page_offset($cpage,$pack['max']);
		$list=$k->getAll($base_stat.' ORDER BY id DESC LIMIT ?,?', array($offset,$pack['max']));
		foreach ($list as $entry) {
			$thisyear=time27($entry['epoch'],2,$entry['gmt']);
			$listall[$thisyear][]=$entry;
		}
		$navibar=mk_navi_bar(1,$totalpgs,$pack['max'],$cpage,$step,$pack['url']);
	}
	return array(
		'title'=>$title,
		'navi'=>array(
			'bar'=>$navibar,
		),
		'data'=>$listall,
		'yearmode'=>$yearmode,
	);
}
?>