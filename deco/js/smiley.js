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
	insert url of this script in <head>
	insert <script type="text/javascript"> smileys(); </script> before end of </body>
	change elem names in smileys() {} to match class names
	remember to apply class="smiley", or style=...
*/
var smileyDir = "/deco/img/myo_smiley/";
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
	for (var b=[],a=0,d="post-innder,content,static".split(","),a=0;a<d.length;a++) for(var e=document.getElementsByClassName(d[a]),c=0;c<e.length;c++)b.push(e[c]);
		for(a=0;a<b.length;a++)
		b[a].innerHTML=b[a].innerHTML.replace(/ :[a-z]\w+?:/ig,function(a) { return' <span class="smiley"><img src="' + smileyDir + smileyMap[a]+'" alt=""/></span>'}
		)
}