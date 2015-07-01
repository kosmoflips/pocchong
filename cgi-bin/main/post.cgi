#!/usr/bin/perlml
BEGIN { unshift @INC, $ENV{DOCUMENT_ROOT}.'/cgi-bin/nerv'; }
use strict;
use warnings;
use Method_Kiyoism;
# use Method_Config;
use Method_SQLBranch;

#not like others, no year mode here as it eats RAM and worth nothing
#available params: id > offset,[limit]
#tag=1, table=post
my $k=Method_Kiyoism->new;
my $p=$k->get_param;
$p->{tag}=1;
process_param_post($p);