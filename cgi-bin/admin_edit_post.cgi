#!/usr/bin/perlml

use strict;
use warnings;
use Method_Kiyoism_Plus;
my $k=Method_Kiyoism_Plus->new;

$k->chklogin(1);

my $p=$k->param;

my $redirectlist='/a/list_table?sel=post';
if ($p->{opt}) { #submit, delete, preview
	if ($p->{opt} eq 'DELETE' and $p->{id}) {
		my @ids=split "\0",$p->{id};
		foreach my $id (@ids) {
			if (my $linked=$k->getOne('SELECT id FROM mygirls WHERE post_id=?',[$id])) {
				$k->dosql('UPDATE mygirls SET post_id=? where id=?',[undef,$linked]);
			}
			$k->dosql('DELETE FROM post WHERE id=?', [$id]);
		}
		$k->redirect($redirectlist);
	}
	elsif ($p->{opt} eq 'View') {
		$k->redirect(sprintf '%s/%s', $Method_Kiyoism_Plus::POCCHONG->{sql_post_url},$p->{id});
	}
=pod
	elsif ($p->{opt} eq 'Preview') {
		my $preview;
		$preview->{post}=1;
		$preview->{title}=($p->{title}?decode("utf-8",$p->{title}):'No Title');
		$preview->{content}=decode("utf-8",$p->{content});
		$k->output_tmpl($Method_Config::FILE->{PREVIEW_TMPL},$preview);
		exit;
	}
=cut
	elsif ($p->{opt} eq 'Submit') {
		my $pile=[$p->{title},$p->{epoch},$p->{gmt},$p->{content}];
		my $s0='post SET title=?,epoch=?,gmt=?,content=?';
		my $stat;
		my $id=$p->{id}||0;
		if ($p->{update}) {
			$stat=sprintf 'UPDATE %s WHERE id=?',$s0;
			push @$pile, $id;
		} elsif ($p->{insert}) {
			$stat=sprintf 'INSERT INTO %s',$s0;
		}
		$k->dosql($stat,$pile);
		if (!$id) {
			$id=$k->getOne('SELECT id FROM post ORDER BY id DESC LIMIT 1');
		}
		my $rurl=sprintf '%s/?id=%i',$Method_Kiyoism_Plus::POCCHONG->{sql_post_edit},$id;
		$k->redirect($rurl);
	}
	else {
		$k->redirect($redirectlist);
	}
}
elsif ($p->{new} or $p->{id}) { # edit post page
	my $edit;
	if ($p->{id} and $edit=$k->getRow('SELECT * FROM post WHERE id=?', [$p->{id}])) {
		$edit->{content}=$k->htmlentities($edit->{content});
		$edit->{update}=1;
		$edit->{id}=$p->{id};
	} else {
		$edit->{content}='';
		$edit->{gmt}=-7; #default TZ
		$edit->{epoch}=time;
		$edit->{insert}=1;
		$edit->{id}='';
		$edit->{title}='';
	}
	# ---------- HTML begins -----------------
	$k->header;
	$k->print_admin_html;
	printf '<form action="%s" method="post" accept-charset="utf-8" target="">%s', $Method_Kiyoism_Plus::POCCHONG->{sql_post_edit},"\n";
	if ($edit->{update}) {
		printf '<input type="hidden" name="update" value="1" />%s', "\n";
	}
	if ($edit->{insert}) {
		printf '<input type="hidden" name="insert" value="1" />%s', "\n";
	}
	printf "<table>\n";
	my $tbstr=<<STR;
<tr><td><b>id*</b></td><td><input type="text" name="id" maxlength="11" value="%s" readonly /></td></tr>
<tr><td><b>title*</b></td><td><input type="text" name="title" maxlength="255" size="50" value="%s" /></td></tr>
<tr><td><b>epoch*</b></td><td><input type="text" name="epoch" maxlength="12" value="%s" /> %s</td></tr>
<tr><td><b>gmt*</b></td><td><input type="text" name="gmt" maxlength="2" value="%s" /></td></tr>
<tr><td><b>content*</b><br />(max=64K)</td><td><textarea rows="40" cols="80" name="content">%s</textarea><br /></td></tr>
</table>
<!--	<input type="submit" name="opt" value="Preview" onclick="this.form.target='_blank'" />-->
	<input type="reset" value="Reset" onclick="return confirm('reset everything?')" />
	<input type="submit" name="opt" value="Submit" onclick="this.form.target='_self'" />

STR
	printf $tbstr,
		$edit->{id},
		$k->htmlentities($edit->{title}),
		$edit->{epoch}, $k->format_epoch2date($edit->{epoch},$edit->{gmt},5),
		$edit->{gmt},
		$edit->{content};
	if ($edit->{update}) {
		printf '<input type="submit" name="opt" value="View" onclick="this.form.target=\'_blank\'" />%s', "\n";
		printf '<input type="submit" name="opt" value="DELETE" onclick="return confirm(\'DELETE?\')" onclick="this.form.target=\'_self\'" />%s', "\n";
	}
	printf "</form>\n";
	$k->print_admin_html(1);
	# ---------- HTML end -----------------

}
else {
	$k->redirect($redirectlist);
}