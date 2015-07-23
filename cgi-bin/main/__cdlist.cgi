#!/usr/bin/perlml
BEGIN { unshift @INC, $ENV{DOCUMENT_ROOT}.'/cgi-bin/nerv'; }
use strict;
use warnings;
use Method_Kiyoism;
use Method_Config;
use Method_SQLBranch;

#tag=3, table=post
#cd list
my $k=Method_Kiyoism->new;
my $p=$k->get_param;
$p->{tag}=3;
if ($p->{id}) {
	process_param_post($p);
	exit;
}
#else => archive list 
my $ASSIGN=5;
my $stat=sprintf 'select id,title from %s where tag=%s order by date',$Method_Config::SECTOR->{$ASSIGN}{SQL},$Method_Config::SECTOR->{$ASSIGN}{TAG}; #no limit. it's not such a big list any way
my $sth=$k->dosql($stat);
if ($sth->rows>0) {
	my $list;
	while (my $ref=$sth->fetchrow_hashref) {
		push @{$list->{list}},{
			url=>(sprintf '%s/%i',$Method_Config::SECTOR->{$ASSIGN}{URL},$ref->{id}),
			title=>$ref->{title},
		};
	}
	if ($k->chklogin) {	
		$list->{edit}=sprintf '%s?sel=%i',$Method_Config::ARCHIV->{EDIT},$ASSIGN;
	}
	$list->{PAGE_TITLE}=sprintf '%s | %s',$Method_Config::SECTOR->{$ASSIGN}{H3},$Method_Config::META->{SITE_TITLE};
	$list->{SECT_TITLE}=$Method_Config::SECTOR->{$ASSIGN}{H3};
	$k->output_tmpl($Method_Config::ARCHIV->{SPECIAL_TMPL}{$ASSIGN},$list);
}
else {
	$k->output_tmpl_404;
}
