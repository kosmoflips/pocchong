#!/usr/bin/perlml

use strict;
use warnings;
use Method_Kiyoism_Plus;
my $k=Method_Kiyoism_Plus->new;

$k->chklogin(1);

my $p=$k->param;
# $k->peek($p);

my $redirectlist='/a/list_table?sel=mygirls';
if ($p->{opt}) { #submit,preview,delete
	my $tmp;
	my @ids=split "\0",$p->{pcs_id};
	foreach my $ii (@ids) {
		push @{$tmp->{stdalone}}, ($p->{'stdalone-'.$ii}?1:0);
	}
	foreach my $key (qw/da_url img_url/) {
		next if !$p->{$key};
		@{$tmp->{$key}}=split "\0",$p->{$key};
	}

	if ($p->{opt} eq 'DELETE' and $p->{id}) {
		my @ids4=split "\0",$p->{id};
		my @stat;
		$stat[0]='DELETE FROM mygirls_pcs WHERE title_id=?';
		$stat[1]='DELETE FROM mygirls WHERE id=?';
		foreach my $id (@ids4) {
			$k->dosql('UPDATE mygirls SET rep_id=? where id=?',[undef,$id]);
			my $s0=$k->getAll('SELECT id FROM mygirls_pcs WHERE title_id=?',[$id]);
			foreach my $rr (@$s0) {
				$k->dosql('DELETE FROM mygirls_tagged WHERE pcs_id=?', [$rr->{id}]);
			}
			foreach my $s (@stat) {
				$k->dosql($s,[$id]);
			}
		}
		$k->redirect($redirectlist);
	}
	elsif ($p->{opt} eq 'View') {
		$k->redirect(sprintf '%s/%s', $Method_Kiyoism_Plus::POCCHONG->{sql_mygirls_url},$p->{id});
	}
	elsif ($p->{opt} eq 'Submit') {
		my ($stat,$rep_id,$title_id);
		my $s0='mygirls SET title=?,epoch=?,gmt=?,post_id=?,notes=?';
		my $pile=[$p->{title},$p->{epoch},$p->{gmt},($p->{post_id}||undef),($p->{notes}||undef)];
		#MG main
		if ($p->{id}) { #update MG except rep_id
			$stat=sprintf 'UPDATE %s WHERE id=?',$s0;
			push @$pile, $p->{id};
			$rep_id=$p->{rep_id};
		}
		else { #insert MG
			$stat=sprintf 'INSERT INTO %s',$s0;
		}
		$k->dosql($stat,$pile);
		$title_id=$p->{id}||$k->getOne('SELECT id FROM mygirls ORDER BY id DESC LIMIT 1');
		#change MG_pcs, including to delete single pieces
		$s0='mygirls_pcs SET title_id=?,stdalone=?,da_url=?,img_url=?';
		for my $i (0..$#ids) {
			next if (length $tmp->{img_url}[$i])<10;
			if ($p->{'del-'.$ids[$i]}) {
				next if $ids[$i]=~/^NEW/;
				$k->dosql('DELETE FROM mygirls_tagged WHERE pcs_id=?',[$ids[$i]]);
				if ($p->{rep_id} and $p->{rep_id}==$ids[$i]) {
					$k->dosql('UPDATE mygirls SET rep_id=? WHERE rep_id=?',[undef,$ids[$i]]);
				}
				$k->dosql('DELETE FROM mygirls_pcs WHERE id=?',[$ids[$i]]);
			}
			else {
				my $gurl=parse_gp_url($tmp->{img_url}[$i]);
				my $pile2=[$title_id,($tmp->{stdalone}[$i]?1:0),$tmp->{da_url}[$i],$gurl];
				my $nh; #new tagged info
				my $idold;
				if ($p->{'namelist-'.$ids[$i]}) {
					my @nids=split "\0",$p->{'namelist-'.$ids[$i]};
					$nh->{$_}=1 foreach (@nids);
				}
				if ($ids[$i]!~/^NEW/) { #update
					$stat=sprintf 'update %s where id=?',$s0;
					push @$pile2, $ids[$i];
					$k->dosql($stat,$pile2);
					$idold=$ids[$i];
					#nametagged
					if ($nh) {
						my $nth=$k->getAll('SELECT id,name_id FROM mygirls_tagged WHERE pcs_id=?',[$ids[$i]]); #current tagged in table
						my $notfound; #in new tag but not in table
						foreach my $r (@$nth) {
							if ($nh->{$r->{name_id}}) {
								delete $nh->{$r->{name_id}};
							} else {
								$notfound->{$r->{name_id}}=$r->{id};
							}
						}
						if (scalar keys %$nh>0) {
							$k->dosql('INSERT INTO mygirls_tagged SET name_id=?,pcs_id=?',[$_,$ids[$i]]) foreach (keys %$nh);
						}
						if (scalar keys %$notfound>0) {
							$k->dosql('DELETE FROM mygirls_tagged WHERE id=?', [$notfound->{$_}]) foreach (keys %$notfound);
						}
					}
				}
				else {
					$stat=sprintf 'INSERT INTO %s',$s0;
					$k->dosql($stat,$pile2);
					$idold=$ids[$i];
					$ids[$i]=$k->getOne('SELECT id FROM mygirls_pcs ORDER BY id DESC LIMIT 1');
					if ($nh) {
						foreach my $ni (keys %$nh) {
							$k->dosql('INSERT INTO mygirls_tagged SET name_id=?,pcs_id=?',[$ni,$ids[$i]]);
						}
					}
				}
				if ($p->{'is_rep-'.$idold}) {
					if (!$rep_id or $ids[$i]!=$rep_id) {
						$k->dosql('UPDATE mygirls SET rep_id=? WHERE id=?', [$ids[$i],$title_id]);
					}
				}
			}
		}
		my $rurl=sprintf '%s/?id=%i',$Method_Kiyoism_Plus::POCCHONG->{sql_mygirls_edit},$title_id;
		$k->redirect($rurl);
	}
	else {
		$k->redirect($redirectlist);
	}
}
elsif ($p->{new} or $p->{id}) { #edit page
	my $new=5;
	my $info;
	my $nameidx=$k->process_nametags;
	if (!$p->{new} and $p->{id} and $info=$k->getRow('SELECT * FROM mygirls WHERE id=?',[$p->{id}])) {
		$info->{id}=$p->{id};
		my $stat='SELECT id,da_url,stdalone,img_url FROM mygirls_pcs WHERE title_id=?';
		if (my $sth=$k->getAll($stat,[$p->{id}])) {
			foreach my $r (@$sth) {
				my $url='';
				my $url_preview='';
				my $is_rep=0;
				if ($r->{img_url}) {
					$url=$k->mk_url_google_img($r->{img_url},'s1024');
					$url_preview=$k->mk_url_google_img($r->{img_url},'s120');
				}
				if ($info->{rep_id} and $info->{rep_id}==$r->{id}) {
					$is_rep=1;
				}
				push @{$info->{list}},{
					is_rep=>$is_rep,
					id=>$r->{id},
					url=>($url||''),
					url_preview=>($url_preview||''),
					da=>($r->{da_url}||''),
					stdalone=>($r->{stdalone}?1:0),
				};
				#name tag area
				#return 'value="" [checked]>name'
				my $name;
				my $nh=$k->getAll('SELECT name_id FROM mygirls_tagged WHERE pcs_id=?',[$r->{id}]);
				foreach my $r2 (@$nh) {
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
	}
	else {
		$info->{id}='';
		$info->{title}='';
		$info->{epoch}=time;
		$info->{gmt}=-7;
		$info->{post_id}='';
		$info->{notes}='';
	}
	#mk new rows
	for my $i (1..$new) {
		push @{$info->{list}},{
			is_rep=>'',
			id=>'NEW'.$i,
			url=>'https://',
			da=>'',
			stdalone=>'',
		};
		foreach my $n (sort {$a cmp $b} keys %$nameidx) {
			push @{$info->{list}[-1]{namelist}}, {
				id=>$info->{list}[-1]{id},
				name=>(sprintf 'value="%i" />%s',$n,$nameidx->{$n})
			};
		}
	}
	# ------------- HTML starts -----------
	$k->header;
	$k->print_admin_html;
	printf '<form action="%s" method="post" accept-charset="utf-8" target="">%s', $Method_Kiyoism_Plus::POCCHONG->{sql_mygirls_edit}, "\n";
	my $tbstr1=<<TABLE1;
<table>
<tr><td><b>id*</b></td><td><input type="text" name="id" maxlength="11" value="%s" readonly></td></tr>
<tr><td><b>title*</b></td><td><input type="text" name="title" maxlength="255" size="50" value="%s"></td></tr>
<tr><td><b>epoch*</b></td><td><input type="text" name="epoch" maxlength="12" value="%s"> %s</td></tr>
<tr><td><b>gmt*</b></td><td><input type="text" name="gmt" maxlength="2" value="%s"></td></tr>
<tr><td><b>rep_id</b></td><td><input type="text" name="rep_id" maxlength="11" value="%s"></td></tr>
<tr><td><b>post_id</b></td><td><input type="text" name="post_id" maxlength="11" value="%s"></td></tr>
<tr><td><b>inspiration</b></td><td><input type="text" name="notes" maxlength="255" size="50" value="%s"></td></tr>
</table>
<hr />
<table>
	<tr>
		<th>info</th>
		<th>preview</th>
		<th>pcs_info</th>
	</tr>
TABLE1
	printf $tbstr1,
		$info->{id},
		$k->htmlentities($info->{title}),
		$info->{epoch}, $k->format_epoch2date($info->{epoch},$info->{gmt},5), $info->{gmt},
		$info->{rep_id}, $info->{post_id},
		$k->htmlentities($info->{notes});

	foreach my $pcs (@{$info->{list}}) {
		my $tbstr2=<<TABLE2A;
<tr>
<td>
<b>id:</b><input type="text" name="pcs_id" maxlength="11" size="3" value="%s" readonly /><br />
<b>std:</b><input type="checkbox" name="stdalone-%s" value="1" %s/><br />
<b>rep:</b><input type="radio" name="is_rep-%s" %s />
</td>
<td style="padding:0 !important;">
<img src="%s">
</td>
<td>
<div style="float:right"><b>DEL:</b><input type="checkbox" name="del-%s" value="1" /></div>
<b>da_id: </b>
http://kosmoflips.deviantart.com/art/<input type="text" name="da_url" maxlength="255" size="50" value="%s" /><br />
<b>img_url: </b><input type="text" name="img_url" maxlength="255" size="120" value="%s" /><br />
<b>namelist: </b><br />

TABLE2A
		printf $tbstr2,
			$pcs->{id}, $pcs->{id}, ($pcs->{stdalone}?'checked':''),
			$pcs->{id}, ($pcs->{is_rep}?'checked':''),
			$pcs->{url_preview},
			$pcs->{id},
			$pcs->{da}, $pcs->{url};
		foreach my $ntg (@{$pcs->{namelist}}) {
		# <input type="checkbox" name="namelist-<TMPL_VAR NAME=id>" <TMPL_VAR NAME="name">
			printf '<input type="checkbox" name="namelist-%s" %s%s',
				$ntg->{id},$ntg->{name},"\n";
		}
		printf "</td>\n</tr>\n";
	}

	print <<TABLE2;
</table>
<!-- <input type="submit" name="opt" value="Preview" onclick="this.form.target='_blank'" /> -->
<input type="reset" value="Reset" onclick="return confirm('reset everything?')" />
<input type="submit" name="opt" value="Submit" onclick="this.form.target='_self'" />

TABLE2
	if (!$p->{new}) {
		printf '<input type="submit" name="opt" value="View" onclick="this.form.target=\'_blank\'" />%s', "\n";
		printf '<input type="submit" name="opt" value="DELETE" onclick="return confirm(\'DELETE?\')" onclick="this.form.target=\'_self\'" />%s', "\n";
	}
	printf "</form>\n";
	$k->print_admin_html(1);
	# ------------- HTML ends -----------

}
else {
	$k->redirect($redirectlist);
}

sub parse_gp_url { #better to separate since it's easier to choose your output img dimension
#old type url: http://lh4.googleusercontent.com/-a-tSvDZLVhU/VndOs2XjNuI/AAAAAAAAi70/8xr7M0mbG8I/s500/ ===> "s500/" can be omitted
	my ($url)=@_;
	$url=~s#   ^https?://   ##xig; #only remove https
	return $url;
}