<?php
$title=fname2name($_SERVER['REDIRECT_URL']);
$p->title=$title;
$extra=['
<style>
/*
span {
	font-family:"Meiryo UI";
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
</style>
'];
$p->add_extra($extra);
$p->html_open();
static_page_open($title);
?>

<div class="codebox" style="line-height: 1.2; white-space: pre-wrap;">
<span class="sc2">##### more links first #####</span>
<span class="sc2"># contact: kosmoflips [at/gmail/dot/com]</a></span>
<span class="sc2"># <a href="http://kosmoflips.deviantart.com/">http://kosmoflips.deviantart.com/</a></span>
<span class="sc2"># <a href="http://www.last.fm/user/kosmoflips">http://www.last.fm/user/kosmoflips</a></span>
<span class="sc2"># PSN: sinfinmelodia</span>
<span class="sc2"># most other places: kosmoflips</span>

<span class="sc2"># updated on 2022-May-15</span><span class="sc0"></span>

<span class="sc5">print</span><span class="sc0"> </span><span class="sc6">"I like platonic girls' love, Silent Hill and Mozart.\n"</span><span class="sc10">;</span><span class="sc0">

</span><span class="sc5">my</span><span class="sc0"> </span><span class="sc12">$SELF</span><span class="sc0"> </span><span class="sc10">=</span><span class="sc0"> </span><span class="sc10">{</span><span class="sc0">
    </span><span class="sc11">personality</span><span class="sc0"> </span><span class="sc10">=></span><span class="sc0"> </span><span class="sc5">int</span><span class="sc0"> </span><span class="sc12">$j</span><span class="sc10">,</span><span class="sc0">
    </span><span class="sc11">zodiac sign</span><span class="sc0"> </span><span class="sc10">=></span><span class="sc0"> </span><span class="sc5">ref</span><span class="sc0"> </span><span class="sc12">$Mozart</span><span class="sc10">,</span><span class="sc0">
    </span><span class="sc11">attribute</span><span class="sc0"> </span><span class="sc10">=></span><span class="sc0"> </span><span class="sc7">'dark'</span><span class="sc10">,</span><span class="sc0"> </span><span class="sc2">#self-claimed</span><span class="sc0">
    </span><span class="sc11">occupation</span><span class="sc0"> </span><span class="sc10">=></span><span class="sc0"> </span><span class="sc7">'nerd bioinformatician'</span><span class="sc10">,</span><span class="sc0">
    </span><s># <span class="sc11">title</span><span class="sc0"> </span><span class="sc10">=></span><span class="sc0"> </span><span class="sc7">'Possessed heretic Daydreamer'</span><span class="sc10">,</span></s><span class="sc0">
    </span><span class="sc11">languages</span><span class="sc0"> </span><span class="sc10">=></span><span class="sc0"> </span><span class="sc10">{</span><span class="sc0">
        </span><span class="sc11">major</span><span class="sc0"> </span><span class="sc10">=></span><span class="sc0"> </span><span class="sc10">[</span><span class="sc30">qw / English Japanese Mandarin Perl HTML CSS R /</span><span class="sc10">],</span><span class="sc0">
        </span><span class="sc11">minor</span><span class="sc0"> </span><span class="sc10">=></span><span class="sc0"> </span><span class="sc10">[</span><span class="sc30">qw/ German PHP Python CMD bash /</span><span class="sc0"> </span><span class="sc10">],</span><span class="sc0">
    </span><span class="sc10">},</span><span class="sc0">
</span><span class="sc10">};</span><span class="sc0">
</span><span class="sc5">my</span><span class="sc0"> </span><span class="sc12">$FAVOURITE</span><span class="sc0"> </span><span class="sc10">=</span><span class="sc0"> </span><span class="sc10">{</span><span class="sc0">
    </span><span class="sc11">music</span><span class="sc0"> </span><span class="sc10">=></span><span class="sc0"> </span><span class="sc10">[</span><span class="sc0">
        </span><span class="sc11">classical</span><span class="sc0"> </span><span class="sc10">=></span><span class="sc0"> </span><span class="sc10">[</span><span class="sc30">qw / Mozart Tchaikovsky Mendelssohn Chopin Bach Vivaldi/</span><span class="sc10">],</span><span class="sc0"> </span><span class="sc2">#list isn't complete</span><span class="sc0">
        </span><span class="sc11">japanese</span><span class="sc0"> </span><span class="sc10">=></span><span class="sc0"> </span><span class="sc10">[</span><span class="sc30">qw/ KOKIA Shikata-Akiko Shimotsuki-Haruka Mitose-Noriko Kawai-Kenji Ito-Masumi GARNET-CROW/</span><span class="sc10">],</span><span class="sc0"> </span><span class="sc2">#list isn't complete</span><span class="sc0">
    </span><span class="sc10">],</span><span class="sc0">
    </span><span class="sc11">painter</span><span class="sc0"> </span><span class="sc10">=></span><span class="sc0"> </span><span class="sc7">'Salvador Dali'</span><span class="sc10">,</span><span class="sc0">
    </span><span class="sc11">photographer</span><span class="sc0"> </span><span class="sc10">=></span><span class="sc0"> </span><span class="sc7">'KAGAYA'</span><span class="sc10">,</span><span class="sc0"> </span><span class="sc2"># <a href="https://twitter.com/kagaya_11949">https://twitter.com/kagaya_11949</a></span><span class="sc0">
    </span><span class="sc11">novelist</span><span class="sc0"> </span><span class="sc10">=></span><span class="sc0"> </span><span class="sc10">[</span><span class="sc30">qw/ Arthur-Conan-Doyle Jules-Verne Leo-Tolstoy Haruki-Murakami /</span><span class="sc10">],</span><span class="sc0">
    </span><span class="sc11">animal</span><span class="sc0"> </span><span class="sc10">=></span><span class="sc0"> </span><span class="sc7">'cats'</span><span class="sc10">,</span><span class="sc0">
    </span><span class="sc11">weather</span><span class="sc0"> </span><span class="sc10">=></span><span class="sc0"> </span><span class="sc10">[</span><span class="sc30">qw / rain snow fog cloud /</span><span class="sc10">],</span><span class="sc0">
</span><span class="sc10">}</span><span class="sc0">

</span><span class="sc5">foreach</span><span class="sc0"> </span><span class="sc5">my</span><span class="sc0"> </span><span class="sc12">$moment</span><span class="sc0"> </span><span class="sc10">(</span><span class="sc12">$</span><span class="sc10">{</span><span class="sc13">@LIFE</span><span class="sc10">})</span><span class="sc0"> </span><span class="sc10">{</span><span class="sc0">
    </span><span class="sc5">if</span><span class="sc0"> </span><span class="sc10">(</span><span class="sc11">is_favourite</span><span class="sc10">(</span><span class="sc12">$moment</span><span class="sc10">))</span><span class="sc0"> </span><span class="sc10">{</span><span class="sc0">
        </span><span class="sc5">push</span><span class="sc0"> </span><span class="sc12">$</span><span class="sc10">{</span><span class="sc13">@memoir</span><span class="sc10">},</span><span class="sc0"> </span><span class="sc11">write_this</span><span class="sc10">(</span><span class="sc12">$moment</span><span class="sc10">);</span><span class="sc0">
    </span><span class="sc10">}</span><span class="sc0">
    </span><span class="sc5">elsif</span><span class="sc0"> </span><span class="sc10">(</span><span class="sc12">$moment</span><span class="sc0"> </span><span class="sc10">=~</span><span class="sc0"> </span><span class="sc17">/crowd|vibration|Apple/ig</span><span class="sc10">)</span><span class="sc0"> </span><span class="sc10">{</span><span class="sc0"> </span><span class="sc2">#viberation of both cellphone and game controller</span><span class="sc0">
        </span><span class="sc5">next</span><span class="sc10">;</span><span class="sc0">
    </span><span class="sc10">}</span><span class="sc0">
    
    </span><span class="sc5">if</span><span class="sc0"> </span><span class="sc10">(</span><span class="sc11">i_am_convinced</span><span class="sc10">(</span><span class="sc12">$moment</span><span class="sc10">))</span><span class="sc0"> </span><span class="sc10">{</span><span class="sc0">
        </span><span class="sc11">change</span><span class="sc10">(</span><span class="sc12">$SELF</span><span class="sc10">,</span><span class="sc0"> </span><span class="sc10">{</span><span class="sc11">permit</span><span class="sc0"> </span><span class="sc10">=></span><span class="sc0"> </span><span class="sc4">1</span><span class="sc10">});</span><span class="sc0">
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
	<li>Apache + Perl/CGI + <s>MySQL</s> SQLite + PHP with HTML5 + CSS3</li>
	<li><a href="https://github.com/jasonmayes/Twitter-Post-Fetcher">TwitterPostFetcher</a></li>
	<li><a href="https://github.com/bgrins/spectrum">Spectrum</a></li>
	<li><a href="https://github.com/cotenoni/jquery-linedtextarea">linedtextarea</a></li>
	<li><a href="https://photos.google.com">Google Photos</a> as a general image host.</li>
</ul>
</div>

<div class="line">★*:.゜:。★*:.゜:。★*:.゜:。★*:.゜:。★*:.゜:。★*:.゜:。★*:.゜:。★*:.゜:。</div>

<h4>Red-letter days!</h4>
<div class="p">
<ul>
<li>2006/08/06: initial launch on my.opera.com (limited ability in HTML and CSS)</li>
<li>2010/04/08: moved to blogger for more flexibility in CSS editing</li>
<li>2010/06/12: domain binding to pocchong.de</li>
<li>2014/07/11-07/27: Reconstruction I: building the main frame</li>
<li>2014/09/05-10/10: Reconstruction II: debugging & optimisation</li>
<li>2014/10/31-11/09: Reconstruction III: transplantation (thanks <a href="http://squimp.net">my <s>zombie friend</s> technician</a> for hosting~)</li>
<li>2014/11/13-11/21: Reconstruction IV: fine-tuning</li>
<li>2014/11/22-11/23: Reconstruction V: finish-up</li>
<li>2015/07/22: SQL restructure (rm photo stuff)</li>
<li>2017/02/17-03/17: Reincarnation with a new set of (more compact) CGI codes</li>
<li>2017/08/02: Switched from MySQL to SQLite</li>
<li>2018/11/14-11/22: Remastered in PHP -- no more mess around with UTF8! (* ﾟ∀ﾟ)ﾉヽ(ﾟ∀ﾟ*)</li>
<li>2019/09/08-09/12: Integration of static pages into SQL and major layout update</li>
<li>2020/01/14-01/19: Calendar-styled yearly archive (for fun), minor php/css improvements and simplified gradient_make</li>
<li>2020/01/20-01/23: Refine PHP for integrated page structure template</li>
<li>2020/01/24-01/25: Theme redesign -- added sidebar again with new "il mare dei suoni" in blue motif</li>
<li>2020/03/22: "One column is the best" minor design update</li>
<li>2020/03/27-28: code update with a Class for easier page layouts</li>
<li>2021/08/25: on github</li>
<li>2022/03/06-05/15: simplify/reorganise -- since new Apache doesn't support php being inside /cgi-bin</li>
</ul>
</div>