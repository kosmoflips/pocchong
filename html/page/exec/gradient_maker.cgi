#!/usr/bin/perlml
BEGIN { unshift @INC, $ENV{DOCUMENT_ROOT}.'/cgi-bin/nerv'; }
use strict;
use warnings;

use Storable qw/dclone/;

# by kiyo @ http://www.pocchong.de
# created: 2015-03-19
# update:
# 15-03-23 alpha support
# 15-04-09 html print bug fix + cgi version

use Method_Kiyoism;
my $k=Method_Kiyoism->new;
my $p=$k->get_param(1);

my ($out,$grad);
$out->{colours}=($p->{colours}=~/\S/?$p->{colours}:'6ED4F7 80E64C FFCF0A FFA8FF'); #preset
if (!$p->{grad}) {
	$p->{grad}=-1;
} elsif ($p->{grad}!~/^\d+$/ or $p->{grad}<1 or $p->{grad}>99) {
	$p->{grad}=5;
}
$grad=$p->{grad}+1;
$out->{grad}=$p->{grad} if $p->{grad}>=0; #required but i forgot why

if ($p->{alfa} and $p->{alfa}=~/^\d+\.?\d+?$/ and $p->{alfa}<1 and $p->{alfa}>0) {
	$out->{alfa}=sprintf '%.1f',$p->{alfa};
} else {
	$out->{alfa}=1;
}
if ($p->{bg}) {
	$out->{bg}=prt_colour(mk_colour($p->{bg}));
} else {
	$out->{bg}='#FFFFFF';
}

#main
my $list;
my @colours=split /\s+/,$out->{colours};
if ($out->{grad} and @colours>=2) { #make gradient
	$list=mk_grad(\@colours,$grad);
} else { #simply do colour code converts
	$list=[[map {mk_colour($_)} @colours]];
}
my $i=1;
foreach my $l (@$list) {
	my $tmp;
	$tmp->{setnumber}=$i;
	if ($p->{bg} ne '#FFFFFF') {
		$tmp->{setbg}=sprintf ' style="background:%s;"',prt_colour(mk_colour($p->{bg}),0);
	}
	foreach my $c (@$l) {
		push @{$tmp->{corset}},{"hex"=>prt_colour($c,0),"rgba"=>prt_colour($c,1,$out->{alfa})};
	}
	push @{$out->{sets}},dclone $tmp;
	$i++;
}
$k->output_tmpl($Method_Config::PATH->{EXEC_TMPL}.'/gradient_maker.tmpl',$out);

##### subs #########
sub prt_colour { #input processed 3-elem A ref. output in : $r rgb, !$r, hex
	my ($triple,$r,$a)=@_;
	if ($r) {
		if ($a and $a<1) {
			return sprintf 'rgba(%d,%d,%d,%.1f)',@$triple,$a;
		} else {
			return sprintf 'rgb(%d,%d,%d)',@$triple;
		}
	} else {
		return uc sprintf '#%02x%02x%02x', @$triple;
	}
}
sub mk_colour { #input code string, return A ref. [R,G,B] 0-255 values
	my ($c)=@_;
	my $triple;
	#guess input format, may not be perfect
	if ($c) {
		if ($c=~/,/) {
			@$triple=split /\s*,\s*/,$c;
		} else {
			$c=~s/^\s*#|\s+$//g;
			if (length $c==3) {
				for my $i (0..2) {
					push @$triple,(hex ((substr $c,$i,1) x 2));
				}
			} elsif (length $c==6) {
				foreach my $i (0,2,4) {
					push @$triple, (hex (substr $c,$i,2));
				}
			}
		}
	}
	#error in making colour, return "white"
	if (!$triple or @$triple!=3) {
		$triple=[qw/255 255 255/];
	} else {
		foreach my $v (@$triple) {
			$v=_verify_colour($v);
		}
	}
	return $triple;
}
sub _verify_colour { #input RGB values
	my $value=shift;
	if ($value!~/^\d+$/ or $value>255) { return 255; }
	else { return $value; }
}
sub mk_grad { #make gradient among >=2 colours. return [ [list of cols] , [list2...] ]
	my ($clist,$part)=@_; #A ref. scalar flag
	my $grad;
	for my $i (1..(scalar @$clist-1)) {
		push @$grad,dclone (mk_grad2($clist->[$i-1],$clist->[$i],$part));
	}
	return $grad;
}
sub prt_list1 { #print one list of colours to stdout
	my ($lv,$a)=@_; #lv is output of &mk_grad2
	foreach my $c (@$lv) {
		printf "%s => %s\n",prt_colour($c,0,$a),prt_colour($c,1,$a); #hex to rgb
	}
	print "\n";
	1;
}
sub mk_grad2 {#make gradient b/w two colours. input: colour1, c2, rgb_flag // start -9levels- end.
	my ($c01,$c02,$part)=@_;
	$part=20 if !$part; #start + mids + end = $part+1
	my $c1=mk_colour($c01);
	my $c2=mk_colour($c02);
	my $lv; #xx+2-elem. first/last are c1,c2. others are the xx-levels. each elem is a 3-elem colour A ref => [[255,255,255],[125,125,125],[...]...]
	push @$lv,dclone $c1;
	for my $i (0..2) {
		my $diff=($c2->[$i] - $c1->[$i])/$part;
		my $tmp=$c1->[$i]; #initialise
		for my $j (1..$part-1) {
			$tmp+=$diff;
			$lv->[$j][$i]=sprintf "%.f", $tmp;
		}
	}
	push @$lv,dclone $c2;
	return $lv;
}
