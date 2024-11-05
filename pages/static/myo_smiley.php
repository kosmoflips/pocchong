<?php
$p->title='MYO smiley';
$p->static_open(1);
?>
<!-- link rel="stylesheet" type="text/css" href="/deco/js/smiley.js" -->
<style>
.smiley {
	background: transparent !important;
	border: 0;
	margin: 0;
	padding:0;
	box-shadow: none !important;
	width:auto;
	height:auto;
}
.myogrid {
	/*width:80%;*/
	margin:auto;
	border-width: 0 0 1px 1px;
	border-spacing: 0;
	border-collapse: collapse;
	border-style: solid;
}
.myogrid img {
	border: none !important;
}
td, th {
	text-align: center;
	margin: 0;
	border-width: 1px 1px 0 0;
	border-style: solid;
}
</style>
<?php $p->static_open(2); ?>
<div>
<ul>
<li>All taken from My Opera Community Smileys (service ended in 2014-Mar) except:
	<ul>
		<li><i>higurashi</i>: Got from a <s>lost</s> friend's comment years ago on MyO (sadly the comment has now gone forever)</li>
		<li><i>oplove</i> and <i>oplove2</i>: from <a href="http://www.greensmilies.com/2007/02/18/firefox-co-smilies/" target="_blank">here</a></li>
	</ul>
</li>
<li><a href="/deco/js/smiley.js">js file</a> -- which shouldn't be of much useful since now browsers begin to support UTF-8 emoji!</li>
<li>there should be at least one space before each code</li>
</ul>
</div>

<hr />

<div><b>js code to use for this -- unsure if it still works or not!</b><br />
<pre><textarea readonly style="width: 90%; height: 150px">
/*
modified from blog.eternal-thinker.com/2009/04/smileys-for-your-blog.html

.smiley {
	background: transparent !important;
	border: 0;
	margin: 0;
	padding:0;
	box-shadow: none !important;
	width:auto;
	height:auto;
}

usage:
	insert url of this script in &lt;head&gt;
	insert &lt;script type=&quot;text/javascript&quot;&gt; smileys(); &lt;/script&gt; before end of &lt;/body&gt;
	change elem names in smileys() {} to match class names
	remember to apply class="smiley", or style=...
*/
var smileyDir = "/axon/myo_smiley/";
var smileyMap={
<?php
$myolist=array(
"smile"=>"smile.png",
"happy"=>"happy.png",
"wink"=>"wink.png",
"pp"=>"p.png",
"bigsmile"=>"bigsmile.png",
"lol"=>"lol.png",
"cool"=>"cool.png",
"eek"=>"eek.png",
"whistle"=>"whistle.png",
"sing"=>"sing.png",
"love"=>"lovep.png",
"flirt"=>"flirtp.png",
"love2"=>"love.png",
"flirt2"=>"flirt.png",
"bad"=>"down.png",
"good"=>"up.png",
"yes"=>"yes.png",
"noo"=>"no.png",
"shy"=>"o.png",
"confused"=>"confused.png",
"right"=>"right.png",
"left"=>"left.png",
"rolleyes"=>"rolleyes.png",
"bigeyes"=>"bigeyes.png",
"yikes"=>"yikes.png",
"irked"=>"irked.png",
"mad"=>"mad.png",
"furious"=>"furious.png",
"bomb"=>"bomb.png",
"scared"=>"scared.png",
"nervous"=>"nervous.png",
"zip"=>"zip.png",
"zzz"=>"zzz.png",
"worried"=>"worried.png",
"sad"=>"sad.png",
"awww"=>"awww.png",
"cry"=>"cry.png",
"yuck"=>"yuck.png",
"knockout"=>"knockout.png",
"faint"=>"faint.png",
"load"=>"load.gif",
"load2"=>"wait.png",
"idea"=>"idea.png",
"coffee"=>"coffee.png",
"party"=>"party.png",
"cheers"=>"cheers.png",
"drunk"=>"drunk.png",
"psst"=>"pssst.png",
"headbang"=>"headbang.png",
"spock"=>"spock.png",
"moustache"=>"moustache.png",
"beard"=>"beard.png",
"chef"=>"chef.png",
"chef2"=>"chefb.png",
"bandit"=>"bandit.png",
"pirate"=>"pirate.png",
"ninja"=>"ninja.png",
"devil"=>"devil.png",
"angel"=>"angel.png",
"alien"=>"alien.png",
"king"=>"kingp.png",
"queen"=>"queenp.png",
"wizard"=>"wizardp.png",
"king2"=>"king.png",
"queen2"=>"queen.png",
"wizard2"=>"wizard.png",
"knight"=>"knight.png",
"sherlock"=>"sherlock.png",
"jester"=>"jester.png",
"sadclown"=>"sadclown.png",
"smurf"=>"smurf.png",
"papasmurf"=>"psmurf.png",
"higurashi"=>"higurashi.gif",
"norris"=>"norris.png",
"oplove"=>"oplove.gif",
"oplove2"=>"oplove2.gif",
"hi"=>"hi.png",
"bye"=>"bye.png",
"doh"=>"doh.png",
"cat"=>"cat.png",
"monkey"=>"monkey.png",
"cow"=>"cow.png",
"pengu"=>"penguin.png",
"dragonfly"=>"dragonfly.png",
"bug"=>"bug.png",
"troll"=>"troll.png",
"troll2"=>"troll.gif",
"banana"=>"banana.png",
"wine"=>"wine.png",
"beeer"=>"beer.png",
"heart"=>"heart.png",
"star"=>"star.png",
"star2"=>"starb.png",
"rip"=>"rip.png"
);
foreach ($myolist as $i1=>$p1) {
	printf ('" :%s:": "%s",%s', $i1, $p1, "\n");
}

/*
" :smile:": "smile.png",
" :happy:": "happy.png",
...
*/
?>
};
 function myosmiley() {
	for (var b=[],a=0,d=&quot;post-innder,content,static&quot;.split(&quot;,&quot;),a=0;a&lt;d.length;a++) for(var e=document.getElementsByClassName(d[a]),c=0;c&lt;e.length;c++)b.push(e[c]);
		for(a=0;a&lt;b.length;a++)
		b[a].innerHTML=b[a].innerHTML.replace(/ :[a-z]\w+?:/ig,function(a) { return' &lt;span class=&quot;smiley&quot;&gt;&lt;img src=&quot;' + smileyDir + smileyMap[a]+'&quot; alt=&quot;&quot;/&gt;&lt;/span&gt;'}
		)
}
</textarea></pre>
</div>

<hr />

<table class="myogrid">
<?php
$ncol=9;
$nrow=ceil(sizeof($myolist)/$ncol);

for ($i=1; $i<=$nrow; $i++) {
?>
<tr>
<?php
	for ($j=1; $j<=$ncol; $j++) {
		$icon=array_keys($myolist)[($i-1)*$ncol+$j]??null;
		if (isset($icon)) {
			$png=$myolist[$icon];
?>
<td>:<?php echo $icon; ?>:<br /><span class="smiley"><img src="/binary/myo_smiley/<?php echo $png; ?>" alt="" /></span></td>
<?php
		} else {
?>
<td></td>
<?php		
		}
	}
?>
</tr>
<?php
}
?>
</table>
