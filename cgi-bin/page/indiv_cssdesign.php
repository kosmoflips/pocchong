<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/'.'Method_Kiyoism_Remaster.php');

$p=new PocPage;

$p->title='All elements for CSS DESIGN';
$p->navi['bar']=mk_navi_bar(1,35,7,6,2,'');
$p->navi['pair']=array(
	'prev'=>array( 'title'=>'prev title preview', 'url'=>'#'),
	'next'=>array( 'title'=>'next title preview', 'url'=>'#'),
);
$p->head['extra']=array(
<<<CSS
<style>
textarea {
	width:100%;
	min-height: 120px;
}
.stru div {
	border: 1px dotted #3D2B8A;
	margin: 10px;
	padding: 5px;
	min-height: 40px;
}
.s1 {
	background: rgba(212,217,58,0.3);
}
.s2 {
	background: rgba(201,142,255,0.3);
}
.s3 {
	background: rgba(70,123,223,0.2);
}
.s4 {
	background: rgba(233,146,151,0.3);
}
.s5 {
	background: rgba(183,205,123,0.3);
}
</style>
CSS
);

$p->html_open(1);
?>
<?php $p->html_open(2); ?>
<h2>Page structure</h2>
&lt;body&gt;<br />
<div class="stru" style="width:100%">
<div class="s1">#master-wrap
	<div class="s2">#header-outer tag=&lt;header&gt;
					<div style="border:none;padding:0;margin:0;display:flex;width:100%">
		<div class="s3" style="display:inline-block; width: 15%">#header-img</div>
		<div class="s3" style="display:inline-block; width: 15%">#headerlink</div>
		<div class="s3" style="display:inline-block; width: 15%">h1</div>
		<div class="s3" style="display:inline-block; width: 50%">#menu-out
			<div class="s4">#menu
				<div class="s1">ul =&gt; li</div>
			</div>
		</div>
					</div>
	</div>
	<div class="s2">#content-wrap
					<div style="border:none;padding:0;margin:0;display:flex;width:100%">
		<div class="s3" style="display:inline-block; width: 15%">#sidelayer-left<br />(may be hidden)</div>
		<div class="s3" style="display:inline-block; width: 60%;">#mainlayer
			<div class="s4">#search-box</div>
			<div class="s4">.post-list-wrap
				<div class="s5">.post-shell
					<div class="s1">.post-inner</div>
				</div>
				<div class="s5">.post-shell
					<div class="s1">.post-inner</div>
				</div>
				<div class="s5">.post-shell
					<div class="s1">.post-inner</div>
				</div>
			</div>
			<div class="s4">#footer-navi</div>
		</div>
		<div class="s3" style="display:inline-block; width: 15%">#sidelayer-right<br />(may be hidden)</div>
					</div>
	</div>
</div>
<div class="s1">#footer-global tag=&lt;footer&gt;
	<div class="s2">credit info</div>
</div>
</div>
&lt;/body&gt;
<?php
$p->html_close(2);
$p->html_open(2);
?>
<article>
<div class="datetime"><a href="...">&lt;div class=&quot;datetime&quot;&gt;&lt;a href=&quot;...&quot;&gt;2019-Dec-16 (Mon), 24:10@GMT-7&lt;/a&gt;&lt;/div&gt;</a></div>
<h3><a href="#">&lt;h3&gt;&lt;a href=&quot;...&quot;&gt;* post title in h3 *&lt;/a&gt;&lt;/h3&gt;</a></h3>

<div class="p">&lt;div class=&quot;p&quot;&gt;first paragraph, first line&lt;/div&gt;&lt;br /&gt;<br />
second line</div>

<div class="line">&lt;div class=&quot;line&quot;&gt;+.+.+.+.decoration line.+.+.+.+&lt;/div&gt;</div>

<h4>&lt;h4&gt;h4 subheading&lt;/h4&gt;</h4>
<div class="p">second &lt;div class=&quot;p&quot;&gt;<br />
&lt;ul&gt;<br />
<ul>
<li>&lt;li&gt;first list item&lt;/li&gt;</li>
<li><a href="#">&lt;a href=&quot;...&quot;&gtlink appearance;&lt;/a&gt;</a></li>
<li><b>&lt;b&gt;bold text&lt;/b&gt;</b></li>
<li><i>&lt;i&gt;italic text&lt;/i&gt;</i></li>
<li><u>&lt;u&gt;underline text&lt;/u&gt;</u></li>
<li><s>&lt;s&gt;strikethrough text&lt;/s&gt;</s></li>
</ul>
&lt;/ul&gt;<br />
</div>

<div class="line">&lt;div class=&quot;line&quot;&gt;+.+.+.+.decoration line.+.+.+.+&lt;/div&gt;</div>

<div class="p">third paragraph:<br />
<div><span class="exbig">&lt;span class=&quot;exbig&quot;&gt;largest text&lt;/span&gt;</span></div>
<div><span class="bigger">&lt;span class=&quot;bigger&quot;&gt;larger text&lt;/span&gt;</span></div>
<div><span>&lt;span&gt;normal text&lt;/span&gt;</span></div>
<div><span class="smaller">&lt;span class=&quot;smaller&quot;&gt;smaller text&lt;/span&gt;</span></div>
<div><span class="exsmall">&lt;span class=&quot;exsmall&quot;&gt;smallest text&lt;/span&gt;</span></div>
<div><span class="netabare">&lt;span class=&quot;netabare&quot;&gt;netabare text&lt;/span&gt;</span>&lt;&lt;hover to show hidden text</div>
</div>

<div class="line">&lt;div class=&quot;line&quot;&gt;+.+.+.+.decoration line.+.+.+.+&lt;/div&gt;</div>

<div class="p">
<textarea>&lt;textarea&gt;&lt;/textarea&gt;</textarea>
<pre>&lt;pre&gt;...&lt;/pre&gt;</pre>
.....
<pre class="codespan">&lt;pre class=&quot;codespan&quot;&gt;...&lt;/pre&gt;</pre>
.....
<span class="codespan">&lt;span class=&quot;codespan&quot;&gt;...&lt;/span&gt;</span>
.....
<div class="codespan">&lt;div class=&quot;codespan&quot;&gt;...&lt;/div&gt;</div>
.....
<span class="codeblock">&lt;span class=&quot;codeblock&quot;&gt;...&lt;/span&gt;</span>
.....
<div class="codeblock">&lt;div class=&quot;codeblock&quot;&gt;...&lt;/div&gt;</div>
</div>

<div class="line">&lt;div class=&quot;line&quot;&gt;+.+.+.+.decoration line.+.+.+.+&lt;/div&gt;</div>

<div class="p">
(image display, hover to zoom in)<br />
&lt;img src=&quot;&quot; alt=&quot;&quot;&gt;&lt;/img&gt;<br />
<img src="/deco/img/img_placeholder_v.png" alt=""></img>
</div>

<blockquote>&lt;blockquote&gt;blockquote content preview. . .&lt;/blockquote&gt;</blockquote>

<hr />
<b>:edit box: only visible after logged in</b><br />
&lt;div class=&quot;inline-box&quot;&gt;&lt;a href=&quot;...&quot;&gt;Edit&lt;/a&gt;&lt;/div&gt;<br />
<div class="inline-box"><a href="...">Edit</a></div>
<div>&lt;/article&gt;</div>
</article>
<?php
$p->html_close(2);
$p->html_open(2);
?>
<h2>&lt;h2&gt;* Archiv *&lt;/h2&gt;</h2>
<div class="archiv">
<div class="archiv-year"><a href="#">2038</a></div>
<ul>
	<li><a href="#"><span class="archivdate">Jan-01</span> dummy post title 1</a></li>
	<li><a href="#"><span class="archivdate">Feb-02</span> dummy post title 2</a></li>
	<li><a href="#"><span class="archivdate">Mar-03</span> dummy post title 3</a></li>
</ul>
</div><!-- archiv -->
<div class="archiv">
<div class="archiv-year"><a href="#">2037</a></div>
<ul>
	<li><a href="#"><span class="archivdate">Jan-01</span> dummy post title 1</a></li>
	<li><a href="#"><span class="archivdate">Feb-02</span> dummy post title 2</a></li>
	<li><a href="#"><span class="archivdate">Mar-03</span> dummy post title 3</a></li>
</ul>
</div><!-- archiv -->
<?php
$p->html_close(2);
$p->html_open(2);
?>
<h2>&lt;h2&gt;MyGirls main page&lt;/h2&gt;</h2>
<div class="gallery">
<div class="mgarchive-container">
	<a href="#"><img class="mgarchive-image" src="/deco/img/img_placeholder_v.png" alt="img" /></a>
	<div class="mgarchive-overlay">[ id01 ]<br />title<br />2019/08/24</div>
</div>
<div class="mgarchive-container">
	<a href="#"><img class="mgarchive-image" src="/deco/img/img_placeholder_v.png" alt="img" /></a>
	<div class="mgarchive-overlay">[ id02 ]<br />title 02<br />2017/10/13</div>
</div>
<div class="mgarchive-container">
	<a href="#"><img class="mgarchive-image" src="/deco/img/img_placeholder_v.png" alt="img" /></a>
	<div class="mgarchive-overlay">[ id03 ]<br />title<br />2019/08/24</div>
</div>
<div class="mgarchive-container">
	<a href="#"><img class="mgarchive-image" src="/deco/img/img_placeholder_v.png" alt="img" /></a>
	<div class="mgarchive-overlay">[ id04 ]<br />title 02<br />2017/10/13</div>
</div>
<div class="mgarchive-container">
	<a href="#"><img class="mgarchive-image" src="/deco/img/img_placeholder_v.png" alt="img" /></a>
	<div class="mgarchive-overlay">[ id05 ]<br />title<br />2019/08/24</div>
</div>
<div class="mgarchive-container">
	<a href="#"><img class="mgarchive-image" src="/deco/img/img_placeholder_v.png" alt="img" /></a>
	<div class="mgarchive-overlay">[ id02 ]<br />title 02<br />2017/10/13</div>
</div>
</div><!-- gallery -->
<?php
$p->html_close(2);
$p->html_open(2);
?>
<div><!-- artwork block begins -->
<h2>&lt;h2&gt;work title&lt;/h2&gt;</h2>
<blockquote>
<ul>
<li>ID: FACL123</li>
<li>October 13, 2017, 13:39</li>
<li>&lt;li&gt;...&lt;/li&gt;</li>
</ul>
</blockquote>
<div class="stdalone">
<a href="#"><img src="/deco/img/img_placeholder_v.png" alt="" /></a><br />
</div><!-- .stdalone -->

<div class="mg-h-line"><b>..｡o○☆*:ﾟ･: Variations :･ﾟ:*☆○o｡..</b></div>

<div class="gallery">
<span class="gallery-img-frame"><a href="#"><img src="/deco/img/img_placeholder_h.png" alt="" /></a></span>
<span class="gallery-img-frame"><a href="#"><img src="/deco/img/img_placeholder_h.png" alt="" /></a></span>
<span class="gallery-img-frame"><a href="#"><img src="/deco/img/img_placeholder_h.png" alt="" /></a></span>
<span class="gallery-img-frame"><a href="#"><img src="/deco/img/img_placeholder_h.png" alt="" /></a></span>
<span class="gallery-img-frame"><a href="#"><img src="/deco/img/img_placeholder_h.png" alt="" /></a></span>
</div>
</div><!-- closing artwork block -->
<?php
$p->html_close(2);
$p->html_open(2);
?>
<h2>Other pages to be checked</h2>
<ul>
<li>Calendar page</li>
<li>status page index</li>
<li>index</li>
<li>admin if shared structure changed</li>
</ul>

all currently in-use symbols<br />
<div>
<?php
$size=rand_deco_symbol(-1);
for ($i=0; $i<($size-1); $i++) {
	echo ($i), ' - ', rand_deco_symbol($i), '<br />';
}
?>
</div>
<?php // write html
$p->html_close(2);
$p->html_close(1);
?>