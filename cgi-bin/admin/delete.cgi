#!/usr/bin/perlml
BEGIN { unshift @INC, $ENV{DOCUMENT_ROOT}.'/cgi-bin/nerv'; }
use strict;
use warnings;
use Method_Kiyoism;
# - batch delete "showlist.cgi" checked items.
# - while deleting one single entry, done by edit_xxx.cgi

my $k=Method_Kiyoism->new;
$k->chklogin(1);

my $p=$k->get_param(1);
if ($p->{ids} and $Method_Config::SECTOR->{$p->{sel}}{SQL}) { #only proceed if the sql table is defined
	my @ids=split "\0",$p->{ids};
	foreach my $id (@ids) {
		{ #check pin
			my $pin_id=$k->get1value('select id from passcode where table_id=? and item_id=?',$p->{sel},$id);
			if ($pin_id) {
				$k->dosql('delete from passcode where id=?',$pin_id);
			}
		}
		# if ($p->{sel}==4 or $p->{sel}==5) { # general updates / musik collection
			# $k->dosql('delete from post where id=? and tag=?',$id,$Method_Config::SECTOR->{$p->{sel}}{TAG});
		# }
		if ($p->{sel}==3) { # post , check mygirls.linkage and tag=1
			my ($linked,$error,$linktype);
			#these 2 tables share FK
			if ($linked=$k->get1value('select id from mygirls where post_id=?',$p->{id})) {
				$k->dosql('update mygirls set post_id=? where id=?',undef,$linked);
			} elsif ($linked=$k->get1value('select id from photo where post_id=?',$p->{id})) {
				$k->dosql('update photo set post_id=? where id=?',undef,$linked);
			}
			$k->dosql('delete from post where id=? and tag=1',$id);
		}
		elsif ($p->{sel}==1) { # mygirls, more linkages
			my @stat;
			$stat[0]='delete from mygirls_pcs where title_id=?';
			$stat[1]='delete from mygirls where id=?';
			foreach my $id (@ids) {
				$k->dosql('update mygirls set rep_id=? where id=?',undef,$id);
				my $s0=$k->dosql('select id from mygirls_pcs where title_id=?',$p->{id});
				while (my $r=$s0->fetchrow_hashref) {
					$k->dosql('delete from mygirls_tagged where pcs_id=?',$r->{id});
				}
				foreach my $s (@stat) {
					$k->dosql($s,$id);
				}
			}
		}
		else { # other tables requires only one simple line delete.
			foreach my $id (@ids) {
				my $stat=sprintf 'delete from %s where id=?',$Method_Config::SECTOR->{$p->{sel}}{SQL};
				$k->dosql($stat,$id);
			}
		}
	}
}
$k->redirect('/a/showlist.cgi?sel='.$p->{sel});
