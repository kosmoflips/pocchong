<?php
$title=fname2name($_SERVER['REDIRECT_URL']);
$p->title=$title;
$extra=['
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
	width:90%;
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
'];
$p->add_extra($extra);
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
<table class="myogrid">
<tr>
	<td>:smile:<br /><span class="smiley"><img src="/deco/img/myo_smiley/smile.png" alt="" /></span></td>
	<td>:happy:<br /><span class="smiley"><img src="/deco/img/myo_smiley/happy.png" alt="" /></span></td>
	<td>:wink:<br /><span class="smiley"><img src="/deco/img/myo_smiley/wink.png" alt="" /></span></td>
	<td>:pp:<br /><span class="smiley"><img src="/deco/img/myo_smiley/p.png" alt="" /></span></td>
	<td>:bigsmile:<br /><span class="smiley"><img src="/deco/img/myo_smiley/bigsmile.png" alt="" /></span></td>
	<td>:lol:<br /><span class="smiley"><img src="/deco/img/myo_smiley/lol.png" alt="" /></span></td>
	<td>:cool:<br /><span class="smiley"><img src="/deco/img/myo_smiley/cool.png" alt="" /></span></td>
	<td>:eek:<br /><span class="smiley"><img src="/deco/img/myo_smiley/eek.png" alt="" /></span></td>
	<td>:whistle:<br /><span class="smiley"><img src="/deco/img/myo_smiley/whistle.png" alt="" /></span></td>
	<td>:sing:<br /><span class="smiley"><img src="/deco/img/myo_smiley/sing.png" alt="" /></span></td>
</tr>
<tr>
	<td>:love:<br /><span class="smiley"><img src="/deco/img/myo_smiley/lovep.png" alt="" /></span></td>
	<td>:flirt:<br /><span class="smiley"><img src="/deco/img/myo_smiley/flirtp.png" alt="" /></span></td>
	<td>:love2:<br /><span class="smiley"><img src="/deco/img/myo_smiley/love.png" alt="" /></span></td>
	<td>:flirt2:<br /><span class="smiley"><img src="/deco/img/myo_smiley/flirt.png" alt="" /></span></td>
	<td>:bad:<br /><span class="smiley"><img src="/deco/img/myo_smiley/down.png" alt="" /></span></td>
	<td>:good:<br /><span class="smiley"><img src="/deco/img/myo_smiley/up.png" alt="" /></span></td>
	<td>:yes:<br /><span class="smiley"><img src="/deco/img/myo_smiley/yes.png" alt="" /></span></td>
	<td>:noo:<br /><span class="smiley"><img src="/deco/img/myo_smiley/no.png" alt="" /></span></td>
	<td>:shy:<br /><span class="smiley"><img src="/deco/img/myo_smiley/o.png" alt="" /></span></td>
	<td>:confused:<br /><span class="smiley"><img src="/deco/img/myo_smiley/confused.png" alt="" /></span></td>
</tr>
<tr>
	<td>:right:<br /><span class="smiley"><img src="/deco/img/myo_smiley/right.png" alt="" /></span></td>
	<td>:left:<br /><span class="smiley"><img src="/deco/img/myo_smiley/left.png" alt="" /></span></td>
	<td>:rolleyes:<br /><span class="smiley"><img src="/deco/img/myo_smiley/rolleyes.png" alt="" /></span></td>
	<td>:bigeyes:<br /><span class="smiley"><img src="/deco/img/myo_smiley/bigeyes.png" alt="" /></span></td>
	<td>:yikes:<br /><span class="smiley"><img src="/deco/img/myo_smiley/yikes.png" alt="" /></span></td>
	<td>:irked:<br /><span class="smiley"><img src="/deco/img/myo_smiley/irked.png" alt="" /></span></td>
	<td>:mad:<br /><span class="smiley"><img src="/deco/img/myo_smiley/mad.png" alt="" /></span></td>
	<td>:furious:<br /><span class="smiley"><img src="/deco/img/myo_smiley/furious.png" alt="" /></span></td>
	<td>:bomb:<br /><span class="smiley"><img src="/deco/img/myo_smiley/bomb.png" alt="" /></span></td>
	<td>:scared:<br /><span class="smiley"><img src="/deco/img/myo_smiley/scared.png" alt="" /></span></td>
</tr>
<tr>
	<td>:nervous:<br /><span class="smiley"><img src="/deco/img/myo_smiley/nervous.png" alt="" /></span></td>
	<td>:zip:<br /><span class="smiley"><img src="/deco/img/myo_smiley/zip.png" alt="" /></span></td>
	<td>:zzz:<br /><span class="smiley"><img src="/deco/img/myo_smiley/zzz.png" alt="" /></span></td>
	<td>:worried:<br /><span class="smiley"><img src="/deco/img/myo_smiley/worried.png" alt="" /></span></td>
	<td>:sad:<br /><span class="smiley"><img src="/deco/img/myo_smiley/sad.png" alt="" /></span></td>
	<td>:awww:<br /><span class="smiley"><img src="/deco/img/myo_smiley/awww.png" alt="" /></span></td>
	<td>:cry:<br /><span class="smiley"><img src="/deco/img/myo_smiley/cry.png" alt="" /></span></td>
	<td>:yuck:<br /><span class="smiley"><img src="/deco/img/myo_smiley/yuck.png" alt="" /></span></td>
	<td>:knockout:<br /><span class="smiley"><img src="/deco/img/myo_smiley/knockout.png" alt="" /></span></td>
	<td>:faint:<br /><span class="smiley"><img src="/deco/img/myo_smiley/faint.png" alt="" /></span></td>
</tr>
<tr>
	<td>:load:<br /><span class="smiley"><img src="/deco/img/myo_smiley/load.gif" alt="" /></span></td>
	<td>:load2:<br /><span class="smiley"><img src="/deco/img/myo_smiley/wait.png" alt="" /></span></td>
	<td>:idea:<br /><span class="smiley"><img src="/deco/img/myo_smiley/idea.png" alt="" /></span></td>
	<td>:coffee:<br /><span class="smiley"><img src="/deco/img/myo_smiley/coffee.png" alt="" /></span></td>
	<td>:party:<br /><span class="smiley"><img src="/deco/img/myo_smiley/party.png" alt="" /></span></td>
	<td>:cheers:<br /><span class="smiley"><img src="/deco/img/myo_smiley/cheers.png" alt="" /></span></td>
	<td>:drunk:<br /><span class="smiley"><img src="/deco/img/myo_smiley/drunk.png" alt="" /></span></td>
	<td>:psst:<br /><span class="smiley"><img src="/deco/img/myo_smiley/pssst.png" alt="" /></span></td>
	<td>:headbang:<br /><span class="smiley"><img src="/deco/img/myo_smiley/headbang.png" alt="" /></span></td>
	<td>:spock:<br /><span class="smiley"><img src="/deco/img/myo_smiley/spock.png" alt="" /></span></td>
</tr>
<tr>
	<td>:moustache:<br /><span class="smiley"><img src="/deco/img/myo_smiley/moustache.png" alt="" /></span></td>
	<td>:beard:<br /><span class="smiley"><img src="/deco/img/myo_smiley/beard.png" alt="" /></span></td>
	<td>:chef:<br /><span class="smiley"><img src="/deco/img/myo_smiley/chef.png" alt="" /></span></td>
	<td>:chef2:<br /><span class="smiley"><img src="/deco/img/myo_smiley/chefb.png" alt="" /></span></td>
	<td>:bandit:<br /><span class="smiley"><img src="/deco/img/myo_smiley/bandit.png" alt="" /></span></td>
	<td>:pirate:<br /><span class="smiley"><img src="/deco/img/myo_smiley/pirate.png" alt="" /></span></td>
	<td>:ninja:<br /><span class="smiley"><img src="/deco/img/myo_smiley/ninja.png" alt="" /></span></td>
	<td>:devil:<br /><span class="smiley"><img src="/deco/img/myo_smiley/devil.png" alt="" /></span></td>
	<td>:angel:<br /><span class="smiley"><img src="/deco/img/myo_smiley/angel.png" alt="" /></span></td>
	<td>:alien:<br /><span class="smiley"><img src="/deco/img/myo_smiley/alien.png" alt="" /></span></td>
</tr>
<tr>
	<td>:king:<br /><span class="smiley"><img src="/deco/img/myo_smiley/kingp.png" alt="" /></span></td>
	<td>:queen:<br /><span class="smiley"><img src="/deco/img/myo_smiley/queenp.png" alt="" /></span></td>
	<td>:wizard:<br /><span class="smiley"><img src="/deco/img/myo_smiley/wizardp.png" alt="" /></span></td>
	<td>:king2:<br /><span class="smiley"><img src="/deco/img/myo_smiley/king.png" alt="" /></span></td>
	<td>:queen2:<br /><span class="smiley"><img src="/deco/img/myo_smiley/queen.png" alt="" /></span></td>
	<td>:wizard2:<br /><span class="smiley"><img src="/deco/img/myo_smiley/wizard.png" alt="" /></span></td>
	<td>:knight:<br /><span class="smiley"><img src="/deco/img/myo_smiley/knight.png" alt="" /></span></td>
	<td>:sherlock:<br /><span class="smiley"><img src="/deco/img/myo_smiley/sherlock.png" alt="" /></span></td>
	<td>:jester:<br /><span class="smiley"><img src="/deco/img/myo_smiley/jester.png" alt="" /></span></td>
	<td>:sadclown:<br /><span class="smiley"><img src="/deco/img/myo_smiley/sadclown.png" alt="" /></span></td>
</tr>	
<tr>	
	<td>:smurf:<br /><span class="smiley"><img src="/deco/img/myo_smiley/smurf.png" alt="" /></span></td>
	<td>:papasmurf:<br /><span class="smiley"><img src="/deco/img/myo_smiley/psmurf.png" alt="" /></span></td>
	<td>:higurashi:<br /><span class="smiley"><img src="/deco/img/myo_smiley/higurashi.gif" alt="" /></span></td>
	<td>:norris:<br /><span class="smiley"><img src="/deco/img/myo_smiley/norris.png" alt="" /></span></td>
	<td>:oplove:<br /><span class="smiley"><img src="/deco/img/myo_smiley/oplove.gif" alt="" /></span></td>
	<td>:oplove2:<br /><span class="smiley"><img src="/deco/img/myo_smiley/oplove2.gif" alt="" /></span></td>
	<td>:hi:<br /><span class="smiley"><img src="/deco/img/myo_smiley/hi.png" alt="" /></span></td>
	<td>:bye:<br /><span class="smiley"><img src="/deco/img/myo_smiley/bye.png" alt="" /></span></td>
	<td>:doh:<br /><span class="smiley"><img src="/deco/img/myo_smiley/doh.png" alt="" /></span></td>
	<td></td>
</tr>
<tr>
	<td>:cat:<br /><span class="smiley"><img src="/deco/img/myo_smiley/cat.png" alt="" /></span></td>
	<td>:monkey:<br /><span class="smiley"><img src="/deco/img/myo_smiley/monkey.png" alt="" /></span></td>
	<td>:cow:<br /><span class="smiley"><img src="/deco/img/myo_smiley/cow.png" alt="" /></span></td>
	<td>:pengu:<br /><span class="smiley"><img src="/deco/img/myo_smiley/penguin.png" alt="" /></span></td>
	<td>:dragonfly:<br /><span class="smiley"><img src="/deco/img/myo_smiley/dragonfly.png" alt="" /></span></td>
	<td>:bug:<br /><span class="smiley"><img src="/deco/img/myo_smiley/bug.png" alt="" /></span></td>
	<td>:troll:<br /><span class="smiley"><img src="/deco/img/myo_smiley/troll.png" alt="" /></span></td>
	<td>:troll2:<br /><span class="smiley"><img src="/deco/img/myo_smiley/troll.gif" alt="" /></span></td>
	<td></td>
	<td></td>
</tr>
<tr>
	<td>:banana:<br /><span class="smiley"><img src="/deco/img/myo_smiley/banana.png" alt="" /></span></td>
	<td>:wine:<br /><span class="smiley"><img src="/deco/img/myo_smiley/wine.png" alt="" /></span></td>
	<td>:beeer:<br /><span class="smiley"><img src="/deco/img/myo_smiley/beer.png" alt="" /></span></td>
	<td>:heart:<br /><span class="smiley"><img src="/deco/img/myo_smiley/heart.png" alt="" /></span></td>
	<td>:star:<br /><span class="smiley"><img src="/deco/img/myo_smiley/star.png" alt="" /></span></td>
	<td>:star2:<br /><span class="smiley"><img src="/deco/img/myo_smiley/starb.png" alt="" /></span></td>
	<td>:rip:<br /><span class="smiley"><img src="/deco/img/myo_smiley/rip.png" alt="" /></span></td>
	<td></td>
	<td></td>
	<td></td>
</tr>
</table>