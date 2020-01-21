<?php #UTF8 anchor (´・ω・｀)
require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/'.'Method_Kiyoism_Remaster.php');
// for listing all static pages, and individual side-projects
//only use  target="_blank"  when not using the site style
?>
<?php //prepare data
$k=new PocDB();
$baseurl=$POCCHONG['STATIC']['url'];
$table=$POCCHONG['STATIC']['table'];
$title=$POCCHONG['STATIC']['title'];

$allpages=$k->getAll('select id,title,desc,perma from '.$table.' where num>0 order by num,title');
// $allpages=$k->getAll('select id,title,desc,perma from '.$table.' where num<0 order by num,title');
?>
<?php // ---------- write html -------------
write_html_open_head($title);
?>
<style>
ul {
	margin-bottom: 45px;
}
ul:last-child {
	margin-bottom: unset;
}
</style>
<?php
write_html_open_body();
print_post_wrap(0);
// print_static_title($title);

print_static_title($title);
echo '<div class="archiv">',"\n"; // use class archiv for line-effects

// --- list static pages (num>0)---
// not retrieve from DB, directly input since not that many here
?>
<h3>Side Projects</h3>
<ul>
	<li><span class="archivname"><a href="/gradient" target="_blank">Gradient Maker</a>: </span><span class="archivdesc">2D multi-colour mixer</span></li>
</ul>

<h3>Individial Pages</h3>
<?php
// --- list side-projects ----
echo '<ul>',"\n";
foreach ($allpages as $entry) {
?>
	<li><span class="archivname"><a href="<?php echo $baseurl.'/'.$entry['perma'] ?>"><?php echo $entry['title'] ?></a><?php echo empty($entry['desc'])?'':': ' ?></span><span class="archivdesc"><?php echo $entry['desc'] ?></span></li>
<?php
}
?>
</ul>

<h3>Dead Archives</h3>
<ul>
	<li><span class="archivname"><a href="/cyouwa" target="_blank">Sea of Harmony</a>: </span><span class="archivdesc">self-translated KOKIA reviews around 2009~10 (no more updates)</span></li>
	<li><span class="archivname"><a href="/kaidan">深夜の怪談ラジオ irresponsible scripts</a>: </span><span class="archivdesc">of a Chata+Annabel CD</span></li>
</ul>
<?php
echo '</div><!-- archiv -->',"\n"; #close the last tag
print_edit_button($POCCHONG['STATIC']['edit']);
print_post_wrap(1);

write_html_close();
?>
