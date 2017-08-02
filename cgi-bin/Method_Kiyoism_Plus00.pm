package Method_Kiyoism_Plus;
use strict;
use warnings; no warnings 'utf8';

use CGI; #http://perldoc.perl.org/CGI.html
use CGI::Carp qw/fatalsToBrowser warningsToBrowser/;
use CGI::Session;
use Data::Dumper;
use DBI;
use Encode qw/encode decode/;
use File::Spec;
use Digest::MD5;
use Storable qw/:DEFAULT nstore dclone/;
use utf8;
# use HTML::Template; #http://search.cpan.org/~samtregar/HTML-Template-2.6/Template.pm

our $POCCHONG={
	tmpdir=>$ENV{DOCUMENT_ROOT}.'/tmp',
	siteurl=>'www.pocchong.de',
	timeout=>3600*2, #timeout session in session
	site_title=>'音時雨 ～Fairy Aria～',
	logfile=>$ENV{DOCUMENT_ROOT}.'/cgi-bin/ctfile.txt',
	logpile=>[qw/
		atime
		REMOTE_ADDR
		REQUEST_METHOD
		HTTP_ACCEPT_LANGUAGE
		HTTP_USER_AGENT
		HTTP_ACCEPT_ENCODING
		HTTP_REFERER
		REQUEST_URI
		SCRIPT_NAME
		QUERY_STRING
		/],
	navi_step=>2, # for page block. show +-2 pages starting at current
	#admin
	admin_url=>'/a/',
	admin_list_max=>77, #for list of sql entries
	#archiv
	archiv_url=>'/archiv',
	archiv_title=>'Archiv',
	archiv_max=>44, # switch by "page"
	#static
	static_path=>$ENV{DOCUMENT_ROOT}.'/html',
	# SQL table define, switch by "offset"
	sql_post_title=>'Diary',
	sql_post_url=>'/post', #/?id=xx or /post/xx
	sql_post_edit=>'/a/edit_post', #self use, no need to change url
	sql_post_max=>7,
	sql_mygirls_title=>'幻想調和',
	sql_mygirls_url=>'/mygirls',
	sql_mygirls_edit=>'/a/edit_mygirls',
	sql_mygirls_max=>5, # 素数で結構
};


sub new {
	my ($class)=@_;
	my $k= bless {},$class;
	$k->{DBH}=undef;
	$k->{CGI}=new CGI;
	$k->{SESSION}= CGI::Session->new("driver:File", $k->{CGI}, {Directory=>$POCCHONG->{tmpdir}}) or die CGI::Session->errstr;
	# if (!$k->{SESSION}->param('POCCHONG_LOGIN_TOKEN')) {
		# raisecount($k);
	# }
	return $k;
}
sub raisecount { #not in use since 170731
	my $k=shift;
	my $ctfile=$POCCHONG->{logfile};
	my $fh;
	if (-e $ctfile) {
		open ($fh, ">>", $ctfile);
	} else {
		open ($fh, ">", $ctfile);
	}
	my @line=($k->{SESSION}->atime);
	foreach my $field (@{$POCCHONG->{logpile}}) {
		push @line, ($ENV{$field}||'');
	}
	printf $fh "%s\n", (join "\t" , @line);
}
sub DESTROY {
	my $k=shift;
	if ($k->{DBH}) {
		$k->{DBH}->disconnect or warn "Disconnection failed: $DBI::errstr\n";
	}
}


# -------------- DB --------------------
sub db_connect {
	my $k=shift;
	return 0 if $k->{DBH};
	my $info={
		db=>'ghostelf_pocchong',
		host=>'localhost',
		pw=>'q[/q[bezf7huystdues',
		usr=>'ghostelf_kiyo',
	};
	my $dsn=sprintf 'DBI:mysql:%s:%s',$info->{db},$info->{host};
	$k->{DBH}=DBI->connect( $dsn, $info->{usr}, $info->{pw}, { mysql_enable_utf8 => 1, PrintError => 1, RaiseError => 1, }) or die "can't connect";
}
sub dosql { #do my sql cmd's
	my ($k,$stat,$vars)=@_; # 'select * from table where id=?', [$var1,$var2...]
	$k->db_connect;
	my $sth = $k->{DBH}->prepare($stat) or die "can't prepare \"$stat\"", $k->{DBH}->errstr;
	$sth->execute(@$vars) or die "can not execute \"$stat\"", $sth->errstr;
	return $sth;
}
sub getOne {#get only 1 single value from a table
	my ($k,$stat,$vars)=@_;
	$k->db_connect;
	return $k->{DBH}->selectrow_array($stat,undef,@$vars);
}
sub getRow {#return H ref from 1 row
	my ($k,$stat,$vars)=@_;
	$k->db_connect;
	return $k->{DBH}->selectrow_hashref($stat,undef,@$vars);
}
sub getAll { #return A ref for all rows
	my ($k,$stat,$vars)=@_;
	$k->db_connect;
	my $rows;
	my $sth=$k->dosql($stat,$vars);
	while (my $r=$sth->fetchrow_hashref) {
		push @$rows, $r;
	}
	return $rows;
}
sub getNext {
	my ($k,$table,$curr,$getprev)=@_; # for table [post/mygirls] or any other tables that have both id,title as field, and id/epoch/time should be ordered the same
	if (!$curr or !$table) { return undef; }
	my $query=sprintf 'SELECT id,title FROM %s WHERE id%s%s ORDER BY id %s LIMIT 1', $table, ($getprev?'<':'>'), $curr, ($getprev?'desc':'');
	my $nextentry=$k->getRow($query);
	return $nextentry;
}
sub process_nametags { #return $mygirls->{name_id}=$name
	my ($k)=@_;
	my $nhash;
	my $allnames=$k->getAll('SELECT id,name FROM mygirls_name');
	foreach my $row (@$allnames) {
		$nhash->{$row->{id}}=$row->{name};
	}
	return $nhash;
}

# --------------------- HTTP ----------------------------
sub param {
	my $k=shift;
	my $vars=$k->{CGI}->Vars();
	foreach my $kk (keys %$vars) {
		$vars->{$kk}=$k->trim($vars->{$kk});
	}
	return $vars;
}
sub header {
	my ($k,$http,$nocache)=@_;
	if (!$nocache and $k->{SESSION} and $k->{SESSION}->id) {
		my $cookie=$k->{CGI}->cookie(-name=>'CGISESSID', -value=>$k->{SESSION}->id);
		$http->{'-cookie'}=$cookie;
	}
	$http->{'-status'}='200 OK' if !$http->{'-status'};
	$http->{'-charset'}='utf-8' if !$http->{'-charset'};
	$http->{'-type'}='text/html' if !$http->{'-type'};
	print $k->{CGI}->header($http);
}
sub peek {
	my ($k,@vars)=@_;
	$k->header({'-type'=>'text/plain'});
	use Data::Dumper;
	foreach (@vars) {
		printf "%s\n%s\n", (Dumper $_) , ('*' x 10);
	}
}
sub redirect { #NOT for header
	my ($k,$path,$code)=@_;
	if ($code and $code==301) {
		print $k->{CGI}->redirect(
			'-url'=>$path,
			'-status'=>'301 Moved Permanently',
		);
	}
	elsif ($code and $code==303) {
		print $k->{CGI}->redirect(
			'-url'=>$path,
			'-status'=>'303 See Other',
		);
	}
	elsif ($code and $code==404) {
		print $k->{CGI}->redirect(
			'-url'=>$path,
			'-status'=>'404 Not Found',
		);
	}
	else {
		print $k->{CGI}->redirect('-url'=>$path);
	}
}
sub htmlentities { #simple, for &, <, >, ä, ö, ü, ï, ß, °, ", '
	my ($k, $str)=@_;
	# use HTML::Entities qw/encode_entities decode_entities/;
	if ($str) {
		$str=~s/&/&amp;/g;
		$str=~s/>/&gt;/g;
		$str=~s/</&lt;/g;
		$str=~s/°/&deg;/g;
		$str=~s/ä/&auml;/g;
		$str=~s/ö/&ouml;/g;
		$str=~s/ü/&uuml;/g;
		$str=~s/ï/&iuml;/g;
		$str=~s/ß/&szlig;/g;
		$str=~s/"/&quot;/g;
		$str=~s/'/&apos;/g;
		return $str;
		# return encode_entities($str);
	} else {
		return '';
	}
}


# ----------------- NAVI CALC ---------------------
sub calc_page_offset_express {
	my ($k,$table,$max,$curr)=@_;
	if (!$table or !$max) { return 0; }
	$curr=1 if !$curr;
	my $allcont=$k->calc_total_rows($table);
	my $pgs=$k->calc_total_page($allcont,$max);
	$curr=$k->calc_curr_page( ($curr?$curr:1), $pgs);
	return $k->calc_page_offset($curr, $max);
}
sub calc_page_offset {
	my ($k,$curr,$limit)=@_; #return offset for SQL
	$curr=0 if !$curr;
	$limit=0 if !$limit;
	return (($curr-1)*$limit);
}
sub calc_curr_page_express {
	# my ($table=0,$curr0=1,$max=0)=@_;
	my ($k,$table,$curr0,$max)=@_;
	if (!$table or !$max) { return 0; }
	if (!$curr0) { $curr0=1; }
	return $k->calc_curr_page($curr0,$k->calc_total_page($k->calc_total_rows($table),$max));
}
sub calc_total_rows {
	my ($k,$table)=@_;
	if (!$table) {return 0; }
	return $k->getOne('SELECT COUNT(*) FROM '.$table) || 0;
}
sub calc_total_page {
	my ($k, $rownum,$max)=@_;
	if (!$rownum or !$max) { return 0; }
	my $pgtotal=sprintf "%d", ($rownum/$max);
	if ($rownum%$max) { $pgtotal++; }
	return $pgtotal;
}
sub calc_curr_page {
	my ($k,$curr,$pgtotal)=@_;
	if (!$curr or !$pgtotal) { return 0; }
	if ($curr>$pgtotal) {
		$curr=$pgtotal;
	} elsif ($curr<1) {
		$curr=1;
	}
	return $curr;
}
sub calc_navi_set_express {
	my ($k,$table,$curr0, $max,$turn)=@_; # FIRST is always 1 !!! //#process set for input of &print_navi_bar
	if (!$table or !$curr0 or !$max or !$turn) { return 0; }
	my $first=1;
	my $last=$k->calc_total_page($k->calc_total_rows($table),$max);
	my $curr=$k->calc_curr_page_express($table,$curr0, $max);
	my $navi=$k->calc_navi_set($first,$last,$curr,$turn);
	return $navi;
}
sub calc_navi_set {
	my ($k,$first,$last,$curr, $turn)=@_; #process set for input of &print_navi_bar
	if (!$last or !$curr or !$turn) { return 0; }
	$first=1 if !$first;
	my $totalcont=$last-$first+1;
	my $navi={ #initial values
		'begin0'=>$first,
		'begin1'=>0,
		'end0'=>0,
		'end1'=>$last,
		'mid'=>0,
		'prev'=>0,
		'next'=>0,
	};
	if (($turn*2+1)>=$totalcont) { #no need to break
		$navi->{begin1}=$navi->{end1};
		$navi->{end1}=0;
	}
	elsif ($curr<=(1+$turn+$navi->{begin0})) { # 1,2,3,4,5 // 10
		$navi->{begin1}=$navi->{begin0}+2*$turn;
		if ($curr==($turn+1+$navi->{begin0})) {
			$navi->{begin1}+=1;
		}
	}
	elsif ($curr>=($navi->{end1}-$turn-1)) { # 1 // 6,7,8,9,10
		$navi->{end0}=$curr-$turn;
		if ($curr>=($navi->{end1}-$turn)) {
			$navi->{end0}=$navi->{end1}-$turn*2;
		}
	}
	else {
		$navi->{mid}=$curr; #+-2 each side
	}

	$navi->{prev}=$curr-1;
	$navi->{next}=$curr+1;
	if ($navi->{prev}<$navi->{begin0}) {
		$navi->{prev}=0; #don't show "prev" at page1
	}
	if (($navi->{next}>$navi->{end1} and $navi->{end1}>0) or
		($navi->{next}>$navi->{begin1} and $navi->{end1}==0)) {
		$navi->{next}=0; # don't show "next" at last page
	}
	return $navi;
}
sub print_navi_bar {
	my ($k, $navi, $turn, $curr, $baseurl)=@_;
	if (!$navi or !$turn or !$curr or !$baseurl) { return 0; }
	printf "<div class=\"navi-bar\">\n";
	if ($navi->{prev}) {
		printf "<span><a href=\"%s/%s\">◀◀</a></span>\n", $baseurl, ($curr-1);
	}
	if ($navi->{begin0} and $navi->{begin1}) {
		for (my $i=$navi->{begin0}; $i<=$navi->{begin1}; $i++) {
			$k->print_navi_square($baseurl, $i,$curr);
		}
	}
	elsif ($navi->{begin0}) {
		$k->print_navi_square($baseurl, $navi->{begin0},$curr);
	}

	if ($navi->{mid}) {
		$k->print_spacer_dots;
		for (my $i=($navi->{mid}-$turn); $i<=($navi->{mid}+$turn); $i++) {
			$k->print_navi_square($baseurl, $i,$curr);
		}
	}
	if ($navi->{end0} and $navi->{end1}) {
		$k->print_spacer_dots;
		for (my $i=$navi->{end0}; $i<=$navi->{end1}; $i++) {
			$k->print_navi_square($baseurl, $i,$curr);
		}
	}
	elsif ($navi->{end1}) {
		$k->print_spacer_dots;
		$k->print_navi_square($baseurl, $navi->{end1},$curr);
	}
	if ($navi->{next}) {
		printf "<span><a href=\"%s/%s\">▶▶</a></span>\n", $baseurl, ($curr+1);
	}
	printf "</div><!-- .navi-bar ends -->\n";
}
sub print_navi_square {
	my ($k,$baseurl, $page,$cpage)=@_;
	my $class='navi-bar-square';
	if ($cpage eq $page) {
		$class.='-self';
	}
	printf '<span class="%s"><a href="%s/%s">%s</a></span>%s', $class, $baseurl, $page, $page,"\n";
}
sub print_spacer_dots {
	shift;
	printf "<span>..</span>\n";
}


# ---------------- HTML LAYOUT ----------------
sub mk_title { #for <title> in <head>
	my ($k,$title)=@_;
	if (!$title) {
		return $POCCHONG->{site_title};
	} else {
		return (sprintf "%s | %s", $title, $POCCHONG->{site_title});
	}
}
sub include {
	shift;
	my ($file)=@_;
	if ($file) {
		if (-e $file) {
			open (my $fh, $file);
			while (<$fh>) {
				print $_;
			}
		} else {
			printf "<div>file <b>%s</b> isn't found on server.</div>\n", $file;
		}
	}
}
sub print_admin_html {
	my $k=shift;
	my $end=shift;
	if (!$end) {
		$k->include ($ENV{DOCUMENT_ROOT}.'/cgi-bin/admin_part_head.html');
	} else {
		$k->include ($ENV{DOCUMENT_ROOT}.'/cgi-bin/admin_part_tail.html');
	}
}
sub print_edit_button {
	my ($k, $edit_url)=@_;
	if ($k->chklogin and $edit_url) {
		printf ('<div class="inline-box"><a href="%s">Edit</a></div>%s', $edit_url,"\n");
	}
}
sub print_html_head {
	my ($k,$title,$specialchunk)=@_; # $specialchunk = COMPLETE CODE , e.g. <style>css</style>, <script>js</script>
	$k->include($ENV{DOCUMENT_ROOT}.'/cgi-bin/part_page_head1.html');
	printf "<title>%s</title>\n", $k->mk_title($title);
	if ($specialchunk) { #any code
		printf ("%s\n", $specialchunk);
	}
	$k->include($ENV{DOCUMENT_ROOT}.'/cgi-bin/part_page_head2.html');
	$k->include($ENV{DOCUMENT_ROOT}.'/cgi-bin/part_page_menu.html');
	printf "</div><!-- #header-outer -->\n";
}
sub print_html_tail {
	my $k=shift;
	$k->include($ENV{DOCUMENT_ROOT}.'/cgi-bin/part_page_tail.html');
}
sub print_main_wrap {
	my ($k,$do_end)=@_; #for main-layer/post-outer, use only ONCE per page. collection of ALL .post-inner-shell
	if (!$do_end) { #<div> opens
		printf '<div id="mainlayer">%s',"\n";
		$k->include($ENV{DOCUMENT_ROOT}.'/cgi-bin/part_page_searchbox.html');
		printf ('<div class="post-outer">%s',"\n");
	} else { #<div> closes
		print <<HTML2;
</div><!-- .post-outer -->
</div><!-- #mainlayer-->

HTML2
	}
}
sub print_post_wrap {
	my ($k,$do_end)=@_; #for .post-inner-shell = ONE individual entry
	if (!$do_end) { #<div> opens
		print <<HTML;
<div class="post-inner-shell">
<div class="post-inner">

HTML
	} else { #<div> closes
		print <<HTML2;
</div><!-- .post-inner -->
</div><!-- .post-inner-shell -->

HTML2
	}
}
sub print_footer_wrap {
	my ($k,$do_end)=@_; #for .post-inner-shell = ONE individual entry
	if (!$do_end) { #<div> opens
		printf '<div id="footer-outer">%s', "\n";
	} else { #<div> closes
		printf '</div><!-- .footer-outer -->%s', "\n";
	}
}
sub print_footer_navi {
	my ($k,$title,$url,$prev)=@_; #footer navi <div> for next/prev entry . url should be ABS path to root.
	if (!$url or !$title) { return 0; }
	my $titlefmt;
	if ($prev) {
		$titlefmt='⇦ '.$title;
	} else {
		$titlefmt=$title.' ⇨';
	}
	printf '<div class="navi-%s"><a href="%s">%s</a></div>%s',
		($prev?'prev':'next'),
		($url?$url:''),
		$titlefmt,
		"\n";
}
sub print_line_seperator {
	shift;
	print '<div style="margin-top: 25px; border-bottom: 3px double #d8afe2; display:block; text-align:center; width: 90%; font-size: 120%; text-shadow: 2px 2px 3px #bd8db4; margin: auto; "><b>..｡o○☆*:ﾟ･: Variations :･ﾟ:*☆○o｡..</b></div>'."\n";
}
=pod
function print_preview_div () {
	return <<<CSS
<div style="position:fixed;background:rgba(0,0,0,0.5);padding:20px 0;text-align:center;display:block;width:100%;left:-100px;top:50px;font-size:30px;font-weight:bold;color:white;transform: rotate(-20deg)">PREVIEW</div>
CSS;
}
=cut


# ------------------ OTHER ---------------
sub chklogin {
	my $k=shift;
	my $retreat=shift;
	if ($k->{SESSION} and $k->{SESSION}->param('POCCHONG_LOGIN_TOKEN')) {
		return 1;
	} else {
		if ($retreat) {
			$k->redirect($POCCHONG->{admin_url});
		}
		return 0;
	}
}
sub trim {
	shift;
	my $str=shift;
	$str=~s/^\s+|\s+$//g;
	return $str;
}
sub mk_url_google_img {
	my ($k, $url,$size)=@_;
	if (!$url) { return ''; }
	my @t=split '/', $url;
	my $fname=pop @t;
	pop @t; #remove size
	if (!$size or $size!~/^[hws]\d+/i) { #simple check. should work for most cases
		$size='s800';
	}
	return sprintf 'https://%s/%s/%s', (join '/', @t), $size, $fname;
}
sub rand_utf8 {
	shift;
	my $set=[ # HTML CODEの形式でより安全だと思う
		# '&#9956;', #⛤
		'&#10031;', #✯
		'&#10045;', #✽
		'&#10056;', #❈
		'&#10047;', #✿
		'&#10048;', #❀
		'&#10046;', #✾
		'&#10017;', #✡
		'&#9825;', #♡
		'&#10059;', #❋
		'&#10054;', #❆
		'&#10053;', #❅
		'&#9733;', #★
		'&#9734;', #☆
		# '&#9752;', #☘
		# '&#9753;', #☙
		'&#9770;', #☪
		'&#9825;', #♠
		'&#9826;', #♢
		'&#9828;', #♣
		'&#9831;', #♥
		'&#9834;', #♪
		'&#9835;', #♫
		'&#9836;', #♬
	];
	return $set->[int rand (scalar @$set)];
}
sub rand_array {
	my ($k, $total)=@_;
	my ($r,$a);
	my $i=0;
	while ($i<$total) {
		my $t=int rand $total;
		if ($r->{$t}) {
			next;
		} else {
			$r->{$t}=1;
			push @$a,$t;
			$i++;
		}
	}
	return $a;
}
sub parse_time_27h {
	my ($k,$epoch, $gmt) =@_; #return hash
# 27-H Mode: time <= 3:00 AM, continue from 24 (0:00 AM=> 24:00, 2:59 AM => 26:59, 3:00 AM => no change)
#as default timezone is -7, when using GMT, e.g. London, need to clearly give 2nd var as "0"
	$epoch=time if !$epoch;
	my $timeref;
	my $tz=-7;
	if ($gmt) {
		if ($tz !=$gmt) { $epoch+=($gmt-$tz)*3600; } #since later "localtime" uses -7, need to adjust the actual time to be the desired GMT here
		$timeref->{gmt}=$gmt;
	} else {
		$timeref->{gmt}=$tz;
	}
	#27H mode
	my @lc=localtime $epoch;
	my $plus;
	if ($lc[2]<3) { #if time is before 3:00am
		$epoch=$epoch-24*60*60; #shift epoch back by 1 day
		$plus=1;
	}
	my @time=localtime $epoch; #this may be a diff. epoch after shift
	$timeref->{second}=$time[0];
	$timeref->{minute}=$time[1];
	$timeref->{hour}=$time[2];
	$timeref->{day}=$time[3];
	$timeref->{month}=$time[4]+1; #human order  1-12
	$timeref->{year} = $time[5]+1900;
	$timeref->{weekday}=$time[6];
	$timeref->{hour}+=24 if $plus; #raises hour after 24
	return $timeref;
}
sub format_epoch2date {
	my ($k,$epoch,$gmt,$simple)=@_; # $simple=-1 return 150825 // other $simple as in &format_date // as 27H format, so not using date()
	$simple=0 if !$simple;
	if (!$epoch or (length $epoch)<9) { return 0; }
	if (!$gmt and $gmt ne '0') {
		$gmt=-7;
	}

	if ($simple==6) { #RSS standard
		use POSIX qw(strftime);
		return strftime("%a, %d %b %Y %H:%M:%S GMT", gmtime($epoch));
	}

	my $time1=$k->parse_time_27h($epoch,$gmt);
	if (!$simple) {
		return sprintf "%s %d, %d",$k->_hash_month($time1->{month}),$time1->{day},$time1->{year};
	}
	elsif ($simple==5) { #for post page, detailed format
		return sprintf "%i-%s-%02d (%s), %02d:%02d\@GMT%+d",$time1->{year},$k->_hash_month($time1->{month},1),$time1->{day},$k->_array_wkday($time1->{weekday},1),$time1->{hour},$time1->{minute},$gmt;
	}
	elsif ($simple==4) { #20140528
		return $time1->{year}.$time1->{month}.$time1->{day};
	}
	elsif ($simple==3) { #140528
		return sprintf "%02d%02d%02d", ($time1->{year}-2000),$time1->{month},$time1->{day};
	}
	elsif ($simple==2) { # 2014
		return $time1->{year};
	}
	elsif ($simple==1) { #May-28
		return sprintf "%s-%02d", $k->_hash_month($time1->{month},1),$time1->{day};
	}
}
sub _hash_month { #H ref, associated to number
	my ($k,$mo,$short)=@_;
	my $data={
		'01'=>'January',
		'02'=>'February',
		'03'=>'March',
		'04'=>'April',
		'05'=>'May',
		'06'=>'June',
		'07'=>'July',
		'08'=>'August',
		'09'=>'September',
		'10'=>'October',
		'11'=>'November',
		'12'=>'December',
	};
	$mo=sprintf "%02d",$mo;
	if ($short) { return substr ($data->{$mo}, 0,3); }
	else { return $data->{$mo}; }
}
sub _array_wkday { #A ref. 0~6
	my ($k,$day,$short)=@_;
	my $week=[qw/
		Sunday Monday Tuesday Wednesday Thursday Friday Saturday Sunday
	/];
	if ($short) { return substr $week->[$day],0,3; }
	else { return $week->[$day]; }
}
sub conjugate_md5_hash {
	my ($k, $entry, $cols) = @_;
	if (!$cols) { return undef; }
	my $hashs='';
	foreach my $col (@$cols) {
		$hashs.="\0".($entry->{$col}?Encode::encode_utf8 ($entry->{$col}):'');
$k->peek($entry->{$col});
	}
	my $teststr=Digest::MD5->new;
	$teststr->add($hashs);
	return $teststr->hexdigest;
}

1;