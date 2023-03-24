<?php // -------- navi-bar related ---------------
function fname2name ($file='') { # split filename by "_" and return name by uc first letter -- unless define a new name later
	# remove last elem: php OR other extension
	$file=basename($file);
	if (preg_match('/\./', $file )) {
		$x0=preg_split('/\./', $file);
		array_pop($x0);
		$x1=$x0[0];
	} else {
		$x1=$file;
	}
	$fsub=preg_split('/_/', $x1);
	foreach (array_keys($fsub) as $i) {
		$fsub[$i]=ucfirst($fsub[$i]);
	}
	return (implode(' ',$fsub) );
}
function static_page_open ($title='No Title') {
	$symbol=rand_deco_symbol();
	echo '<h2>', $symbol,' ', $title,' ',$symbol, '</h2>', "\n";
	echo '<article>', "\n";
}
?>