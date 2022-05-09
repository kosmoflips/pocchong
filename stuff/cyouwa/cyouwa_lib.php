<?php
$CYOUWA=array(
	'title'=>'Sea of Harmony ～Cyouwa Oto～',
	'db'=>$_SERVER['DOCUMENT_ROOT'].'/stuff/cyouwa/cyouwa_archive.sqlite',
);
function write_html_open($title='') {
	include ('incl_html_open.php');
}
function write_html_close($tag=0) {
	if ($tag==1) { //infinity special set
		include('incl_html_close_infinity.html');
	}
	elseif ($tag==2) { //real world special set
		include('incl_html_close_realworld.html');
	}
	include('incl_html_close.html');
}
?>
<?php // ----------- db access. in a class obj ---------------
class CwDB { // copied from main lib.
public function connect() {
	global $CYOUWA;
	if (file_exists($CYOUWA['db'])) {
		$dbh = new PDO('sqlite:'.$CYOUWA['db']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $dbh;
	} else {
		return false;
	}
}
public function dosql($stat,$vars=null) {
	try {
		if (!isset($stat)) { return false; }
		$dbh=$this->connect();
		$sth=$dbh->prepare($stat);
		if (isset($vars) and !empty($vars)) {
			for ($i=0; $i< count($vars); $i++) {
				$sth->bindValue( ($i+1), $vars[$i]);
			}
		}
		$sth->execute();
		return $sth;
	}
	catch(PDOException $e) { echo $e->getMessage(); }
}
public function getAll($stat,$vars=null) { # get an array of all rows, each row is a hash ref
	try {
		$sth=$this->dosql($stat,$vars);
		return $sth->fetchAll();
	} catch(PDOException $e) { echo $e->getMessage(); }
}
public function getRow($stat,$vars=null) { # return H ref from 1 row. stat must match
	try {
		$sth=$this->dosql($stat,$vars);
		$rows=$sth->fetchAll();
		if (isset ($rows[0]) ) {
			return $rows[0];
		} else {
			return null;
		}
	} catch(PDOException $e) { echo $e->getMessage(); }
}
public function getOne($stat, $vars=null) { #get only one single [string] value
	try {
		$sth=$this->dosql($stat,$vars);
		$col=$sth->fetchColumn(); #Fetch the first column from the first row in the result set
		if (isset ($col) ) {
			return $col;
		} else {
			return null;
		}
	} catch(PDOException $e) { echo $e->getMessage(); }
}
} // closing class
?>

