<?php
print_oneliner();

//-------------------------------
function print_oneliner () {
	$k=new PocDB;
	$line=$k->getOne('SELECT line FROM '.POCCHONG['ONELINER']['table'].' ORDER BY RANDOM() LIMIT 1');
	echo $line??'(._.)';
	1;
}
?>