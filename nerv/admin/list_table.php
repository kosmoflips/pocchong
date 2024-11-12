<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/synapse.php');

chklogin(1);

$k=new PocDB();
$table='';
$tablecode=0;
if (isset($_GET['sel']) and $_GET['sel']==2) {
	$tablecode=2;
	$table='mygirls';
	$qextra=',art_id';
} else {
	$tablecode=1;
	$table='post';
	$qextra='';
}
$maxperpage=40; # how many items per page
$totalpg=calc_total_page($k->countRows($table), $maxperpage);
$curr=$_GET['page']??1;
$offset=calc_page_offset($curr,$maxperpage);
$query=sprintf ('SELECT id,hide,title,epoch,gmt%s FROM %s ORDER BY epoch DESC LIMIT ?,?', $qextra, $table);
$lists=$k->getAll($query, array($offset, $maxperpage));
$PAGE=new PocPage;
$PAGE->navi['bar']=mk_navi_bar(1,$totalpg,$maxperpage,$curr,POC_NAVI_STEP,$tablecode,1);



// ----- HTML --------------
$PAGE->html_admin();

?>
<div><a href="/a/">Return to Control Panel</a> || <a href="<?php echo get_const_by_id($tablecode)['new']; ?>">Create New</a></div>
<?php $PAGE->show_navi_bar(); ?>
<form action="<?php echo get_const_by_id($tablecode)['save']; ?>" method="post" accept-charset="utf-8" >
<input type="hidden" name="list_view_chk" value="1" />
<table>
<?php
foreach (array('id', 'date', 'title', 'art_id', 'hidden?', 'edit', 'DEL') as $tt) {
	echo "<th>", $tt,"</th>\n";
}
foreach ($lists as $entry) {
	$editurl=mk_id_view_url($tablecode,$entry['id'],1);
?>
<tr>
<td><?php echo $entry['id']; ?></td>
<td><?php echo clock27($entry['epoch'],3,$entry['gmt']); ?></td>
<td><a href="<?php echo mk_id_view_url($tablecode,$entry['id']); ?>"><?php echo $entry['title']; ?></a></td>
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
