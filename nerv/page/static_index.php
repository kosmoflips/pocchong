<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/synapse.php');
// for listing all static pages, and individual side-projects
//only use  target="_blank"  when not using the site style

print_page_static_list();

// -------- subs ------------
function print_page_static_list () {
	$p=new PocPage;
	$p->html_open();
	print_page_static_sideprojs();
	print_page_static_indivs();
	print_page_static_deads();
	print_edit_button(POC_DB['STATIC']['edit']);
	$p->html_close();
}

function process_data_static($pobj=null) {
	if (!$pobj) {
		return null;
	}
	$pack=POC_DB['STATIC'];
	$k=new PocDB();
	$allpages=$k->getAll('SELECT id,title,desc,perma FROM '.$pack['table'].' WHERE num>0 ORDER BY num,title');
	$cssex='<style>
ul {
	margin-bottom: 45px;
}
ul:last-child {
	margin-bottom: unset;
}
</style>';
	$pobj->title=$pack['title'];
	$pobj->head['extra']=array($cssex);
	$pobj->data=$allpages;
}

function print_page_static_sideprojs () {
	$symbol=rand_deco_symbol();
	?>
<h3><?php echo $symbol ?> Side Projects <?php echo $symbol ?></h3>
<div class="archiv">
<ul>
<li><span class="archivname"><a href="/gradient" target="_blank">Gradient Maker</a>: </span><span class="archivdesc">2D multi-colour mixer</span></li>
</ul>
</div>
<?php
}

function print_page_static_indivs () {
	$symbol2=rand_deco_symbol();
	$pack=POC_DB['STATIC'];
	$p=new PocPage;
	process_data_static($p);
	?>
<h3><?php echo $symbol2 ?> Individial Pages <?php echo $symbol2 ?></h3>
<div class="archiv">
<ul>
<?php // --- list side-projects ----
foreach ($p->data as $entry) {
?>
<li><span class="archivname"><a href="<?php echo $pack['url'],'/',$entry['perma'] ?>"><?php echo $entry['title'] ?></a><?php echo empty($entry['desc'])? '' : ': ' ?></span><span class="archivdesc"><?php echo $entry['desc'] ?></span></li>
<?php
}
?>
</ul>
</div>
<?php
}

function print_page_static_deads() {
	$symbol3=rand_deco_symbol();
	?>
<h3><?php echo $symbol3 ?> Dead Archives <?php echo $symbol3 ?></h3>
<div class="archiv"><ul>
<li><span class="archivname"><a href="/cyouwa" target="_blank">Sea of Harmony</a>: </span><span class="archivdesc">self-translated KOKIA reviews around 2009~10 (no more updates)</span></li>
<li><span class="archivname"><a href="/s/kaidan">深夜の怪談ラジオ irresponsible scripts</a>: </span><span class="archivdesc">of a Chata+Annabel CD</span></li>
</ul>
</div>
<?php
}

?>