<?php
print_oneliner();

//-------------------------------
function print_oneliner () {
	$db=new PocDB;
	$line=$db->getOne('SELECT line FROM '.POC_DB['ONELINER']['table'].' ORDER BY RANDOM() LIMIT 1');
	echo $line??'(._.)';
	1;
}
?>