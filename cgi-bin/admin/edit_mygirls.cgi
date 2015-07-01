#!/usr/bin/perlml
BEGIN { unshift @INC, $ENV{DOCUMENT_ROOT}.'/cgi-bin/nerv'; }
use strict;
use warnings;
use Method_Kiyoism;
use Method_Config;
use Storable qw/dclone/;

my $k=Method_Kiyoism->new;
$k->chklogin(1);
my $SECTOR=1;
my $p=$k->get_param(1);
if ($p->{opt}) { #submit,preview,delete
	my $tmp;
	my @ids=split "\0",$p->{pcs_id};
	foreach my $key (qw/stdalone pix_url da_url img_url/) {
		next if !$p->{$key};
		@{$tmp->{$key}}=split "\0",$p->{$key};
	}
	if ($p->{opt} eq 'Preview') {
		use Encode qw/decode/;
		my $preview;
		$preview->{mygirls}=1;
		$preview->{title}=decode("utf-8",$p->{title});
		$preview->{intro}='DUMMY INTRO';
		for my $i (0..$#ids) {
			next if (length $tmp->{img_url}[$i]<10);
			if ($tmp->{stdalone}[$i]) {
				push @{$preview->{pcs1}}, {url=>'dummy url', img=>$tmp->{img_url}[$i]};
			} else {
				push @{$preview->{pcs2}}, {url=>'', img=>$tmp->{img_url}[$i]};
			}
		}
		$k->output_tmpl($Method_Config::FILE->{PREVIEW_TMPL},$preview);
	}
	elsif ($p->{opt} eq 'DELETE' and $p->{id}) {
		$k->redirect(sprintf '/a/delete.cgi?sel=%d&ids=%s',$SECTOR,$p->{id});
	}
	elsif ($p->{opt} eq 'Submit') {
		my ($stat,$rep_id,$title_id,@pile);
		my $s0='mygirls set title=?,date=?,post_id=?,notes=?';
		@pile=($k->escape_quote($p->{title}),$p->{date},$p->{post_id},$p->{notes});
		$k->mk_null(\@pile);
		#MG main
		if ($p->{id}) { #update MG except rep_id
			$stat=sprintf 'update %s where id=?',$s0;
			$k->dosql($stat,@pile,$p->{id});
			$rep_id=$p->{rep_id};
			$title_id=$p->{id};
		}
		else { #insert MG
			$stat=sprintf 'insert into %s',$s0;
			$k->dosql($stat,@pile);
			$title_id=$k->get1value('select id from mygirls order by id desc limit 1');
		}
		#change MG_pcs, including to delete single pieces
		$s0='mygirls_pcs set title_id=?,stdalone=?,pix_url=?,da_url=?,gp_lh=?,gp_mid=?,gid=?,ext=?';
		for my $i (0..$#ids) {
			next if (length $tmp->{img_url}[$i]<10);
			if ($p->{'del-'.$ids[$i]}) {
				next if $ids[$i]=~/^NEW/;
				$k->dosql('delete from mygirls_tagged where pcs_id=?',$ids[$i]);
				$k->dosql('update mygirls set rep_id=? where rep_id=?',undef,$ids[$i]) if ($p->{rep_id} and $p->{rep_id}==$ids[$i]);
				$k->dosql('delete from mygirls_pcs where id=?',$ids[$i]);
			}
			else {
				my @gurl=$k->parse_gp_url($tmp->{img_url}[$i]);
				@pile=($title_id,$tmp->{stdalone}[$i],$tmp->{pix_url}[$i],$tmp->{da_url}[$i],@gurl);
				$k->mk_null(\@pile);
				my $nh; #new tagged info
				my $idold;
				if ($p->{'namelist-'.$ids[$i]}) {
					my @nids=split "\0",$p->{'namelist-'.$ids[$i]};
					$nh->{$_}=1 foreach (@nids);
				}
				if ($ids[$i]!~/^NEW/) { #update
					$stat=sprintf 'update %s where id=?',$s0;
					$k->dosql($stat,@pile,$ids[$i]);
					$idold=$ids[$i];
					#nametagged
					if ($nh) {
						my $nth=$k->dosql('select id,name_id from mygirls_tagged where pcs_id=?',$ids[$i]); #current tagged in table
						my $notfound; #in new tag but not in table
						while (my $r=$nth->fetchrow_hashref) {
							if ($nh->{$r->{name_id}}) {
								delete $nh->{$r->{name_id}};
							} else {
								$notfound->{$r->{name_id}}=$r->{id};
							}
						}
						if (scalar keys %$nh>0) {
							$k->dosql('insert into mygirls_tagged set name_id=?,pcs_id=?',$_,$ids[$i]) foreach (keys %$nh);
						}
						if (scalar keys %$notfound>0) {
							$k->dosql('delete from mygirls_tagged where id=?',$notfound->{$_}) foreach (keys %$notfound);
						}
					}
				}
				else {
					$stat=sprintf 'insert into %s',$s0;
					$k->dosql($stat,@pile);
					$idold=$ids[$i];
					$ids[$i]=$k->get1value('select id from mygirls_pcs order by id desc limit 1');
					if ($nh) {
						foreach my $ni (keys %$nh) {
							$k->dosql('insert into mygirls_tagged set name_id=?,pcs_id=?',$ni,$ids[$i]);
						}
					}
				}
				if ($p->{'is_rep-'.$idold}) {
					if (!$rep_id or $ids[$i]!=$rep_id) {
						$k->dosql('update mygirls set rep_id=? where id=?',$ids[$i],$title_id);
					}
				}
			}
		}
		$k->rss_update($SECTOR,$title_id) if !$p->{id};
		#pin related
		if ($p->{detele_pin}) {
			$k->update_pin_info($SECTOR,$title_id,0,0,1);
		} elsif ($p->{pin} and $p->{pin_hint}) {
			$k->update_pin_info($SECTOR,$title_id,$p->{pin},$p->{pin_hint});
		}
		$k->redirect(sprintf '%s?id=%i',$Method_Config::SECTOR->{$SECTOR}{EDIT},$title_id);
	}
	else {
		$k->redirect('/a/showlist.cgi?sel='.$SECTOR);
	}
}
else { #edit page
	my $new=10;
	my $info;
	my $nameidx=get_namehash($k);
	if (!$p->{new} and $p->{id} and $k->get1value('select id from mygirls where id=?',$p->{id})) {
		$info=$k->get1row('select title,date,post_id,rep_id,notes from mygirls where id=?',$p->{id});
		$info->{notes}=$k->escape_amp($info->{notes});
		$info->{id}=$p->{id};
		my $stat='select id,pix_url,da_url,stdalone,gp_lh,gp_mid,gid,ext from mygirls_pcs where title_id=?';
		my $sth=$k->dosql($stat,$p->{id});
		if ($sth->rows>0) {
			$new=5; #has stuff added , append 5 new rows in case
			while (my $r=$sth->fetchrow_hashref) {
				my $url=$k->mk_url_google_img($r->{gp_lh},$r->{gp_mid},$r->{gid},$r->{ext},'s288') if $r->{gid};
				my $is_rep; $is_rep=1 if ($info->{rep_id} and $info->{rep_id}==$r->{id});
				push @{$info->{list}},{
					is_rep=>$is_rep,
					id=>$r->{id},
					url=>($url?$url:''),
					pix=>($r->{pix_url}?$r->{pix_url}:''),
					da=>($r->{da_url}?$r->{da_url}:''),
					stdalone=>($r->{stdalone}?1:0),
				};
				#name tag area
				#return 'value="" [checked]>name'
				my $name;
				my $nh=$k->dosql('select name_id from mygirls_tagged where pcs_id=?',$r->{id});
				while (my $r2=$nh->fetchrow_hashref) {
					$name->{$r2->{name_id}}=1;
				}
				foreach my $n (sort {$nameidx->{$a} cmp $nameidx->{$b}} keys %$nameidx) {
					push @{$info->{list}[-1]{namelist}}, {
						name=>(sprintf 'value="%i"%s>%s',$n,($name->{$n}?' checked':''),$nameidx->{$n}),
						id=>$info->{list}[-1]{id},
					};
				}
			}
		}
		if (my $pin=$k->get_pin_info($SECTOR,$p->{id})) {
			$info->{pin_md5}=$pin->{md5hex};
			$info->{pin_hint}=$pin->{hint};
		}
	}
	else {
		$info->{id}='';
		$info->{title}='';
		$info->{date}=$k->epoch2date;
		$info->{post_id}='';
		$info->{notes}='';
		$info->{pin_md5}='';
		$info->{pin_hint}='';
	}
	#mk new rows
	for my $i (1..$new) {
		push @{$info->{list}},{
			is_rep=>'',
			id=>'NEW'.$i,
			url=>'https://',
			pix=>'',
			da=>'',
			stdalone=>'',
		};
		foreach my $n (keys %$nameidx) {
			push @{$info->{list}[-1]{namelist}}, {
				id=>$info->{list}[-1]{id},
				name=>(sprintf 'value="%i">%s',$n,$nameidx->{$n})
			};
		}
	}
	$k->output_tmpl($Method_Config::SECTOR->{$SECTOR}{EDIT_TMPL},$info);
}

sub get_namehash {
	my ($k)=@_;
	my $sth=$k->dosql('select name,id from mygirls_name');
	my $name;
	while (my $n=$sth->fetchrow_hashref) {
		$name->{$n->{id}}=$n->{name};
	}
	return $name;
}