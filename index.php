<?php
//as of 2022-10-23, I redirect homepage to /days, as I really don't see a point to have a "homepage"
header("Location: /days/");
exit();

require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/synapse.php');

$p=new PocPage;
// $p->add_css(['/deco/css/index.css']);
// $p->add_css(['/deco/css/index_tweeter.css']);
// $p->add_js(['/deco/js/fetch_tweet.js']);
$p->add_head_html_block(['<link href="https://fonts.googleapis.com/css?family=Black+Ops+One&display=swap" rel="stylesheet">']);
$p->html_open();
?>

<div class="line" style="text-align:center">*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*</div>

<div style="text-align:center; font-size: 105%"><s>old twitter parser is retired, don't want to use others but also lazy to rewrite.<br />
so the only option left is to go with the dull-looking and lack-of-feature official API <br />
still better than keeping the HP empty, maybe. &#128580;</s><br />
<br />
I'm never happy with twitter's own embedding widget,<br />so as of 2022-July-1, the old widget was brought back and reborn!</div>

<div class="line" style="text-align:center">*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*</div>

<?php
$langs = array('en','de','ja','fr');
$rand_lang=rand(0, (sizeof($langs)-1));
?>
<script>
var configProfile = {
  "profile": {"screenName": 'kosmoflips'},
  "domId": 'poc-tweets',
  "maxTweets": 7,
  "enableLinks": true, 
  "showUser": false,
  "showTime": true,
  "showImages": true,
  "lang": '<?php echo $langs[$rand_lang]; ?>'
};
twitterFetcher.fetch(configProfile);
</script>
<div id="poc-tweets"></div>

<div class="line" style="text-align:center">*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*――*☆*</div>

<?php
$p->html_close();
?>
