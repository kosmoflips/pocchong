<?php
$title=fname2name($_SERVER['REDIRECT_URL']);
$p->title=$title;
$extra=[
<<<STYLEBLOCK
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
	border: none;
}
td, th {
	text-align: center;
	margin: 0;
	border-width: 1px 1px 0 0;
	border-style: solid;
}
</style>
STYLEBLOCK
];
$p->add_html_head_block($extra);
$p->html_open();
static_page_open($title);
?>

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
I can't do js, modified from blog.eternal-thinker.com/2009/04/smileys-for-your-blog.html


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
" :smile:": "smile.png",
" :happy:": "happy.png",
" :wink:": "wink.png",
" :pp:": "p.png",
" :bigsmile:": "bigsmile.png",
" :lol:": "lol.png",
" :cool:": "cool.png",
" :eek:": "eek.png",
" :whistle:": "whistle.png",
" :sing:": "sing.png",
" :love:": "lovep.png",
" :flirt:": "flirtp.png",
" :love2:": "love.png",
" :flirt2:": "flirt.png",
" :bad:": "down.png",
" :good:": "up.png",
" :yes:": "yes.png",
" :noo:": "no.png",
" :shy:": "o.png",
" :confused:": "confused.png",
" :right:": "right.png",
" :left:": "left.png",
" :rolleyes:": "rolleyes.png",
" :bigeyes:": "bigeyes.png",
" :yikes:": "yikes.png",
" :irked:": "irked.png",
" :mad:": "mad.png",
" :furious:": "furious.png",
" :bomb:": "bomb.png",
" :scared:": "scared.png",
" :nervous:": "nervous.png",
" :zip:": "zip.png",
" :zzz:": "zzz.png",
" :worried:": "worried.png",
" :sad:": "sad.png",
" :awww:": "awww.png",
" :cry:": "cry.png",
" :yuck:": "yuck.png",
" :knockout:": "knockout.png",
" :faint:": "faint.png",
" :load:": "load.gif",
" :load2:": "wait.png",
" :idea:": "idea.png",
" :coffee:": "coffee.png",
" :party:": "party.png",
" :cheers:": "cheers.png",
" :drunk:": "drunk.png",
" :psst:": "pssst.png",
" :headbang:": "headbang.png",
" :spock:": "spock.png",
" :moustache:": "moustache.png",
" :beard:": "beard.png",
" :chef:": "chef.png",
" :chef2:": "chefb.png",
" :bandit:": "bandit.png",
" :pirate:": "pirate.png",
" :ninja:": "ninja.png",
" :devil:": "devil.png",
" :angel:": "angel.png",
" :alien:": "alien.png",
" :king:": "kingp.png",
" :queen:": "queenp.png",
" :wizard:": "wizardp.png",
" :king2:": "king.png",
" :queen2:": "queen.png",
" :wizard2:": "wizard.png",
" :knight:": "knight.png",
" :sherlock:": "sherlock.png",
" :jester:": "jester.png",
" :sadclown:": "sadclown.png",
" :smurf:": "smurf.png",
" :papasmurf:": "psmurf.png",
" :higurashi:": "higurashi.gif",
" :norris:": "norris.png",
" :oplove:": "oplove.gif",
" :oplove2:": "oplove2.gif",
" :hi:": "hi.png",
" :bye:": "bye.png",
" :doh:": "doh.png",
" :cat:": "cat.png",
" :monkey:": "monkey.png",
" :cow:": "cow.png",
" :pengu:": "penguin.png",
" :dragonfly:": "dragonfly.png",
" :bug:": "bug.png",
" :troll:": "troll.png",
" :troll2:": "troll.gif",
" :banana:": "banana.png",
" :wine:": "wine.png",
" :beeer:": "beer.png",
" :heart:": "heart.png",
" :star:": "star.png",
" :star2:": "starb.png",
" :rip:": "rip.png",
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
<tr>
	<td>:smile:<br /><span class="smiley"><img src="/axon/myo_smiley/smile.png" alt="" /></span></td>
	<td>:happy:<br /><span class="smiley"><img src="/axon/myo_smiley/happy.png" alt="" /></span></td>
	<td>:wink:<br /><span class="smiley"><img src="/axon/myo_smiley/wink.png" alt="" /></span></td>
	<td>:pp:<br /><span class="smiley"><img src="/axon/myo_smiley/p.png" alt="" /></span></td>
	<td>:bigsmile:<br /><span class="smiley"><img src="/axon/myo_smiley/bigsmile.png" alt="" /></span></td>
	<td>:lol:<br /><span class="smiley"><img src="/axon/myo_smiley/lol.png" alt="" /></span></td>
	<td>:cool:<br /><span class="smiley"><img src="/axon/myo_smiley/cool.png" alt="" /></span></td>
	<td>:eek:<br /><span class="smiley"><img src="/axon/myo_smiley/eek.png" alt="" /></span></td>
	<td>:whistle:<br /><span class="smiley"><img src="/axon/myo_smiley/whistle.png" alt="" /></span></td>
</tr>
<tr>
	<td>:love:<br /><span class="smiley"><img src="/axon/myo_smiley/lovep.png" alt="" /></span></td>
	<td>:flirt:<br /><span class="smiley"><img src="/axon/myo_smiley/flirtp.png" alt="" /></span></td>
	<td>:love2:<br /><span class="smiley"><img src="/axon/myo_smiley/love.png" alt="" /></span></td>
	<td>:flirt2:<br /><span class="smiley"><img src="/axon/myo_smiley/flirt.png" alt="" /></span></td>
	<td>:bad:<br /><span class="smiley"><img src="/axon/myo_smiley/down.png" alt="" /></span></td>
	<td>:good:<br /><span class="smiley"><img src="/axon/myo_smiley/up.png" alt="" /></span></td>
	<td>:yes:<br /><span class="smiley"><img src="/axon/myo_smiley/yes.png" alt="" /></span></td>
	<td>:noo:<br /><span class="smiley"><img src="/axon/myo_smiley/no.png" alt="" /></span></td>
	<td>:shy:<br /><span class="smiley"><img src="/axon/myo_smiley/o.png" alt="" /></span></td>
</tr>
<tr>
	<td>:right:<br /><span class="smiley"><img src="/axon/myo_smiley/right.png" alt="" /></span></td>
	<td>:left:<br /><span class="smiley"><img src="/axon/myo_smiley/left.png" alt="" /></span></td>
	<td>:rolleyes:<br /><span class="smiley"><img src="/axon/myo_smiley/rolleyes.png" alt="" /></span></td>
	<td>:bigeyes:<br /><span class="smiley"><img src="/axon/myo_smiley/bigeyes.png" alt="" /></span></td>
	<td>:yikes:<br /><span class="smiley"><img src="/axon/myo_smiley/yikes.png" alt="" /></span></td>
	<td>:irked:<br /><span class="smiley"><img src="/axon/myo_smiley/irked.png" alt="" /></span></td>
	<td>:mad:<br /><span class="smiley"><img src="/axon/myo_smiley/mad.png" alt="" /></span></td>
	<td>:furious:<br /><span class="smiley"><img src="/axon/myo_smiley/furious.png" alt="" /></span></td>
	<td>:bomb:<br /><span class="smiley"><img src="/axon/myo_smiley/bomb.png" alt="" /></span></td>
</tr>
<tr>
	<td>:nervous:<br /><span class="smiley"><img src="/axon/myo_smiley/nervous.png" alt="" /></span></td>
	<td>:zip:<br /><span class="smiley"><img src="/axon/myo_smiley/zip.png" alt="" /></span></td>
	<td>:zzz:<br /><span class="smiley"><img src="/axon/myo_smiley/zzz.png" alt="" /></span></td>
	<td>:worried:<br /><span class="smiley"><img src="/axon/myo_smiley/worried.png" alt="" /></span></td>
	<td>:sad:<br /><span class="smiley"><img src="/axon/myo_smiley/sad.png" alt="" /></span></td>
	<td>:awww:<br /><span class="smiley"><img src="/axon/myo_smiley/awww.png" alt="" /></span></td>
	<td>:cry:<br /><span class="smiley"><img src="/axon/myo_smiley/cry.png" alt="" /></span></td>
	<td>:yuck:<br /><span class="smiley"><img src="/axon/myo_smiley/yuck.png" alt="" /></span></td>
	<td>:knockout:<br /><span class="smiley"><img src="/axon/myo_smiley/knockout.png" alt="" /></span></td>
</tr>
<tr>
	<td>:load:<br /><span class="smiley"><img src="/axon/myo_smiley/load.gif" alt="" /></span></td>
	<td>:load2:<br /><span class="smiley"><img src="/axon/myo_smiley/wait.png" alt="" /></span></td>
	<td>:idea:<br /><span class="smiley"><img src="/axon/myo_smiley/idea.png" alt="" /></span></td>
	<td>:coffee:<br /><span class="smiley"><img src="/axon/myo_smiley/coffee.png" alt="" /></span></td>
	<td>:party:<br /><span class="smiley"><img src="/axon/myo_smiley/party.png" alt="" /></span></td>
	<td>:cheers:<br /><span class="smiley"><img src="/axon/myo_smiley/cheers.png" alt="" /></span></td>
	<td>:drunk:<br /><span class="smiley"><img src="/axon/myo_smiley/drunk.png" alt="" /></span></td>
	<td>:psst:<br /><span class="smiley"><img src="/axon/myo_smiley/pssst.png" alt="" /></span></td>
	<td>:headbang:<br /><span class="smiley"><img src="/axon/myo_smiley/headbang.png" alt="" /></span></td>
</tr>
<tr>
	<td>:moustache:<br /><span class="smiley"><img src="/axon/myo_smiley/moustache.png" alt="" /></span></td>
	<td>:beard:<br /><span class="smiley"><img src="/axon/myo_smiley/beard.png" alt="" /></span></td>
	<td>:chef:<br /><span class="smiley"><img src="/axon/myo_smiley/chef.png" alt="" /></span></td>
	<td>:chef2:<br /><span class="smiley"><img src="/axon/myo_smiley/chefb.png" alt="" /></span></td>
	<td>:bandit:<br /><span class="smiley"><img src="/axon/myo_smiley/bandit.png" alt="" /></span></td>
	<td>:pirate:<br /><span class="smiley"><img src="/axon/myo_smiley/pirate.png" alt="" /></span></td>
	<td>:ninja:<br /><span class="smiley"><img src="/axon/myo_smiley/ninja.png" alt="" /></span></td>
	<td>:devil:<br /><span class="smiley"><img src="/axon/myo_smiley/devil.png" alt="" /></span></td>
	<td>:angel:<br /><span class="smiley"><img src="/axon/myo_smiley/angel.png" alt="" /></span></td>
</tr>
<tr>
	<td>:king:<br /><span class="smiley"><img src="/axon/myo_smiley/kingp.png" alt="" /></span></td>
	<td>:queen:<br /><span class="smiley"><img src="/axon/myo_smiley/queenp.png" alt="" /></span></td>
	<td>:wizard:<br /><span class="smiley"><img src="/axon/myo_smiley/wizardp.png" alt="" /></span></td>
	<td>:king2:<br /><span class="smiley"><img src="/axon/myo_smiley/king.png" alt="" /></span></td>
	<td>:queen2:<br /><span class="smiley"><img src="/axon/myo_smiley/queen.png" alt="" /></span></td>
	<td>:wizard2:<br /><span class="smiley"><img src="/axon/myo_smiley/wizard.png" alt="" /></span></td>
	<td>:knight:<br /><span class="smiley"><img src="/axon/myo_smiley/knight.png" alt="" /></span></td>
	<td>:sherlock:<br /><span class="smiley"><img src="/axon/myo_smiley/sherlock.png" alt="" /></span></td>
	<td>:jester:<br /><span class="smiley"><img src="/axon/myo_smiley/jester.png" alt="" /></span></td>
</tr>
<tr>
	<td>:sing:<br /><span class="smiley"><img src="/axon/myo_smiley/sing.png" alt="" /></span></td>
	<td>:confused:<br /><span class="smiley"><img src="/axon/myo_smiley/confused.png" alt="" /></span></td>
	<td>:scared:<br /><span class="smiley"><img src="/axon/myo_smiley/scared.png" alt="" /></span></td>
	<td>:faint:<br /><span class="smiley"><img src="/axon/myo_smiley/faint.png" alt="" /></span></td>
	<td>:spock:<br /><span class="smiley"><img src="/axon/myo_smiley/spock.png" alt="" /></span></td>
	<td>:alien:<br /><span class="smiley"><img src="/axon/myo_smiley/alien.png" alt="" /></span></td>
	<td>:sadclown:<br /><span class="smiley"><img src="/axon/myo_smiley/sadclown.png" alt="" /></span></td>
	<td>:doh:<br /><span class="smiley"><img src="/axon/myo_smiley/doh.png" alt="" /></span></td>
	<td>:cat:<br /><span class="smiley"><img src="/axon/myo_smiley/cat.png" alt="" /></span></td>
</tr>	
<tr>	
	<td>:smurf:<br /><span class="smiley"><img src="/axon/myo_smiley/smurf.png" alt="" /></span></td>
	<td>:papasmurf:<br /><span class="smiley"><img src="/axon/myo_smiley/psmurf.png" alt="" /></span></td>
	<td>:higurashi:<br /><span class="smiley"><img src="/axon/myo_smiley/higurashi.gif" alt="" /></span></td>
	<td>:norris:<br /><span class="smiley"><img src="/axon/myo_smiley/norris.png" alt="" /></span></td>
	<td>:oplove:<br /><span class="smiley"><img src="/axon/myo_smiley/oplove.gif" alt="" /></span></td>
	<td>:oplove2:<br /><span class="smiley"><img src="/axon/myo_smiley/oplove2.gif" alt="" /></span></td>
	<td>:hi:<br /><span class="smiley"><img src="/axon/myo_smiley/hi.png" alt="" /></span></td>
	<td>:bye:<br /><span class="smiley"><img src="/axon/myo_smiley/bye.png" alt="" /></span></td>
	<td>:monkey:<br /><span class="smiley"><img src="/axon/myo_smiley/monkey.png" alt="" /></span></td>
</tr>
<tr>
	<td>:cow:<br /><span class="smiley"><img src="/axon/myo_smiley/cow.png" alt="" /></span></td>
	<td>:pengu:<br /><span class="smiley"><img src="/axon/myo_smiley/penguin.png" alt="" /></span></td>
	<td>:dragonfly:<br /><span class="smiley"><img src="/axon/myo_smiley/dragonfly.png" alt="" /></span></td>
	<td>:bug:<br /><span class="smiley"><img src="/axon/myo_smiley/bug.png" alt="" /></span></td>
	<td>:troll:<br /><span class="smiley"><img src="/axon/myo_smiley/troll.png" alt="" /></span></td>
	<td>:troll2:<br /><span class="smiley"><img src="/axon/myo_smiley/troll.gif" alt="" /></span></td>
	<td>:banana:<br /><span class="smiley"><img src="/axon/myo_smiley/banana.png" alt="" /></span></td>
	<td>:wine:<br /><span class="smiley"><img src="/axon/myo_smiley/wine.png" alt="" /></span></td>
	<td>:beeer:<br /><span class="smiley"><img src="/axon/myo_smiley/beer.png" alt="" /></span></td>
</tr>
<tr>
	<td>:heart:<br /><span class="smiley"><img src="/axon/myo_smiley/heart.png" alt="" /></span></td>
	<td>:star:<br /><span class="smiley"><img src="/axon/myo_smiley/star.png" alt="" /></span></td>
	<td>:star2:<br /><span class="smiley"><img src="/axon/myo_smiley/starb.png" alt="" /></span></td>
	<td>:rip:<br /><span class="smiley"><img src="/axon/myo_smiley/rip.png" alt="" /></span></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
</tr>
</table>