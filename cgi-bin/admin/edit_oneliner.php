<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/'.'Method_Kiyoism_Remaster.php');
$TVAR=POCCHONG['ONELINER']['table'];
$DATA_IN=$_POST;

chklogin(1);
$k=new PocDB();
$all=$k->getAll('SELECT * FROM '.$TVAR.' ORDER BY id DESC');
if (isset($DATA_IN['line'])) {
	$id=$k->nextID($TVAR);
	$k->dosql('INSERT INTO '.$TVAR.' ("id","line") VALUES(?,?)', array($id,$DATA_IN['line']));	jump(POCCHONG['ONELINER']['edit']);
	exit;
}


// ----- HTML --------------
PocPage::html_admin();
?>
<div>
<form action="<?php echo $_SERVER['REDIRECT_URL'] ?>" method="post" accept-charset="utf-8">
<input type="text" name="line" size="70" value="" />
<input type="submit" />
</form>
</div>
<hr />
Notes<br />
- this table is too simple. use SQL for delete.<br />
- do NOT put time-sensitive thing (go to twitter instead) unless it's necessarily the case<br />
<br />
<table>
<?php
foreach (array('id','line') as $tt) {
	echo "<th>", $tt,"</th>\n";
}
foreach ($all as $row) {
?>
<tr>
<td><?php echo $row['id'] ?></td>
<td><?php echo $row['line'] ?></td>
</tr>
<?php
}
?>
</table>
<?php
PocPage::html_admin(1);
?>