<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/synapse.php');

chklogin(1);

$k=new PocDB();
$table='';
$viewbase='';
$editbase='';
if (isset($_GET['sel']) and $_GET['sel']=="mygirls") {
	$table='mygirls';
	$viewbase=POC_DB_MG['url'];
	$editbase=POC_DB_MG['edit'];
	$mknewbase=POC_DB_MG['new'];
	$qextra=',art_id';
	$actionurl=POC_DB_MG['save'];
} else {
	$table='post';
	$viewbase=POC_DB_POST['url'];
	$editbase=POC_DB_POST['edit'];
	$qextra='';
	$mknewbase=POC_DB_POST['new'];
	$actionurl=POC_DB_POST['save'];
}
$maxperpage=40; # how many items per page
$totalpg=calc_total_page($k->countRows($table), $maxperpage);
$curr=$_GET['page']??1;
$offset=calc_page_offset($curr,$maxperpage);
$query=sprintf ('SELECT id,hide,title,epoch,gmt%s FROM %s ORDER BY epoch DESC LIMIT ?,?', $qextra, $table);
$lists=$k->getAll($query, array($offset, $maxperpage));
$selurl=sprintf ('/a/list_table?sel=%s&page=', $table);

$PAGE=new PocPage;
$PAGE->navi['bar']=mk_navi_bar(1,$totalpg,$maxperpage,$curr,POC_NAVI_STEP,$selurl);



// ----- HTML --------------
$PAGE->html_admin();

?>
<div><a href="/a/">Return to Control Panel</a> || <a href="<?php echo $mknewbase; ?>">Create New</a></div>
<?php
$PAGE->html_admin_navi();
?>
<form action="<?php echo $actionurl; ?>" method="post" accept-charset="utf-8" >
<input type="hidden" name="list_view_chk" value="1" />
<table>
<?php
foreach (array('id', 'date', 'title', 'art_id', 'hidden?', 'edit', 'DEL') as $tt) {
	echo "<th>", $tt,"</th>\n";
}
foreach ($lists as $entry) {
	$viewurl=sprintf ('%s?id=%s', $viewbase, $entry['id']);
	$editurl=sprintf ('%s?id=%s', $editbase, $entry['id']);
?>
<tr>
<td><?php echo $entry['id']; ?></td>
<td><?php echo clock27($entry['epoch'],3,$entry['gmt']); ?></td>
<td><a href="<?php echo $viewurl; ?>"><?php echo $entry['title']; ?></a></td>
<td><?php echo $entry['art_id'] ?? ''; ?></td>
<td><?php echo $entry['hide']?'yes':''; ?></td>
<td><a href="<?php echo $editurl; ?>">edit</a></td>
<td><input type="checkbox" name="del_id[]" value="<?php echo $entry['id']; ?>" /></td>
</tr>
<?php
}
?>
</table>
<input type="submit" name="opt" value="DELETE selected entries" onclick="return confirm('delete selected entries?')">
</form>
<?php
PocPage::html_admin(1);
?>
