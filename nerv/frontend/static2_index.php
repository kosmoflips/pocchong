<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/synapse.php');

// for listing all static pages, and individual side-projects
//only use  target="_blank"  when not using the site style

$p=new PocPage;
$p->title='Unlisted Pages';
$p->static_open(0);

// ----- parse static dir -----
$files=scandir($_SERVER['DOCUMENT_ROOT'].POC_DB_STATIC['dir2']);
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
	array_push( $list, array($file,$link) );
}

?>

<div>
<ul>
<?php
foreach ($list as $row) {
?>
<li><a href="/v/<?php echo $row[1]; ?>"><?php echo $row[0]; ?></a></li>
<?php
}
?>
</ul>
</div>

<?php
$p->html_close();
?>