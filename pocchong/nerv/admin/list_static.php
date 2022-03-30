<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/synapse.php');

chklogin(1);
$k=new PocDB();

$query=sprintf ('SELECT id,title,num,perma FROM %s ORDER BY num, title', POC_DB['STATIC']['table']);
// currently there's no need to set up navibar
$lists=$k->getAll($query);
$selurl=sprintf ('/a/list_static/%s', POC_DB['STATIC']['table']);

// ----- HTML --------------
PocPage::html_admin();

if (isset($_GET['dst'])) {
	if ($_GET['dst']==3) {
		print_system_msg('selected entry(s) deleted.');
	}
	elseif ($_GET['dst']==4) {
		print_system_msg('new order updated.');
	}
}
?>
<div><a href="<?php echo POC_DB['STATIC']['edit'] ?>/?new=1">Create New</a></div>

<form action="<?php echo POC_DB['STATIC']['edit'] ?>" method="post" accept-charset="utf-8" >
<input type="hidden" name="list_view_chk" value="1" />
<div>note: for convenience, use Order&lt;=0 to avoid being processed in "misc" summary page.<br />
[0] general purpose<br />
[<0] specific topic/project</div>
<table>

<?php
foreach (array('del', 'id','order','title','permalink','edit') as $tt) {
	echo "<th>", $tt,"</th>\n";
}
foreach ($lists as $entry) {
	$viewurl=sprintf ('%s/%s', POC_DB['STATIC']['url'], (empty($entry['perma'])?$entry['id']:$entry['perma']) );
	$editurl=sprintf ('%s/?id=%s', POC_DB['STATIC']['edit'], $entry['id']);
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
PocPage::html_admin(1);
?>