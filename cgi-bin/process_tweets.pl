#!/usr/bin/perlml

use strict;
use warnings;
use LWP::Simple;
use Storable qw/dclone/;
use Time::Local;  #needed for timegm()

#https://syndication.twitter.com/timeline/profile?callback=twitterFetcher.callback&dnt=false&screen_name=kosmoflips&suppress_response_codes=true&lang=en&rnd=1

# ----------------- json formats as of 18-11-21-------------
=pod
codes are escaped in json raw file. each tweet is surrounded by:
<li class="timeline-TweetList-tweet customisable-border"> xxxxx </li>
=cut

#be careful on file location
my ($rawfile,$outfile)=@ARGV;
if (!$rawfile and !$outfile) {
	die "need in and out put files";
}

my $url='https://syndication.twitter.com/timeline/profile?callback=twitterFetcher.callback&dnt=false&screen_name=kosmoflips&suppress_response_codes=true&lang=en&rnd=1';
if(!is_success(getstore($url,$rawfile))) {
	die "can't save tweets to raw file";
}

if (!$outfile) {
	$outfile=$rawfile.'__part_index_tweets.html';
}

open (my $fh, "<:encoding(utf8)", $rawfile);
open (my $fh2,">:encoding(utf8)", $outfile);
my $chunk=<$fh>; close ($fh);
my $turl='https://twitter.com';
my $all;
while ($chunk=~/<li\s+class=\\"timeline-TweetList-tweet customisable-border.+?Tumblr<\\\/a>/g) {
	my $entry=$&;
	my $tweet;
	if ($entry=~m{TweetAuthor-link Identity.+?\(screen name: (\S+)\)}) { $tweet->{author}=$1; }
	if ($entry=~m{data-src-1x=.+?(https.+?pbs.twimg.com.+?profile_images.+?)\\">}) { $tweet->{avatar}=$1; }
	# if ($entry=~/Retweeted\\n/) { $tweet->{retweet}=1; }
	if ($entry=~m{timeline-Tweet-text.+?>(.+?)\s*<\\\/p>}) { $tweet->{txt}=$1; }
	while ($tweet->{txt}=~m{<img class.+?Emoji Emoji--forText.+?src.+?alt.+?\\"(.+?)\\".+?>}g) {
			my ($p,$alt,$q)=($`,$1,$');
			$tweet->{txt}=sprintf '%s<span class="emojisma">%s</span>%s', $p,$alt,$q;
	}
	if ($entry=~m{data-rendered-tweet-id=\\"(\d+)\\"}) { $tweet->{id}=$1; }

	if ($entry=~m{<img class=\\"NaturalImage-image\\" data-image=\\"(https:.+?)\\"}) { $tweet->{img}=$1.'.jpg'; }
	elsif ($entry=~m{data-expanded-url=\\"http:\\/\\/youtu.be\\/(.+?)\?a\\}) { $tweet->{img}='https://img.youtube.com/vi/'.$1.'/0.jpg'; }
	elsif ($entry=~m{element:photo.+?img.+?data-srcset=.+(https.+?)%3Asmall}) { $tweet->{img}=$1.':small'; }
	elsif ($entry=~m{background-image: url\((https:.+?)\)}) { $tweet->{img}=$1; }
	elsif ($entry=~m{<img.+?class=.+?CroppedImage-image js-cspForcedStyle.+?data-srcset.+?(https.+?)%3Alarge}) {
	$tweet->{img}=$1.':small'; }
	
	if ($entry=~m{<h2 class=\\"TwitterCard-title.+?>(.+?)<\\/h2>}) { $tweet->{note}=$1; }
	if ($entry=~m{<p class=\\"tcu-resetMargin.+?>(.+?)<\\/p>}) { $tweet->{note2}=$1; }
	if ($entry=~m{ndatetime=\\"(.+?)T(.+?)\+}) { $tweet->{time}=$1.' '.$2; }

	foreach my $k (keys %$tweet) {
		$tweet->{$k}=decape($tweet->{$k});
	}
	push @{$all}, dclone $tweet;
}

printf $fh2 "<table class=\"post-grid\">\n";
printf $fh2 "<tr>\n";
for my $i0 (0..2) { #3 column
	printf $fh2 "<td class=\"post-grid-col\">\n";
	for (my $i=$i0;$i<(scalar @$all); $i+=3) {
		my $tweet=$all->[$i];
		printf $fh2 '<div class="feed-grid">%s', "\n";
		printf $fh2 '<div><span class="feed-post-author"><a href="%s/%s" target="_blank"><img src="%s" alt="" /></a></span><span class="feed-post-time"><a href="%s/%s/status/%s" target="_blank">%s</a></span></div>',
			$turl,$tweet->{author},$tweet->{avatar},
			$turl,$tweet->{author},$tweet->{id},UTC2LocalString($tweet->{time});
		printf $fh2 '<div class="feed-post-content">%s</div>', $tweet->{txt};
		if ($tweet->{img}) { printf $fh2 '<div class="feed-post-attach"><img src="%s" alt="" /></div>', $tweet->{img}; }
		if ($tweet->{note}) { printf $fh2 '<div class="feed-post-note"><b>%s</b>', $tweet->{note};
			if ($tweet->{note2}) { printf $fh2 '<div>%s</div>', $tweet->{note2}; }
			printf $fh2 "</div>\n";
		}
		printf $fh2 "</div>\n\n";
	}
	printf $fh2 "</td>\n";
}
printf $fh2 "</tr>\n";
printf $fh2 "</table>\n";


sub decape {
# https%3A%2F%2Fpbs.twimg.com%2Fmedia%2FCpk8eByUsAA-a6n.jpg
	my $url=shift;
	$url=~s/%3A/:/g;
	$url=~s/%2F/\//g;
	$url=~s/\\"/"/g;
	$url=~s{\\/}{/}g;
	$url=~s{\\n}{<br />}g;
	return $url;
}

sub UTC2LocalString {
#from http://www.perlmonks.org/?node_id=873435
  my $t = shift;
  my ($datehour, $rest) = split(/:/,$t,2);
  my ($year, $month, $day, $hour) = 
      $datehour =~ /(\d+)-(\d\d)-(\d\d)\s+(\d\d)/;
  
  #  proto: $time = timegm($sec,$min,$hour,$mday,$mon,$year);
  my $epoch = timegm (0,0,$hour,$day,$month-1,$year);
  
  #  proto: ($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) =
  #          localtime(time);
  my ($lyear,$lmonth,$lday,$lhour,$isdst) = 
            (localtime($epoch))[5,4,3,2,-1];
  
  $lyear += 1900;  # year is 1900 based
  $lmonth++;       # month number is zero based
  #print "isdst: $isdst\n"; #debug flag day-light-savings time
  return ( sprintf("%04d-%02d-%02d %02d:%s",
           $lyear,$lmonth,$lday,$lhour,$rest) );
}