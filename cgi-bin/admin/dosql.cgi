#!/usr/bin/perlml
BEGIN { unshift @INC, $ENV{DOCUMENT_ROOT}.'/cgi-bin/nerv'; }
use strict;
use warnings;
use Method_Kiyoism;
use Method_Config;

my $k=Method_Kiyoism->new;
$k->chklogin(1);

my $p=$k->get_param(1);
$k->echo_header;
my $file=$Method_Config::PATH->{HTML_ADMIN}.'/shared_pre_a.tmpl'; #header of html
open (my $fh,$file);
while (<$fh>) { print $_; }
printf '<body><div class="main">';

my @stats=split ";",$p->{'stat'};
foreach my $stat (@stats) {
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

print '</div></body></html>';