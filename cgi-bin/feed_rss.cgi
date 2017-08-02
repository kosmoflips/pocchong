#!/usr/bin/perlml

use strict;
use warnings;
use Method_Kiyoism_Plus;
use utf8;
my $k=Method_Kiyoism_Plus->new;

use Encode qw/encode decode/;
binmode *STDOUT, ":utf-8";

my $max=50;
my $posts=$k->getAll('SELECT id,title,epoch,substr(content,0,500) as "content" FROM post ORDER BY id DESC LIMIT ?', [$max]);
my $arts=$k->getAll('SELECT mygirls.id,mygirls.epoch,mygirls.title,mygirls.notes,mygirls_pcs.img_url FROM mygirls JOIN mygirls_pcs ON mygirls.rep_id=mygirls_pcs.id ORDER BY id DESC LIMIT 20');

$k->header({'-type'=>'text/xml'});
# $k->header; #toggle the two for local testing and online use
print <<XMLBEGIN;
<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
<title>音時雨 ～Fairy Aria～</title>
<link>http://www.pocchong.de</link>
<atom:link rel="self" type="application/rss+xml" href="http://www.pocchong.de/feed.rss"/>
<lastBuildDate>Fri, 22 Jul 2016 06:39:46 GMT</lastBuildDate>
<description>Melodies from a rainy soul ♪</description>
<generator>Method_Kiyoism_Plus/.</generator>

XMLBEGIN

my $ic=0;
my $items;
while ($ic<=$max) {
	my $ta=$arts->[0]{epoch};
	my $tp=$posts->[0]{epoch};
	if ($ta>$tp) {
		printf ('<item><title>[Art]%s</title><guid isPermaLink="true">http://%s%s/%s</guid><link>http://%s%s/%s</link><pubDate>%s</pubDate><description><![CDATA[<img src="https://%s" alt="" />%s]]></description></item>%s',
			$arts->[0]{title},
			$Method_Kiyoism_Plus::POCCHONG->{siteurl},
			$Method_Kiyoism_Plus::POCCHONG->{sql_mygirls_url}, $arts->[0]{id},
			$Method_Kiyoism_Plus::POCCHONG->{siteurl},
			$Method_Kiyoism_Plus::POCCHONG->{sql_mygirls_url}, $arts->[0]{id},
			$k->format_epoch2date($arts->[0]{epoch},-7,6),
			$arts->[0]{img_url},
			($arts->[0]{notes}?'<br />inspiration: '.$arts->[0]{notes}:''),
			"\n");
		shift @$arts;
	} else {
		$posts->[0]{content}=~s/<br( \/)?>/\0/g;
		$posts->[0]{content}=~s/<[^><]+>//g;
		$posts->[0]{content}=~s/<[^><]+$//g;
		$posts->[0]{content}=~s/\0/<br \/>/g;
		printf ('<item><title>[Diary]%s</title><guid isPermaLink="true">http://%s%s/%s</guid><link>http://%s%s/%s</link><pubDate>%s</pubDate><description><![CDATA[%s]]></description></item>%s', 
			$posts->[0]{title},
			$Method_Kiyoism_Plus::POCCHONG->{siteurl},
			$Method_Kiyoism_Plus::POCCHONG->{sql_post_url}, $posts->[0]{id},
			$Method_Kiyoism_Plus::POCCHONG->{siteurl},
			$Method_Kiyoism_Plus::POCCHONG->{sql_post_url}, $posts->[0]{id},
			$k->format_epoch2date($posts->[0]{epoch},-7,6),
			$posts->[0]{content},
			"\n");
		shift(@$posts);
	}
	$ic++;
}
print <<RSS2;
	</channel>
</rss>

RSS2

__END__

