#!/usr/bin/perlml
BEGIN { unshift @INC, $ENV{DOCUMENT_ROOT}.'/cgi-bin/nerv'; }
use strict;
use warnings;
use Storable qw/dclone/;
use Method_Kiyoism;
use Method_Config;

# single id or year, dont bother name tags
my $ASSIGN=1;
my $k=Method_Kiyoism->new;
my $p=$k->get_param(1); #year uses non digits as well
$p->{offset}=0 if !$p->{offset};
# my $limit=($p->{limit}?$p->{limit}:$Method_Config::SECTOR->{$ASSIGN}{LIMIT});
my $limit=$Method_Config::SECTOR->{$ASSIGN}{LIMIT};

my ($sect,$error,$case);
if ($p->{id}) { $case=11; }
elsif ($p->{year}) { $case=12; }

my $mygirls_title=$Method_Config::SECTOR->{$ASSIGN}{H3};
my $imgs=[]; #pcs2 array placeholder before randomisation
#sect: title,intro,pcs1(img,url),pcs2(img,url)
if ($case==11) { #by id // sect->intro, NEED prev/next later. ONLY check pin on this page.
	$sect->{indiv}=1;
	if ($k->chklogin) { $sect->{edit}=sprintf '%s?id=%i',$Method_Config::SECTOR->{$ASSIGN}{EDIT},$p->{id}; }

	#album info
	my $stat = "select title,date,post_id,notes from mygirls where id=?";
	if (my $info=$k->get1row($stat,$p->{id})) {
		$sect->{title}=$info->{title};

		#now check for each post whether it's pin protected
		if (my $pin=$k->get_pin_info($ASSIGN,$p->{id})) { #this entry has a pin
			#verify input pin
			my $v=$k->verify_pin($ASSIGN,$p->{id},$p->{pin});
			if (!$v) {
				$sect->{need_pin}=1;
				$sect->{id}=$p->{id};
				$sect->{pin_hint}=$pin->{hint};
			}
		}

		if (!$sect->{need_pin}) {
			$sect->{intro}=sprintf "<li>Finished on: %s</li>",$k->format_date($info->{date},1);
			if ($info->{post_id}) {
				my $title=$k->get1value('select title from post where id=?',$info->{post_id});
				$sect->{intro}.=sprintf "<li>Liner Notes: <a href=\"%s/%i\">%s</a></li>",$Method_Config::SECTOR->{3}{URL},$info->{post_id},$title;
			}
			if ($info->{notes}) {
				$sect->{intro}.=sprintf "<li>Inspiration: %s</li>",$info->{notes};
			}

			#all pics from this album
			my $sth=$k->dosql('select id,pix_url,da_url,gp_lh,gp_mid,gid,ext,stdalone from mygirls_pcs where title_id=?',$p->{id});
			while (my $r=$sth->fetchrow_hashref) {
				my ($img,$link);
				if ($r->{stdalone}) {
					$img=$k->mk_url_google_img($r->{gp_lh},$r->{gp_mid},$r->{gid},$r->{ext},'s900'); #bigger img for stdalone
					if ($r->{da_url}) {
						$link.=sprintf '<a href="%s" target="_blank">deviantART</a>', mk_url_da($r->{da_url});
						$link .= ' | ' if $r->{pix_url};
					}
					if ($r->{pix_url}) {
						$link.=sprintf '<a href="%s" target="_blank">pixiv</a>', mk_url_pixiv($r->{pix_url});
					}
					push @{$sect->{pcs1}},{img=>$img,url=>$link};
				}
				else {
					$img=$k->mk_url_google_img($r->{gp_lh},$r->{gp_mid},$r->{gid},$r->{ext},'s640'); #smaller img for mass arrangements
					$link=sprintf '%s" target="_blank',mk_url_da($r->{da_url}) if $r->{da_url};
					$link='#' if !$link;
					push @$imgs,{img=>$img,url=>$link};
				}
			}
			{ #prev/next indiv album name
				my $s0= 'select id,title from mygirls where id';
				my $s1= 'order by date';
				my $s2= 'limit 1';
				#for prev
				my $s_pre;
				$s_pre=sprintf '%s<? %s desc %s',$s0,$s1,$s2;
				if (my $r=$k->get1row($s_pre,$p->{id})) {
					$sect->{prev}=$r->{id};
					$sect->{prev1}=$r->{title};
				}
				#for next
				my $s_nxt;
				$s_nxt=sprintf '%s>? %s %s',$s0,$s1,$s2;
				if (my $r=$k->get1row($s_nxt,$p->{id})) {
					$sect->{next}=$r->{id};
					$sect->{next1}=$r->{title};
				}
			}
		}
	} else {
		$error=1;
	}
}
elsif ($case==12) { #by year, HAVE prev/next NOW
	my ($stat,$sth);
	my $s0='select mygirls.date "date",mygirls.title "title",mygirls.id "id",mygirls.notes "notes",';#do not forget add a ',' at the very last!
	my $s4url='mygirls_pcs.gp_lh "lh",mygirls_pcs.gp_mid "mid",mygirls_pcs.gid "gid",mygirls_pcs.ext "ext"';
	# my $so='order by mygirls.date desc limit ?,?';
	#IMPORTANT:
	#regular by year range : 2009-present
	#before 2009 inclusive, fetch with self=1, 2 or 3 or null 
	my $s2='from mygirls join mygirls_pcs on mygirls.rep_id=mygirls_pcs.id';
	my ($pre,$next,$s1,$min,$max);
	my $nonyear=1;
	if ($p->{year} eq '2009pre') { #dispersed in 2009
		$pre='the29b';
		$next='2009';
		$s1='where tag=3';
	} elsif ($p->{year} eq 'the29a') { #2006-winter days
		$next='the29b';
		$s1='where tag=1';
	} elsif ($p->{year} eq 'the29b') {#miko style-love song
		$pre='the29a';
		$next='2009pre';
		$s1='where tag=2';
	} else { #only this does SQL
		$nonyear=0;
		$p->{year}=$k->validate_year($p->{year},1);
		($min,$max)=$k->get_year_range($p->{year});
		if ($p->{year} ==2009) {#start year of single works
			$pre='2009pre';
			$next='2010';
			$s1=sprintf 'where date<? and date>? and tag is null';
		} else {
			if (($p->{year}+1)<=$k->get_year_this) { $next=$p->{year}+1; }
			if (($p->{year}-1)>=2009) { $pre=$p->{year}-1; }
			$s1=sprintf 'where date<? and date>?';
		}
	}
	$sect->{title}=sprintf '%s::%s', $mygirls_title,$p->{year};
	if ($pre) { $sect->{prev}=sprintf '%s/?year=%s',$Method_Config::SECTOR->{$ASSIGN}{URL},$pre; }
	if ($next) { $sect->{next}=sprintf '%s/?year=%s',$Method_Config::SECTOR->{$ASSIGN}{URL},$next; }
	#do sql
	$stat=sprintf '%s %s %s %s',$s0,$s4url,$s2,$s1;
	if ($nonyear) { $sth=$k->dosql($stat); }
	else { $sth=$k->dosql($stat,$max,$min); }

	#parse db retrieved for the selected year
	if ($sth->rows>0) {
		while (my $r=$sth->fetchrow_hashref) {
		{	#output a list of title (like archiv)
			my $date=$k->format_date($r->{date});
			if ($p->{year}!~/^2/) { #all <=2009, so {date} must be 5-char long
				$date=sprintf '0%i-%s',(substr $r->{date},0,1),$date;
			}
			my $title=sprintf '%s%s',$r->{title},($r->{notes}?'('.$r->{notes}.')':'');
			my $url=sprintf '%s/%i',$Method_Config::SECTOR->{$ASSIGN}{URL},$r->{id};
			push @{$sect->{archiv}},{date=>$date,title=>$title,url=>$url};
		}
		{ #img list
			my ($img,$url,$title);
			$img=$k->mk_url_google_img($r->{lh},$r->{mid},$r->{gid},$r->{ext},'s640');
			#html page title string
			$title=sprintf "%06d %s",$r->{date},$r->{title};
			$title=$k->escape_html($title,1);
			$url=sprintf '%s/%i',$Method_Config::SECTOR->{$ASSIGN}{URL},$r->{id};
			push @$imgs,{img=>$img,url=>$url,title=>$title};
		}
		}
	} else {
		$error=1;
	}
}
else {
	$sect->{main}=1;
	$sect->{title}=$mygirls_title;
}
#randomise image order, for $sect->pcs2
my $num=scalar @$imgs;
if ($num>0) {
	my @ran=$k->randomise($num);
	for my $i (0..$num-1) {
		$sect->{pcs2}[$ran[$i]]=dclone $imgs->[$i];
	}
}

if (($sect and !$error)) {
	$sect->{PAGE_TITLE}=sprintf '%s | %s',$sect->{title},$Method_Config::META->{SITE_TITLE};
	$k->output_tmpl($Method_Config::SECTOR->{$ASSIGN}{TMPL},$sect);
} else {
	$k->output_tmpl_404;
}

### subs ####
sub mk_url_da { #feed in string after ../art/. uses my dA account
	return sprintf "http://kosmoflips.deviantart.com/art/%s",$_[0];
}
sub mk_url_pixiv { #feed in ID
	return sprintf "http://www.pixiv.net/member_illust.php?mode=medium&amp;illust_id=%s",$_[0];
}
