#!/usr/bin/perlml

use strict;
use warnings;
use Method_Kiyoism_Plus;
my $k=Method_Kiyoism_Plus->new;

$k->chklogin(1);

my $p=$k->param;

$k->header;
$k->print_admin_html();
if ($p->{sql}) {
	print "<pre>\n";
	my @sqls=split ';', $p->{sql};
	foreach my $stat (@sqls) {
		next if $stat!~/\S/;
		my $sth=$k->dosql($stat);
		printf '<b>sql-stat:</b><pre>%s</pre><br />',$stat;
		eval {
			my $fields=$sth->{NAME};#,"<br />";
			if ($fields and @$fields>0) {
				# use Encode; #safer
				print '<table><tr>';
				foreach my $f (@{$fields}) {
					printf '<th>%s</th>',$f;
				}
				print '</tr>';
				while (my $r=$sth->fetchrow_arrayref) {
					print '<tr>';
					for my $i (0..scalar@$r-1) {
						# printf "<td>%s</td>",encode("utf-8",$r->[$i]);
						printf "<td>%s</td>",$r->[$i];
					}
					print "</tr>";
				}
				print '</table><br />';
			}
		};
		if ($@) {
			print "there's no result can be shown for this statement<br />";
		}
		print '<hr />';
	}
}
$k->print_admin_html(1);