<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/synapse.php');

$symbol=rand_deco_symbol();
$p=new PocPage;
process_data_archiv($p,$_GET['year']??null,$_GET['page']??null);
$p->html_open();
?>
<h2><?php echo $symbol,' ', $p->title,' ',$symbol ?></h2>
<?php
foreach ($p->data['list'] as $loopyear=>$ylist) {
	print_archiv_block($p->data['yearmode'],$loopyear,$ylist);
}
$p->html_close();

//--------------------------------
function process_data_archiv ($pobj=null, $year=0, $page=0) {
	if (!$pobj) {
		return null;
	}
	$k=new PocDB();
	$TITLE=POC_DB['ARCHIV']['title'];

	$year_last=$k->yearlast(1);

	# setup yearmode if valid
	$yearmode=0;
	if ($year) {
		$yearmode=1;
		if ($year<POC_DB['year-start']) {
			$yearmode=0;
		} else {
			if ($year>$year_last) {
				$yearmode=0; # no new posts created since last post stored in db
			}
		}
	}

	$cpage=$page??1; #current offset for page, non-year only
	$listall=array();
	$base_stat='SELECT id,title,epoch,gmt FROM '.POC_DB['ARCHIV']['table'];
	if ($yearmode) {
		$cpage=$year; // no need to verify year anymore
		$list1=$k->getAll($base_stat.' WHERE year=? ORDER by epoch', array($_GET['year']-2000)); # year mode, order asc
		$listall[$year]=$list1;
		$TITLE.=POC_DB['separator'].$year;
		$navibar=mk_navi_bar(POC_DB['year-start'], $year_last,1,$cpage,POC_DB['navi_step'],POC_DB['ARCHIV']['url'].'?year=');
	}
	else { #non year mode, go by page (one page has fixed number of items)
		$totalrows=$k->countRows(POC_DB['ARCHIV']['table']);
		$totalpgs=calc_total_page($totalrows,POC_DB['ARCHIV']['max']);
		$offset=calc_page_offset($cpage,POC_DB['ARCHIV']['max']);
		$list=$k->getAll($base_stat.' ORDER BY epoch DESC LIMIT ?,?', array($offset,POC_DB['ARCHIV']['max']));
		foreach ($list as $entry) {
			$thisyear=clock27($entry['epoch'],2,$entry['gmt']);
			$listall[$thisyear][]=$entry;
		}
		$navibar=mk_navi_bar(1,$totalpgs,POC_DB['ARCHIV']['max'],$cpage,POC_DB['navi_step'],POC_DB['ARCHIV']['url'].'?page=');
	}
	$pobj->title=$TITLE;
	$pobj->navi['bar']=$navibar;
	$pobj->data=array(
		'list'=>$listall,
		'yearmode'=>$yearmode,
	);
}

function print_archiv_block ($yearmode=0,$loopyear=0,$ylist=null) {
	?>
<div class="archiv">
<?php
	if (!$yearmode) {
	?>
<div class="archiv-year"><a href="<?php echo POC_DB['ARCHIV']['url'],'?year=',$loopyear ?>"><?php echo $loopyear?></a></div>
<?php
	}
	?>
<ul>
<?php
	foreach ($ylist as $entry) {
		print_archiv_list_item($entry);
	}
	?>
</ul>
</div><!-- archiv -->
<?php
}

function print_archiv_list_item ($entry=null) {
	?>
<li><a href="<?php echo POC_DB['POST']['url'],'?id=',$entry['id'] ?>"><span class="archivdate"><?php echo clock27($entry['epoch'],1,$entry['gmt']) ?></span> <?php echo $entry['title'] ?></a></li>	
<?php
}

?>