#!/usr/bin/perlml

use strict;
use warnings;
use File::Path qw/rmtree/;

my $dir=$ARGV[0];
if (-d $dir) {
rmtree($dir,{keep_root=>1});
}