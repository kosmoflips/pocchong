#!/usr/bin/perlml
BEGIN { unshift @INC, $ENV{DOCUMENT_ROOT}.'/cgi-bin/nerv'; }
use strict;
use warnings;

use Storable qw/dclone/;
use Method_Kiyoism;
use Method_Config;
# modified cgi version of rand_notes.pl

my $k=Method_Kiyoism->new;
my $p=$k->get_param(1);

my @strings;
my $total_lines=($p->{lines}?$p->{lines}:3);
if ($p->{strings}=~/[aedg]/i) {
	@strings = split /\0/, $p->{strings};
} else {
	@strings = qw/g d a e/;
}
my $out=randnotes(\@strings,$total_lines,$p->{plain});
$out->{chk_g}=1 if $p->{strings}=~/g/;
$out->{chk_d}=1 if $p->{strings}=~/d/;
$out->{chk_a}=1 if $p->{strings}=~/a/;
$out->{chk_e}=1 if $p->{strings}=~/e/;
$out->{chk_plain}=1 if $p->{plain};
$out->{chk_lines}=$total_lines;
$k->output_tmpl($Method_Config::PATH->{EXEC_TMPL}.'/rand_notes.tmpl',$out);

sub randnotes {
	my ($str,$line,$plain)=@_; #A ref, how many notes
	$line=3 if (!$line or $line!~/^\d+/);
	$line++; #important
	my $notes;
	my $total=0;
	my $range=_rangestock();
	foreach (@$str) {
		push @$notes,@{$range->{lc $_}};
	}
	if ($plain) {
		@$notes=grep {length $_==2} @$notes;
	}
	$total=scalar @{$notes};
	my $out;
	my $measure=0;
# $out->{lines}=[ {measures=>[{notes=>[0 1 2 3]},{notes=>[0 1 2 3]},...]}, ]
	my ($tmp_line,$tmp_measure,$tmp_notes);
	for (1..$line*12) { #12 notes per line
		if ($_%12==0) {
			push @{$out->{lines}},{line_number=>$_/12-1,line=>dclone $tmp_line} if $tmp_line;
			undef $tmp_line;
		}
		if ($measure==4) { #new measure []
			push @{$tmp_measure},{notes=>dclone $tmp_notes};
			undef $tmp_notes;
			$measure=0;
		}
		my $choose=$notes->[(int rand $total)];
		push @$tmp_notes,{note=>$choose};
		if ($_%12==1) { #new line []
			push @$tmp_line, { measures=>dclone $tmp_measure } if $tmp_measure;
			undef $tmp_measure;
		}
		$measure++;
	}
	return $out;
}

sub _rangestock {
	return {
		g=>[qw/g3 a3b a3 b3b b3 c4 c4s d4/],
		d=>[qw/d4 e4b e4 f4 f4s g4 a4b a4/],
		a=>[qw/a4 b4b b4 c5 c5s d5 e5b e5/],
		e=>[qw/e5 f5 f5s g5 a5b a5 b5b b5 c6/], #just an extra for c6!
	};
}