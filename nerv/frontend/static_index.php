<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/synapse.php');

// for listing all static pages, and individual side-projects
//only use  target="_blank"  when not using the site style

$p=new PocPage;
$p->title=POC_DB_STATIC['title'];
$p->static_open(0,1);

$slist=array(
# x(.php) => title, description
'classical' => array('Classical Composer Names', 'for tagging purpose'),
'freetype' => array('Free Type', 'enlarge typed text for kanji study'),
'linearts' => array('Line Arts', 'plain text based horizontal decoration lines'),
'mg_palette' => array('MG Palette','standard colour reference chart'),
'myo_smiley' => array("MyOpera Smileys", "MyOpera legacy smiley list"),
'tuning'=> array('Tuning Chart', 'pitch frequency for tuning')
);

$symbol2=rand_deco_symbol();
$symbol3=rand_deco_symbol();
?>
<h2><?php echo $symbol2 ?> Individial Pages <?php echo $symbol2 ?></h2>
<div class="archiv">
<ul>
<?php
foreach ($slist as $fn => $row) {
?>
<li>
	<span class="archivname" style="margin-right: 7px;"><a href="/s/<?php echo $fn; ?>"><?php echo $row[0]??'No Title'; ?></a></span>
	<span class="archivdesc"><?php echo $row[1]??''; ?></span>
</li>
<?php
}
?>
</ul>
</div>


<h2><?php echo $symbol3 ?> Dead Archives <?php echo $symbol3 ?></h2>
<div class="archiv"><ul>
<li><span class="archivname"><a href="/cyouwa" target="_blank">Sea of Harmony</a>: </span><span class="archivdesc">self-translated KOKIA reviews around 2009~10 (no more updates)</span></li>
</ul>
</div>

<?php
$p->html_close();
?>