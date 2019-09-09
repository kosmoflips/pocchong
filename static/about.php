<?php #UTF8 anchor (´・ω・｀)
require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/'.'Method_Kiyoism_Remaster.php');
?>
<?php // extra vars
$title='About';
$extracss='<style>
/*
span {
	font-family: \'Meiryo UI\';
	font-size: 11pt;
	color: #000000;
}
*/
.sc0 {
	color: #FF0000;
}
.sc2, .sc2 a:link, .sc2 a:visited {
	color: #008000;
}
.sc2 a:link {
	text-decoration: underline;
}
.sc4 {
	color: #FF0000;
}
.sc5 {
	color: #0000FF;
}
.sc6 {
	color: #808080;
}
.sc7 {
	color: #808080;
}
.sc10 {
	color: #000080;
}
.sc11 {
}
.sc12 {
	color: #FF8000;
}
.sc13 {
	color: #CF34CF;
}
.sc17 {
	color: #8080FF;
	background: #F8FEDE;
}
.sc30 {
}
.codebox {
	background: rgba(255,255,255,0.7);
	margin: 30px auto;
	width: 95%;
	padding: 10px 15px 15px;
	border: dotted 1px rgb(200,132,184);
}
</style>';
?>
<?php // html begins
write_html_open($title,$extracss,1);
print_post_wrap(0);
print_static_title($title);
?>

<div class="codebox" style="line-height: 1.2; white-space: pre-wrap;">
<span class="sc2">##### more links first #####</span><span class="sc0">
</span><span class="sc2"># <a href="http://www.pocchong.de/feed.rss">http://www.pocchong.de/feed.rss</a></span><span class="sc0">
</span><span class="sc2"># <a href="https://plus.google.com/u/0/+KiyokoKisaragi">https://plus.google.com/u/0/+KiyokoKisaragi</a></span><span class="sc0">
</span><span class="sc2"># <a href="http://kosmoflips.deviantart.com/">http://kosmoflips.deviantart.com/</a></span><span class="sc0">
</span><span class="sc2"># <a href="http://www.last.fm/user/kosmoflips">http://www.last.fm/user/kosmoflips</a></span><span class="sc0">
</span><span class="sc2"># <a href="http://myanimelist.net/animelist/kosmoflips">http://myanimelist.net/animelist/kosmoflips</a></span><span class="sc0">
</span><span class="sc2"># <a href="http://psnprofiles.com/sinfinmelodia">http://psnprofiles.com/sinfinmelodia</a></span><span class="sc0">

</span><span class="sc2"># updated on 2018-Nov-26</span><span class="sc0">

</span><span class="sc5">print</span><span class="sc0"> </span><span class="sc6">"I like platonic girls' love, Silent Hill and Mozart.\n"</span><span class="sc10">;</span><span class="sc0">

</span><span class="sc5">my</span><span class="sc0"> </span><span class="sc12">$SELF</span><span class="sc0"> </span><span class="sc10">=</span><span class="sc0"> </span><span class="sc10">{</span><span class="sc0">
    </span><span class="sc11">personality</span><span class="sc0"> </span><span class="sc10">=&gt;</span><span class="sc0"> </span><span class="sc5">int</span><span class="sc0"> </span><span class="sc12">$j</span><span class="sc10">,</span><span class="sc0">
    </span><span class="sc11">sign</span><span class="sc0"> </span><span class="sc10">=&gt;</span><span class="sc0"> </span><span class="sc5">ref</span><span class="sc0"> </span><span class="sc12">$Mozart</span><span class="sc10">,</span><span class="sc0">
    </span><span class="sc11">attribute</span><span class="sc0"> </span><span class="sc10">=&gt;</span><span class="sc0"> </span><span class="sc7">'dark'</span><span class="sc10">,</span><span class="sc0"> </span><span class="sc2">#self-claimed</span><span class="sc0">
    </span><span class="sc11">occupation</span><span class="sc0"> </span><span class="sc10">=&gt;</span><span class="sc0"> </span><span class="sc7">'mad scientist dealing with genetic sequences'</span><span class="sc10">,</span><span class="sc0">
    </span><span class="sc11">title</span><span class="sc0"> </span><span class="sc10">=&gt;</span><span class="sc0"> </span><span class="sc7">'Possessed heretic Daydreamer'</span><span class="sc10">,</span><span class="sc0">
    </span><span class="sc11">languages</span><span class="sc0"> </span><span class="sc10">=&gt;</span><span class="sc0"> </span><span class="sc10">{</span><span class="sc0">
        </span><span class="sc11">major</span><span class="sc0"> </span><span class="sc10">=&gt;</span><span class="sc0"> </span><span class="sc10">[</span><span class="sc30">qw / English Japanese Mandarin Perl HTML CSS /</span><span class="sc10">],</span><span class="sc0">
        </span><span class="sc11">minor</span><span class="sc0"> </span><span class="sc10">=&gt;</span><span class="sc0"> </span><span class="sc10">[</span><span class="sc30">qw/ German Javascript PHP CMD bash /</span><span class="sc0"> </span><span class="sc10">],</span><span class="sc0">
    </span><span class="sc10">},</span><span class="sc0">
</span><span class="sc10">};</span><span class="sc0">
</span><span class="sc5">my</span><span class="sc0"> </span><span class="sc12">$FAVOURITE</span><span class="sc0"> </span><span class="sc10">=</span><span class="sc0"> </span><span class="sc10">{</span><span class="sc0">
    </span><span class="sc11">music</span><span class="sc0"> </span><span class="sc10">=&gt;</span><span class="sc0"> </span><span class="sc10">[</span><span class="sc0">
        </span><span class="sc11">classical</span><span class="sc0"> </span><span class="sc10">=&gt;</span><span class="sc0"> </span><span class="sc10">[</span><span class="sc30">qw / Mozart Tchaikovsky Mendelssohn Chopin Bach Vivaldi/</span><span class="sc10">],</span><span class="sc0"> </span><span class="sc2">#list isn't complete</span><span class="sc0">
        </span><span class="sc11">japanese</span><span class="sc0"> </span><span class="sc10">=&gt;</span><span class="sc0"> </span><span class="sc10">[</span><span class="sc30">qw/ KOKIA Shikata-Akiko Shimotsuki-Haruka Mitose-Noriko Kawai-Kenji Ito-Masumi GARNET-CROW/</span><span class="sc10">],</span><span class="sc0"> </span><span class="sc2">#list isn't complete</span><span class="sc0">
    </span><span class="sc10">],</span><span class="sc0">
    </span><span class="sc11">painter</span><span class="sc0"> </span><span class="sc10">=&gt;</span><span class="sc0"> </span><span class="sc7">'Salvador Dalí'</span><span class="sc10">,</span><span class="sc0">
    </span><span class="sc11">photographer</span><span class="sc0"> </span><span class="sc10">=&gt;</span><span class="sc0"> </span><span class="sc7">'KAGAYA'</span><span class="sc10">,</span><span class="sc0"> </span><span class="sc2"># <a href="https://twitter.com/kagaya_11949">https://twitter.com/kagaya_11949</a></span><span class="sc0">
    </span><span class="sc11">novelist</span><span class="sc0"> </span><span class="sc10">=&gt;</span><span class="sc0"> </span><span class="sc10">[</span><span class="sc30">qw/ Arthur-Conan-Doyle Jules-Verne Leo-Tolstoy Haruki-Murakami /</span><span class="sc10">],</span><span class="sc0">
    </span><span class="sc11">animal</span><span class="sc0"> </span><span class="sc10">=&gt;</span><span class="sc0"> </span><span class="sc7">'cats'</span><span class="sc10">,</span><span class="sc0">
    </span><span class="sc11">weather</span><span class="sc0"> </span><span class="sc10">=&gt;</span><span class="sc0"> </span><span class="sc10">[</span><span class="sc30">qw / rain snow fog cloud /</span><span class="sc10">],</span><span class="sc0">
</span><span class="sc10">}</span><span class="sc0">

</span><span class="sc5">foreach</span><span class="sc0"> </span><span class="sc5">my</span><span class="sc0"> </span><span class="sc12">$moment</span><span class="sc0"> </span><span class="sc10">(</span><span class="sc12">$</span><span class="sc10">{</span><span class="sc13">@LIFE</span><span class="sc10">})</span><span class="sc0"> </span><span class="sc10">{</span><span class="sc0">
    </span><span class="sc5">if</span><span class="sc0"> </span><span class="sc10">(</span><span class="sc11">is_favourite</span><span class="sc10">(</span><span class="sc12">$moment</span><span class="sc10">))</span><span class="sc0"> </span><span class="sc10">{</span><span class="sc0">
        </span><span class="sc5">push</span><span class="sc0"> </span><span class="sc12">$</span><span class="sc10">{</span><span class="sc13">@memoir</span><span class="sc10">},</span><span class="sc0"> </span><span class="sc11">write_this</span><span class="sc10">(</span><span class="sc12">$moment</span><span class="sc10">);</span><span class="sc0">
    </span><span class="sc10">}</span><span class="sc0">
    </span><span class="sc5">elsif</span><span class="sc0"> </span><span class="sc10">(</span><span class="sc12">$moment</span><span class="sc0"> </span><span class="sc10">=~</span><span class="sc0"> </span><span class="sc17">/crowd|vibration|Apple/ig</span><span class="sc10">)</span><span class="sc0"> </span><span class="sc10">{</span><span class="sc0"> </span><span class="sc2">#viberation of both cellphone and game controller</span><span class="sc0">
        </span><span class="sc5">next</span><span class="sc10">;</span><span class="sc0">
    </span><span class="sc10">}</span><span class="sc0">
    
    </span><span class="sc5">if</span><span class="sc0"> </span><span class="sc10">(</span><span class="sc11">i_am_convinced</span><span class="sc10">(</span><span class="sc12">$moment</span><span class="sc10">))</span><span class="sc0"> </span><span class="sc10">{</span><span class="sc0">
        </span><span class="sc11">change</span><span class="sc10">(</span><span class="sc12">$SELF</span><span class="sc10">,</span><span class="sc0"> </span><span class="sc10">{</span><span class="sc11">permit</span><span class="sc0"> </span><span class="sc10">=&gt;</span><span class="sc0"> </span><span class="sc4">1</span><span class="sc10">});</span><span class="sc0">
    </span><span class="sc10">}</span><span class="sc0">
</span><span class="sc10">}</span>
</div>

<div class="line">★*:.゜:。★*:.゜:。★*:.゜:。★*:.゜:。★*:.゜:。★*:.゜:。★*:.゜:。★*:.゜:。</div>

<h4>Not professional, but passionate</h4>
<div class="p">Free services can be good, but are never perfect. From <u>my.opera.com</u> to <u>blogger.com</u>, there are always things I wished they can do but sadly they can't. Therefore, in November 2014, I was very pleased to eventually finish up building this site on my own. However, no one wants a "static" website, tasks related to database, scripts, HTML templates, and CSS are always the first class demands. DIY is fun and full of possibilities, although it could be tough sometimes. I indeed enjoyed it.</div>

<div class="p">I hesitated a little bit right before set up the server, on whether I should get another domain. But I didn't. Not my idea, but I love the way how the current domain spells: <u>pocchong.de</u>, thanks to KOKIA and her "Song of Pocchong ～雫の唄～". Amazingly, my site title was also got connected to the domain smoothly. "音 (oto)" is sound, tune, or <i>a song</i>; "時雨 (shigure)" is light rain, drizzle, or <i>fine drops</i>. I don't remember whether I chose "pocchong" in this sense, but as a result I'm pleased. However, I didn't have specific intention to use a ".de"--simply because it looks good being after "pocchong", with a few other reasons: ".net/.com" are not fun, ".jp" is ridiculously expensive, and ".ca" looks like an online shop.</div>

<div class="line">★*:.゜:。★*:.゜:。★*:.゜:。★*:.゜:。★*:.゜:。★*:.゜:。★*:.゜:。★*:.゜:。</div>

<h4>Site Spec</h4>
<div class="p">→ No harassment from wordpress or that sort. Everything is from scratch, with the power of:
<ul>
	<li>Apache + Perl/CGI + <s>MySQL</s> SQLite + PHP</li>
	<li>HTML5 + CSS3</li>
	<li><a href="https://github.com/jasonmayes/Twitter-Post-Fetcher">TwitterPostFetcher</a></li>
	<li><a href="https://github.com/bgrins/spectrum">Spectrum</a></li>
	<li><a href="https://photos.google.com">Google Photos</a> as a general image host.</li>
</ul>
</div>

<div class="line">★*:.゜:。★*:.゜:。★*:.゜:。★*:.゜:。★*:.゜:。★*:.゜:。★*:.゜:。★*:.゜:。</div>

<h4>Important Days</h4>
<div class="p">
<ul>
<li>2006/08/06: initial launch on my.opera.com</li>
<li>2010/06/12: host moved to google blogger</li>
<li>2010/08/06: domain binding → pocchong.de</li>
<li>2014/07/11~07/27: Reconstruction I: building the main frame</li>
<li>2014/09/05~10/10: Reconstruction II: debugging &amp; optimisation</li>
<li>2014/10/31~11/09: Reconstruction III: transplantation (thanks <a href="http://squimp.net">my <s>zombie friend</s> technician</a> for hosting~)</li>
<li>2014/11/13~11/21: Reconstruction IV: fine-tuning</li>
<li>2014/11/22~11/23: Reconstruction V: finish-up</li>
<li>2017/02/17~03/17: Reincarnation with a new set of CGI codes</li>
<li>2017/08/02: Switched from MySQL to the lighter SQLite</li>
<li>2018/11/14~11/22: Remastered in PHP -- no more mess regarding to UTF8! (* ﾟ∀ﾟ)ﾉヽ(ﾟ∀ﾟ*)</li>
</ul>
</div>

<?php
print_post_wrap(1);
write_html_close();
?>