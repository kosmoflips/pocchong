<?php #UTF8 anchor (´・ω・｀)
require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/'.'Method_Kiyoism_Remaster.php');
?>
<?php // extra vars
$title='Free Type';
$extracss=''; // css, js, etc... direct html code
?>
<?php // html begins
write_html_open($title,$extracss,1);
print_post_wrap(0);
print_static_title($title);
?>

<div style="width: 90%; text-align: center;margin:auto"><textarea rows="5" cols="13" placeholder="type some kanji so I can see it large!" style="font-size: 50pt"></textarea></div>

<?php
print_post_wrap(1);
write_html_close();
?>