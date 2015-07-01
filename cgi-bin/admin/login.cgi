#!/usr/bin/perlml
BEGIN { unshift @INC, $ENV{DOCUMENT_ROOT}.'/cgi-bin/nerv'; }
use strict;
use warnings;
use Method_Kiyoism;
use Method_Config;

my $k=Method_Kiyoism->new;
my $p=$k->get_param(1);
my $session = $k->load_session;
my ($cookie);
if ($p->{'sessionid'}) {
	$cookie=$p->{'sessionid'};
} else {
	$cookie=$k->bake_cookie({
		'-name' => "sessionid",
		'-value' => $session->id(),
		'-expires' => "+1h",
		#'-path' => $Method_Config::PATH->{SAFE}.'/', #remove this on remote server. keep this on localhost << but i dont know why
		});
}

if ($p->{cmd}) {
	if ($p->{cmd} eq 'logout') {
		$k->logout($session);
	} elsif ($p->{cmd} eq 'login') {
		$k->login($session);
	}
}

if ($session->param('logged-in')) { #go to admin super zone
	my $timeout=localtime ($session->param('_SESSION_ETIME')+$session->param('_SESSION_CTIME'));
	$k->output_tmpl($Method_Config::PATH->{HTML_ADMIN}.'/admin.tmpl',
		{timeout=>$timeout},
		{-cookie => $cookie});
} else { #re-login
	$session->delete;
	$k->output_tmpl($Method_Config::PATH->{HTML_ADMIN}.'/login.tmpl',{});
}
