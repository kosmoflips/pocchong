<?php #UTF8 anchor (´・ω・｀)
require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/'.'Method_Kiyoism_Remaster.php');
// for listing all static pages, and individual side-projects
//only use  target="_blank"  when not using the site style

$pack=$POCCHONG['STATIC']; // type, table, title, url, url_page, max, edit
$PAGE=process_data_static();

?>
<?php //write html
include ($_SERVER['DOCUMENT_ROOT'].$POCCHONG['TMPL']['page1']);
include ($_SERVER['DOCUMENT_ROOT'].$POCCHONG['TMPL']['entry1']);
?>
<div class="archiv">
<h3>Side Projects</h3>
<ul>
<li><span class="archivname"><a href="/gradient" target="_blank">Gradient Maker</a>: </span><span class="archivdesc">2D multi-colour mixer</span></li>
</ul>

<h3>Individial Pages</h3>
<ul>
<?php // --- list side-projects ----
foreach ($PAGE['data'] as $entry) {
?>
<li><span class="archivname"><a href="<?php echo $pack['url'],'/',$entry['perma'] ?>"><?php echo $entry['title'] ?></a><?php echo empty($entry['desc'])?'':': ' ?></span><span class="archivdesc"><?php echo $entry['desc'] ?></span></li>
<?php
}
?>
</ul>

<h3>Dead Archives</h3>
<ul>
<li><span class="archivname"><a href="/cyouwa" target="_blank">Sea of Harmony</a>: </span><span class="archivdesc">self-translated KOKIA reviews around 2009~10 (no more updates)</span></li>
<li><span class="archivname"><a href="/kaidan">深夜の怪談ラジオ irresponsible scripts</a>: </span><span class="archivdesc">of a Chata+Annabel CD</span></li>
</ul>
</div><!-- archiv -->
<?php
print_edit_button($POCCHONG['STATIC']['edit']);
include ($_SERVER['DOCUMENT_ROOT'].$POCCHONG['TMPL']['entry2']);
include ($_SERVER['DOCUMENT_ROOT'].$POCCHONG['TMPL']['page2']);
?>
<?php //prepare data
function process_data_static() {
	$k=new PocDB();
	global $POCCHONG;
	global $pack;

	$allpages=$k->getAll('select id,title,desc,perma from '.$pack['table'].' where num>0 order by num,title');
	$cssex='<style>
ul {
	margin-bottom: 45px;
}
ul:last-child {
	margin-bottom: unset;
}
</style>';
	return array(
		'title'=>$pack['title'],
		'head-extra'=>array($cssex),
		'data'=>$allpages,
	);
}
?>