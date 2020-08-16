<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/'.'Method_Kiyoism_Remaster.php');

print_page_static();


//---------------------------
function process_data_static_tag ($pobj=null, $tag='') {
	if (!$pobj) {
		return null;
	}
	$pack=POCCHONG['STATIC'];
	$k=new PocDB();
	if (isset($tag)) {
		$entry=$k->getRow('SELECT * FROM '.$pack['table'].' WHERE perma=?',array($tag));
		if (empty($entry)) {
			$entry=$k->getRow('SELECT * FROM '.$pack['table'].' WHERE id=?',array($tag));
		}
	}
	if ($entry) {
		$pobj->title=$entry['title'];
		$pobj->data=$entry;
		$pobj->head['extra']=array($entry['extra']);
	} else {
		jump($pack['url']);
		exit;
	}
}

function print_page_static() {
	$p=new PocPage;
	$pack=POCCHONG['STATIC'];
	$symbol=rand_deco_symbol();
	process_data_static_tag($p,$_GET['tag']??null);
	$p->html_open();
	?>
<h2><?php echo $symbol,' ', $p->title,' ',$symbol ?></h2>
<article>
<?php
echo $p->data['content'],"\n";
print_edit_button($pack['edit'].'/?id='.$p->data['id']);
?>
</article>
<?php
	$p->html_close();
}

?>
