<?php // ----------- db access. in a class obj ---------------

/* --------- enable following in php.ini ------------
enable extension=mbstring
enable extension=mysqli
enable extension=pdo_sqlite
-----------------*/

class PocDB {

const POC_DB_FILE = '/axon/pocchong_data.sqlite';

public function connect() {
	$dbfile=$_SERVER['DOCUMENT_ROOT'].$this::POC_DB_FILE;
	if (file_exists($dbfile) ) {
		$dbh = new PDO('sqlite:'.$dbfile);
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
public function getRow($stat,$vars=null) { # return H ref from 1 row. stat must match this purpose. e.g. stat can NOT ask for multiple rows
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
public function _getNext($table='',$curr=1,$getprev=0) { # for table [post/mygirls] or any other tables that have both id,title as field, and id/epoch/time should be ordered the same
	if (!$table) { return null; }
	$query=sprintf ('SELECT id,title FROM %s WHERE id %s %s ORDER BY id %s LIMIT 1',
		$table,
		($getprev?'<':'>'),
		$curr,
		($getprev?'desc':'') );
	try {
		return $this->getRow($query);
	} catch(PDOException $e) { echo $e->getMessage(); }
}
public function countRows ($table=null) { # $k is db-obj
	if (!isset($table)) {return 0; }
	return $this->getOne('SELECT COUNT(*) FROM '.$table);
}
public function getTags() { // return $tags->{$tag_id} = $tag_name
	$tags=array();
	$tags0=$this->getAll('SELECT id,name FROM mygirls_tag');
	foreach ($tags0 as $tag1) {
		$tags[$tag1['id']] = $tag1['name'];
	}
	ksort($tags); # sort by tag id
	return $tags;
}
public function yearlast ($sel=0) { // return year 4 digits. get years of the most recent post from DB, so $k is required
	// yearfirst is given in ini file
	$yr1b=0;
	$yr2b=0;
	$yr1b=$this->getOne('select year from post order by year desc limit 1') +2000;
	$yr2b=$this->getOne('select year from mygirls order by year desc limit 1') +2000;
	if ($sel==1) { # newest POST
		return $yr1b;
	} elseif ($sel==2) { // newest MG
		return $yr2b;
	} else {
		return (($yr1b>$yr2b)?$yr1b:$yr2b);
	}
}
public function nextID ($table=null) { // for INSERT . return current last id +1
	if (isset($table)) {
		$id=$this->getOne('SELECT id FROM '.$table.' ORDER BY id DESC LIMIT 1');
	}
	if (!isset($id)) {
		$id=0;
	}
	return ($id+1);
}
public function prepNew ($table=null) { // prepare a new entry for input table
	$stru=array();
	if (isset($table)) {
		$stat=sprintf('PRAGMA table_info(%s)', $table);
		$info=$this->getAll($stat);
		if (!empty($info)) {
			foreach ($info as $row) {
				$stru[$row['name']]=isset($row['dflt_value'])?$row['dflt_value']:'';
			}
		}
	}
	return ($stru ?? null);
}
} // closing class
?>
