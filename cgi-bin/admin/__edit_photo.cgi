#!/usr/bin/perlml
BEGIN { unshift @INC, $ENV{DOCUMENT_ROOT}.'/cgi-bin/nerv'; }
use strict;
use warnings;
use Method_Kiyoism;
use Method_Config;
use Storable qw/dclone/;

my $k=Method_Kiyoism->new;
$k->chklogin(1);
my $SECTOR=2;
my $p=$k->get_param(1);
if ($p->{opt}) { #submit, delete, NO preview
	if ($p->{opt} eq 'DELETE' and $p->{id}) {
		$k->redirect(sprintf '/a/delete.cgi?sel=%i&ids=%s',$SECTOR,$p->{id});
	}
	elsif ($p->{opt} eq 'Submit') {
		if (!$p->{self} or $p->{self}>4) { $p->{self}=1; }
		my $s0='photo set title=?,date=?,date2=?,album_id=?,post_id=?,self=?,gp_lh=?,gp_mid=?,gid=?,ext=?';
		my @gurl=$k->parse_gp_url($p->{img_url}) if $p->{img_url};
		my @pile=($p->{title},$p->{date},$p->{date2},$p->{album_id},$p->{post_id},$p->{self},@gurl);
		$k->mk_null(\@pile);$pile[5]=0 if !$pile[5];
		my $stat;
		if ($p->{update}) {
			$stat=sprintf 'update %s where id=?',$s0;
			$k->dosql($stat,@pile,$p->{id});
		} elsif ($p->{insert}) {
			$stat=sprintf 'insert into %s',$s0;
			$k->dosql($stat,@pile);
			if ($p->{self}<=2) {
				my $id=$k->get1value('select id from photo where self<=2 order by id desc limit 1');
				$k->rss_update(2,$id);
			}
		}
		$k->redirect('/a/showlist.cgi?sel='.$SECTOR);
	}
}
else { # edit page
	my $url='https://';
	my $edit;
	if (!$p->{new} and $p->{id} and $k->get1value('select id from photo where id=?',$p->{id})) {
		my $stat='select title,date,date2,post_id,album_id,gp_lh,gp_mid,gid,ext,self from photo where id=?';
		my $r=$k->get1row($stat,$p->{id});
		if ($r->{gid}) { $url=$k->mk_url_google_img($r->{gp_lh},$r->{gp_mid},$r->{gid},$r->{ext}); }
		$edit->{title}=$k->escape_amp($r->{title});
		$edit->{album_id}=$r->{album_id};
		$edit->{date}=$r->{date};
		$edit->{date2}=($r->{date2}?$r->{date2}:'');
		$edit->{post_id}=($r->{post_id}?$r->{post_id}:'');
		$edit->{self}=$r->{self};
		$edit->{update}=1;
		$edit->{id}=$p->{id};
	}
	else {
		$edit->{album_id}='';
		$edit->{post_id}='';
		$edit->{date2}='';
		$edit->{self}=1;
		$edit->{insert}=1;
		$edit->{id}='';
		$edit->{title}='';
		$edit->{date}=$k->epoch2date(time);
	}
	$edit->{img_url}=$url;

	$k->output_tmpl($Method_Config::SECTOR->{$SECTOR}{EDIT_TMPL},$edit);
}