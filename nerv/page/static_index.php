<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/synapse.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/lib_static.php');
// for listing all static pages, and individual side-projects
//only use  target="_blank"  when not using the site style

$p=new PocPage;
$cssex='<style>
ul {
	margin-bottom: 45px;
}
ul:last-child {
	margin-bottom: unset;
}
</style>';
$p->title=POC_DB['STATIC']['title'];
$p->add_extra([$cssex]);
$p->html_open();

# exclude these pages in scan loop
$exclude=[
	'about.php',
	'kaidan.php',
	];

# page.php => description
$desrc=readini($_SERVER['DOCUMENT_ROOT'].POC_DB['STATIC']['dir'].'/'.POC_DB['STATIC']['info']);
// ----- parse static dir -----
$files=scandir($_SERVER['DOCUMENT_ROOT'].POC_DB['STATIC']['dir']);
$list=array();
foreach ($files as $file) {
	if (preg_match('/^_/', $file)) {
		continue;
	}
	if (in_array(strtolower($file), $exclude)) {
		continue;
	}
	if (preg_match('/(.+?)\.(php)$/i', $file,$x)) {
		array_push( $list, array('name'=>fname2name($file),'link'=>$x[1]) );
	}
}

$symbol2=rand_deco_symbol();
$symbol3=rand_deco_symbol();
?>
<h3><?php echo $symbol2 ?> Individial Pages <?php echo $symbol2 ?></h3>
<div class="archiv">
<ul>
<?php
foreach ($list as $row) {
?>
<li><span class="archivname"><a href="/s/<?php echo $row['link']; ?>"><?php echo $row['name']; ?></a></span>
	<?php if (array_key_exists($row['link'],$desrc)) { ?>
	<span class="archivdesc"><?php echo $desrc[$row['link']]; ?></span>
	<?php } ?>
</li>
<?php
}
?>
</ul>
</div>


<h3><?php echo $symbol3 ?> Dead Archives <?php echo $symbol3 ?></h3>
<div class="archiv"><ul>
<li><span class="archivname"><a href="/cyouwa" target="_blank">Sea of Harmony</a>: </span><span class="archivdesc">self-translated KOKIA reviews around 2009~10 (no more updates)</span></li>
</ul>
</div>

<?php
$p->html_close();
?>