#!/usr/bin/perlml
BEGIN { unshift @INC, $ENV{DOCUMENT_ROOT}.'/cgi-bin/nerv'; }
use strict;
use warnings;
use Method_Kiyoism;
use Method_Config;

#no year mode here!
my $k=Method_Kiyoism->new;
my $p=$k->get_param(1);
$p->{offset}=0 if !$p->{offset};
my $limit=$Method_Config::ARCHIV->{LIMIT};
if ($p->{limit} and $p->{limit}=~/^\d+$/) {
	$limit=$p->{limit};
}
my $thisyear=$k->get_year_this;
my ($sth,$archiv);

my $switch=3;
if ($p->{sel} and !$p->{year}) { # {year} is reserved for post only!
	if ($p->{sel} eq 'mygirls') {
		$switch=1;
	}
	# elsif ($p->{sel} eq 'photo') {
		# $switch=2;
	# }
}

$archiv->{SECT_TITLE}=sprintf '%s::%s',$Method_Config::ARCHIV->{H3},$Method_Config::SECTOR->{$switch}{H3};
$archiv->{PAGE_TITLE}=sprintf '%s | %s',$archiv->{SECT_TITLE},$Method_Config::META->{SITE_TITLE};

my $stat0='select date,title,id';
$stat0.=',notes' if $switch==1;

if ($p->{year}) { #extract all titles in this year, table=post
# by year, 4digit-format only and >=2000
	$p->{year}=$k->validate_year($p->{year},1);
	my ($min,$max)=$k->get_year_range($p->{year});
	my $stat;
	my $stat1='where date>? and date<?';
	# if ($switch==2) {#photo
		# $stat=sprintf "%s,self from photo %s and self<=2 order by date desc",$stat0,$stat1;
	# } elsif ($switch==3) {
		$stat=sprintf "%s from post %s and tag=1 order by epoch desc",$stat0,$stat1;
	# } elsif ($switch==1) {
		# $stat=sprintf "%s from mygirls %s order by date desc",$stat0,$stat1;
	# }
	$sth=$k->dosql($stat,$min,$max);
}
else {
	my $stat;
	if ($switch==2) {
		$stat=sprintf "%s,self from photo where self<=2 order by date desc limit ?,?",$stat0;
	} elsif ($switch==1) {
		$stat=sprintf "%s from mygirls order by date desc limit ?,?",$stat0;
	} elsif ($switch==3) {
		$stat=sprintf "%s from post where tag=1 order by epoch desc limit ?,?",$stat0;
	}
	$sth=$k->dosql($stat,$p->{offset},$limit);
}

my $curr;
if ($sth->rows>0) {
	my ($idfirst,$idlast);
	while (my $ref=$sth->fetchrow_hashref) {
		$idfirst=$ref->{date} if !$idfirst;
		$idlast=$ref->{date};
		my ($yr,$mo,$dy)=$k->split_date($ref->{date});
		my $this=2000+$yr;
		my $date;
		if ($mo==0) {
			$date='Single';
		} else {
			$date=$k->format_date($ref->{date});
		}
		if (!$curr or ($curr ne $this)) {
			#redirect to archiv list by year
			my $yrurl;
			if ($switch==3) {
				$yrurl=sprintf '<a href="%s%s/%i">%s</a>',$Method_Config::ARCHIV->{URL},$Method_Config::SECTOR->{$switch}{URL},$this,$this;
			} else {
				$yrurl=sprintf '<a href="%s/?year=%i">%s</a>',$Method_Config::SECTOR->{$switch}{URL},$this,$this;
			}
			push @{$archiv->{archiv}},{year=>$yrurl};
			$curr=$this;
		}
		#have to do like below b/c limited TMPL_LOOP
		my $url;
		# if ($switch==2) { #photo album, go to gplus
			# my $aid=$k->get1value('select album_id from photo where id=?',$ref->{id});
			# $url=sprintf '<a href="%s">',$k->mk_url_google_album($aid);
		# } else {
			$url=sprintf '<a href="%s/%i">',$Method_Config::SECTOR->{$switch}{URL},$ref->{id};
		# }
		my $title2=$ref->{title};
		$title2.=sprintf ' (%s)',$ref->{notes} if $ref->{notes};
		push @{$archiv->{archiv}[-1]{list}},{date=>$date,title=>$title2,url=>$url};
	}

	#for prev/next links
{
	if ($p->{year}) { #again, table=post only!
		if ($p->{year} < $thisyear) {
			$archiv->{next}=sprintf "%s/%i",$Method_Config::SECTOR->{$switch}{URL},$p->{year}+1;
		}
		if ($p->{year} > $Method_Config::CONSTANT->{YEAR_BEGIN}) {
			$archiv->{prev}=sprintf "%s/%i",$Method_Config::SECTOR->{$switch}{URL},$p->{year}-1;
		}
	}
	else {
		my $ex='';
		# if ($switch==2) { $ex='and self<=2'; }
		if ($switch==3) { $ex='and tag=1'; }
		my $s0=sprintf 'select id from %s where date>? %s order by date limit 1', $Method_Config::SECTOR->{$switch}{SQL}, $ex;
		my $s1=sprintf 'select id from %s where date<? %s order by date desc limit 1',$Method_Config::SECTOR->{$switch}{SQL},$ex;

		if (my $ref=$k->get1row($s0,$idfirst)) {
			$archiv->{next}=sprintf '%s/offset/%i',$Method_Config::SECTOR->{$switch}{URL},$k->calc_offset($p->{offset},$limit);
		}
		if (my $ref=$k->get1row($s1,$idlast)) {
			$archiv->{prev}.=sprintf '%s/offset/%i',$Method_Config::SECTOR->{$switch}{URL},$k->calc_offset($p->{offset},$limit,1);
		}
	}
	#now get the full url
	if ($archiv->{prev}) { $archiv->{prev}=$Method_Config::ARCHIV->{URL}.$archiv->{prev}; }
	if ($archiv->{next}) { $archiv->{next}=$Method_Config::ARCHIV->{URL}.$archiv->{next}; }
}

	#for html's top [select years]. only do it for post.
	# if ($switch==3) {
		# my @yrs=get_years($k);
		# foreach (@yrs) {
			# my $yprog=sprintf '%s%s/%i',$Method_Config::ARCHIV->{URL},$Method_Config::SECTOR->{$switch}{URL},$_;
			# unshift @{$archiv->{yrs}},{yprog=>$yprog,selyear=>$_};
		# }
	# }
	if ($k->chklogin) {
		$archiv->{edit}=sprintf '%s?sel=%i',$Method_Config::ARCHIV->{EDIT},$switch;
	}
	$k->output_tmpl($Method_Config::ARCHIV->{TMPL},$archiv);
} else {
	$k->redirect($Method_Config::ARCHIV->{URL});
}

sub get_years { #return Array. range of year. 4-digit
	my $k=shift;
	my @years;
	for ($Method_Config::CONSTANT->{YEAR_BEGIN}..$k->get_year_this) {
		push @years, $_;
	}
	return @years;
}