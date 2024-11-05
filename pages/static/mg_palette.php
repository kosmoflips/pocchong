<?php
$p->title='MG palette';
$p->static_open(1);
?>
<style>
.lefthalf, .righthalf {
	display: inline-block;
}
.lefthalf {
	width: 80%;
}
.colblock {
	height: 40px;
	width: 40px;
	display: block;
	border: 1px solid #000;
	margin:auto;
}
th, td {
	padding: 3px 5px;
}
td {
	font-size: 75%;
}
</style>
<?php
function col2rgb ($hex) { # hex has no #
	list($r, $g, $b) = sscanf($hex, "%02x%02x%02x");
	return (array($r, $g, $b));
}

$p->static_open(2);

$mgps=array(
's1'=>array("FCEFDE"),
's2'=>array("FFF6E5")
);
$mgp=array(
'M'=>array("93A4E4","02121F","950B2F"),
'K'=>array("E40929","37040C","974046"),
'Y'=>array("9E373A","2F0E0F","363636"),
'I'=>array("3C9537","012C06","EFAD21"),
'C'=>array("D9D682","394222","9F9AF6"),
'T'=>array("DE68CC","24061F","614D82"),
'A'=>array("C40C0A","4C1A1A","965BB7"),
'F'=>array("4591E6","122849","035807"),
'L'=>array("C69EFF","361F58","FEF2FF"),
'E'=>array("ECD35E","1B1704","251F89"),
'S'=>array("3CAA49","06340B","F7C44E"),
'N'=>array("A5C9D2","06241F","FDFFAB")
);
ksort($mgp);
?>
<div class="lefthalf">
<table style="text-align:center; width: 90%;margin:auto; padding: 10px; background: #ffffff">
<?php
$i=0;
foreach ($mgp as $x=>$ps) {
	if (($i%2)==0) {
?>
<tr>
<?php
	}
?>
<th><?php echo $x; ?></th>
<?php
	foreach ($ps as $cor) {
		$rgb=col2rgb($cor);
?>
<td><div class="colblock" style="background: #<?php echo $cor;?>"></div>#<?php echo $cor;?><br />(<?php echo $rgb[0], ',', $rgb[1], ',', $rgb[2];?>)</td>
<?php
	}
	if ((($i+1)%2)==0) {
?>
</tr>
<?php
	}
	$i++;
}
?>
</table>
</div>
<div class="righthalf">
<table>
<?php
foreach ($mgps as $x1=>$c1) {
?>
<tr>
<th><?php echo $x1; ?></th>
<?php
	foreach ($c1 as $c2) {
		$rgb=col2rgb($c2);
?>
<td><div class="colblock" style="background: #<?php echo $c2;?>"></div>#<?php echo $c2;?><br />(<?php echo $rgb[0], ',', $rgb[1], ',', $rgb[2];?>)</td>
<?php
	}
?>
</tr>
<?php
}
?>
</table>
</div>
