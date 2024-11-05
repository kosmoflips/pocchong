<?php
$p->title='Tuning Chart';
$p->static_open(1);
?>
<style>
table {
	border-collapse: collapse;
	border-spacing:0;
	max-width:100%;
	margin:auto;
}
.key-chart td {
	padding:5px;
	vertical-align:top;
}
.key-chart ul {
	padding-left: 0;
	margin:0 auto 20px;
	background:rgba(255,255,255,0.5);
}
.key-chart li {
	list-style-type:none;
	padding: 3px 7px;
}
.key-chart .key-name {
	display:inline-block;
	width: 65px;
}
.key-chart .key-freq {
	display:inline-block;
	width: 80px;
}
.pn-key {
	width: 50px;
}
.str-g { border: 1px solid #6ED4F7; }
.str-d { border: 1px solid #80E64C; }
.str-a { border: 1px solid #FFCF0A; }
.str-e { border: 1px solid #FFA8FF; }
.str-g li:hover { background: #B6EAFB; }
.str-d li:hover { background: #C0F2A6; }
.str-a li:hover { background: #FFE784; }
.str-e li:hover { background: #FFD4FF; }
.str-cello { border: 1px solid #FFBD9C; }
.str-cello li:hover { background: #FFD4B6; }
.rest { border: 1px solid #DBB8DB; }
.rest li:hover { background: #EDDCED; }
</style>
<?php $p->static_open(2); ?>
<div style="text-align:center"><a href="http://en.wikipedia.org/wiki/Piano_key_frequencies" target="_blank">source: wikipedia</a></div>
<table class="key-chart">
<tr>
<td>
<ul class="str-g">
<li style="background:#6ED4F7"><span class="key-name">G3</span><span class="key-freq">195.998</span></li>
<li><span class="key-name">A♭3</span><span class="key-freq">207.652</span></li>
<li><span class="key-name">A3</span><span class="key-freq">220</span></li>
<li><span class="key-name">B♭3</span><span class="key-freq">233.082</span></li>
<li><span class="key-name">B3</span><span class="key-freq">246.942</span></li>
<li><span class="key-name">C4</span><span class="key-freq">261.626</span></li>
<li><span class="key-name">C♯4</span><span class="key-freq">277.183</span></li>
<li><span class="key-name">D4</span><span class="key-freq">293.665</span></li>
</ul>

<ul class="rest">
<li style="background: #DBB8DB"><span class="key-name">A0</span><span class="key-freq">27.5</span></li>
<li><span class="key-name">B♭0</span><span class="key-freq">29.1352</span></li>
<li><span class="key-name">B0</span><span class="key-freq">30.8677</span></li>
<li><span class="key-name">C1</span><span class="key-freq">32.7032</span></li>
<li><span class="key-name">C♯1</span><span class="key-freq">34.6478</span></li>
<li><span class="key-name">D1</span><span class="key-freq">36.7081</span></li>
<li><span class="key-name">E♭1</span><span class="key-freq">38.8909</span></li>
<li><span class="key-name">E1</span><span class="key-freq">41.2034</span></li>
<li><span class="key-name">F1</span><span class="key-freq">43.6535</span></li>
<li><span class="key-name">F♯1</span><span class="key-freq">46.2493</span></li>
<li><span class="key-name">G1</span><span class="key-freq">48.9994</span></li>
<li><span class="key-name">A♭1</span><span class="key-freq">51.9131</span></li>
<li><span class="key-name">A1</span><span class="key-freq">55</span></li>
<li><span class="key-name">B♭1</span><span class="key-freq">58.2705</span></li>
<li><span class="key-name">B1</span><span class="key-freq">61.7354</span></li>
</ul>
</td>

<td>
<ul class="str-d">
<li style="background:#80E64C"><span class="key-name">D4</span><span class="key-freq">293.665</span></li>
<li><span class="key-name">E♭4</span><span class="key-freq">311.127</span></li>
<li><span class="key-name">E4</span><span class="key-freq">329.628</span></li>
<li><span class="key-name">F4</span><span class="key-freq">349.228</span></li>
<li><span class="key-name">F♯4</span><span class="key-freq">369.994</span></li>
<li><span class="key-name">G4</span><span class="key-freq">391.995</span></li>
<li><span class="key-name">A♭4</span><span class="key-freq">415.305</span></li>
<li><span class="key-name">A4</span><span class="key-freq">440</span></li>
</ul>

<ul class="str-cello">
<li style="background:#FFBD9C"><span class="key-name">C2</span><span class="key-freq">65.4064</span></li>
<li><span class="key-name">C♯2</span><span class="key-freq">69.2957</span></li>
<li><span class="key-name">D2</span><span class="key-freq">73.4162</span></li>
<li><span class="key-name">E♭2</span><span class="key-freq">77.7817</span></li>
<li><span class="key-name">E2</span><span class="key-freq">82.4069</span></li>
<li><span class="key-name">F2</span><span class="key-freq">87.3071</span></li>
<li><span class="key-name">F♯2</span><span class="key-freq">92.4986</span></li>
<li style="background:#FFBD9C"><span class="key-name">G2</span><span class="key-freq">97.9989</span></li>
<li><span class="key-name">A♭2</span><span class="key-freq">103.826</span></li>
<li><span class="key-name">A2</span><span class="key-freq">110</span></li>
<li><span class="key-name">B♭2</span><span class="key-freq">116.541</span></li>
<li><span class="key-name">B2</span><span class="key-freq">123.471</span></li>
<li><span class="key-name">C3</span><span class="key-freq">130.813</span></li>
<li><span class="key-name">C♯3</span><span class="key-freq">138.591</span></li>
<li style="background:#FFBD9C"><span class="key-name">D3</span><span class="key-freq">146.832</span></li>
<li><span class="key-name">E♭3</span><span class="key-freq">155.563</span></li>
<li><span class="key-name">E3</span><span class="key-freq">164.814</span></li>
<li><span class="key-name">F3</span><span class="key-freq">174.614</span></li>
<li><span class="key-name">F♯3</span><span class="key-freq">184.997</span></li>
</ul>
</td>

<td>
<ul class="str-a">
<li style="background:#FFCF0A"><span class="key-name">A4</span><span class="key-freq">440</span></li>
<li><span class="key-name">B♭4</span><span class="key-freq">466.164</span></li>
<li><span class="key-name">B4</span><span class="key-freq">493.883</span></li>
<li><span class="key-name">C5</span><span class="key-freq">523.251</span></li>
<li><span class="key-name">C♯5</span><span class="key-freq">554.365</span></li>
<li><span class="key-name">D5</span><span class="key-freq">587.33</span></li>
<li><span class="key-name">E♭5</span><span class="key-freq">622.254</span></li>
<li><span class="key-name">E5</span><span class="key-freq">659.255</span></li>
</ul>

<ul class="rest">
<li style="background: #DBB8DB"><span class="key-name">G7</span><span class="key-freq">3135.96</span></li>
<li><span class="key-name">A♭7</span><span class="key-freq">3322.44</span></li>
<li><span class="key-name">A7</span><span class="key-freq">3520</span></li>
<li><span class="key-name">B♭7</span><span class="key-freq">3729.31</span></li>
<li><span class="key-name">B7</span><span class="key-freq">3951.07</span></li>
<li><span class="key-name">C8</span><span class="key-freq">4186.01</span></li>
</ul>
</td>

<td>
<ul class="str-e">
<li style="background:#FFA8FF"><span class="key-name">E5</span><span class="key-freq">659.255</span></li>
<li><span class="key-name">F5</span><span class="key-freq">698.456</span></li>
<li><span class="key-name">F♯5</span><span class="key-freq">739.989</span></li>
<li><span class="key-name">G5</span><span class="key-freq">783.991</span></li>
<li><span class="key-name">A♭5</span><span class="key-freq">830.609</span></li>
<li><span class="key-name">A5</span><span class="key-freq">880</span></li>
<li><span class="key-name">B♭5</span><span class="key-freq">932.328</span></li>
<li><span class="key-name">B5</span><span class="key-freq">987.767</span></li>
<li style="background:#FFD4FF"><span class="key-name">C6</span><span class="key-freq">1046.5</span></li>
<li><span class="key-name">C♯6</span><span class="key-freq">1108.73</span></li>
<li><span class="key-name">D6</span><span class="key-freq">1174.66</span></li>
<li><span class="key-name">E♭6</span><span class="key-freq">1244.51</span></li>
<li><span class="key-name">E6</span><span class="key-freq">1318.51</span></li>
<li><span class="key-name">F6</span><span class="key-freq">1396.91</span></li>
<li><span class="key-name">F♯6</span><span class="key-freq">1479.98</span></li>
<li><span class="key-name">G6</span><span class="key-freq">1567.98</span></li>
<li><span class="key-name">A♭6</span><span class="key-freq">1661.22</span></li>
<li><span class="key-name">A6</span><span class="key-freq">1760</span></li>
<li><span class="key-name">B♭6</span><span class="key-freq">1864.66</span></li>
<li><span class="key-name">B6</span><span class="key-freq">1975.53</span></li>
<li style="background:#FFD4FF"><span class="key-name">C7</span><span class="key-freq">2093</span></li>
<li><span class="key-name">C♯7</span><span class="key-freq">2217.46</span></li>
<li><span class="key-name">D7</span><span class="key-freq">2349.32</span></li>
<li><span class="key-name">E♭7</span><span class="key-freq">2489.02</span></li>
<li><span class="key-name">E7</span><span class="key-freq">2637.02</span></li>
<li><span class="key-name">F7</span><span class="key-freq">2793.83</span></li>
<li><span class="key-name">F♯7</span><span class="key-freq">2959.96</span></li>
<li><span class="key-name">G7</span><span class="key-freq">3135.96</span></li>
</ul>
</td>
</tr>
</table>
