<?php
$recent_year=3;
$total=15;
$list=get_rand_days($recent_year,$total);
?>
<h4><?php echo rand_deco_symbol(); ?> <a href="/days">Random days</a></h4>
<?php print_plist($list,1,$recent_year); ?>
<div class="line">・～・～・～・～・～・～・～・～・～・～・～・～・～・～・～・～・</div>
<?php print_plist($list,(1+$recent_year)); ?>
<?php
function get_rand_days ($recent=3,$total=15) {
	$max=$total-$recent; // non-most recent
	$k=new PocDB;
	// newest ones
	$list=array();
	$TABLE=POC_DB['POST']['table'];
	$stat0='SELECT id,title,gmt,epoch FROM '.$TABLE;
	$es=$k->getAll($stat0.' ORDER BY id DESC LIMIT '.$recent);
	foreach ($es as $e1) {
		$list[$e1['id']]=$e1;
	}

	// randoms
	$epomin=mktime(1, 1, 1, 1, 1, (date('Y')-3));
	$maxrecent=sprintf ('%.0f', ($max*0.5)); // xx% posts to be from the past X yrs
	$maxrest=$max-$maxrecent;
	$stat=$stat0.' ORDER BY RANDOM() LIMIT 1';
	while ($max>0) {
		$entry=$k->getRow($stat);
		if (isset($list[$entry['id']])) {
			continue;
		}
		if ($maxrecent>0 and $entry['epoch']>=$epomin) {
			$list[$entry['id']]=$entry;
			$maxrecent--;
			$max--;
		}
		elseif ($maxrest>0) {
			$list[$entry['id']]=$entry;
			$maxrest--;
			$max--;
		}
	}
	krsort($list);
	return $list;
}
function print_plist ($list=null, $idx=0, $total=0) {
	if (!$list) {
		return false;
	}
	if ($total<=0) {
		$total=sizeof($list);
	}
	if ($idx<=0) {
		$idx=1;
	}
	$cid=0;
	$csum=0;
	foreach ($list as $id=>$entry) {
		$cid++;
		if ($cid<$idx) {
			continue;
		}
		$csum++;
		if ($csum>$total) {
			break;
		}
	?>
<div><a href="<?php echo POC_DB['POST']['url']; ?>/<?php echo $entry['id']; ?>">[<?php echo clock27($entry['epoch'], 5, $entry['gmt']); ?>] <?php echo $entry['title']; ?></a></div>
<?php
	}
}
?>
