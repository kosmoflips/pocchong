<?php #UTF8 anchor (´・ω・｀)
require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/'.'Method_Kiyoism_Remaster.php');
?>
<?php //prepare data
$k=new PocDB();
$baseurl=$POCCHONG['STATIC']['url'];
$table=$POCCHONG['STATIC']['table'];

if (isset($_GET['tag'])) {
	$entry=$k->getRow('select * from '.$table.' where perma=?',array($_GET['tag']));
	if (empty($entry)) {
		$entry=$k->getRow('select * from '.$table.' where id=?',array($_GET['tag']));
	}
	if (empty($entry)) { #specified entry doesn't exist
		jump ('/');
	}
} else {
	jump ('/');
}
?>
<?php // ---------- write html -------------
write_html_open_head($entry['title']);
echo $entry['extra'];
write_html_open_body();
print_post_wrap(0);
echo '<article>',"\n";
print_static_title($entry['title']);

echo $entry['content'];

print_edit_button($POCCHONG['STATIC']['edit'].'/?id='.$entry['id']);

echo '</article>',"\n";
print_post_wrap(1);
write_html_close();
?>