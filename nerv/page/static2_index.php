<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/synapse.php');

// for listing all static pages, and individual side-projects
//only use  target="_blank"  when not using the site style

$p=new PocPage;
$p->title="non-listed static pages";
$p->html_open();

// ----- parse static dir -----
$files=scandir(ROOT.POC_DB['STATIC']['dir2']);
$list=array();
foreach ($files as $file) {
	if (preg_match('/^\.+$/', $file)) {
		continue;
	}
	if (preg_match('/^_/', $file)) {
		continue;
	}
	if (preg_match('/(.+?)\.(php)$/i', $file,$x)) { # in htaccess, can ignore extension for php files 
		$link=$x[1];
	} else {
		$link=$file;
	}
	array_push( $list, array('name'=>$file,'link'=>$link) );
}

?>
<h3>Non-listed Individial Pages</h3>
<div>
<ul>
<?php
foreach ($list as $row) {
?>
<li><a href="/v/<?php echo $row['link']; ?>"><?php echo $row['name']; ?></a></li>
<?php
}
?>
</ul>
</div>

<?php
$p->html_close();
?>