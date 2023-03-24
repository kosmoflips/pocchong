<?php // ----- db table info ----
const POC_DB_POST = array(
	'title' => 'Days',
	'table' => 'post',
	'url' => '/days/',
	'max' => 7, # show N posts per page
	'edit' => '/a/edit_post/'
);

const POC_DB_MG = array(
	'title' => 'MyGirls',
	'title2' => "幻想調和",
	'table' => 'mygirls',
	'table_link' => 'mygirls_link',
	'table_pcs' => 'mygirls_pcs',
	'url' => '/mygirls/',
	'edit' => '/a/edit_mygirls/',
	'max_gallery' => 12
);

const POC_DB_ARCHIV = array(
	'title' => 'Archiv',
	'table' => 'post',
	'url' => '/archiv/',
	'max' => 50
);

const POC_DB_STATIC = array(
	'title' => 'Backyard',
	'url_index' => '/backyard/',
	'url' => '/s',
	'dir' => '/dendron/static', # for indexed static pages
	'dir2' => '/dendron/static2', # for non-indexed 'private' pages
	'info' => '_desrc.ini'
);
?>
<?php // ----------- db access. in a class obj ---------------

/* --------- enable following in php.ini ------------
enable extension=mbstring
enable extension=mysqli
enable extension=pdo_sqlite
-----------------*/

class PocDB {

// const POC_DB_FILE = $_SERVER['DOCUMENT_ROOT'].'/axon/pocchong_data.sqlite';
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

} // closing class
?>
