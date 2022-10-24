<?php
$title=fname2name($_SERVER['REDIRECT_URL']);
$p->title=$title;
$extra=['
<style>
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
'];
$p->add_extra($extra);
$p->html_open();
static_page_open($title);
?>

<table style="text-align:center; width: 90%;margin:auto; background: #ffffff">
<tr>
	<th></th>
	<th>I1</th>
	<th>I2</th>
	<th>H</th>
	<td><br /></td>
	<th>I1</th>
	<th>I2</th>
	<th>H</th>
	<th></th>
	<th></th>
</tr>
<tr>
	<th>M</th>
	<td><div class="colblock" style="background: #93A4E4"></div>#93A4E4<br />(147,164,228)</td>
	<td><div class="colblock" style="background: #02121F"></div>#02121F<br />(2,18,31)</td>
	<td><div class="colblock" style="background: #950B2F"></div>#950B2F<br />(149,11,47)</td>

	<th>E</th>
	<td><div class="colblock" style="background: #E40929"></div>#E40929<br />(228,9,41)</td>
	<td><div class="colblock" style="background: #37040C"></div>#37040C<br />(55,4,12)</td>
	<td><div class="colblock" style="background: #974046"></div>#974046<br />(151,64,70)</td>

	<th>s1</th>
	<td><div class="colblock" style="background: #fcefde"></div>#FCEFDE<br />(252,239,222)</td>
</tr>
<tr>
	<th>T</th>
	<td><div class="colblock" style="background: #9E373A"></div>#9E373A<br />(158,55,58)</td>
	<td><div class="colblock" style="background: #2F0E0F"></div>#2F0E0F<br />(47,14,15)</td>
	<td><div class="colblock" style="background: #363636"></div>#363636<br />(54,54,54)</td>

	<th>Y</th>
	<td><div class="colblock" style="background: #3C9537"></div>#3C9537<br />(60,149,55)</td>
	<td><div class="colblock" style="background: #012C06"></div>#012C06<br />(1,44,6)</td>
	<td><div class="colblock" style="background: #EFAD21"></div>#EFAD21<br />(239,173,33)</td>

	<th>s2</th>
	<td><div class="colblock" style="background: #FFF6E5"></div>#FFF6E5<br />(255,246,229)</td>
</tr>
<tr>
	<th>C</th>
	<td><div class="colblock" style="background: #D9D682"></div>#D9D682<br />(217,214,130)</td>
	<td><div class="colblock" style="background: #394222"></div>#394222<br />(57,66,34)</td>
	<td><div class="colblock" style="background: #9F9AF6"></div>#9F9AF6<br />(159,154,246)</td>

	<th>T</th>
	<td><div class="colblock" style="background: #DE68CC"></div>#DE68CC<br />(222,104,204)</td>
	<td><div class="colblock" style="background: #24061F"></div>#24061F<br />(30,6,31)</td>
	<td><div class="colblock" style="background: #614D82"></div>#614D82<br />(97,77,130)</td>

	<th>u</th>
	<td><div class="colblock" style="background: #1E284D"></div>#1E284D<br />(30,40,77)</td>
</tr>
<tr>
	<th>A</th>
	<td><div class="colblock" style="background: #C40C0A"></div>#C40C0A<br />(196,12,10)</td>
	<td><div class="colblock" style="background: #4C1A1A"></div>#4C1A1A<br />(76,26,26)</td>
	<td><div class="colblock" style="background: #965BB7"></div>#965BB7<br />(150,91,183)</td>

	<th>F</th>
	<td><div class="colblock" style="background: #4591E6"></div>#4591E6<br />(69,145,230)</td>
	<td><div class="colblock" style="background: #122849"></div>#122849<br />(18,40,73)</td>
	<td><div class="colblock" style="background: #035807"></div>#035807<br />(3,88,7)</td>

	<th>r</th>
	<td><div class="colblock" style="background: #FFFF9D"></div>#FFFF9D<br />(255,255,157)</td>
</tr>
<tr>
	<th>L</th>
	<td><div class="colblock" style="background: #C69EFF"></div>#C69EFF<br />(198,158,255)</td>
	<td><div class="colblock" style="background: #361F58"></div>#361F58<br />(54,31,88)</td>
	<td><div class="colblock" style="background: #FEF2FF"></div>#FEF2FF<br />(254,242,255)</td>

	<th>K</th>
	<td><div class="colblock" style="background: #ECD35E"></div>#ECD35E<br />(236,211,94)</td>
	<td><div class="colblock" style="background: #1B1704"></div>#1B1704<br />(27,23,4)</td>
	<td><div class="colblock" style="background: #251F89"></div>#251F89<br />(37,31,137)</td>

	<td><br /></td>
	<td><br /></td>
</tr>
<tr>
	<th>S</th>
	<td><div class="colblock" style="background: #3CAA49"></div>#3CAA49<br />(60,170,73)</td>
	<td><div class="colblock" style="background: #06340B"></div>#06340B<br />(65,47,33)</td>
	<td><div class="colblock" style="background: #F7C44E"></div>#F7C44E<br />(247,196,78)</td>

	<th>N</th>
	<td><div class="colblock" style="background: #A5C9D2"></div>#A5C9D2<br />(165,201,210)</td>
	<td><div class="colblock" style="background: #06241F"></div>#06241F<br />(6,36,31)</td>
	<td><div class="colblock" style="background: #FDFFAB"></div>#FDFFAB<br />(253,255,171)</td>

	<td><br /></td>
	<td><br /></td>
</tr>
</table>