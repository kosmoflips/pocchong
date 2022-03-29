<?php // -------- navi-bar related ---------------
function mk_navi_pair ($k=null,$table='',$cid=1, $url='') {
	// var_dump ($k);
	if (!$k or !$table) { return array(); }
	$n0=$k->_getNext($table,$cid,0);
	$p0=$k->_getNext($table,$cid,1);
	$navi1=array();
	if (isset($n0)) {
		$navi1['next']['url']=sprintf ('%s/%d', $url, $n0['id']);
		$navi1['next']['title']=$n0['title'];
	}
	if (isset($p0)) {
		$navi1['prev']['url']=sprintf ('%s/%d', $url, $p0['id']);
		$navi1['prev']['title']=$p0['title'];
	}
	return $navi1;
}
function mk_navi_bar ($first=1,$last=1,$perpage=1, $curr=1, $step=0, $urlbase='/') { // new version! urlbase has leading but no trailing slash / offset, useful for year as page e.g. 2006..2009
	$smin=5; // minimal for one side is 5
	if ($perpage>($last-$first+1)) {
		$perpage=5; // randomly given
	}
	if ($curr>$last) {
		$curr=1;
	}
	$block=array();
	$begin=0;
	$end=0;
	if (($curr-$step-1)<=$first) {
		$begin=$first;
	}
	if ($curr+$step>=($last-1)) {
		$end=$last;
	}

	if ($begin==$first and $end==$last) { //whole bar
		$block[]=array($first,$last);
	} else {
		if ($begin==$first) { // first half
			if (($curr+$step-$first+1)<$smin) {
				$block[]=array($first,($smin+$first-1));
			} else {
				$block[]=array($first,($curr+$step));
			}
			$block[]=array(0,0);
			$block[]=array($last,$last);
		}
		elseif ($end==$last) { //last half
			$block[]=array($first,$first);
			$block[]=array(0,0);
			if (($last-$smin)<($curr-$step)) {
				$block[]=array(($last-$smin+1),$last);
			} else {
				$block[]=array(($curr-$step),$last);
			}
		}
		else {//middle
			$block[]=array($first,$first);
			$block[]=array(0,0);
			$block[]=array(($curr-$step),($curr+$step));
			$block[]=array(0,0);
			$block[]=array($last,$last);
		}
	}
	return array(
		'block'=>$block,
		'prev'=>($curr==$first)?0:($curr-1),
		'next'=>($curr==$last)?0:($curr+1),
		'curr'=>$curr,
		'url'=>$urlbase, // ready to be connected with id e.g. "url/id"
	);
}
function calc_total_page($totalrows=1,$max_per_page=1) {
	$pgtotal=intdiv ($totalrows,$max_per_page);
	if ($totalrows%$max_per_page) { $pgtotal++; }
	return $pgtotal;
}
function calc_page_offset($curr_page=1,$max_per_page=0) { #return offset for SQL. MUST ensure $curr (current page) is right . better use after &verify_current_page()
	if ($curr_page <1) {
		$curr_page=1;
	}
	return (($curr_page-1)*$max_per_page);
}
?>