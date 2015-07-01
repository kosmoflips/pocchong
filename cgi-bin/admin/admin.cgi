#!/usr/bin/perlml
BEGIN { unshift @INC, $ENV{DOCUMENT_ROOT}.'/cgi-bin/nerv'; }
use strict;
use warnings;
use Method_Kiyoism;
use Method_Config;

my $k=Method_Kiyoism->new;
$k->chklogin(1);
#is redundant but only 2 subs are using them so dont borther to optimise for now
my $session = $k->load_session;
my $timeout=localtime ($session->param('_SESSION_ETIME')+$session->param('_SESSION_CTIME'));
$k->output_tmpl($Method_Config::PATH->{HTML_ADMIN}.'/admin.tmpl',
		{timeout=>$timeout});
# $k->output_tmpl($Method_Config::PATH->{HTML_ADMIN}.'/admin.tmpl',{});