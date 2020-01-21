<?php
require_once('cyouwa_lib.php');
$c=new CwDB();
if (isset($_GET['id'])) {
	$entry=$c->getRow('select * from article where id=?',array($_GET['id']));
}
if (!isset($entry)) {
	$entry=array();
}
?>
<?php
write_html_open($entry['title']);

echo '<h2>', $entry['title'], '</h2>', "\n";

echo $entry['content'];

write_html_close($entry['tag']);
?>