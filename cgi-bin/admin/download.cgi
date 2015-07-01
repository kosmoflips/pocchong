#!/usr/bin/perlml
BEGIN { unshift @INC, $ENV{DOCUMENT_ROOT}.'/cgi-bin/nerv'; }
use strict;
use warnings;
use Method_Kiyoism;

my $k=Method_Kiyoism->new;

my $p=$k->get_param(1);
if ($p->{file} and -e $p->{file}) {
	$k->downloadfile($p->{file},$p->{binmode});
}
$k->redirect('/a/admin.cgi');