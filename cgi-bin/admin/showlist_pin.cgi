#!/usr/bin/perlml
BEGIN { unshift @INC, $ENV{DOCUMENT_ROOT}.'/cgi-bin/nerv'; }
use strict;
use warnings;
use Method_Kiyoism;
use Method_Config;

my $k=Method_Kiyoism->new;
my $p=$k->get_param();
$p->{limit}=$Method_Config::ARCHIV->{LIMIT} if !$p->{limit};
$p->{offset}=0 if !$p->{offset};
#id,table_id,item_id,hint,md5hex
my $stat='select * from passcode order by id desc limit ?,?';
my $sth=$k->dosql($stat,$p->{offset},$p->{limit});
my $table;
if ($sth->rows>0) {
	my $lastid;
	while (my $r=$sth->fetchrow_hashref) {
		$lastid=$r->{id};
		push @{$table->{list}},{
			id=>$r->{id},
			table=>$Method_Config::SECTOR->{$r->{table_id}}{SQL},
			item_id=>$r->{item_id},
			title=>$k->get1value('select title from ? where id=?',$Method_Config::SECTOR->{$r->{table_id}}{SQL},$r->{item_id}),
			md5hex=>$r->{md5hex},
			edit=>(sprintf '%s?id=%i',$Method_Config::SECTOR->{$r->{table_id}}{EDIT},$r->{item_id}),
			view=>($r->{table_id}==2?'':(sprintf '%s/%i',$Method_Config::SECTOR->{$r->{table_id}}{URL},$r->{item_id})),
		};
	}
	#prev, next
	my $s2='select id from passcode where id<? order by id desc limit 1';
	if (my $r2=$k->get1value($s2,$lastid)) {
		$table->{prev}=sprintf 'showlist_pin?offset=%i',$k->calc_offset($p->{offset},$p->{limit},1);
	}
	if ($p->{offset}>0) {
		$table->{next}=sprintf 'showlist_pin?offset=%i',$k->calc_offset($p->{offset},$p->{limit});
	}
} else {
	$table->{empty}=1;
}
$k->output_tmpl($Method_Config::PATH->{HTML_ADMIN}.'/showlist_pin.tmpl',$table);
