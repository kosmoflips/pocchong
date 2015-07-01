package Method_SQLBranch;
use strict;
use warnings; no warnings 'utf8';
use utf8;
use Method_Kiyoism;
use Method_Config; #stores all constants

#use this shared sub to solve the problem of web address mask
#for cgi-bin/main, table=post, tag=1,2,...
use base 'Exporter';
our @EXPORT = qw/
process_param_post
/;

sub process_param_post { #input tag is important!
	my ($p)=@_; #$p is cgi param
	my $k=Method_Kiyoism->new;
	my $ASSIGN=3;
	$p->{offset}=0 if !$p->{offset};
	my $limit=($p->{limit}?$p->{limit}:$Method_Config::SECTOR->{$ASSIGN}{LIMIT});
	my $tag=1;
	if (!$p->{tag} or $p->{tag}!~/^\d+$/) {
		#do nothing
	} elsif ($p->{tag}==2) {
		$tag=2;
		$ASSIGN=4;
	} elsif ($p->{tag}==3) {
		$tag=3;
		$ASSIGN=5;
	}
	my $sth;
	my $stat0=sprintf "select id,title,epoch,content,gmt from post where tag=%i",$tag;
	my $stat;
	my ($min,$max);
	if ($p->{id}) {
		$stat=sprintf "%s and id=?",$stat0;
		$sth=$k->dosql($stat,$p->{id});
	}
	else { #no id given, take the newest
		$stat=sprintf "%s order by epoch desc limit ?,?",$stat0;
		$sth=$k->dosql($stat,$p->{offset},$limit);
	}

	my ($post,$firstepoch,$lastepoch);
	#mk post loop
	if ($sth->rows>0) {
		$post->{PAGE_TITLE}=$Method_Config::SECTOR->{$ASSIGN}{H3};
		while (my $ref=$sth->fetchrow_hashref) {
			$firstepoch=$ref->{epoch} if !$firstepoch;
			$lastepoch=$ref->{epoch};
			push @{$post->{posts}},{
				timestamp=>$k->format_time($ref->{epoch},$ref->{gmt}),
				title=>$ref->{title},
				content=>$ref->{content},
			};
			if ($k->chklogin) {
				$post->{posts}[-1]{edit}=sprintf '%s?id=%i&amp;tag=%i',$Method_Config::SECTOR->{$ASSIGN}{EDIT},$ref->{id},$tag;
			}
			if ($p->{id}) { #has id in param, show post title in head
				$post->{PAGE_TITLE}=$ref->{title};
			}
			#now check for each post whether it's pin protected
			if (my $pin=$k->get_pin_info($ASSIGN,$ref->{id})) { #this entry has a pin
				#verify input pin
				my $v=$k->verify_pin($ASSIGN,$ref->{id},$p->{pin});
				if (!$v) {
					$post->{posts}[-1]{content}='';
					$post->{posts}[-1]{need_pin}=1;
					$post->{posts}[-1]{id}=$ref->{id};
					$post->{posts}[-1]{pin_hint}=$pin->{hint};
				}
			}
					#remove unnecessary content for cd list
			if ($tag==3) {
				delete $post->{posts}[-1]{timestamp};
			}

			# link to each indiv post
			$post->{posts}[-1]{this_url}=sprintf '%s/%i',$Method_Config::SECTOR->{$ASSIGN}{URL},$ref->{id};
		}

		#grab titles for prev/next
		if (my $r=$k->get1row('select id,title from post where tag=? and epoch<? order by epoch desc limit 1',$tag,$lastepoch)) { #if nothing extracted, you're at the beginning of table
			$post->{prev}=$r->{title};
			if ($p->{id}) {
				$post->{prev_url}=sprintf '%s/%i',$Method_Config::SECTOR->{$ASSIGN}{URL},$r->{id};
			} else {
				$post->{prev_url}=sprintf '%s/?offset=%i',$Method_Config::SECTOR->{$ASSIGN}{URL},$k->calc_offset($p->{offset},$limit,1);
			}
		}
		if (my $r=$k->get1row('select id,title from post where tag=? and epoch>? order by epoch limit 1',$tag,$firstepoch)) {
			$post->{next}=$r->{title};
			if ($p->{id}) {
				$post->{next_url}=sprintf '%s/%i',$Method_Config::SECTOR->{$ASSIGN}{URL},$r->{id};
			} else {
				$post->{next_url}=sprintf '%s/?offset=%i',$Method_Config::SECTOR->{$ASSIGN}{URL},$k->calc_offset($p->{offset},$limit);
			}
		}

		$post->{PAGE_TITLE}=sprintf '%s | %s', $post->{PAGE_TITLE},$Method_Config::META->{SITE_TITLE};
		$k->output_tmpl($Method_Config::SECTOR->{$ASSIGN}{TMPL},$post);
	} else {
		$k->output_tmpl_404;
	}
	exit;
}

1;