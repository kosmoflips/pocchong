#!/usr/bin/perlml

use strict;
use warnings;
use lib $ENV{DOCUMENT_ROOT}.'/cgi-bin/';
use Method_Kiyoism_Plus;
my $k=Method_Kiyoism_Plus->new;

$k->chklogin(1);

my $p=$k->param;

$k->header({'type'=>'text/plain'});
# $k->print_admin_html();
if ($p->{sql}) {
	# print "<pre>\n";
	my @sqls=split ';', $p->{sql};
	foreach my $stat (@sqls) {
		next if $stat!~/\S/;
		my $sth=$k->dosql($stat);
		printf ">>sql-stat: %s\n",$stat;
		eval {
			my $fields=$sth->{NAME};#,"<br />";
			if ($fields and @$fields>0) {
				foreach my $f (@{$fields}) {
					printf "%s\t",$f;
				}
				print "\n";
				while (my $r=$sth->fetchrow_arrayref) {
					for my $i (0..scalar@$r-1) {
						printf "%s\t",($r->[$i]||"NULL");
					}
					print "\n";
				}
			}
		};
		if ($@) {
			print "there's no result can be shown for this statement\n";
		}
		print "\n-----\n";
	}
}
# $k->print_admin_html(1);