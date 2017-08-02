#!/usr/bin/perlml

use strict;
use warnings;
use Method_Kiyoism_Plus;
my $k=Method_Kiyoism_Plus->new;
my $p=$k->param;

my $mode=1; #show all.  =2, single id , =3 tag
my $max_in_mini_gallery=4;
my $page_title=$Method_Kiyoism_Plus::POCCHONG->{sql_mygirls_title};
my $baseurl=$Method_Kiyoism_Plus::POCCHONG->{sql_mygirls_url};
my $page_max=$Method_Kiyoism_Plus::POCCHONG->{sql_mygirls_max};
my $page_turn=$Method_Kiyoism_Plus::POCCHONG->{navi_step};

my $posts;
my $table='mygirls';
my $navi;
my $curr=0;

if ($p->{id}) {
	if (my $entry1=$k->getRow('SELECT * FROM mygirls WHERE id=?',[$p->{id}])) {
		$mode=2;
		$page_title=$entry1->{title};
		push @$posts, $entry1;
		$navi->{next}{info}=$k->getNext($table,$posts->[0]{id},0);
		$navi->{prev}{info}=$k->getNext($table,$posts->[0]{id},1);
		$navi->{prev}{url}=sprintf ('%s/%d', $baseurl, $navi->{prev}{info}{id});
		$navi->{next}{url}=sprintf ('%s/%d', $baseurl, $navi->{next}{info}{id});
	}
}
elsif ($p->{tag}) {
	my $nhash=$k->process_nametags;
	if ($nhash->{$p->{tag}}) {#name is defined
		$mode=3;
		$curr=$p->{offset}||1;
		$page_max=$Method_Kiyoism_Plus::POCCHONG->{archiv_max}/2;
		my $numrows=$k->getOne('SELECT COUNT(*) FROM mygirls_pcs JOIN mygirls_tagged ON mygirls_tagged.pcs_id=mygirls_pcs.id JOIN mygirls ON mygirls_pcs.title_id=mygirls.id WHERE mygirls_tagged.name_id=?', [$p->{tag}]);
		my $pgtotal=$k->calc_total_page($numrows,$page_max);
		my $curr=$k->calc_curr_page($curr,$pgtotal);
		my $offset=$k->calc_page_offset($curr,$page_max);
		$navi=$k->calc_navi_set(1,$pgtotal,$curr,$page_turn);
		$posts=$k->getAll('SELECT mygirls.title,mygirls_pcs.title_id,mygirls_pcs.id,mygirls_pcs.img_url,mygirls_pcs.da_url,mygirls.title FROM mygirls_pcs JOIN mygirls_tagged ON mygirls_tagged.pcs_id=mygirls_pcs.id JOIN mygirls ON mygirls_pcs.title_id=mygirls.id WHERE mygirls_tagged.name_id=? ORDER BY mygirls.id DESC LIMIT ?,?', [$p->{tag}, $offset,$page_max]);
		$baseurl.=sprintf '/tag/%s', $p->{tag};
	}
}
if ($mode==1) {
	$curr=$k->calc_curr_page_express($table,($p->{page}||1),$page_max);
	my $offset=$k->calc_page_offset_express($table, $page_max, ($p->{page}||1));
	$posts=$k->getAll('SELECT * FROM mygirls ORDER BY id DESC LIMIT ?,?', [$offset,$page_max]);
	$navi=$k->calc_navi_set_express($table,$curr,$page_max,$page_turn);
	$baseurl.='/page';
}

# --------------- HTML BEGIN -------------
$k->header;
$k->print_html_head($page_title);
$k->print_main_wrap(0);
if ($mode==2) {
	print_art_entry($k,$posts->[0],0,0);
} elsif ($mode==1) {
	foreach my $entry (@$posts) {
		print_art_entry($k, $entry,1);
	}
} else { #no "index" view for tag
	print_art_entry_tag($k, $posts,$p->{tag});
}
$k->print_main_wrap(1);
$k->print_footer_wrap(0);
if ($navi) {
	if ($mode==2) {
		$k->print_footer_navi($navi->{prev}{info}{title}, $navi->{prev}{url},1);
		$k->print_footer_navi($navi->{next}{info}{title}, $navi->{next}{url},0);
	# } elsif ($mode==1) {
	} else {
		$k->print_navi_bar($navi, $page_turn, $curr, $baseurl);
	}
}
$k->print_footer_wrap(1);
$k->print_html_tail;
# --------------- HTML END -------------

########## subs ##########
sub print_art_entry {
	my ($k,$entry,$idx)=@_; #idx=1, on index page, w/o arrange // <div class="post-inner-shell">....</div>
	if (!$entry) { return undef; }
	my $pcs=$k->getAll('SELECT * FROM mygirls_pcs WHERE title_id=?',[$entry->{id}]);
	my $rlist=$k->rand_array(scalar @$pcs);
	my $stds;
	foreach my $i (@$rlist) {
		my $pc1 = $pcs->[$i];
		if ($pc1->{stdalone}) {
			push @$stds, $pc1;
			# $pcs->[$i]=undef;
			splice @$pcs, $i, 1;
			if ($idx) {
				last;
			}
		}
	}
	if (!$stds and $pcs) { #if no given stdalone, get 1 random pcs from all
		my $elem=int rand scalar @$pcs;
		push @$stds, $pcs->[$elem];
		# $pcs->[$elem]=undef;
		splice @$pcs, $elem, 1;
	}

	$k->print_post_wrap();
	printf ('<div><!-- artwork block begins -->%s',"\n");
	print_mygirls_h3($k, $entry,$idx);
	if (!$idx) { #print stdalone+variations
		print_art_entry_single($k,$entry,$stds,$pcs);
	} else { #print only 1 stdalone
		print_art_entry_index($k,$entry,$stds,$pcs);
	}
	$k->print_edit_button(sprintf "%s/?id=%s", $Method_Kiyoism_Plus::POCCHONG->{sql_mygirls_edit}, $entry->{id});
	printf ("</div><!-- closing artwork block -->\n");
	$k->print_post_wrap(1);
}
sub print_art_entry_tag {
	my ($k, $posts,$tag)=@_;
	if (!$posts or !$tag) { return 0; }
	my $nhash=$k->process_nametags;

	$k->print_post_wrap();
	printf ("<div>\n");
	print_mygirls_h3($k,{title=>'NameTag::'.$nhash->{$tag}},0);
	my $cid=0;
	printf ('<div class="gallery">%s',"\n");
	foreach my $entry (@$posts) {
		if ($cid != $entry->{title_id}) {
			$cid=$entry->{title_id};
			printf '<div class="gallery-img-frame-mid" style="background: #%s; width: 110px;"><a href="%s/%s"><div class="gallery-img-title-block">%s</div></a></div>%s',
				rand_bg_colour(),
				$Method_Kiyoism_Plus::POCCHONG->{sql_mygirls_url},
				$cid,
				$entry->{title},
				"\n";
		}
		print_pcs_vars($k,$entry,0,1);
	}
	printf ("</div>\n");
	printf ("</div>\n");
	$k->print_post_wrap(1);
}
sub print_mygirls_h3 {
	my ($k,$entry,$idx)=@_;
	if ($entry) {
		my $title_h2=sprintf ('%s %s %s', $k->rand_utf8, $k->htmlentities($entry->{title}), $k->rand_utf8);
		# my $title_h2=sprintf ('%s %s %s', $k->rand_utf8, $entry->{title}, $k->rand_utf8);
		if ($idx) { #include link on h3
			$title_h2=sprintf ('<a href="%s/%s">%s</a>',
				$Method_Kiyoism_Plus::POCCHONG->{sql_mygirls_url},
				$entry->{id},
				$title_h2);
		}
		printf ("<h2>%s</h2>\n", $title_h2);
	}
}
sub print_pcs_vars {
	my ($k, $pc2,$idx,$mid)=@_;
	if (!$pc2) { return 0; }
	my $picsize='s640'; if ($idx) { $picsize='s80'; }
	my $class='gallery-img-frame';
	if ($idx) {
		$class.='-mini';
	} elsif ($mid) {
		$class.='-mid';
	}
	my $piclink=sprintf '<img src="%s" alt="" />', $k->mk_url_google_img($pc2->{img_url},$picsize);
	$piclink=sprintf '<a href="%s" target="_blank">%s</a>',mk_url_da($pc2->{da_url}),$piclink;
	printf ('<span class="%s">%s</span>%s', $class,$piclink,"\n");
}
sub print_art_entry_single {
	my ($k, $entry,$stds,$pcs)=@_;
	if (!$entry or !$stds) { return undef; }
	print_info_block($k, $entry, 0);
	foreach my $pc1 (@$stds) {
		print_stdalone($k, $pc1, 0);
	}
	$k->print_line_seperator;
	if ($pcs) {
		printf ('<div class="gallery">%s', "\n");
		foreach my $pc2 (@$pcs) {
			print_pcs_vars($k,$pc2,0);
		}
		printf ("</div>\n");
	}
}
sub print_art_entry_index {
	my ($k, $entry, $stds, $pcs)=@_;
	if (!$entry or !$stds or !$pcs) { return 0; }
	printf "<table style=\"width: 97%\">\n<tr>\n";
	printf "<td style=\"width: 42%;\">\n";
	print_stdalone($k, $stds->[0],$entry,1);
	printf "</td>\n";
	printf "<td style=\"vertical-align:top;\">\n";
	print_info_block($k, $entry,1);
	if ($pcs) {
		printf ('<div class="gallery-mini">%s',"\n");
		my $i=0;
		foreach my $pc2 (@$pcs) {
			print_pcs_vars($k, $pc2,$entry,1);
			$i++;
			if ($i>=$max_in_mini_gallery) {
				last;
			}
		}
		printf ("<div>\n");
	}
	printf ("</td>\n");
	printf ("</tr>\n</table>\n");
}
sub print_stdalone {
	my ($k, $pc1,$idx)=@_;
	if (!$pc1) { return undef; }
	my $pcsize='s900'; if ($idx) { $pcsize='s400'; }
	my $imgsrc=$k->mk_url_google_img($pc1->{img_url}, $pcsize);
	my $daurl='';
	my $imglink=sprintf '<img src="%s" alt="" />', $imgsrc;
	if ($pc1->{da_url}) {
		$daurl=mk_url_da($pc1->{da_url});
		$imglink=sprintf ('<a href="%s" target="_blank">%s</a>', $daurl, $imglink);
	}

	printf ('<div class="stdalone">%s',"\n");
	printf ("%s<br />\n", $imglink);
	if (!$idx) {
		if ($daurl) {
			printf ('<a href="%s" target="_blank">deviantART</a>', $daurl);
		}
	}
	print "</div>\n";
}
sub print_info_block {
	my ($k, $entry,$idx)=@_; #indiv page only
	if (!$entry) { return undef; }
	my $widthsize='';
	if ($idx) { $widthsize=' style="width: 80%"'; }
	printf "<blockquote%s>\n<ul>\n", $widthsize;
	printf "<li>Done on: %s</li>\n", $k->format_epoch2date($entry->{epoch},$entry->{gmt},0);

	#get rep title
	if ($entry->{post_id}) {
		my $rep_title=$k->getOne('SELECT title FROM post WHERE id=?', [$entry->{post_id}]);
		my $rep_url=sprintf '%s/%s',$Method_Kiyoism_Plus::POCCHONG->{sql_post_url},$entry->{post_id};
		printf "<li>Liner notes: <a href=\"%s\">%s</a></li>\n",$rep_url,$rep_title;
	}
	if ($entry->{notes}) {
		printf "<li>Inspiration: %s</li>\n",$entry->{notes};
	}

	#name tags
	if (!$idx) {
		if (my $names=$k->getAll('SELECT mygirls_name.id FROM mygirls_tagged JOIN mygirls_name ON mygirls_name.id=mygirls_tagged.name_id JOIN mygirls_pcs ON mygirls_pcs.id= mygirls_tagged.pcs_id JOIN mygirls ON mygirls.id=mygirls_pcs.title_id WHERE mygirls.id=?', [$entry->{id}])) {
			my $nhash=$k->process_nametags;
			my $name2;
			foreach my $name (@$names) {
				if (!$name2->{id}) {
					$name2->{$name->{id}}=1;
				}
			}
			printf ("<li>Name tags: \n");
			my $name3=[keys %$name2];
			my $rlist=$k->rand_array(scalar keys %$name2);
			foreach my $i (@$rlist) {
				printf '%s<a href="%s/tag/%s">%s</a>',
					$k->rand_utf8,
					$Method_Kiyoism_Plus::POCCHONG->{sql_mygirls_url},
					$name3->[$i],
					$nhash->{$name3->[$i]};
			}
			print $k->rand_utf8; #finish the line-deco
			printf ("</li>");
		}
	}

	printf ("</ul>\n</blockquote>\n");
}
sub mk_url_da { #feed in string after ../art/. uses my dA account
	my $url=shift;
	if ($url) {
		return sprintf "http://kosmoflips.deviantart.com/art/%s",$url;
	} else {
		return '';
	}
}
sub rand_bg_colour {
	my $cols=[qw/
	fa896a 	ffa729  fff829  61cf1d  10c554  849fca
	0bd3ac  19c9f1  1184e5  dd8067  99efdf  5acdf1
	4466e1  7c4ef8  d561cc  f6476d
	/];
	return $cols->[(rand int (scalar @$cols))];
}