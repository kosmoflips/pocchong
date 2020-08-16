<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/'.'Method_Kiyoism_Remaster.php');

print_page_archiv();

//--------------------------------
function process_data_archiv ($pobj=null, $year=0, $page=0) {
	if (!$pobj) {
		return null;
	}
	$k=new PocDB();
	$pack=POCCHONG['ARCHIV'];
	$step=POCCHONG['navi_step'];
	$title=$pack['title'];

	$year_last=$k->yearlast(1);

	# setup yearmode if valid
	$yearmode=0;
	if ($year) {
		$yearmode=1;
		if ($year<POCCHONG['year-start']) {
			$yearmode=0;
		} else {
			if ($year>$year_last) {
				$yearmode=0; # no new posts created since last post stored in db
			}
		}
	}

	$cpage=$page??1; #current offset for page, non-year only
	$listall=array();
	$base_stat='SELECT id,title,epoch,gmt FROM '.$pack['table'];
	if ($yearmode) {
		$cpage=$year; // no need to verify year anymore
		$list1=$k->getAll($base_stat.' WHERE year=? ORDER by epoch', array($_GET['year']-2000)); # year mode, order asc
		$listall[$year]=$list1;
		$url_yr=$pack['url'].$pack['url_year'];
		$title.=POCCHONG['separator'].$year;
		$navibar=mk_navi_bar(POCCHONG['year-start'], $year_last,1,$cpage,$step,$url_yr);
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
	$pobj->title=$title;
	$pobj->navi['bar']=$navibar;
	$pobj->data=array(
		'list'=>$listall,
		'yearmode'=>$yearmode,
	);
}

function print_page_archiv() {
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
}

function print_archiv_list_item ($entry=null) {
	?>
<li><a href="<?php echo POCCHONG['POST']['url'],'/',$entry['id'] ?>"><span class="archivdate"><?php echo time27($entry['epoch'],1,$entry['gmt']) ?></span> <?php echo $entry['title'] ?></a></li>	
<?php
}

function print_archiv_block ($yearmode=0,$loopyear=0,$ylist=null) {
	$pack=POCCHONG['ARCHIV'];
	?>
<div class="archiv">
<?php
	if (!$yearmode) {
	?>
<div class="archiv-year"><a href="<?php echo $pack['url'], $pack['url_year'],'/',$loopyear ?>"><?php echo $loopyear?></a></div>
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

?>