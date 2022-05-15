<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/synapse.php');

// output plaintext
header("Content-Type: text/plain");

$sqls=preg_split('/;/',$_POST['sql']);
// ------------ data process --------------
chklogin(1);

$k=new PocDB();
foreach ($sqls as $sql) {
	$sql=preg_replace('/^\s+|\s+$/', '', $sql);
	if (!preg_match('/\S/', $sql)) {
		continue;
	}
	if (preg_match('/^#/', $sql)) {
		continue;
	}
	
	echo str_repeat("=", 70),"\n";
	echo "statement: ", $sql,"\n";
	echo str_repeat("=", 70),"\n";
	$do=$k->getAll($sql);

	// echo "<table>";
	$ncol=0;
	$header=0;
	foreach ($do as $row) {
		// print header
		if (!$header) {
			print_row($row, 1);
			$header=1;
		}
		// print rows
		print_row($row);
	}
}

function print_row ($row,$header=0) {
	$ncol=sizeof($row)/2; // IMPORTANT: somehow, the array has 2 sets of key/value pairs, one set have keys being index and other set have key by names
	foreach ($row as $key=>$item) {
		if (is_numeric($key) and $key<$ncol) {
			continue;
		}
		// echo '<td>', $item,"</td>";
		if ($header) {
			echo $key;
		} else {
			echo $item;
		}
		echo "\t";
	}
	echo "\n";
}

?>

