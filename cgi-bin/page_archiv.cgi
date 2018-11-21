#!/usr/bin/perlml

use strict;
use warnings;
use Encode;
use lib $ENV{DOCUMENT_ROOT}.'/cgi-bin/';
use Method_Kiyoism_Plus;
my $k=Method_Kiyoism_Plus->new;
my $p=$k->param;

my $listall;
my $table='post';
my $yearmode=0;
my $page_title=$Method_Kiyoism_Plus::POCCHONG->{archiv_title};
my $page_max=$Method_Kiyoism_Plus::POCCHONG->{archiv_max};
my $page_turn=$Method_Kiyoism_Plus::POCCHONG->{navi_step};
my $baseurl=$Method_Kiyoism_Plus::POCCHONG->{archiv_url};
my $curr=1; #current page/year
my $navi;

if ($p->{year}) { #year mode, normal year, I don't want to calculate year in 27 H mode
	$curr=$p->{year};
	if ($p->{year}<2006) {
		$curr=2006;
	} elsif ($p->{year}>$k->format_epoch2date(time,-7,2)) {
		$curr=$k->format_epoch2date(time,-7,2);
	}
	use Time::Local;
	my $t0=timelocal(0,0,0,1,0,$curr);
	my $t1=timelocal(0,0,0,1,0,($curr+1));
	if (my $list1=$k->getAll('SELECT id,title,epoch,gmt FROM post WHERE (epoch>=? and epoch<?) ORDER by id', [$t0, $t1])) {
		$yearmode=1;
		$baseurl.='/year';
		$page_title.='::'.$curr;
		$listall->{$curr}=$list1;
		my $ep0=$k->getRow('SELECT epoch,gmt FROM post ORDER BY id LIMIT 1');
		my $ep1=$k->getRow('SELECT epoch,gmt FROM post ORDER BY id DESC LIMIT 1');
		my $page_first=$k->format_epoch2date($ep0->{epoch},$ep0->{gmt},2);
		my $page_last=$k->format_epoch2date($ep1->{epoch},$ep1->{gmt},2);
		$navi=$k->calc_navi_set($page_first,$page_last,$curr,$page_turn);
	}
}
if (!$yearmode) { #process for regular.
	$curr=$k->calc_curr_page_express($table,($p->{page}||1),$page_max);
	my $offset=$k->calc_page_offset($curr,$page_max);
	my $list=$k->getAll('SELECT id,title,epoch,gmt FROM post ORDER BY id DESC LIMIT ?,?', [$offset,$page_max]);
	$navi=$k->calc_navi_set_express($table,$curr,$page_max,$page_turn);
	foreach my $entry (@$list) {
		my $thisyear=$k->format_epoch2date($entry->{epoch},$entry->{gmt},2);
		push @{$listall->{$thisyear}}, $entry;
	}
}

# --------------- HTML BEGIN -------------
$k->header;
$k->print_html_head($page_title);
$k->print_main_wrap();
$k->print_post_wrap();
print_year_h23($k,$curr,0); #page title <h2>
foreach my $loopyear (sort {$b<=>$a} keys %$listall) {
	print_archiv_block();
	if (!$yearmode) {
		print_year_h23($k, $loopyear,1);
	}
	foreach my $entry (@{$listall->{$loopyear}}) {
		print_span_line($k, $entry);
	}
	print_archiv_block(1);
}
$k->print_post_wrap(1);
$k->print_main_wrap(1);
$k->print_footer_wrap(0);
$k->print_navi_bar($navi, $page_turn, $curr, $baseurl);
$k->print_footer_wrap(1);
$k->print_html_tail();
# --------------- HTML END -------------

########## subs ##########
sub print_year_h23 {
	my ($k, $year,$doh3)=@_;
	if (!$doh3) {
		printf "<h2>%s %s %s</h2>\n",$k->rand_deco_symbol,$page_title,$k->rand_deco_symbol;
	} else {
		my $url=sprintf '%s/year/%s',$Method_Kiyoism_Plus::POCCHONG->{archiv_url}, $year;
		printf '<h3><a href="%s">%s %04d %s</a></h3>%s', $url, $k->rand_deco_symbol, $year, $k->rand_deco_symbol, "\n";
	}
}
sub print_archiv_block {
	my ($end)=@_;  #open/close html tags
	if (!$end) {
		printf "<div class=\"archiv\">\n<ul>\n";
	} else {
		printf "</ul>\n</div><!-- archiv -->\n"; #close the last tag
	}
}
sub print_span_line {
	my ($k,$entry)=@_;
	if ($entry) {
		my $date=$k->format_epoch2date($entry->{epoch},$entry->{gmt},1);
		printf '<li><a href="%s/%s"><span class="archivdate">%s</span> %s</a></li>%s',
			$Method_Kiyoism_Plus::POCCHONG->{sql_post_url},
			$entry->{id},
			$date,
			encode('UTF-8',$entry->{title}),
			"\n";
	}
}
