package Method_Kiyoism;
use strict;
use warnings; no warnings 'utf8';

use CGI; #http://perldoc.perl.org/CGI.html
use CGI::Carp qw/fatalsToBrowser warningsToBrowser/;
use DBI;
use Storable qw/store retrieve dclone/;
use File::Spec;
use HTML::Template; #http://search.cpan.org/~samtregar/HTML-Template-2.6/Template.pm
use CGI::Session;

use Method_Config; #stores all constants

my ($DBH,$CGI);
sub new {
	my ($class)=@_;
	my $k= bless {},$class;
	$CGI=new CGI;
	return $k;
}
sub DESTROY { if ($DBH) { $DBH->disconnect or warn "Disconnection failed: $DBI::errstr\n"; } }

{##mysql/DB stuff
sub _get_db_info { #info keys=host,db,usr,pw
	shift;
	return $Method_Config::DBINFO;
}
sub _talk2db { #mk db connect
	my $k=shift; #self
	my $info=$k->_get_db_info;
	my $dsn=sprintf 'DBI:mysql:%s:%s',$info->{db},$info->{host};
	$DBH=DBI->connect( $dsn, $info->{usr}, $info->{pw}, {
		mysql_enable_utf8 => 1,
		PrintError => 1,
		RaiseError => 1,
	}) or die "can't connect";
}
sub dosql { #do my sql cmd's
	my $k=shift;
	my ($stat,@vars)=@_; # 'select * from table where id=?', $var1,$var2...
	$k->_talk2db if !$DBH;
	my $sth = $DBH->prepare($stat) or die "can't prepare \"$stat\"", $DBH->errstr;
	$sth->execute(@vars) or die "can not execute \"$stat\"", $sth->errstr;
	return $sth;
}
sub get1value {#get only 1 single value from a table
	my ($k,$stat,@vars)=@_;
	$k->_talk2db if !$DBH;
	return $DBH->selectrow_array($stat,undef,@vars);
}
sub get1row {#return H ref from 1 row
	my ($k,$stat,@vars)=@_;
	$k->_talk2db if !$DBH;
	# my $href=$DBH->selectrow_hashref($stat,undef,@vars);
	return $DBH->selectrow_hashref($stat,undef,@vars);
}
sub sqldump { #return full path of dumped file
	my $k=shift;
	my $prog;
	$prog=$Method_Config::PROG->{SQLDUMP};
	my $info=$k->_get_db_info;
	my $fname='sqldump_'.time.'.sql';
	my $outfile=File::Spec->catfile($Method_Config::PATH->{TMP},$fname);
	my @cmd;
	push @cmd,
		$prog,
		"--host=".$info->{host},
		"-u", $info->{usr},
		"-p".$info->{pw},
		$info->{db},
		">", $outfile;
	my $out;
	eval { $out=system("@cmd"); };
	if (!$@ and $out==0) { return $outfile; }
	else { $k->peek($@); exit; }
}
}

{#cgi & system operations
sub get_param { #return CGI params as a H ref
# multivalued parameters will be returned as a packed string, separated by the "\0" (null) character. You must split this packed string in order to get at the individual values.
	my ($k,$notint)=@_;
	my $vars=$CGI->Vars;
	foreach my $key (keys %$vars) {
		if (!$notint) { #all input param's MUST be an  int
			if (!$vars->{$key} or $vars->{$key}!~/^[\+\-]?\d+$/) {
				undef $vars->{$key};
			}
		}
	}
	return $vars;
}
sub echo_header { #same as CGI.pm
# print "Content-Type: text/html; charset=utf-8\n\n";
	shift;
	my $http=shift;
	if (!$http) {
		$http->{'-type'}='text/html',
		$http->{'-status'}='200 OK',
		$http->{'-charset'}='utf-8',
	} else {
		$http->{'-charset'}='utf-8' if !$http->{'-charset'},
	}
	print $CGI->header($http);
}
sub redirect { #NOT for header
	my ($k,$path,$code)=@_;
	if ($code and $code=~/^30[13]$/) {
		if ($code==301) {
			print $CGI->redirect(
				'-url'=>$path,
				'-status'=>'301 Moved Permanently',
			);
		} else {
			print $CGI->redirect(
				'-url'=>$path,
				'-status'=>'303 See Other',
			);
		}
	} else {
		print $CGI->redirect('-url'=>$path);
	}
}
sub peek { #same as method_peek but browser output
	my ($k,@vars)=@_;
	$k->echo_header;
	use Data::Dumper;
	printf "<pre>%s</pre><hr />", Dumper $_ foreach (@vars);
}
sub downloadfile { #specify file(s), gzip them, and send to download popup
	my ($k,$fpath,$binmode)=@_;
	return undef if (!-e $fpath or !-r $fpath);
	my @t=File::Spec->splitpath($fpath);
	my $fname=pop @t;
	print $CGI->header(
		'-Type' => "application/x-download",
		'-Content-Disposition'=>"attachment; filename=\"$fname\""
	);
	open my $fh, "<", $fpath or die "can't process to download";
	binmode $fh;
	local $/ = \10240; ## 10 k blocks <??????
	while (<$fh>){ print $_; }
	close ($fh);
}
sub zipfiles { #requires tar and gzip on system
#ref: http://ss64.com/bash/tar.html
# tar -zcvf compressFileName folderToCompress
	my ($k,$outfile,$files,$chdir,$exclude)=@_; #self, string (has no ext), A REF
	$chdir=$Method_Config::PATH->{TMP} if (!$chdir or !-d $chdir);
	chdir $chdir;
	my (@cmd,$prog);
	$outfile .= '.tgz';
	@cmd=(
		$Method_Config::PROG->{TARGZ},
		'-zcvf', $outfile,
		'-C',$chdir,
	);
	if ($exclude) {
		push @cmd, '--exclude',$_ foreach (@$exclude);
	}
	foreach my $f (@$files) {
		if (-e $f) {
			my $f2=File::Spec->abs2rel($f,$chdir);
			push @cmd, $f2;
		}
	}
	push @cmd,'>',$Method_Config::PATH->{TMP}.'/_dummy.txt';
	my $stat;
	eval { $stat=system("@cmd"); };
	if (!$@ and $stat==0) { $outfile; }
	else { return 0; }
}
}

{# login, session, cookie, pin protect
# ref: http://search.cpan.org/~sherzodr/CGI-Session-3.95/Session/CookBook.pm#MEMBERS_AREA
sub bake_cookie {
	my ($k,$cinfo)=@_; # -name,-value,-domain,-expires,-path,-secure
	return $CGI->cookie($cinfo);
}
sub get_pin_info {
	my ($k,$table_id,$item_id) =@_;
	my $pin;
	$pin=$k->get1row('select * from passcode where table_id=? and item_id=?',$table_id,$item_id);
	return $pin;
}
sub update_pin_info {
	my ($k,$table_id,$item_id,$pin,$pin_hint,$rm_pin) =@_;
	if ($rm_pin) {
		$k->dosql('delete from passcode where table_id=? and item_id=?',$table_id,$item_id);
	}
	elsif ($pin and $pin_hint) {
		$pin=~s/^\s+|\s+$//g; #remove all white spaces
		my $pin_md5=$k->_mk_md5hex($pin);
		my $stat='';
		my $stat0='passcode set table_id=?,item_id=?,md5hex=?,hint=?';
		my @pile=($table_id,$item_id,$pin_md5,$pin_hint);
		if (my $pin_info=$k->get_pin_info($table_id,$item_id)) { #existing pin found for this id, update
			$stat=sprintf 'update %s where id=?',$stat0;
			push @pile,$pin_info->{id};
		} else {
			$stat=sprintf 'insert into %s',$stat0;
		}
		$k->dosql($stat,@pile);
	}
	1;
}
sub verify_pin {
	my ($k,$table_id,$item_id,$pin) =@_;
	my $verify=0;
	if (my $pin_info=$k->get_pin_info($table_id,$item_id)) { #this post has to be associated to a pin
		if ($pin and $pin=~/\S/) {
			$pin=~s/^\s+|\s+$//g; #remove all white spaces	
			my $md5=$k->_mk_md5hex($pin);
			if ($md5 eq $pin_info->{md5hex}) {
				$verify=1;
			}
		}
	} else { #no pin set up at all.
		$verify=1;
	}
	$verify;
}
sub _mk_md5hex { #use hex b/c its safer to write as filename
	my ($k,$str)=@_;
	use Digest::MD5;
	my $md5=Digest::MD5->new;
	$md5->add($str);
	return $md5->hexdigest;
}
sub _verifyusr {
# verify existence of file: [md5-ed lc user string]+[md5-ed pw string]
	my ($k,$usr,$pw)=@_;
	my $urx=$k->_mk_md5hex($usr);
	my $pwx=$k->_mk_md5hex($pw);
	my $verify=sprintf "%s/%s%s",$Method_Config::PATH->{SAFE},$urx,$pwx; #DIR
	if (-e $verify) { return 1; }
	else { return 0; }
}
sub chklogin { #redirect to login page , for super zone
	my ($k,$del) = @_;
	my $session = $k->load_session;
	if (!$session->param('logged-in')) {
		$session->delete;
		if ($del) { #go to login.cgi for admin zone
			$k->redirect('/a/login.cgi');
			exit;
		}
		else { #regular login check for placing extra info on public pages
			return 0;
		}
	}
	else {
		return 1;
	}
}
sub login {
	my ($k, $session) = @_;
	my $lg_user = $CGI->param('usr') or return;
	my $lg_pwd = $CGI->param('pw') or return;
	if ($k->_verifyusr($lg_user,$lg_pwd)) {
		$session->param('logged-in', 1);
		return 1;
	}
	return;
}
sub logout {
	my ($k, $session) = @_;
	$session->clear();
}
sub load_session {
	my ($k) = @_;
	my $sessionid=undef;
	if ($CGI->cookie('sessionid')) {
		$sessionid=$CGI->cookie('sessionid');
	} elsif ($CGI->param('sessionid')) {
		$sessionid=$CGI->param('sessionid');
	}
	my $session = CGI::Session->new('driver:File', $sessionid, {Directory => $Method_Config::PATH->{TMP} });
	$session->param('logged-in') or $session->param('logged-in', 0);
	$session->expire('+1h');
	return $session;
}
}

{#rss
sub _rss_1item { #take newest item (by the auto-increasing id)
	my ($k,$type,$id)=@_; #type = 3.2.1 post,photo,mygirls
	my $stat;
	my $s0='select title,date';
	my $s1='and id=?';
	my $table=$Method_Config::SECTOR->{$type}{SQL};
	if ($type==1) {
		$stat=sprintf '%s,mygirls_pcs.gp_lh "g1",mygirls_pcs.gp_mid "g2",mygirls_pcs.gid "g3",mygirls_pcs.ext "g4",mygirls.notes from mygirls join mygirls_pcs on mygirls.rep_id=mygirls_pcs.id and mygirls.id=?',$s0;
	}
	elsif ($type==2) {
		$stat=sprintf '%s,album_id,gp_lh "g1",gp_mid "g2",gid "g3",ext "g4",self from photo where self<=2 %s',$s0,$s1;
	}
	elsif ($type==3) {
		$stat=sprintf '%s,content from post where tag=1 %s',$s0,$s1;
	}
	elsif ($type==4) {
		$stat=sprintf '%s,content from post where tag=2 %s',$s0,$s1;
	}
	my $r=$k->get1row($stat,$id);
	return undef if !$r;

	my $item;
	$item->{title}=$r->{title};
	$item->{pubDate}=$k->format_date($r->{date},2);

	# if ($r->{album_id} and $r->{self}) {
	# link
	if ($type==2) {
		$item->{link}=$k->mk_url_google_album($r->{album_id});
	} else {
		$item->{link}=sprintf 'http://%s%s/%i', $Method_Config::META->{DOMAIN},$Method_Config::SECTOR->{$type}{URL},$id;
	}

	#description
	if ($type==3 or $type==4) { #text based
		use utf8;
		my $txt=$r->{content};
		$txt=~s{<div class="line">.+?<\/div>}{}sig;
		if ($type==3) { # remove html tags as content is truncated. keep whole entry for [4]=updates
			$txt=substr $txt, 0, 500;
			$txt=~s/\r\n/\n/sg;
			$txt=~s{<\/?\w+?.*?>}{}sig;
			$txt=~s{<[^>]*$}{};
			$txt.='.....';
		}
		$item->{description}=$txt;
	}
	else { #img based
		if ($r->{self}==2 and $type==2) {
			my $tmpimg=$Method_Config::CONSTANT->{IMG_PLACEHOLDER};
			$tmpimg=~s/s800/h255/;
			$item->{description}=sprintf '<a href="%s"><img src="%s" alt="" /></a>',$item->{link},$tmpimg;
		} else {
			$item->{description}=sprintf '<a href="%s"><img src="%s" alt="" /></a>%s',$item->{link},$k->mk_url_google_img($r->{g1},$r->{g2},$r->{g3},$r->{g4},'h255'),($r->{notes}?'<br />inspiration: '.$r->{notes}:'');
		}
	}
	$item->{description}=$r->{title} if !$item->{description};
	$item->{category}=$type;
	#check pin, wipeout content if pin found
	if (my $pin=$k->get_pin_info($type,$id)) {
		$item->{description}='pin protected content.';
	}
	return $item;
}
sub rss_update { #add a new entry, remove oldest one, also write to xml
	# my ($k)=@_;
	my ($k,$type,$id)=@_;
	my $file=$Method_Config::FILE->{RSS_DAT};
	if (!-e $file) {
		$k->rss_rebuild;
	} else {
		my $info=retrieve ($file); #4 3 2 1 by date
		pop @$info;
		unshift @$info,(dclone $k->_rss_1item($type,$id));
		store $info,$file;
	}
	$k->rss_write;
}
sub rss_rebuild { #rebuild array ref
	my ($k)=@_;
	my $feednum=$Method_Config::ARCHIV->{LIMIT}; #use archiv's set-up limit for feed as well.
	my $hash;
	my $sth;
	$sth=$k->dosql('select id,date from post where tag=1 order by id desc limit ?',$feednum);
	while (my $r=$sth->fetchrow_hashref) { push @{$hash->{$r->{date}}}, "3\t".$r->{id}; }

	$sth=$k->dosql('select id,date from photo where self<=2 order by id desc limit ?',$feednum);
	while (my $r=$sth->fetchrow_hashref) { push @{$hash->{$r->{date}}}, "2\t".$r->{id}; }

	$sth=$k->dosql('select id,date from mygirls order by id desc limit ?',$feednum);
	while (my $r=$sth->fetchrow_hashref) { push @{$hash->{$r->{date}}}, "1\t".$r->{id}; }
	my @keys=sort {$a<=>$b} keys %$hash;
	my $asave;
	my $i=0;
	while ($i<$feednum) {
		my $key=pop @keys; #take from end, as key array increases
		foreach my $entry (@{$hash->{$key}}) {
			my ($type,$id)=split /\t/,$entry;
			my $item=$k->_rss_1item($type,$id);
			next if !$item;
			push @{$asave},dclone $item; #unshift so most recent entries go to the end
			$i++;
		}
	}
	store $asave,$Method_Config::FILE->{RSS_DAT};
}
sub rss_write { #write to xml
	my ($k,$file)=@_;
	my $afile=$Method_Config::FILE->{RSS_DAT};
	if (!-e $afile or -z $afile) { $k->rss_rebuild(); }
	my $items=retrieve $afile;
	$file=$Method_Config::ROOT.$Method_Config::FILE->{RSS_XML} if !$file;
	open (my $fh,">encoding(utf-8)",$file);
	use utf8;
	printf $fh '<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom"><channel><title>%s</title><link>http://%s</link><atom:link rel="self" type="application/rss+xml" href="http://%s%s"/><lastBuildDate>%s</lastBuildDate><description>Melodies from a rainy soul &#9834;</description><generator>%s/.</generator>%s',$Method_Config::META->{SITE_TITLE},$Method_Config::META->{DOMAIN},$Method_Config::META->{DOMAIN},$Method_Config::FILE->{RSS_XML},$k->format_date(undef,2),__PACKAGE__,"\n";
	foreach my $item (@{$items}) {
		printf $fh '<item><title>[%s]%s</title><guid isPermaLink="true">%s</guid><link>%s</link><pubDate>%s</pubDate><description><![CDATA[%s<br /><a href="%s" style="font-size: 80%">(expand)</a>]]></description></item>%s',
			$Method_Config::SECTOR->{$item->{category}}{CATEGORY},$item->{title},
			$item->{link},$item->{link},
			$item->{pubDate},$item->{description},
			$item->{link},"\n";
	}
	printf $fh '</channel></rss>';
}
}

{#html output
sub clean_break { #READ IN SCALAR REF!!!!
	shift;
	my $string=shift;
	$$string=~s/[\r\n]+/\n/g;
	1;
}
sub escape_amp { #used for post to literally display '&'
	shift;
	my $str=shift;
	$str=~s/&/&amp;/g;
	$str;
}
sub escape_html { #utf-8 all my stuff for this sake!
	my $k=shift;
	my ($string,$attr)=@_;
	$string=~s{>}{&gt;}g;
	$string=~s{<}{&lt;}g;
	$string=~s{&}{&amp;}g;
	if ($attr) {
		$k->escape_quote($string);
	}
	$string;
}
sub escape_quote {
	shift;
	my $string=shift;
	$string=~s{'}{&apos;}g;
	$string=~s{"}{&quot;}g;
	$string;
}
sub output_tmpl {
	my ($k,$outfile,$param,$http)=@_; #http is H ref for response code
	my $template = HTML::Template->new(
		filename => $outfile,
		utf8=>1,
	);
	$template->param($param);
	$k->echo_header($http);
	print $template->output;
}
sub output_tmpl_404 {
	my $k=shift;
	$k->output_tmpl($Method_Config::FILE->{NOTFOUND},{},{
		'-status'=> 404,
		'-type'=>'text/html', });
}
sub calc_offset { #return calculated offset num , assuming can go prev/next
	my ($k,$offset,$limit,$pre)=@_; #if !pre, calc for next
	if ($pre) { #prev
		return ($offset+$limit);
	} else {
		return (($offset>$limit)?($offset-$limit):0);
	}
}
sub randomise { # define # of elem, return Array with randomnised number
	my $k=shift;
	my $total=shift;
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
	return @$a;
}
sub mk_null {
	my ($k,$pile)=@_; #A ref
	foreach my $i (@$pile) {
		if (!$i or $i=~/^\s*$/ or $i=~/^(\0)*$/) { $i=undef; }
	}
}
}

{#date time
sub get_year_this { #return this year
	shift;
	my @lt=localtime(time);
	return $lt[5]+1900;
}
sub get_year_range { #for select between 2 dates, NOT inclusive
	my ($k,$year)=@_;
	my $min=($year-2000)*10000-1;
	my $max=($year-1999)*10000;
	return $min,$max;
}
sub validate_year { #4digit year, trigger ($cmp) => if year > this year, return this year
	my ($k,$year,$cmp)=@_;
	my $thisyear=$k->get_year_this;
	if ($year!~/^2\d{3}$/) { #remove invalid year
		return $thisyear;
	}
	if ($cmp and $year>$thisyear) { return $thisyear; }
	else { return $year; }
}
sub split_date { #from 140802 to [14,08,02];
	my ($k,$date)=@_;
	$date=sprintf "%06d",$date;
	my ($yr,$mo,$dy)=$date=~/(\d\d)(\d\d)(\d\d)/;
	return ($yr,$mo,$dy);
}
sub _format_time_feed_gmt { #input epoch, output GMT time in RFC-822 format. for feed usage.
	my ($epoch)=@_;
	use POSIX qw(strftime);
	return strftime("%a, %d %b %Y %H:%M:%S GMT", gmtime($epoch));
}
sub format_date { #from 140802 . to: formatted string. year MUST >=2000
#switch mode:
#0 => Aug-02
#1 => August 2, 2014
#2 => standard format for rss, use GMT only
	my ($k,$date,$switch)=@_;
	if ($switch and $switch==2) {
		my $epoch;
		if (!$date or $date!~/^\d{5,6}$/) {
			$epoch=time;
		} else {
			use Time::Local;
			my ($yr,$mo,$dy)=$k->split_date($date);
			$mo=1 if $mo==0; #for "year single"
			$dy=1 if $dy==0;
			$mo--;
			$epoch=timegm(59,59,23,$dy,$mo,$yr+100);
		}
		return _format_time_feed_gmt($epoch);
	}
	else {
		my ($yr,$mo,$dy)=$k->split_date($date);
		if (!$switch) {
			return sprintf "%s-%02d",$k->_hash_month($mo,1),$dy;
		} elsif ($switch==1) {
			return sprintf "%s %d, 20%d",$k->_hash_month($mo),$dy,$yr;
		}
	}
}
sub _epoch2stamp27 { #27-H Mode: time < 3:00 AM, continue from 24 (0:00 AM=> 24:00, 2:59 AM => 26:59, 3:00AM => no change)
	my ($k,$epoch,$gmt)=@_;
	$epoch=time if !$epoch;
	my $timeref;
	my $tz=-7;
	if ($gmt) {
		if ($tz !=$gmt) { $epoch+=($gmt-$tz)*3600; } #since later "localtime" uses -7, need to adjust the actual time to be the desired GMT here
		$timeref->{timezone}=$gmt;
	} else {
		$timeref->{timezone}=$tz;
	}
	#27H mode
	my @lc=localtime $epoch;
	my $plus;
	if ($lc[2]<3) { #if time is before 3:00am
		$epoch=$epoch-24*60*60; #shift epoch back by 1 day
		$plus=1;
	}
	my @time=localtime $epoch;
	$timeref->{second}=$time[0];
	$timeref->{minute}=$time[1];
	$timeref->{hour}=$time[2];
	$timeref->{day}=$time[3];
	$timeref->{month}=$time[4]+1; #human order  1-12
	$timeref->{year} = $time[5]+1900;
	$timeref->{weekday}=$time[6];
	$timeref->{hour}+=24 if $plus; #raises hour after 24
	$timeref;
}
#below: timezone. won't work since cgi runs on the server
=pod
sub get_timezone { #absolute timezone. in -7 with summer time, timezone is NOT switched to -6
	shift;
	my $epoch=time;
	my @ltime=localtime $epoch;
	my @gtime=gmtime $epoch;
	return 24*($ltime[3]-$gtime[3])+$ltime[2]-$gtime[2]-$ltime[8];
}
=cut
sub format_time { #from epoch . to: 2014-Jun-29 (Wed), 23:12@GMT-7 or RFC-822
	my ($k,$epoch,$gmt,$forfeed)=@_;
	my $tstr;
	if ($forfeed) { #for feed use, standard GMT time in RFC-822
		$tstr=_format_time_feed_gmt($epoch);
	} else { #my 27H clock for blog use!
		my $tref=$k->_epoch2stamp27($epoch,$gmt);
		$tstr=sprintf "%i-%s-%02d (%s), %02d:%02d\@GMT%+d",$tref->{year},$k->_hash_month($tref->{month},1),$tref->{day},$k->_array_wkday($tref->{weekday},1),$tref->{hour},$tref->{minute},$tref->{timezone};
	}
	$tstr;
}
sub epoch2date { #140821, 27H flag
	my ($k,$epoch,$gmt)=@_;
	my $tr=$k->_epoch2stamp27($epoch,$gmt);
	return sprintf "%02d%02d%02d",(substr $tr->{year},-2),$tr->{month},$tr->{day};
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
}

{#img url/tagging...
sub mk_url_google_album {
	my ($k,$pid)=@_;
	return sprintf "https://plus.google.com/photos/%s/albums/%s",$Method_Config::CONSTANT->{GPLUS_UID},$pid;
}
sub mk_url_google_img { #feed in lh,mid,picid return full url
	my ($k,$gp_lh,$gp_mid,$gid,$ext,$size)=@_;
	$size='s640' if !$size;
	if ($ext and $ext==1) { $ext='png'; }
	elsif ($ext and $ext==2) { $ext='gif'; }
	elsif ($ext and $ext==3) { $ext='bmp'; }
	else { $ext='jpg'; }
	return sprintf "https://lh%i.googleusercontent.com/%s/%s/%s.%s",$gp_lh,$gp_mid,$size,$gid,$ext;
}
sub parse_gp_url { #better to separate since it's easier to choose your output img dimension
# regular url example: https://lh6.googleusercontent.com/-fpq3bdyGRZk/VFBTEfnlCmI/AAAAAAAAY3U/0G6WJGu8mKY/w1425-h802-no/141028%2Bsh4%2B%283%29.png
	my ($k,$url)=@_;
	$url=~s#   ^https?://   ##xig;
	my ($lh,$tmp)=$url=~m{^lh(\d+)\.googleusercontent\.com/(.+)$}i;
	my @t2=split "/",$tmp;
	my $name=pop @t2;
	pop @t2; #size info
	my $mid=join "/",@t2;
	my $ext;
	if ($name=~/\.bmp$/i) {  $name=$`;$ext=3; }
	elsif ($name=~/\.gif$/i) { $name=$`; $ext=2; }
	elsif ($name=~/\.png$/i) { $name=$`; $ext=1; }
	elsif ($name=~/\.jpe?g$/i) { $name=$`; $ext=0; }
	else { $ext=0; }
	return ($lh,$mid,$name,$ext);
}
}

1;
