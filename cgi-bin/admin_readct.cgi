#!/usr/bin/perlml
use strict;
use Storable;
use Data::Dumper;
use lib $ENV{DOCUMENT_ROOT}.'/cgi-bin/';
use Method_Kiyoism_Plus;
use CGI;
my $k=CGI->new;
my $ctfile=$Method_Kiyoism_Plus::POCCHONG->{logfile};

print $k->header;
printf '<!DOCTYPE html>
<html>
<body>
<pre>
';
if (-e $ctfile) {
	open (my $fh, $ctfile);
	# printf "<tr><th>%s</th></tr>\n", (join "</th><th>", @{$Method_Kiyoism_Plus::POCCHONG->{logpile}});
	while (<$fh>) {
		chomp;
		my @line=split /\t/, $_;
		my $ltime=localtime (shift @line);
		printf "# %s\n", $ltime;
		for (my $i=1; $i<scalar @{$Method_Kiyoism_Plus::POCCHONG->{logpile}}; $i++) {
			printf "%s: %s\n", $Method_Kiyoism_Plus::POCCHONG->{logpile}[$i], $line[$i];
		}
	}
}
printf '</pre>
</body>
</html>';