<?php #UTF8 anchor (´・ω・｀)
require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/'.'Method_Kiyoism_Remaster.php');
?>
<?php
chklogin(1);
$k=new PocDB();
$table='';
$viewbase='';
$editbase='';
if (isset($_GET['sel']) and $_GET['sel']=="mygirls") {
	$table='mygirls';
	$viewbase=$POCCHONG['MYGIRLS']['url'];
	$editbase=$POCCHONG['MYGIRLS']['edit'];
} else {
	$table='post';
	$viewbase=$POCCHONG['POST']['url'];
	$editbase=$POCCHONG['POST']['edit'];
}
$maxperpage=$POCCHONG['ADMIN']['max'];
$totalpg=calc_total_page($k->countRows($table), $maxperpage);
$curr=verify_current_page( (isset($_GET['page'])?$_GET['page']:1) , $totalpg);
$offset=calc_page_offset($curr,$maxperpage);
$query=sprintf ('SELECT id,title,epoch,gmt FROM %s ORDER BY id DESC LIMIT ?,?', $table);
$lists=$k->getAll($query, array($offset, $maxperpage));
$navi=calc_navi_set(1,$totalpg,$curr,$POCCHONG['GENERAL']['navi_step']);
$actionurl=sprintf ('/a/edit_%s', $table);
$selurl=sprintf ('/a/list_table/%s/page', $table);
?>
<?php // ----- HTML --------------
write_html_admin(0);
?>
<div><a href="<?php echo $editbase ?>/?new=1">Create New</a></div>
<?php print_navi_bar($navi, $POCCHONG['GENERAL']['navi_step'], $curr, $selurl); ?>
<form action="<?php echo $actionurl ?>" method="post" accept-charset="utf-8" >
<input type="hidden" name="list_view_chk" value="1" />
<table>

<?php
foreach (array('del', 'id', 'date', 'title', 'edit') as $tt) {
	echo "<th>", $tt,"</th>\n";
}
foreach ($lists as $entry) {
	$viewurl=sprintf ('%s/%s', $viewbase, $entry['id']);
	$editurl=sprintf ('%s/?id=%s', $editbase, $entry['id']);
?>
<tr>
<td><input type="checkbox" name="del_id[]" value="<?php echo $entry['id'] ?>" /></td>
<td><?php echo $entry['id'] ?></td>
<td><?php echo time27($entry['epoch'],3,$entry['gmt']) ?></td>
<td><a href="<?php echo $viewurl ?>"><?php echo $entry['title'] ?></a></td>
<td><a href="<?php echo $editurl ?>">edit</a></td>
</tr>

<?php
}
?>
</table>
<input type="submit" name="opt" value="DELETE" onclick="return confirm('delete selected?')">
</form>
<?php
write_html_admin(1);
?>
