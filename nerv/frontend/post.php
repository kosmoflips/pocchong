<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/synapse.php');

$p=new PocPage;
$k=new PocDB();
$curr=$_GET['page']??1; #current page index
$id=$_GET['id']??0;
$posts=array();
if ($id) {
	$entry1=$k->getRow('SELECT * FROM post WHERE id=?',array($id));
	if ($entry1) { #id exists
		$canread=check_post_access($entry1['hide']);
		if (!$canread) { # no access, return 403
			show_response(403);
		}
		$posts[]=$entry1;
		$page_title=$entry1['title'];
		#prev/next info when showing only 1 id
		$navipair=mk_navi_pair($k,'post',$id, POC_DB_POST['url']);
	} else {
		show_response(404);
	}
}
else { # id isn't given, go to page mode
	$totalrows=$k->countRows('post');
	$entry_per_page=POC_DB_POST['max'];
	$totalpgs=calc_total_page($totalrows,$entry_per_page);
	if ($totalpgs<$curr) { # currently selected page > total pages
		show_response(404);
	}
	$offset=calc_page_offset($curr,$entry_per_page);
	$stat1='SELECT id,title,epoch,gmt,content FROM post';
	if (!chklogin()) { # not logged in, no access to hidden posts
		$stat1.=' WHERE hide is null '; # only select non-hidden entries
	}
	$stat1.=' ORDER BY epoch DESC LIMIT ?,?';
	$posts=$k->getAll($stat1, array($offset,$entry_per_page));
	if (!empty($posts)) { # can get at least one post (doesnt count hidden post when there's no admin access)
		$baseurl_p=POC_DB_POST['url'].'?page=';
		$navibar=mk_navi_bar(1,$totalpgs,$entry_per_page,$curr,POC_NAVI_STEP,$baseurl_p);
	} else {
		show_response(403);
	}
}
$p->title=$page_title??POC_DB_POST['title'];
$p->navi['pair']=$navipair??null;
$p->navi['bar']=$navibar??null;

$p->html_open(1);
foreach ($posts as $entry) {
	$posturl=POC_DB_POST['url'].'?id='.$entry['id'];
	$entry2=add_lightbox_tag($entry['content'], $entry['id']); # add lightbox for <img> if alt="!lightbox" isn't present
	$p->html_open(2);
?>
<div class="datetime"><a href="<?php echo $posturl ?>"><?php echo clock27( $entry['epoch'],0,$entry['gmt']) ?></a></div>
<h3><a href="<?php echo $posturl ?>"><?php echo rand_deco_symbol(), ' ',$entry['title']; ?></a></h3>
<article>
<?php
echo $entry2,"\n";
?>
</article>
<?php
	print_edit_button(POC_DB_POST['edit'].'?id='.$entry['id']);
	$p->html_close(2);
}
$p->html_close(1);
?>
<?php // ----- subs -----
function check_post_access ($is_hidden=0) { # is_hidden is the value in DB , column "hide"
	$canread=0;
	if ($is_hidden) { # is hidden post, chk if can access
		if (chklogin()) { # logged-in session, always grant access
			$canread=1;
		}
	} else { # not a hidden post
		$canread=1;
	}
	return ($canread);
}
function process_img_alt_tag ($matches, $tag="") {
	if (!preg_match('/<a/', $matches[1])) { # only decide if to add lightbox tag when <img> isn't wrapped by <a href="xxx">
		$url=trim($matches[2]);
		$rest=$matches[3];
		if (preg_match('/alt=\s*["\'](.*?)["\']/', $rest, $zz)) { # when alt="xxx" is present
			$altstr=trim($zz[1]);
			if ($altstr == "!lightbox") { # my special tag to DISABLE using lightbox
				return ($matches[0]); # original <img> line
			}
			elseif (!preg_match('/\S/', $altstr)) { # alt="" (all empty string), use original img url
				$url2=$url;
			}
			elseif ($altstr == 'lightbox' ) { # alt="lightbox", use original img url
				$url2=$url;
			}
			else { # alt="SUPPOSED_TO_BE_AN_URL" , will use the new URL as ligntbox linked target
				$url2=$altstr;
			}
		} else { # alt="xxx" isn't present, use original img url
			$url2=$url;
		}
		$x=sprintf ('<a href="%s" data-lightbox="pid%s"><img src="%s"%s></a>', $url2, $tag, $url, $rest);
		return ($x);
	} else {
		return $matches[0];
	}
}
function add_lightbox_tag ($content='', $tag="uniq_string") { # convert <img ...> not wrapped by <a> with lightbox tag. treat all images in one post as a set
	# find pattern as: <a href=...><img src...></a>; <a> pair is optional
	$content = preg_replace_callback (
		'/(<a href=.+?>)?<img \s+ src \s* = \s* [\'"] (.*?) [\'"] (.*?)>(<\/a\s*>)?/x', # being careful here and included places for possible white spaces
		function ($matches) use ($tag) { return (process_img_alt_tag($matches, $tag)); },
		$content
	);
	return ($content);
}
?>
