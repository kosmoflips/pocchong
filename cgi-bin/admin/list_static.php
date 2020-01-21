<?php #UTF8 anchor (´・ω・｀)
require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/'.'Method_Kiyoism_Remaster.php');
?>
<?php
chklogin(1);
$k=new PocDB();
$table=$POCCHONG['STATIC']['table'];
$viewbase=$POCCHONG['STATIC']['url'];
$editbase=$POCCHONG['STATIC']['edit'];

$maxperpage=$POCCHONG['ADMIN']['max'];
$totalpg=calc_total_page($k->countRows($table), $maxperpage);
$curr=verify_current_page( (isset($_GET['page'])?$_GET['page']:1) , $totalpg);
$offset=calc_page_offset($curr,$maxperpage);
$query=sprintf ('SELECT id,title,num,perma FROM %s ORDER BY num, title LIMIT ?,?', $table);
$lists=$k->getAll($query, array($offset, $maxperpage));
$navi=calc_navi_set(1,$totalpg,$curr,$POCCHONG['GENERAL']['navi_step']);
$selurl=sprintf ('/a/list_static/%s', $table);
?>
<?php // ----- HTML --------------
write_html_admin(0);
?>
<?php
if (isset($_GET['dst'])) {
	if ($_GET['dst']==3) {
		print_system_msg('selected entry(s) deleted.');
	}
	elseif ($_GET['dst']==4) {
		print_system_msg('new order updated.');
	}
}
?>
<div><a href="<?php echo $editbase ?>/?new=1">Create New</a></div>
<?php print_navi_bar($navi, $POCCHONG['GENERAL']['navi_step'], $curr, $selurl); ?>
<form action="<?php echo $editbase ?>" method="post" accept-charset="utf-8" >
<input type="hidden" name="list_view_chk" value="1" />
<div>note: for convenience, use Order<=0 to avoid being processed in "misc" summary page.<br />
[0] general purpose<br />
[<0] specific topic/project</div>
<table>

<?php
foreach (array('del', 'id','order','title','permalink','edit') as $tt) {
	echo "<th>", $tt,"</th>\n";
}
foreach ($lists as $entry) {
	$viewurl=sprintf ('%s/%s', $viewbase, (empty($entry['perma'])?$entry['id']:$entry['perma']) );
	$editurl=sprintf ('%s/?id=%s', $editbase, $entry['id']);
?>
<tr>
<td><input type="checkbox" name="del_id[]" value="<?php echo $entry['id'] ?>" /></td>
<td><?php echo $entry['id'] ?></td>
<td><input type="number" name="<?php printf ("num[%s]",$entry['id']) ?>" value="<?php echo $entry['num'] ?>" style="width:40px" /></td>
<td><a href="<?php echo $viewurl ?>"><?php echo $entry['title'] ?></a></td>
<td><?php echo $entry['perma'] ?></td>
<td><a href="<?php echo $editurl ?>">edit</a></td>
</tr>

<?php
}
?>
</table>
<input type="submit" name="opt" value="DELETE" onclick="return confirm('delete selected?')">
<input type="submit" name="opt" value="Reorder" onclick="this.form.target='_self'">
</form>
<?php
write_html_admin(1);
?>
