#!/usr/bin/perlml
BEGIN { unshift @INC, $ENV{DOCUMENT_ROOT}.'/cgi-bin/nerv'; }
use strict;
use warnings;
use Method_Kiyoism;
use Method_Config;
use Storable qw/dclone/;

my $k=Method_Kiyoism->new;
$k->chklogin(1);
my $p=$k->get_param(1);
my $SECTOR=3;
my $tag=1;
if (!$p->{tag} or $p->{tag}!~/^\d+$/) {
	#do nothing
} elsif ($p->{tag}==2) {
	$tag=2;
	$SECTOR=4;
} elsif ($p->{tag}==3) {
	$tag=3;
	$SECTOR=5;
}

if ($p->{opt}) { #submit, delete, preview
	if ($p->{opt} eq 'DELETE' and $p->{id}) {
		$k->redirect(sprintf '/a/delete.cgi?sel=%i&ids=%s',$SECTOR,$p->{id});
	}
	elsif ($p->{opt} eq 'Preview') {
		use Encode qw/decode encode/; #i dont know why but you've to decode it first
		my $preview;
		$preview->{post}=1;
		$preview->{title}=($p->{title}?decode("utf-8",$p->{title}):'No Title');
		$preview->{content}=decode("utf-8",$p->{content});
		$k->output_tmpl($Method_Config::FILE->{PREVIEW_TMPL},$preview);
		exit;
	}
	elsif ($p->{opt} eq 'Submit') {
		my @pile=($p->{title},$p->{date},$p->{epoch},$p->{gmt},$p->{content});
		$k->mk_null(\@pile);
		my $s0='post set title=?,date=?,epoch=?,gmt=?,content=?';
		my $stat;
		my $id=$p->{id};
		if ($p->{update}) {
			$stat=sprintf 'update %s where id=?',$s0;
			$k->dosql($stat,@pile,$id);
		} elsif ($p->{insert}) {
			$stat=sprintf 'insert into %s,tag=%i',$s0,$tag;
			$k->dosql($stat,@pile);
			$id=$k->get1value('select id from post where tag=? order by id desc limit 1',$tag);
			$k->rss_update($SECTOR,$id) if $tag!=3;
		}
		#pin related
		if ($p->{detele_pin}) {
			$k->update_pin_info($SECTOR,$id,0,0,1);
		} elsif ($p->{pin} and $p->{pin_hint}) {
			$k->update_pin_info($SECTOR,$id,$p->{pin},$p->{pin_hint});
		}
		$k->redirect(sprintf '%s?id=%i&tag=%i',$Method_Config::SECTOR->{$SECTOR}{EDIT},$id,$tag);
	}
}
else { # edit post page
	my $edit;
	if (!$p->{new} and $p->{id} and $edit=$k->get1row('select title,date,gmt,epoch,content from post where id=? and tag=?',$p->{id},$tag)) {
		$edit->{content}=$k->escape_amp($edit->{content});
		# $edit->{title}=$k->escape_amp($edit->{title});
		$edit->{update}=1;
		$edit->{id}=$p->{id};
		#get pin
		if (my $pin=$k->get_pin_info($SECTOR,$p->{id})) {
			$edit->{pin_md5}=$pin->{md5hex};
			$edit->{pin_hint}=$pin->{hint};
		}
	} else {
		$edit->{content}='';
		$edit->{gmt}=-7; #default TZ
		$edit->{epoch}=time;
		$edit->{insert}=1;
		$edit->{id}='';
		$edit->{title}='';
		$edit->{pin_md5}='';
		$edit->{pin_hint}='';
		$edit->{date}=$k->epoch2date($edit->{epoch}?$edit->{epoch}:time);
	}
	$edit->{edit_title}=$Method_Config::SECTOR->{$SECTOR}{H3};
	$edit->{tag}=$tag;
	$k->output_tmpl($Method_Config::SECTOR->{$SECTOR}{EDIT_TMPL},$edit);
}