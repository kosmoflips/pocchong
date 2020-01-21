<?php #UTF8 anchor (´・ω・｀)
require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/'.'Method_Kiyoism_Remaster.php');
?>
<?php // extra vars
$title='';
$extracss=''; // css, js, etc... direct html code
?>
<?php // html begins
write_html_open($title);
// write_html_open_head($title);
//<style></style>
// write_html_open_body();
print_post_wrap(0);
print_static_title($title);
?>

<!-- static content here -->

<?php
print_post_wrap(1);
write_html_close();
?>