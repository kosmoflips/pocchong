#!/usr/bin/perlml
#!/usr/bin/perlml

use strict;
use warnings;
use lib $ENV{DOCUMENT_ROOT}.'/cgi-bin/';
use Method_Kiyoism_Plus;
use Encode;

my $k=Method_Kiyoism_Plus->new;

my $p=$k->param;

my $posts;
my $table='post';
my $baseurl=$Method_Kiyoism_Plus::POCCHONG->{sql_post_url};
my $page_title=$Method_Kiyoism_Plus::POCCHONG->{sql_post_title};
my $page_max=$Method_Kiyoism_Plus::POCCHONG->{sql_post_max};
my $page_turn=$Method_Kiyoism_Plus::POCCHONG->{navi_step};
my $navi;
my $curr=1;

my $process_all=1;

my $idxlimit=800; #max chars to show on post-index page
if ($p->{id}) {
	if (my $entry1=$k->getRow('SELECT * FROM post WHERE id=?',[$p->{id}])) {
		$process_all=0;
		$page_title=$entry1->{title};
		push @$posts, $entry1;
		#prev/next as id-mode
		$navi->{next}{info}=$k->getNext($table,$posts->[0]{id},0);
		$navi->{prev}{info}=$k->getNext($table,$posts->[0]{id},1);
		$navi->{next}{url}=sprintf '%s/%d', $baseurl, $navi->{next}{info}{id};
		$navi->{prev}{url}=sprintf '%s/%d', $baseurl, $navi->{prev}{info}{id};
	}
}
if ($process_all) {
	$curr=$k->calc_curr_page_express($table,($p->{page}||1),$page_max);
	my $offset=$k->calc_page_offset_express($table,$page_max,$curr);
	# $posts=$k->getAll('SELECT id,title,epoch,gmt,LEFT (content,800) as "content" FROM post ORDER BY id DESC LIMIT ?,?', [$offset,$page_max]); # MySQL
	# $posts=$k->getAll('SELECT id,title,epoch,gmt, substr(content,0,800) as "content" FROM post ORDER BY id DESC LIMIT ?,?', [$offset,$page_max]); #SQLite
	$posts=$k->getAll('SELECT id,title,epoch,gmt, content FROM post ORDER BY id DESC LIMIT ?,?', [$offset,$page_max]); #SQLite
	#prev/next
	$navi=$k->calc_navi_set_express($table,$curr,$page_max,$page_turn);
	$baseurl.='/page';
}

# --------------- HTML BEGIN -----------------
$k->header;
$k->print_html_head($page_title);
$k->print_main_wrap(0);
if (!$process_all) {
	print_post_entry($k,$posts->[0]);
} else {
	foreach my $entry (@$posts) {
		print_post_entry($k,$entry,1);
	}
}
$k->print_main_wrap(1);
$k->print_footer_wrap(0);
if ($navi) {
	if (!$process_all) {
		$k->print_footer_navi($navi->{prev}{info}{title}, $navi->{prev}{url},1);
		$k->print_footer_navi($navi->{next}{info}{title}, $navi->{next}{url},0);
	} else {
		$k->print_navi_bar($navi, $page_turn, $curr, $baseurl);
	}
}
$k->print_footer_wrap(1);
$k->print_html_tail;
# --------------- HTML END -----------------


# --------------- subs -----------------
sub print_post_entry {
	my ($k,$entry,$idx)=@_; # <div class="post-inner-shell">....</div>
	if (!$entry) { return 0; }
	my $tag=$idx?'div':'article';
	$k->print_post_wrap();
	printf "<%s>\n",$tag;
	print_post_timestamp($k,$entry);
	print_post_h3($k,$entry);
	if ($idx) { # short content for index page, remove html tags
		my $imgcatch='';
		if ($entry->{content}=~/<img\s+src="(.+?)"/) {
			$imgcatch=$1;
		}
		elsif ($entry->{content}=~m{\s+src="https?://www.youtube.com/embed/(\w+)}i) {
			$imgcatch=sprintf "https://img.youtube.com/vi/%s/0.jpg", $1;
		}

		$entry->{content}=~s/<div class="line".+?<\/div>//g;
		$entry->{content}=~s/<[^>]+>//g;
		$entry->{content}=~s/<[^>]+$//g;
		$entry->{content}=~s{[\r\n]+}{<br />}g;
		$entry->{content}=encode('UTF-8',$entry->{content});
		$entry->{content}=substr $entry->{content}, 0, $idxlimit;
		printf ('<div class="idx-txt">%s', "\n");
		if ($imgcatch) {
			printf '<div class="idx-preview"><img src="%s" alt=""/ ></div>', $imgcatch;
		}
		printf '%s . . . <a href="%s/%s" style="color: green; font-weight:bold">&gt;&gt;&gt;</a></div>%s',
			$entry->{content},
			$Method_Kiyoism_Plus::POCCHONG->{sql_post_url},
			$entry->{id},
			"\n";
	} else {
		printf "%s\n", encode('UTF-8',$entry->{content});
	}
	$k->print_edit_button(sprintf "%s/?id=%s", $Method_Kiyoism_Plus::POCCHONG->{sql_post_edit}, $entry->{id});
	printf "</%s>\n",$tag;
	$k->print_post_wrap(1);
}
sub print_post_timestamp {
	my ($k,$entry)=@_;
	if (!$entry) { return undef; }

	if (!$entry->{epoch}) { $entry->{epoch}=time; }
	if (!$entry->{gmt}) { $entry->{gmt}=-7; }
	# my $tref=$k->parse_time_27h();
	# my $tstr= sprintf "%i-%s-%02d (%s), %02d:%02d\@GMT%+d",$tref->{year},$k->_hash_month($tref->{month},1),$tref->{day},$k->_array_wkday($tref->{weekday},1),$tref->{hour},$tref->{minute},$tref->{gmt};

	printf '<div class="datetime"><a href="%s/%d">%s</a></div>%s', 
		$Method_Kiyoism_Plus::POCCHONG->{sql_post_url},
		$entry->{id},
		$k->format_epoch2date($entry->{epoch},$entry->{gmt},5),
		"\n";
}
sub print_post_h3 {
	my ($k,$entry)=@_;
	if ($entry) {
		printf '<h3><a href="%s/%s">%s %s %s</a></h3>%s',
			$Method_Kiyoism_Plus::POCCHONG->{sql_post_url},
			$entry->{id},
			$k->rand_deco_symbol,
			# $k->htmlentities($entry->{title}),
			encode('UTF-8',$entry->{title}), #don't escape, since it's supposed to be html safe already
			$k->rand_deco_symbol,
			"\n";
	}
}
