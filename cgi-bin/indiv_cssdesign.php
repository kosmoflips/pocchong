<?php # UTF8 anchor (´・ω・｀)
require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/'.'Method_Kiyoism_Remaster.php');

$title="All elements for CSS DESIGN";
write_html_open_head($title);
?>
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
<?php // open html, site structure
write_html_open_body();
print_post_wrap(0);
?>
<h2>Page structure</h2>
&lt;body&gt;<br />
<div class="stru" style="width:100%">
<div class="s1">#outlayer
	<div class="s2">#header-outer
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
			<div class="s4">.post-outer
				<div class="s5">.post-inner-shell
					<div class="s1">.post-inner</div>
				</div>
				<div class="s5">.post-inner-shell
					<div class="s1">.post-inner</div>
				</div>
				<div class="s5">.post-inner-shell
					<div class="s1">.post-inner</div>
				</div>
			</div>
			<div class="s4">#footer-navi</div>
		</div>
		<div class="s3" style="display:inline-block; width: 15%">#sidelayer-right<br />(may be hidden)</div>
					</div>
	</div>
	<div class="s2">#footer-global
		<div class="s3">credit info</div>
	</div>
</div>
</div>
&lt;/body&gt;<br />
<?php // basic data
print_post_wrap(1);
print_post_wrap(0);
?>
<b>from &lt;body&gt; begin to opening &lt;.post-outer&gt;</b><br />
<textarea rows="25">
&lt;body&gt;
	&lt;div id=&quot;outlayer&quot;&gt;
	&lt;div id=&quot;header-img&quot;&gt;&lt;/div&gt;

	&lt;div id=&quot;header-outer&quot;&gt;
		&lt;a href=&quot;/&quot;&gt;&lt;span id=&quot;headerlink&quot;&gt;&lt;/span&gt;&lt;/a&gt;
		&lt;h1&gt;&lt;a href=&quot;/&quot;&gt;&#38899;&#26178;&#38632; &#65374;Fairy Aria&#65374;&lt;/a&gt;&lt;/h1&gt;
		&lt;div id=&quot;menu-out&quot;&gt;
			&lt;ul id=&quot;menu&quot;&gt;
				&lt;li&gt;&lt;a href=&quot;/days&quot;&gt;Days&lt;/a&gt;&lt;/li&gt;
				&lt;li&gt;&lt;a href=&quot;/archiv&quot;&gt;Archiv&lt;/a&gt;
					&lt;ul class=&quot;sub-menu&quot;&gt;&lt;li&gt;&lt;a href=&quot;/calendar&quot;&gt;Calendar&lt;/a&gt;&lt;/li&gt;&lt;/ul&gt;
				&lt;/li&gt;
				...
				&lt;li style=&quot;background: none;min-width:20px;border:none&quot;&gt;search box icon&lt;/li&gt;
			&lt;/ul&gt;
		&lt;/div&gt;&lt;!-- menu-out --&gt;
	&lt;/div&gt;&lt;!-- #header-outer --&gt;

&lt;div id=&quot;content-wrap&quot;&gt;

&lt;div id=&quot;sidelayer-left&quot;&gt;
&lt;/div&gt;

&lt;div id=&quot;mainlayer&quot;&gt;
&lt;div id=&quot;g-search-area&quot; style=&quot;display:none&quot;&gt;&lt;!-- search box begin --&gt;
	&lt;div class=&quot;post-outer&quot;&gt;&lt;div class=&quot;post-inner-shell&quot;&gt;&lt;div class=&quot;post-inner&quot;&gt;
		&lt;script&gt;...&lt;/script&gt;
		&lt;div class=&quot;gcse-search&quot;&gt;&lt;/div&gt;
		&lt;span style=&quot;float:right&quot;&gt;&lt;a href=&quot;javascript:toggleDiv('g-search-area');&quot;&gt;Hide&lt;/a&gt;&lt;/span&gt;
	&lt;/div&gt;&lt;/div&gt;&lt;/div&gt;
&lt;/div&gt;&lt;!-- search box end --&gt;

&lt;div class=&quot;post-outer&quot;&gt;
&lt;/textarea&gt;

&lt;hr /&gt;

&lt;b&gt;a single POST-block&lt;/b&gt;
&lt;textarea&gt;
&lt;div class=&quot;post-inner-shell&quot;&gt;
	&lt;div class=&quot;post-inner&quot;&gt;
		...html for content...
	&lt;/div&gt;&lt;!-- .post-inner --&gt;
&lt;/div&gt;&lt;!-- .post-inner-shell --&gt;
</textarea>
<?php // post-block & custom tags
print_post_wrap(1);
print_post_wrap(0,1);
?>
<textarea>
&lt;article&gt;
	&lt;div class=&quot;datetime&quot;&gt;&lt;a href=&quot;...&quot;&gt;2020-Jan-14 (Tue), 17:45@GMT-7&lt;/a&gt;&lt;/div&gt;
	&lt;h3&gt;&lt;a href=&quot;...&quot;&gt;Post title&lt;/a&gt;&lt;/h3&gt;
	&lt;div class=&quot;p&quot;&gt;...&lt;/div&gt;
&lt;/article&gt;
</textarea>
&lt;article&gt;<br />
<h2>&lt;h2&gt;static page title in h2&lt;/h2&gt;</h2><br />
<hr />
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
<img src="https://i.stack.imgur.com/jB2pV.png" alt=""></img>
</div>

<blockquote>&lt;blockquote&gt;blockquote content preview. . .&lt;/blockquote&gt;</blockquote>

<hr />
<b>:edit box: only visible after logged in</b><br />
&lt;div class=&quot;inline-box&quot;&gt;&lt;a href=&quot;...&quot;&gt;Edit&lt;/a&gt;&lt;/div&gt;<br />
<div class="inline-box"><a href="...">Edit</a></div>
<div>&lt;/article&gt;</div>
<?php // archive preview block
print_post_wrap(1,1);
print_post_wrap(0);
?>
<textarea>
&lt;h2&gt;&lt;h2&gt;* Archiv *&lt;/h2&gt;&lt;/h2&gt;
&lt;div class=&quot;archiv&quot;&gt;
	&lt;ul&gt;
		&lt;h3&gt;&lt;a href=&quot;#&quot;&gt;* 2038 *&lt;/a&gt;&lt;/h3&gt;
		&lt;li&gt;&lt;a href=&quot;#&quot;&gt;&lt;span class=&quot;archivdate&quot;&gt;Jan-01&lt;/span&gt; dummy post title 1&lt;/a&gt;&lt;/li&gt;
		&lt;li&gt;...&lt;/li&gt;
	&lt;/ul&gt;
&lt;/div&gt;&lt;!-- archiv --&gt;

&lt;div class=&quot;archiv&quot;&gt;
	&lt;ul&gt;
		&lt;h3&gt;&lt;a href=&quot;#&quot;&gt;* 2038 *&lt;/a&gt;&lt;/h3&gt;
		&lt;li&gt;...&lt;/li&gt;
	&lt;/ul&gt;
&lt;/div&gt;&lt;!-- archiv --&gt;
</textarea>
<h2>&lt;h2&gt;* Archiv *&lt;/h2&gt;</h2>
<div class="archiv">
<ul>
	<h3><a href="#">* 2038 *</a></h3>
	<li><a href="#"><span class="archivdate">Jan-01</span> dummy post title 1</a></li>
	<li><a href="#"><span class="archivdate">Feb-02</span> dummy post title 2</a></li>
	<li><a href="#"><span class="archivdate">Mar-03</span> dummy post title 3</a></li>
</ul>
</div><!-- archiv -->

&lt;div class=&quot;archiv&quot;&gt;<br />
<div class="archiv">
&lt;ul&gt;<br />
<ul>
	<h3><a href="#">&lt;h3&gt;&lt;a href=&quot;#&quot;&gt;* 2037 *&lt;/a&gt;&lt;/h3&gt;</a></h3>
	<li>&lt;li&gt;&lt;a href=&quot;#&quot;&gt;&lt;span class=&quot;archivdate&quot;&gt;Jan-01&lt;/span&gt; dummy post title 1&lt;/a&gt;&lt;/li&gt;</li>
	<li><a href="#"><span class="archivdate">Jan-01</span> dummy post title 1</a></li>
	<li><a href="#"><span class="archivdate">Feb-02</span> dummy post title 2</a></li>
	<li><a href="#"><span class="archivdate">Mar-03</span> dummy post title 3</a></li>
</ul>
&lt;/ul&gt;<br />
</div><!-- archiv -->
&lt;/div&gt;&lt;!-- archiv --&gt;<br />
<?php // MG gallery
print_post_wrap(1);
print_post_wrap();
?>
<hr />
<textarea>
&lt;h2&gt;&lt;h2&gt;MyGirls main page&lt;/h2&gt;&lt;/h2&gt;
&lt;div class=&quot;gallery&quot;&gt;
	&lt;div class=&quot;mgarchive-container&quot;&gt;
		&lt;a href=&quot;...&quot;&gt;&lt;img class=&quot;mgarchive-image&quot; src=&quot;...&quot; alt=&quot;img&quot; /&gt;&lt;/a&gt;
		&lt;div class=&quot;mgarchive-overlay&quot;&gt;[ id001 ]&lt;br /&gt;img title&lt;br /&gt;2017/05/22&lt;/div&gt;
	&lt;/div&gt;
&lt;/div&gt;&lt;!-- gallery --&gt;
</textarea>
<h2>&lt;h2&gt;MyGirls main page&lt;/h2&gt;</h2>
<div class="gallery">
<div class="mgarchive-container">
	<a href="#"><img class="mgarchive-image" src="http://www.atfund.gatech.edu/sites/default/files/images/verticalplaceholderimage-440x680.png" alt="img" /></a>
	<div class="mgarchive-overlay">[ id01 ]<br />title<br />2019/08/24</div>
</div>
<div class="mgarchive-container">
	<a href="#"><img class="mgarchive-image" src="http://www.atfund.gatech.edu/sites/default/files/images/verticalplaceholderimage-440x680.png" alt="img" /></a>
	<div class="mgarchive-overlay">[ id02 ]<br />title 02<br />2017/10/13</div>
</div>
<div class="mgarchive-container">
	<a href="#"><img class="mgarchive-image" src="http://www.atfund.gatech.edu/sites/default/files/images/verticalplaceholderimage-440x680.png" alt="img" /></a>
	<div class="mgarchive-overlay">[ id03 ]<br />title<br />2019/08/24</div>
</div>
<div class="mgarchive-container">
	<a href="#"><img class="mgarchive-image" src="http://www.atfund.gatech.edu/sites/default/files/images/verticalplaceholderimage-440x680.png" alt="img" /></a>
	<div class="mgarchive-overlay">[ id04 ]<br />title 02<br />2017/10/13</div>
</div>
<div class="mgarchive-container">
	<a href="#"><img class="mgarchive-image" src="http://www.atfund.gatech.edu/sites/default/files/images/verticalplaceholderimage-440x680.png" alt="img" /></a>
	<div class="mgarchive-overlay">[ id05 ]<br />title<br />2019/08/24</div>
</div>
<div class="mgarchive-container">
	<a href="#"><img class="mgarchive-image" src="http://www.atfund.gatech.edu/sites/default/files/images/verticalplaceholderimage-440x680.png" alt="img" /></a>
	<div class="mgarchive-overlay">[ id02 ]<br />title 02<br />2017/10/13</div>
</div>
</div><!-- gallery -->
<?php  // MG single
print_post_wrap(1);
print_post_wrap();
?>
<textarea rows="15">
&lt;div&gt;&lt;!-- artwork block begins --&gt;
	&lt;h2&gt;img title&lt;/h2&gt;

	&lt;blockquote&gt;
		&lt;ul&gt;
			&lt;li&gt;ID: FACL123&lt;/li&gt;
			&lt;li&gt;October 13, 2017, 13:39&lt;/li&gt;
			&lt;li&gt;...&lt;/li&gt;
		&lt;/ul&gt;
	&lt;/blockquote&gt;

	&lt;div class=&quot;stdalone&quot;&gt;
		&lt;a href=&quot;#&quot;&gt;&lt;img src=&quot;...&quot; alt=&quot;&quot; /&gt;&lt;/a&gt;&lt;br /&gt;
	&lt;/div&gt;&lt;!-- .stdalone --&gt;

	&lt;div style=&quot;margin-top: 25px; border-bottom: 3px double #d8afe2; display:block; text-align:center; width: 90%; font-size: 120%; text-shadow: 2px 2px 3px #bd8db4; margin: auto; &quot;&gt;&lt;b&gt;..&#65377;o&#9675;&#9734;*:&#65439;&#65381;: Variations :&#65381;&#65439;:*&#9734;&#9675;o&#65377;..&lt;/b&gt;&lt;/div&gt;

	&lt;div class=&quot;gallery&quot;&gt;
		&lt;span class=&quot;gallery-img-frame&quot;&gt;&lt;a href=&quot;...&quot;&gt;&lt;img src=&quot;...&quot; alt=&quot;&quot; /&gt;&lt;/a&gt;&lt;/span&gt;
		&lt;span class=&quot;gallery-img-frame&quot;&gt;&lt;a href=&quot;...&quot;&gt;&lt;img src=&quot;...&quot; alt=&quot;&quot; /&gt;&lt;/a&gt;&lt;/span&gt;
	&lt;/div&gt;

&lt;/div&gt;&lt;!-- closing artwork block --&gt;
</textarea>
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
<a href="#"><img src="http://www.atfund.gatech.edu/sites/default/files/images/verticalplaceholderimage-440x680.png" alt="" /></a><br />
</div><!-- .stdalone -->

<div style="margin-top: 25px; border-bottom: 3px double #d8afe2; display:block; text-align:center; width: 90%; font-size: 120%; text-shadow: 2px 2px 3px #bd8db4; margin: auto; "><b>..｡o○☆*:ﾟ･: Variations :･ﾟ:*☆○o｡..</b></div>

<div class="gallery">
<span class="gallery-img-frame"><a href="#"><img src="https://i.stack.imgur.com/jB2pV.png" alt="" /></a></span><span class="gallery-img-frame"><a href="#"><img src="https://i.stack.imgur.com/jB2pV.png" alt="" /></a></span><span class="gallery-img-frame"><a href="#"><img src="https://i.stack.imgur.com/jB2pV.png" alt="" /></a></span><span class="gallery-img-frame"><a href="#"><img src="https://i.stack.imgur.com/jB2pV.png" alt="" /></a></span><span class="gallery-img-frame"><a href="#"><img src="https://i.stack.imgur.com/jB2pV.png" alt="" /></a></span></div>
</div><!-- closing artwork block -->
<?php  // footer, navi
print_post_wrap(1);
print_post_wrap(0);
?>
<b>closing &lt;.post-outer&gt;</b><br />
<textarea rows="15">
&lt;/div&gt;&lt;!-- .post-navi --&gt;
&lt;div id=&quot;footer-outer&quot;&gt;
	print navi content if specified
&lt;/div&gt;&lt;!-- .footer-navi --&gt;
&lt;/div&gt;&lt;!-- #mainlayer--&gt;

&lt;div id=&quot;sidelayer-right&quot;&gt;
&lt;/div&gt;

&lt;/div&gt;&lt;!-- #content-wrap --&gt;
&lt;/div&gt;&lt;!-- #outlayer --&gt;

&lt;div id=&quot;footer-global&quot;&gt;
	&lt;a href=&quot;/about&quot;&gt;2006-2020 kiyoko@FairyAria&lt;/a&gt;
&lt;/div&gt;

&lt;/body&gt;
&lt;/html&gt;
</textarea>

<hr />

<b>navi single - prev/next</b><br />
<textarea>
&lt;div class=&quot;navi-prev&quot;&gt;&lt;a href=&quot;#&quot;&gt;&#8678; Prev Post&lt;/a&gt;&lt;/div&gt;
&lt;div class=&quot;navi-next&quot;&gt;&lt;a href=&quot;#&quot;&gt;Next Post &#8680;&lt;/a&gt;&lt;/div&gt;
</textarea>

<hr />

<b>navi bar - listing pages</b><br />
<textarea rows="12">
&lt;div class=&quot;navi-bar&quot;&gt;
	&lt;span class=&quot;navi-bar-square-self&quot;&gt;&lt;a href=&quot;#&quot;&gt;1&lt;/a&gt;&lt;/span&gt;
	&lt;span class=&quot;navi-bar-square&quot;&gt;&lt;a href=&quot;#&quot;&gt;2&lt;/a&gt;&lt;/span&gt;
	&lt;span class=&quot;navi-bar-square&quot;&gt;&lt;a href=&quot;#&quot;&gt;3&lt;/a&gt;&lt;/span&gt;
	&lt;span class=&quot;navi-bar-square&quot;&gt;&lt;a href=&quot;#&quot;&gt;4&lt;/a&gt;&lt;/span&gt;
	&lt;span class=&quot;navi-bar-square&quot;&gt;&lt;a href=&quot;#&quot;&gt;5&lt;/a&gt;&lt;/span&gt;
	&lt;span&gt;..&lt;/span&gt;
	&lt;span class=&quot;navi-bar-square&quot;&gt;&lt;a href=&quot;#&quot;&gt;82&lt;/a&gt;&lt;/span&gt;
	&lt;span&gt;&lt;a href=&quot;#&quot;&gt;&#9654;&#9654;&lt;/a&gt;&lt;/span&gt;
&lt;/div&gt;&lt;!-- .navi-bar ends --&gt;
</textarea>
<?php
print_post_wrap(1);
// dummy navi data
$navi1=array(
	"next"=> array(
		"url"=> "#",
		"title"=> "Next Post"
	),
	"prev"=> array(
		"url"=> "#",
		"title"=> "Prev Post"
	),
);

$naviset=array(
	"navi"=> array(
		"begin0"=> 1,
		"begin1"=> 5,
		"end0"=> 0,
		"end1"=> 82,
		"mid"=> 0,
		"prev"=> 0,
		"next"=> 2
	),
	"turn"=> 2,
	"currpage"=> 1,
	"baseurl"=> "",
);
write_html_close($naviset,$navi1);
?>
