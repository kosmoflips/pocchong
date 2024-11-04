<?php
$PAGE=new PocPage;
$PAGE->html_open();
write_preview_sash();
echo '<article>',"\n";
printf ('<div class="datetime">%s</div>%s', clock27( time(),0,POC_META['default_gmt'],0), "\n");
printf ('<h3>* %s *</h3>%s', ($_POST['entry']['title']??'No Title'), "\n");
echo $_POST['entry']['content']??'',"\n";
echo '</article>',"\n";
$PAGE->html_close();

?>
