#!/usr/bin/perlml
BEGIN { unshift @INC, $ENV{DOCUMENT_ROOT}.'/cgi-bin/nerv'; }
use strict;
use warnings;
use Method_Kiyoism;
use Method_Config;

my $k=Method_Kiyoism->new;
$k->chklogin(1);

my $URL=$Method_Config::ARCHIV->{URL_ADMIN};
my $p=$k->get_param;
$p->{sel}=3 if (!$p->{sel} or $p->{sel}!~/^\d+$/);
$p->{limit}=$Method_Config::ARCHIV->{LIMIT} if !$p->{limit};
$p->{offset}=0 if !$p->{offset};

my $table;
$table->{switch}=$p->{sel};
$table->{h3}=sprintf '%s::%s',$Method_Config::ARCHIV->{H3},$Method_Config::SECTOR->{$p->{sel}}{H3};

my ($stat,$ptag);
if ($p->{sel}==1) { #mygirls
	$stat='select id,title,date,tag from mygirls order by id desc limit ?,?';
}
# elsif ($p->{sel}==2) { #photo
	# $stat='select id,title,date,self,album_id from photo ';
	# if ($p->{self} and $p->{self}=~/^[1234]$/) {
		# $stat.='where self='.$p->{self};
	# }
	# $stat.=' order by id desc limit ?,?';
# }
elsif ($p->{sel}>=3 and $p->{sel}<=5) { #post, table=post, tag=1/2/3
	$stat=sprintf 'select id,title,date,tag from post where tag=%i order by %s desc limit ?,?',
		$Method_Config::SECTOR->{$p->{sel}}{TAG},
		($p->{sel}==5?'date':'id'); #cd log list uses field "date" as the sorting method
	$ptag=1;
}
my $sth=$k->dosql($stat,$p->{offset},$p->{limit});

my $lastid;
while (my $r=$sth->fetchrow_hashref) {
	$lastid=$r->{date};
	my $tag= $r->{tag} || 0;
	my $viewurl=sprintf '%s/%i',$Method_Config::SECTOR->{$p->{sel}}{URL},$r->{id};
	push @{$table->{list}},{
		id=>$r->{id},
		title=>($r->{title}?$r->{title}:'-'),
		date=>$r->{date},
		edit=>(sprintf '%s?id=%i%s',$Method_Config::SECTOR->{$p->{sel}}{EDIT},$r->{id},($ptag?'&amp;tag='.$Method_Config::SECTOR->{$p->{sel}}{TAG}:'')),
		view=>$viewurl,
		tag=>$tag,
	};
}

#prev, next
my $s2=sprintf 'select id from %s where (date<?%s) order by id desc limit 1',$Method_Config::SECTOR->{$p->{sel}}{SQL},($ptag?' and tag='.$Method_Config::SECTOR->{$p->{sel}}{TAG}:'');
if (my $r2=$k->get1value($s2,$lastid)) {
	$table->{prev}=sprintf '%s?sel=%i&amp;offset=%i',$URL,$p->{sel},$k->calc_offset($p->{offset},$p->{limit},1);
	if (defined $p->{self}) {
		$table->{prev}.=sprintf '&amp;self=%i',$p->{self};
	}
}
if ($p->{offset}>0) {
	$table->{next}=sprintf '%s?sel=%i&amp;offset=%i',$URL,$p->{sel},$k->calc_offset($p->{offset},$p->{limit});
	if (defined $p->{self}) {
		$table->{next}.=sprintf '&amp;self=%i',$p->{self};
	}
}

#for tmpl use
$table->{mknew}=sprintf '%s?new=1%s',$Method_Config::SECTOR->{$p->{sel}}{EDIT},($ptag?'&amp;tag='.$Method_Config::SECTOR->{$p->{sel}}{TAG}:'');

$k->output_tmpl($Method_Config::ARCHIV->{TMPL_ADMIN},$table);
