#!/usr/bin/perlml

# easier to do this with perl

use strict;
use warnings;

use DBI;
use CGI qw(-utf8); #http://perldoc.perl.org/CGI.html
use CGI::Carp qw/fatalsToBrowser warningsToBrowser/;

my $k;
$k->{DBH}=undef;
$k->{CGI}=new CGI;

my $http;
$http->{'-status'}='200 OK';
$http->{'-charset'}='utf-8';
$http->{'-type'}='text/plain';
print $k->{CGI}->header($http);


my $p=$k->{CGI}->Vars();
if ($p->{sql}) {
	my $dbf=$ENV{DOCUMENT_ROOT}.'/nerv/pocchong_data.sqlite';
	if (!-e $dbf or -z $dbf) {
		die "db file doesn't exist or empty";
	}
	$k->{DBH}=DBI->connect( "dbi:SQLite:dbname=".$dbf,"","", {
			RaiseError     => 1,
			sqlite_unicode => 1, # MUST!
		} ) or die "can't connect to db file ".$dbf;

	my @sqls=split /;/, $p->{sql};
	foreach my $stat (@sqls) {
		next if $stat!~/\S/;
		next if $stat=~/^#/;
		my $sth=dosql($k,$stat);
		printf "%s\n\n>>query: %s\n%s\n\n", ('=' x 70), $stat, ('=' x 70);
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
		print "\n\n\n";
	}
}

# ---------------------------
sub dosql { #do my sql cmd's
	my ($k,$stat,$vars)=@_; # 'select * from table where id=?', [$var1,$var2...]
	my $sth = $k->{DBH}->prepare($stat) or die "can't prepare \"$stat\"", $k->{DBH}->errstr;
	$sth->execute(@$vars) or die "can not execute \"$stat\"", $sth->errstr;
	return $sth;
}
