use strict;
use warnings;
use LWP::Simple;

my ($rawfile,$type)=(@ARGV);
my $user='kosmoflips';
my $key="e4da37dc839a5d5c061defda41a52588";
my $apiurl;
if ($type eq 1) { #recent tracks
	my $num=13;
	$apiurl=sprintf ('https://ws.audioscrobbler.com/2.0/?method=user.getRecentTracks&user=%s&limit=%s&api_key=%s&format=json', $user, $num, $key);
}
elsif ($type eq 2) { #recent artist chart
	$apiurl=sprintf 'http://ws.audioscrobbler.com/2.0/?method=user.gettopartists&user=%s&api_key=%s&format=json', $user, $key;
	$apiurl.='&period=1month&limit=5';
}
if(!is_success(getstore($apiurl,$rawfile))) {
	die "can't save tweets to raw file";
}
