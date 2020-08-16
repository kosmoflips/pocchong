<?php
print_tweet_whole(12);

function tweet_root () {
	return 'https://twitter.com/kosmoflips';
}
function process_tweet_raw () {
	$timespan=60*24*60*0.5; # 1/2 day in second
	$tweetfile=POCCHONG['PATH']['widget'].'/tweets_parsed.json';
	if (!file_exists($tweetfile) or isset($_GET['force']) or ((time() - filemtime($tweetfile)) > $timespan)) { #retrieve twitter data if raw file missing, force update, or since last time is true
		$chunkfile=POCCHONG['PATH']['widget'].'/tweets_raw.txt';
		$cmd=sprintf ('perl %s %s %s', POCCHONG['PATH']['widget'].'/process_tweets.pl', $chunkfile, $tweetfile); // PERL IS easier for this task, especially regex
		exec($cmd);
	}
	if (file_exists($tweetfile)) {
		$jsonraw = file_get_contents($tweetfile);
		$json = json_decode($jsonraw,true);
		return $json;
	} else {
		return null;
	}
}
function print_tweet_whole ($maxtweet=12) {
	$json=process_tweet_raw();
	?>
<h4><?php echo rand_deco_symbol(); ?> <a href="<?php echo tweet_root(); ?>">Outdated updates</a></h4>
<div class="tweet-all-wrap">
<?php
	if ($json) {
		foreach ($json as $entry) {
			print_tweet_entry($entry); // direct HTML output
			$maxtweet--;
			if ($maxtweet==0) {
				break;
			}
		}
	} else {
	?>
tweet file can't be located (._.)
<?php
	}
?>
</div><!-- .tweet-all-wrap -->
<?php
	1;
}
function print_tweet_entry ($entry=null) {
	$turl=tweet_root().'/status';
?>
<div class="tweet-entry-wrap">
<div class="tweet-time"><a href="<?php echo $turl; ?>/<?php echo $entry['id']; ?>" target="_blank"><?php echo $entry['time']; ?></a></div>
<div><?php echo $entry['txt']; ?></div>
<?php
	if (isset($entry['img'])) {
	?>
<div class="tweet-attach-img"><img src="<?php echo $entry['img']; ?>" alt="" /></div>
	<?php
	}
?>
</div>
<?php
}
?>