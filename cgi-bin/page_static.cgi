#!/usr/bin/perlml

use strict;
use warnings;
use lib $ENV{DOCUMENT_ROOT}.'/cgi-bin/';
use Method_Kiyoism_Plus;
my $k=Method_Kiyoism_Plus->new;
my $p=$k->param;
my $file=sprintf '%s/%s.html',$Method_Kiyoism_Plus::POCCHONG->{static_path}, $p->{file};
if (!-e $file) {
	$k->redirect('/', 404);
} else {
	open (my $fh, $file); #special format, META comes first
	my $pgtitle='';
	my $extra='';
	my $process=0;
	my $pseek;
	while (<$fh>) {
		if ($process==-1) {
			$pseek=tell $fh;
		}
		elsif ($process==1 and m{^-->}) {
			$process=-1;
		}
		elsif (/^<!--\s*META/) {
			$process=1;
		}
		elsif ($process and m{<title>(.+)</title>}i) {
			$pgtitle=$1;
		}
		elsif ($process and m{<extra>}i) {
			$process=2;
		}
		elsif ($process==2) {
			if (m{</extra>}i) {
				$process=1;
				last;
			}
			$extra.=$_;
		}
	}

	$k->header;
	$k->print_html_head($pgtitle,$extra);
	$k->print_main_wrap(0);
	$k->print_post_wrap();
	printf "<div>\n";
	printf "<h2>%s %s %s</h2>\n", $k->rand_deco_symbol, $pgtitle, $k->rand_deco_symbol;
	seek ($fh, $pseek, 0);
	while (<$fh>) {
		print $_;
	}
	printf "</div>\n";
	$k->print_post_wrap(1);
	$k->print_main_wrap(1);
	$k->print_footer_wrap(0);
	$k->print_footer_wrap(1);
	$k->print_html_tail();
}