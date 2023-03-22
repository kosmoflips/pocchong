<?php
// THIS PAGE DOESN'T BELONG TO BACKYARD
// this page only serves as a sandbox preview when I edit site-wise CSS themes
// can be accessed from: /s/theme_preview

require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/synapse.php');
require_once(NERV.'/lib_navicalc.php');

$p=new PocPage;
$p->html_open(1);
?>

<!-- ---------------posts x2---------------------------------------- -->

<?php 	$p->html_open(2); ?>
<div class="datetime"><a href="#">2022-Oct-22 (Sat), 12:00@GMT-7</a></div>
<h3><a href="#"><?php echo rand_deco_symbol(); ?> Tester entry title</a></h3>
<article>
<div class="p">random text copied online</div>

<h4>font sizes</h4>
<div class="p">
<span class="exbig">exbig text line.</span>
<span class="bigger">bigger text line.</span>
<span>normal text line.</span>
<span class="smaller">smaller text line.</span>
<span class="exsmall">exsmall text line.</span></div>

<div class="p"><span class="exbig">exbig text line.</span><br />
<span class="bigger">bigger text line.</span><br />
<span>normal text line.</span><br />
<span class="smaller">smaller text line.</span><br />
<span class="exsmall">exsmall text line.</span></div>

<h4>link in text</h4>
<div class="p">O tempo continua instavel no Espirito Santo nesse fim de semana. Segundo o Instituto Capixaba de Pesquisa e Extensao Rural (Incaper), nesta <a href="#">quinta-feira (15), a previsao e de sol entre nuvens e pancadas de chuva</a>, a partir da tarde, em alguns pontos nas regioes Sul, Serrana e Noroeste do estado. Para a Grande Vitoria, nao existe previsao de chuva ate o fim do dia. No entanto, de sexta-feira (16) a domingo (18), sao <a href="#">esperadas pancadas</a> de chuvas em alguns momentos do dia.</div>

<h4>h4 header line preview</h4>
<div class="p">dummy img<br />
<img src="/img/d03/151015_wokashi.jpg.webp" alt="" /></div>

<div class="p">A partir de sexta-feira (16) a temperatura ja diminui em todas as regioes. A sexta-feira (16) sera de sol entre nuvens e pancadas de chuva, a partir da tarde, nas regioes Sul, Serrana, Noroeste e Grande Vitoria.</div>

<div class="p"><blockquote>As demais areas tem chuva rapida no inicio da manha, mas o sol predomina nos demais horarios. Na Grande Vitoria, a temperatura minima sera de 22 °C e maxima de 25 °C.</blockquote></div>

<div class="line">**☆**:━:**☆**:━:**☆**:━:**☆**:━:**☆**:━:**☆**</div>

<div class="p">O domingo tambem sera de temperaturas amenas em todas as regioes. O ultimo dia do fim de semana sera de sol entre muitas nuvens, alem da chuva em alguns momentos do dia em todo o estado.</div>

<h4>Pre preview</h4>
<div class="p"><pre>No sabado (17), alem da temperatura cair mais um pouco, a chuva fraca aparece em alguns momentos do dia em todo o estado. Na Grande Vitoria, a temperatura minima sera de 20 °C e maxima de 24 °C.</pre></div>

<h4>textarea preview</h4>
<div class="p"><textarea style="width: 500px; height: 200px;">No sabado (17), alem da temperatura cair mais um pouco, a chuva fraca aparece em alguns momentos do dia em todo o estado. Na Grande Vitoria, a temperatura minima sera de 20 °C e maxima de 24 °C.</textarea></div>

<h4>netabare preview</h4>
<div class="p"><span class="netabare">O domingo tambem sera de temperaturas amenas em todas as regioes. O ultimo dia do fim de semana sera de sol entre muitas nuvens, alem da chuva em alguns momentos do dia em todo o estado.</span></div>

<div class="p">O domingo tambem sera de temperaturas amenas em todas as regioes. O ultimo dia do fim de semana sera de sol entre muitas nuvens, alem da chuva em alguns momentos do dia em todo o estado.</div>
<?php print_edit_button('#'); ?>
</article>
<?php $p->html_close(2); ?>

<?php $p->html_open(2); ?>
<div class="datetime"><a href="#">2022-Oct-22 (Sat), 12:00@GMT-7</a></div>
<h3><a href="#"><?php echo rand_deco_symbol(); ?> second entry title</a></h3>
<article>
<div class="p">random text copied online</div>

<div class="p">O tempo continua instavel no Espirito Santo nesse fim de semana. Segundo o Instituto Capixaba de Pesquisa e Extensao Rural (Incaper), nesta quinta-feira (15), a previsao e de sol entre nuvens e pancadas de chuva, a partir da tarde, em alguns pontos nas regioes Sul, Serrana e Noroeste do estado. Para a Grande Vitoria, nao existe previsao de chuva ate o fim do dia. No entanto, de sexta-feira (16) a domingo (18), sao esperadas pancadas de chuvas em alguns momentos do dia.</div>

<div class="p">A partir de sexta-feira (16) a temperatura ja diminui em todas as regioes. A sexta-feira (16) sera de sol entre nuvens e pancadas de chuva, a partir da tarde, nas regioes Sul, Serrana, Noroeste e Grande Vitoria.</div>

<div class="p">As demais areas tem chuva rapida no inicio da manha, mas o sol predomina nos demais horarios. Na Grande Vitoria, a temperatura minima sera de 22 °C e maxima de 25 °C.</div>

<div class="p">No sabado (17), alem da temperatura cair mais um pouco, a chuva fraca aparece em alguns momentos do dia em todo o estado. Na Grande Vitoria, a temperatura minima sera de 20 °C e maxima de 24 °C.</div>

<div class="p">O domingo tambem sera de temperaturas amenas em todas as regioes. O ultimo dia do fim de semana sera de sol entre muitas nuvens, alem da chuva em alguns momentos do dia em todo o estado.</div>
<?php print_edit_button('#'); ?>
</article>
<?php $p->html_close(2); ?>



<!-- ---------------archiv------------------------------------------ -->

<?php 	$p->html_open(2); ?>
<h2>&#9826; Archiv &#9826;</h2>
<div class="archiv">
<div class="archiv-year"><a href="/archiv/y/2022">2022</a></div>
<ul>
<li><a href="/days/682"><span class="archivdate">Oct-22</span> Autumn coming</a></li>	
<li><a href="/days/681"><span class="archivdate">Oct-10</span> 怒涛の三連休</a></li>	
<li><a href="/days/680"><span class="archivdate">Sep-25</span> The worst paid book (so far)</a></li>	
<li><a href="/days/679"><span class="archivdate">Jul-22</span> Déraciné in a rush</a></li>	
<li><a href="/days/678"><span class="archivdate">Jul-02</span> After a long journey</a></li>	
<li><a href="/days/677"><span class="archivdate">Mar-05</span> 今度こそ本気(マジ)モード</a></li>	
</ul>
</div><!-- archiv -->
<div class="archiv">
<div class="archiv-year"><a href="/archiv/y/2021">2021</a></div>
<ul>
<li><a href="/days/676"><span class="archivdate">May-19</span> Finally something with cue support</a></li>	
<li><a href="/days/675"><span class="archivdate">Jan-14</span> Refreshed with Amabie</a></li>	
</ul>
</div><!-- archiv -->
<?php 	$p->html_close(2); ?>


<!-- --------------mg index ------------------------------ -->
<?php $p->html_open(2); ?>
<h2>&#10047; 幻想調和::1:: &#10047;</h2>
<div class="gallery">
<div class="mgarchive-container">
<a href="/mygirls/149"><img class="mgarchive-image" src="/img/g04/210221_moeb2_full.jpg.webp" alt="img" /></a>
<div class="mgarchive-overlay">Möbius Mechanismus II<br />2021/02/21</div>
</div>
<div class="mgarchive-container">
<a href="/mygirls/148"><img class="mgarchive-image" src="/img/g04/200814_capriccio_final.jpg.webp" alt="img" /></a>
<div class="mgarchive-overlay">客人綺想曲 / Marebito Capriccio<br />2020/08/14</div>
</div>
<div class="mgarchive-container">
<a href="/mygirls/147"><img class="mgarchive-image" src="/img/g04/200201_cutesouls_final.jpg.webp" alt="img" /></a>
<div class="mgarchive-overlay">CUTE SOULS<br />2020/02/01</div>
</div>
<div class="mgarchive-container">
<a href="/mygirls/146"><img class="mgarchive-image" src="/img/g04/190824_notturna_final.jpg.webp" alt="img" /></a>
<div class="mgarchive-overlay">Serenata notturna<br />2019/08/24</div>
</div>
<div class="mgarchive-container">
<a href="/mygirls/145"><img class="mgarchive-image" src="/img/g04/171013_lastshow_re_big.jpg.webp" alt="img" /></a>
<div class="mgarchive-overlay">LAST SHOW redevelop<br />2017/10/13</div>
</div>
<div class="mgarchive-container">
<a href="/mygirls/144"><img class="mgarchive-image" src="/img/g04/170828_iroglass_big.jpg.webp" alt="img" /></a>
<div class="mgarchive-overlay">色硝子の裏側に乙女たちの音合わせ<br />2017/08/28</div>
</div>
</div><!-- gallery -->
<?php $p->html_close(2); ?>


<!-- --------------mg single preview ------------------------------ -->
<?php $p->html_open(2); ?>
<div><!-- artwork block begins -->
<h2>&#10046; 客人綺想曲 / Marebito Capriccio &#10046;</h2>
<blockquote>
<ul>
<li>August 14, 2020, 18:49</li>
<li>Liner notes: <a href="/days/674">the catlike Canidae critter</a></li>
<li>Inspiration: Mitose Noriko + Hirota Yoshitaka - KitsuneTsuki (+Nioh2)</li>
<li>Remake of: <a href="/mygirls/61">キツネツキ - kitsunetsuki -</a></li>
</ul>
</blockquote>
<div class="stdalone">
<a href="https://www.deviantart.com/kosmoflips/art/FACL126-Marebito-Capriccio-852047214" target="_blank">
<img src="/img/g04/200814_capriccio_final.jpg.webp" alt="" />
</a>
<br />
</div><!-- .stdalone -->
<div class="mg-h-line"><b>..｡o○☆*:ﾟ･: Variations :･ﾟ:*☆○o｡..</b></div>
<div class="gallery">
<span class="gallery-img-frame"><a href="https://www.deviantart.com/kosmoflips/art/FACL126-capriccio-wp2-852047272"><img src="/img/g04/200814_capriccio_wp2.jpg.webp" alt="" /></a></span>
<span class="gallery-img-frame"><a href="https://www.deviantart.com/kosmoflips/art/FACL126-capriccio-wp1-852047242"><img src="/img/g04/200814_capriccio_wp1.jpg.webp" alt="" /></a></span>
<span class="gallery-img-frame"><a href="https://www.deviantart.com/kosmoflips/art/FACL126-capriccio-back-852047064"><img src="/img/g04/200814_capriccio_back.jpg.webp" alt="" /></a></span>
</div>
</div><!-- closing artwork block -->
<?php $p->html_close(2); ?>

<!-- ---------------backyard links----------------------- -->
<?php $p->html_open(2); ?>
<h2>&#10056; Individial Pages &#10056;</h2>
<div class="archiv">
<ul>
<li><span class="archivname"><a href="/s/classical_composer_name_reference">Classical Composer Name Reference</a></span>
		<span class="archivdesc">for tagging</span>
	</li>
<li><span class="archivname"><a href="/s/free_type">Free Type</a></span>
		<span class="archivdesc">enlarge typed text</span>
	</li>
<li><span class="archivname"><a href="/s/line_arts">Line Arts</a></span>
		<span class="archivdesc">plain text based horizontal decoration lines</span>
	</li>
<li><span class="archivname"><a href="/s/my_girls_palette">My Girls Palette</a></span>
		<span class="archivdesc">my girls' standard colour chart</span>
	</li>
<li><span class="archivname"><a href="/s/myo_smiley_chart">Myo Smiley Chart</a></span>
		<span class="archivdesc">MyOpera smileys legacy</span>
	</li>
<li><span class="archivname"><a href="/s/tuning_chart">Tuning Chart</a></span>
		<span class="archivdesc">pitch frequency for tuning</span>
	</li>
</ul>
</div>


<h2>&#10046; Dead Archives &#10046;</h2>
<div class="archiv"><ul>
<li><span class="archivname"><a href="/cyouwa" target="_blank">Sea of Harmony</a>: </span><span class="archivdesc">self-translated KOKIA reviews around 2009~10 (no more updates)</span></li>
</ul>
</div>
<?php $p->html_close(2); ?>





</div><!-- #post-list-wrap -->
<div id="footer-navi">
<div class="navi-bar">
<span class="navi-bar-square-self"><a href="/days/p/1">1</a></span>
<span class="navi-bar-square"><a href="/days/p/2">2</a></span>
<span class="navi-bar-square"><a href="/days/p/3">3</a></span>
<span class="navi-bar-square"><a href="/days/p/4">4</a></span>
<span class="navi-bar-square"><a href="/days/p/5">5</a></span>
<span>&#65381;&#65381;</span>
<span class="navi-bar-square"><a href="/days/p/84">84</a></span>
<span> <a href="/days/p/2">&#9654;&#9654;</a></span>
</div><!-- .navi-bar -->
</div><!-- .footer-navi -->
</div><!-- #mainlayer-->
</div><!-- #content-wrap -->
</div><!-- #master-wrap -->
<footer id="footer-global">
<div class='credit'><a href="/about">2006-2022 kiyo@otoshigure</a> | <a href="https://github.com/kosmoflips/pocchong/" target="_blank ">version: 01010101</a></div>
</footer>
</body>
</html>
<?php
exit(); // so when this page is accessed through /s/ redirect, the static.php won't process extra stuff here.
?>
