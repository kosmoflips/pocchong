<?php
print_recent_tracks_full();

//----------------
function print_recent_tracks_full () {
	$json=parse_lastfm();
	?>
<div>
<h4><?php echo rand_deco_symbol(); ?> <a href="https://www.last.fm/de/user/kosmoflips" target="_blank">Recent play-counts</a></h4>
<div>
<?php
	print_artists($json);
	?>
</div>
</div>
<?php
	1;
}
function parse_lastfm () {
	/*
https://www.last.fm/api/

Application name	side_track
API key	e4da37dc839a5d5c061defda41a52588
Shared secret	88b188669acc9654a1ee7585dfa2858d
Registered to	kosmoflips

$user='kosmoflips';
$key="e4da37dc839a5d5c061defda41a52588";

*/

	$rawfile=POCCHONG['PATH']['widget'].'/lastfmraw.txt';
	$timespan= 60 * 60; # x min * 60 sec
	if (!file_exists($rawfile) or ((time() - filemtime($rawfile)) > $timespan)) { #retrieve twitter data if raw file missing, force update, or since last time is true
		$cmd=sprintf ('perl %s %s %s', POCCHONG['PATH']['widget'].'/lastfmparse.pl', $rawfile, 2); // type 1: recent tracks, 2: artist chart
		exec($cmd);
	}
	$jsonchunk=file_get_contents($rawfile);
	$json=json_decode($jsonchunk, 1);
	return $json;
}
function print_tracks ($json=null) {
	$epoch1=0; // most recent
	$clear=7;
	foreach ($json['recenttracks']['track'] as $r) {
		if (!$epoch1) {
	?>
<div><b><?php time27($r['date']['uts'], -7, 7) ?></b></div>
<?php			$epoch1=1;
		}
		if ($clear>0) {
	?>
<div>- <?php echo $r['artist']['#text']; ?>: <?php echo $r['name']; ?></div>
<?php
			$clear--;
		} else {
			break;
		}
	}
}
function print_artists ($json=null) {
	foreach ($json['topartists']['artist'] as $row) {
	?>
<div><?php echo $row['name']; ?> (<?php echo $row['playcount']; ?>)</div>
<?php
	}
}
?>