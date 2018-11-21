#!/usr/bin/perlml

use strict;
use warnings;

use Storable qw/:DEFAULT dclone/;

use strict;
use warnings;

# by kiyo @ http://www.pocchong.de
# created: 2015-03-19
# update:
# 15-03-23 alpha support
# 15-04-09 html print bug fix + cgi version
# 16-07-08 continues display
# 16-10-29 2D gradient
# 17-03-17 hover to see colour code

use lib $ENV{DOCUMENT_ROOT}.'/cgi-bin/';
use Method_Kiyoism_Plus;
my $k=Method_Kiyoism_Plus->new;
my $p=$k->param;

# inspired by w3school's colour mixer: www.w3schools.com/tags/ref_colormixer.asp
# but is more focusing on my own demands: when I colour/gradient code much text, the <span> tags are ready to use
# and I don't like those online rainbow text generators, they can't either process utf8 , or split all my text to single letters => a big mess

my $zero; my $xx; my $yy; my $opt;
if (!$p->{RESET}) { #presets
	if (!$p->{lv} or $p->{lv}!~/^\d+$/) { $opt->{lv}=0; }
	elsif ($p->{lv}>254) { $opt->{lv}=254; }
	else { $opt->{lv}=$p->{lv}; }
	$opt->{lv}++;

	$zero=unify_colour($p->{zero});
	if ($p->{bg}) { $opt->{bg}=unify_colour($p->{bg}); }
	if ($p->{alfa} and
			$p->{alfa}=~/^\d+\.?\d+?/ and
			$p->{alfa}<1 and
			$p->{alfa}>0) {
		$opt->{alfa}=sprintf '%.2f',$p->{alfa};
		$opt->{alfa}=~s/0+$//g;
		$opt->{alfa}=~s/\.$//g;
	}

	foreach my $x0 (split /\s+/, $p->{tbx}) { push @$xx, unify_colour($x0); }
	foreach my $y0 (split /\s+/, $p->{tby}) { push @$yy, unify_colour($y0); }
} else {
	$opt->{lv}=1;
}
my $oversize;
{
	my $limit=1024*1024*3; #MB , limit output html page ~1MB , if bigger, zip and ask dl.
	my $size_x=$xx?((scalar @{$xx})*$opt->{lv}+(scalar @{$xx})+1):1;
	my $size_y=$yy?((scalar @{$yy})*$opt->{lv}+(scalar @{$yy})+1):1;
	my $size=$size_x * $size_y *40; # about 40 byte to show 1 colour <tb>
	if ($size>$limit) { $oversize = 1; }
}

# -------- html start ----------
$k->header;

print_html_head_cgi_ver();
if (($xx or $yy) and !$oversize) {
print_html_head($opt);
{ #1st row, has additional header row for code names
	my $row=mk_row($zero,$zero, $xx, $opt->{lv});
	print_html_tr($row,1); #1 for firstrow
}
{#2nd row and following
	my $row_begin=$zero;
	foreach my $this_y (@$yy) {
		my $step_y=calc_step($row_begin, $this_y, $opt->{lv});
		# $k->peek($row_begin,$step_y,$this_y);exit;
		if ($step_y) {
			for my $i (1..$opt->{lv}) { #loop all mixed b/w y0~y1
				my $labeltxt;
				if ($i==$opt->{lv}) {
					$row_begin=$this_y;
					$row_begin->[3]=1; #ref mark
					$labeltxt=1;
				} else {
					$row_begin=mix_colour($row_begin,$step_y);
				}
				my $row=mk_row($row_begin, $zero,$xx, $opt->{lv});
				print_html_tr($row);
			}
		} else {
			$row_begin=$this_y;
			$row_begin->[3]=1; #ref mark
			my $row=mk_row($row_begin, $zero,$xx, $opt->{lv});
			print_html_tr($row);
		}
	}
}
print_html_end();
}
elsif ($oversize) {
	# printf "<a href=\"https://gist.github.com/kosmoflips/3faf44f6a2ac4e5efc5a#file-exec_gradient_maker-pl\" target=\"_blank\">the resulting table may be too big, do it locally instead!</a>";
	printf "<div style=\"color: red\">too many input colours/high mixing level, the resulting html will be too big to load in ease. reduce inputs and try again!</div>\n";
}
print_html_end_cgi_ver($k);

# -------- html end ----------


# -------- subs ----------

sub print_html_head_cgi_ver {
printf '<!DOCTYPE html>
<html>
<head>
<title>～彩～Gradient Maker | Pocchong.de</title>
<style>
	body {
		font-family: "Lucida Console", Monaco, monospace;
	}
	table {
		border-collapse: collapse;
		border: 1px solid #000;
	}
	td {
		width:20px;
		height:20px;
/*		text-align: center;*/
		font-size: 85%%;
	}
	td.rotate {
		padding-top:37px;
		height: 35px;
	}
	td.rotate > div {
		transform: rotate(270deg);
		width: 20px;
	}
	.refcol {
		font-weight:bold;
		text-decoration: underline;
	}
	.hover-toggle .normal-hidden {
		display:none;
		text-align:center;
		padding: 10px;
		border: #fff 1px solid;
	}
	.hover-toggle:hover .normal-hidden {
		display:block;
	}
</style>

<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.1.1.min.js"></script>
<script src="/elem/js/spectrum/spectrum.js"></script>
<script src="/elem/js/spectrum/setting.js"></script>
<link rel="stylesheet" type="text/css" href="/elem/js/spectrum/spectrum.css">

</head>

<body>
<h1>～彩～Gradient Maker</h1>

<hr />

<div>inspired by w3school\'s <a href="https://www.w3schools.com/colors/colors_mixer.asp">colour mixer</a>, mixing 2 sets of colours into a 2D table.</div>

<form action="/exec/gradient_maker" method="post">
<ul>';
printf '
	<li>starting colour <input type="text" maxlength="15" size="10" value="%s" name="zero"></li>
	<li>alpha (0~1): <input type="text" maxlength="4" size="3" value="%s" name="alfa"></li>
	<li>mix level: <input type="text" maxlength="3" size="3" value="%s" name="lv"> (0~254)</li>
	<li>background: <input type="text" maxlength="15" size="8" value="%s" name="bg"></li>',
		($zero?print_colour_code($zero):'#FFFFFF'),
		($opt->{alfa}||1),
		(defined $opt->{lv}?(($opt->{lv}-1)||3):0),
		($opt->{bg}?print_colour_code($opt->{bg}):'#FFFFFF');
printf '	<li><i>acceptable: HEX, HEX 3-letter, RGB, separate by whitespaces</i></li>';
printf '	<li>x division: <input type="text" size="100" value="%s" name="tbx" /></li>', ($p->{RESET}?'':($p->{tbx}||'C98EFF 467BDF'));
printf '	<li>y division: <input type="text" size="100" value="%s" name="tby" /></li>', ($p->{RESET}?'':($p->{tby}||'E6FF3A 000'));
printf '	<li>Find a colour <input id="full" /> <span id="basic-log" style="color: red"></span></li>
</ul>
<input type="submit" name="" value="Mix" onclick="this.form.target=\'_self\'" />
<input type="submit" name="RESET" value="RESET" onclick="this.form.target=\'_self\'" />
</form>
';
1;
}
sub print_html_head {
my ($opt)=@_;
=pod
printf <<HTML;
<!doctype html>
<html>
<head>
<style>
	body {
		font-family: \"Lucida Console\", Monaco, monospace;
	}
	table {
		border-collapse: collapse;
		border: 1px solid #000;
	}
	td {
		width:20px;
		height:20px;
		text-align: center;
		font-size: 85%%;
	}
	td.rotate {
		padding-top:37px;
		height: 35px;
	}
	td.rotate > div {
		transform: rotate(270deg);
		width: 20px;
	}
	.refcol {
		font-weight:bold;
		text-decoration: underline;
	}
</style>
</head>
<body>
HTML
=cut
printf "<hr />\n";
printf "<div>hover on a cell to see colour code</div>\n";
if ($opt->{bg}) {
	printf '<div style="display: inline-block; padding: 30px; background: %s">%s',print_colour_code($opt->{bg}),"\n";
} else {
	printf "<div>\n";
}
if ($opt->{alfa} and $opt->{alfa}<1) {
	printf '<table style="opacity: %.1f">%s',$opt->{alfa},"\n";
} else {
	printf "<table>\n";
}
}
sub print_html_end_cgi_ver {
	my ($k)=@_;
	printf "<hr />\n";
	printf "<div><a href=\"/\">pocchong.de</a>\n"; #for closing #outlayer in below "include"
	$k->print_html_tail;
}
sub print_html_end {
printf <<HTML;
</table>
</div>
HTML
}
sub mk_row {
	my ($begin,$z,$x,$lv)=@_; #A ref, A ref, A ref, INT
	my $row;
	my $last_x=$z;
	my $last_col=$begin;
	push @$row,$begin;
	my $samerow=print_colour_code($z) eq print_colour_code($begin)?1:0;
	foreach my $this_x (@{$x}) {
		if ($lv>1) {
			my $step_x=calc_step($last_x, $this_x, $lv);
			for (1..$lv) {
				my $last_col2=mix_colour($last_col,$step_x);
				push @$row, $last_col2;
				$last_col=$last_col2;
			}
			if ($samerow) {
				$row->[-1]=$this_x;
				$row->[-1][3]=1; #ref mark
			}
		} else {
			$last_col=$this_x;
			$last_col->[3]=1;
			push @$row, $last_col;
		}
		$last_x=$this_x;
	}
	return $row;
}
sub print_html_tr {
	my ($row,$firstrow)=@_;
	if ($firstrow) { #need top row for code name;
		printf "<tr>\n\t";
		printf "<td></td>";
		foreach my $i (0..(@$row-1)) {
			my $cc=$row->[$i];
			if (!ref $cc) {
				printf '<td>%s</td>', ($cc?'x':'');
			} else {
				print_html_td($cc,-1);
			}
		}
		printf "\n</tr>\n";
	}
	printf "<tr>\n\t";
	print_html_td($row->[0],1); #1st cell in the row, display code.
	foreach my $i (1..(@$row-1)) {
		my $cc=$row->[$i];
		if (!ref $cc) {
			printf '<td>%s</td>', ($cc?'x':'');
		} else {
			print_html_td($cc);
		}
	}
	printf "\n</tr>\n";
	1;
}
sub print_html_td {
	my ($col,$showcode)=@_; #showcode=0, none; =1, additional <td>. =-1, code only
	$showcode=0 if !$showcode;
	my $invert=calc_display_colour($col);
	my $showcol=sprintf ' style="color:#ffffff;"';
	my $txt=print_colour_code($col);
	if ($showcode and $col->[3]) {
		$txt=sprintf '<span class="refcol">%s</span>', $txt;
	}
	my $sline=sprintf '<td %sstyle="background: %s"><div%s>%s</div></td>',
		($showcode==-1?'class="rotate"':''),
		print_colour_code($col),
		($invert?$showcol:''),
		$txt;
	my $cline=sprintf '<td class="hover-toggle" style="background: %s"><span class="normal-hidden"%s>%s<br />%s</span></td>',
		print_colour_code($col),
		($invert?' style="color:#fff"':''),
		print_colour_code($col),
		print_colour_code($col,1);
	if ($showcode==1) {
		printf "%s%s", $sline, $cline;
	}
	elsif ($showcode==-1) {
		printf "%s", $sline;
	} else {
		printf "%s", $cline;
	}
	1;
}
sub print_colour_code {
	my ($col,$rgb,$chkdisplay)=@_;
	my $col2='';
	if ($rgb) {
		$col2=sprintf "rgb(%.f,%.f,%.f)", $col->[0],$col->[1],$col->[2];
	} else {
		$col2=uc (sprintf "#%02x%02x%02x", $col->[0],$col->[1],$col->[2]);
	}
	return $col2;
}
sub calc_display_colour { #default = #fff, if bg colour all <125, use #fff
	my ($bg)=@_;
	my $low=0;
	for my $i (0..2) {
		$low++ if $bg->[$i]<125;
	}
	if ($low>=2) {
		return 1; #use white txt
	} else {
		return 0; #use dark txt
	}
}
sub invert_colour {
	my ($col)=@_;
	my $c2;
	for my $i (0..2) {
		$c2->[$i]=abs (255-$col->[$i]);
	}
	return $c2;
}
sub unify_colour { # in=string, output=A ref, R,G,B as 0..255
	my ($colour)=@_;
	my $cv;
	my $is_hex;
	if (!$colour) {
		$cv=[255,255,255];
	}
	elsif ($colour=~/(\d+)\s*,\s*(\d+)\s*,\s*(\d+)/) { #treat as RGB
		($cv->[0],$cv->[1],$cv->[2])=($1,$2,$3);
		for my $i (0..2) {
			$cv->[$i]=255 if $cv->[$i]>255;
		}
	}
	elsif ($colour=~/([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})/i) { #treat as HEX, complete
		($cv->[0],$cv->[1],$cv->[2])=($1,$2,$3);
		$is_hex=1;
	}
	elsif ($colour=~/[0-9a-f]{3}/i) { #treat as HEX, 3-letter
		$is_hex=1;
		my $x0=$&;
		for my $i (0..2) {
			my $xx=substr $x0, $i,1;
			$cv->[$i]=$xx x 2;
		}
	}
	# print Dumper $colour;<>;
	$cv=chk_colour($cv,$is_hex);
	return $cv;
}
sub chk_colour { #in=A ref, out=A ref b/w 0~255
	my ($c2,$is_hex)=@_;
	if (!$c2) {
		$c2=[255,255,255];
	} else {
	# print Dumper $c2 if $stop;
		for my $i (0..2) {
			if ($is_hex) {
				$c2->[$i]=hex($c2->[$i]);
			}
			if ($c2->[$i]<0) {
				$c2->[$i]=0;
			} elsif ($c2->[$i]>255) {
				$c2->[$i]=255;
			}
		}
	}
	# die Dumper $c2 if $stop;
	return $c2;
}
sub calc_step { #give 2 colours and lv between, calculate [step]
	my ($c1,$c2,$lv)=@_; #A ref, A ref, INT
	# die Dumper \@_;
	my $dif;
	if ($lv>1) {
		for my $i (0..2) {
			$dif->[$i]=sprintf "%1.2f", (($c2->[$i]-$c1->[$i])/$lv);
		}
	}
	return $dif;
}
sub mix_colour { #give starting colour and step, output new colour
	my ($ori,$step)=@_; #both A ref
	# die Dumper \@_;
	my $c2;
	if ($step) {
		for my $i (0..2) {
			$c2->[$i]=$ori->[$i]+$step->[$i];
		}
	# die Dumper chk_colour($c2);
		return chk_colour($c2);
	} else {
		return undef;
	}
}
