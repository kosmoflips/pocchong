#!/usr/bin/perl
use strict;
print "Content-type: text/html\n\n";
my $file='_ct.txt';
open (my $fh,$file) or die "file not exist";
my $num=<$fh>;
my @stat = stat($file);
my $time=localtime $stat[9];
printf '<!DOCTYPE html>
<html><body>%s @ %s</body></html>', $num,$time;