#!/usr/bin/perlml
BEGIN { unshift @INC, $ENV{DOCUMENT_ROOT}.'/cgi-bin/nerv'; }
use strict;
use warnings;
use Storable qw/dclone/;
use Method_Kiyoism;
use Method_Config;

my $ASSIGN=2;
my $k=Method_Kiyoism->new;
my $p=$k->get_param;

#2015-03-07 // this cgi processes by year only.
#self: 1,public ; 2,circle limited; 3,public unlisted; 4,private
my ($sect,$error,$yr);
if ($p->{id}) {
	if (my $aid=$k->get1value('select album_id from photo where id=?',$p->{id})) {
		$k->redirect($k->mk_url_google_album($aid));
		exit;
	}
}

if ($p->{year}) {
	$yr=$k->validate_year($p->{year},1);
} else {
	$yr=$k->get_year_this;
}
# if ($yr eq $k->get_year_this) { $sect->{main}=1; }

my $imgs=[]; #pcs2 array placeholder before randomisation
#output img list to A ref for all cases
my ($stat,$sth);
my $s0='select photo.date "date",photo.title "title",photo.id "id"';
my $s4url='photo.gp_lh "lh",photo.gp_mid "mid",photo.gid "gid",photo.ext "ext"';
$sect->{title}=sprintf '%s::%s',$Method_Config::SECTOR->{$ASSIGN}{H3},$yr;
$sect->{PAGE_TITLE}=sprintf "%s | %s",$sect->{title},$Method_Config::META->{SITE_TITLE};
my ($min,$max)=$k->get_year_range($yr);
my $s1='where date<? and date>?';
$stat=sprintf '%s,%s,photo.self "self",photo.album_id "aid" from photo %s and self<=2',$s0,$s4url,$s1;
$sth=$k->dosql($stat,$max,$min);

#get by offset and limit
=pod
	elsif ($case==23) { #similar to 22, but offset only, NEED prev/next later
		$sect->{title}=$Method_Config::SECTOR->{$ASSIGN}{H3};
		$sect->{PAGE_TITLE}=sprintf "%s | %s",$sect->{title},$Method_Config::META->{SITE_TITLE};
		$stat=sprintf '%s,%s,photo.self "self",photo.album_id "aid" from photo where self<=2 order by photo.date desc limit ?,?',$s0,$s4url;
		$sth=$k->dosql($stat,$p->{offset},$limit);
	}
=cut
#parse db retrieved
if ($sth->rows>0) {
	while (my $r=$sth->fetchrow_hashref) {
		my ($img,$url,$title);
		#img src, hide private
		if ($r->{self}>1) {
			$img=$Method_Config::CONSTANT->{IMG_PLACEHOLDER};
		} else {
			$img=$k->mk_url_google_img($r->{lh},$r->{mid},$r->{gid},$r->{ext},'s480');
		}
		$url=$k->mk_url_google_album($r->{aid});
		#album title string for js pop-up
		$title=sprintf "%06d %s",$r->{date},$r->{title};
		push @$imgs,{img=>$img,url=>$url,title=>$title};

		#for archiv block
		# my $url=sprintf '<a href="%s/%i">',$Method_Config::SECTOR->{$ASSIGN}{URL},$r->{id};
		push @{$sect->{archiv}},{date=>($r->{date}=~/0000/?'Single':$k->format_date($r->{date})),title=>$r->{title},url=>$url};
	}

	#randomise image order, for $sect->pcs2
	my $num=scalar @$imgs;
	if ($num>0) {
		my @ran=$k->randomise($num);
		for my $i (0..$num-1) { $sect->{pcs2}[$ran[$i]]=dclone $imgs->[$i]; }
	}
	#pre next
	if (($yr+1)<=$k->get_year_this) { $sect->{next}=sprintf '%s/?year=%i',$Method_Config::SECTOR->{$ASSIGN}{URL},$yr+1; }
	if (($yr-1)>=$Method_Config::CONSTANT->{YEAR_BEGIN}) { $sect->{prev}=sprintf '%s/?year=%i',$Method_Config::SECTOR->{$ASSIGN}{URL},$yr-1; }
} else {
	$error=1;
}

=pod
#next/prev
#case 21, already exit at this stage, no need any more
#case 22, has next/prev b/c that's year based
if ($case==23) {
	my $s0=sprintf 'select id from photo';
	my $s1=sprintf 'limit ?,1';
	#prev
	my ($s,$test);
	$s=sprintf '%s where self<=1 %s',$s0,$s1;
	$test=$k->get1value($s,($p->{offset}+$limit));
	if ($test) {
		$sect->{prev}=sprintf '%s/?offset=%i',$Method_Config::SECTOR->{$ASSIGN}{URL},$k->calc_offset($p->{offset},$limit,1);
	}
	#next
	if ($p->{offset}!=0) {
		$sect->{next}=sprintf '%s?offset=%i',$Method_Config::SECTOR->{$ASSIGN}{URL},$k->calc_offset($p->{offset},$limit);
	}
}
=cut

if ($sect and !$error) {
	$k->output_tmpl($Method_Config::SECTOR->{$ASSIGN}{TMPL},$sect);
} else {
	$k->redirect('/photo/?year='.($yr-1));
}

### subs ####
#just leave them here as a ref in the future
=pod
sub mk_url_google_imgview { #the url to google's jq interface
	my ($k,$aid,$gid)=@_;
	return sprintf "https://plus.google.com/photos/%s/albums/%s/%s",$Method_Config::CONSTANT->{GPLUS_UID},$aid,$gid;
}
sub fetch_picasa_json { #for picasa api, url to retireve all pics in one album
#about partial response: https://developers.google.com/gdata/docs/2.0/reference#PartialResponse
#convert picasa xml to json: https://developers.google.com/gdata/docs/json
	my ($k,$aid)=@_;
	return sprintf 'https://picasaweb.google.com/data/feed/api/user/%s/albumid/%s?fields=entry(gphoto:id,content(@src))&alt=json', $Method_Config::CONSTANT->{GPLUS_UID}, $aid; #do NOT escape & here or get/JSON won't work
}
=cut