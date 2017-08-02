#!/usr/bin/perlml

use strict;
use warnings;
use Method_Kiyoism_Plus;
use utf8;
my $k=Method_Kiyoism_Plus->new;
$k->chklogin(1);

my $p=$k->param;

my ($table, $viewbase, $editbase);
if ($p->{sel} and $p->{sel}=~/mygirls/i) {
	$table='mygirls';
	$viewbase=$Method_Kiyoism_Plus::POCCHONG->{sql_mygirls_url};
	$editbase=$Method_Kiyoism_Plus::POCCHONG->{sql_mygirls_edit};
} else {
	$table='post';
	$viewbase=$Method_Kiyoism_Plus::POCCHONG->{sql_post_url};
	$editbase=$Method_Kiyoism_Plus::POCCHONG->{sql_post_edit};
}
my $curr=$k->calc_curr_page_express($table, ($p->{page}||1), $Method_Kiyoism_Plus::POCCHONG->{admin_list_max});
my $offset=$k->calc_page_offset_express($table,$Method_Kiyoism_Plus::POCCHONG->{admin_list_max},$curr);
my $query=sprintf 'SELECT id,title,epoch,gmt FROM %s ORDER BY id DESC LIMIT ?,?', $table;
my $lists=$k->getAll($query, [$offset, $Method_Kiyoism_Plus::POCCHONG->{admin_list_max}]);
my $navi=$k->calc_navi_set_express($table,$curr,$Method_Kiyoism_Plus::POCCHONG->{admin_list_max},$Method_Kiyoism_Plus::POCCHONG->{navi_step});
my $actionurl=sprintf '/a/edit_%s', $table;
my $selurl=sprintf '/a/list_table/%s/page', $table,

# ----- HTML --------------
$k->header;
$k->print_admin_html();
printf '<div><a href="%s/?new=1">Create New</a></div>%s', $editbase,"\n";
$k->print_navi_bar($navi, $Method_Kiyoism_Plus::POCCHONG->{navi_step}, $curr, $selurl);
print <<TABLE1;
<form action="$actionurl" method="post" accept-charset="utf-8" >
<input type="hidden" name="list_view_chk" value="1" />
<table>

TABLE1
	foreach my $tt (qw/del id date title edit/) {
		printf "<th>%s</th>\n", $tt;
	}
	foreach my $entry (@$lists) {
		my $viewurl=sprintf '%s/%s', $viewbase, $entry->{id};
		my $editurl=sprintf '%s/?id=%s', $editbase, $entry->{id};
		printf "<tr>\n";
		printf '<td><input type="checkbox" name="id" value="%s" /></td>%s', $entry->{id}, "\n";
		printf "<td>%s</td>\n", $entry->{id};
		printf "<td>%s</td>\n", $k->format_epoch2date($entry->{epoch},$entry->{gmt},3);
		printf '<td><a href="%s" target="_blank">%s</a></td>%s', $viewurl, $k->htmlentities($entry->{title}), "\n";
		# printf '<td><a href="%s" target="_blank">%s</a></td>%s', $viewurl, $entry->{title}, "\n";
		printf '<td><a href="%s">edit</a></td>%s', $editurl, "\n";
		printf "</tr>\n";
	}
	print <<TABLE2;
</table>
<input type="submit" name=opt value="DELETE" onclick="return confirm('delete selected?')">
</form>

TABLE2

$k->print_admin_html(1);