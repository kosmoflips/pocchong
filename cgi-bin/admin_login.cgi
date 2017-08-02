#!/usr/bin/perlml

use strict;
use warnings;
use Method_Kiyoism_Plus;
my $k=Method_Kiyoism_Plus->new;

my $error;
my $p=$k->param;

my $trylogin=0;
if ($p->{logout}) { # log out
	push @$error,'log out successful';
	logout($k);
}
elsif ($p->{login}) {
	if (!login($k,$p->{user},$p->{pw})) {
		push @$error,'wrong login info';
	}
}


#---- html-------
$k->header;
$k->print_admin_html;
if (!$k->chklogin) {
	printf ('<div style="text-align: center">%s', "\n");
	print_errors($error);
	print_login_form();
	print "</div>\n";
} else {
	my $timeout=localtime ($k->{SESSION}->param('_SESSION_ATIME')+$Method_Kiyoism_Plus::POCCHONG->{timeout});
	printf "session timeout: %s<br />", $timeout;
	$k->include($ENV{DOCUMENT_ROOT}.'/cgi-bin/admin_part_panel.html');
}
# $k->peek($k);
$k->print_admin_html(1);


############ subs ######

sub print_errors {
	my $error=shift;
	if (!$error) {
		return 0;
	}
	printf ("<div>\n");
	foreach my $line (@$error) {
		printf ("%s<br />\n", $line);
	}
	printf ("</div>\n");
}
sub print_login_form {
	my $actionurl='/cgi-bin/admin_login.cgi';
	$k->include($ENV{DOCUMENT_ROOT}.'/cgi-bin/admin_part_loginform.html');
}
sub login {
	my ($k, $usr, $pw)=@_;
	if ($usr) {
		$usr=lc $usr;
		my $admin_info={
			kiyoko=>'c8fa915c31b0d3b2e7780e64e9d98c3f', #MD5 with VANILLA
		};
		if ($admin_info->{$usr}) {
			my $VANILLA='silent hill+biohazard+siren';
			use Digest::MD5;
			my $teststr=Digest::MD5->new;
			$teststr->add($pw.$VANILLA);
			if ($admin_info->{$usr} eq $teststr->hexdigest) { #good match
				$k->{SESSION}->param(-name=>'POCCHONG_LOGIN_TOKEN', -value=>1);
				$k->{SESSION}->expire(POCCHONG_LOGIN_TOKEN => $Method_Kiyoism_Plus::POCCHONG->{timeout} );
				return 1;
			}
		}
	}
	return 0;
}
sub logout {
	my $k=shift;
	if ($k->{SESSION}) {
		$k->{SESSION}->clear(['POCCHONG_LOGIN_TOKEN']);
	}
	$k->redirect('/cgi-bin/admin_core.cgi?cleandir=1');
}
