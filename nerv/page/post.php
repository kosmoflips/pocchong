<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/synapse.php');
require_once(NERV.'/lib_navicalc.php');

$p=new PocPage;
process_data_post($p,$_GET['id']??null, $_GET['page']??null);
$p->html_open(1);
foreach ($p->data as $entry) {
	print_post_single($p,$entry);
}
$p->html_close(1);

//-------------------------
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
function process_data_post ($pobj=null,$id=0,$page=0) {
	if (!$pobj) {
		show_response(500);
	}
	$pack=POC_DB['POST'];
	$k=new PocDB();
	$step=POC_DB['navi_step'];
	$curr=$page??1; #current page index
	$posts=array();
	if ($id) {
		$entry1=$k->getRow('SELECT * FROM '.$pack['table'].' WHERE id=?',array($id));
		if ($entry1) { #id exists
			$canread=check_post_access($entry1['hide']);
			if (!$canread) { # no access, return 403
				show_response(403);
			}
			$posts[]=$entry1;
			$page_title=$entry1['title'];
			#prev/next info when showing only 1 id
			$navipair=mk_navi_pair($k,$pack['table'],$id, $pack['url']);
		} else {
			show_response(404);
		}
	}
	else { # id isn't given, go to page mode
		$totalrows=$k->countRows($pack['table']);
		$totalpgs=calc_total_page($totalrows,$pack['max']);
		if ($totalpgs<$curr) { # currently selected page > total pages
			show_response(404);
		}
		$offset=calc_page_offset($curr,$pack['max']);
		$stat1='SELECT id,title,epoch,gmt,content FROM '.$pack['table'];
		if (!chklogin()) { # not logged in, no access to hidden posts
			$stat1.=' WHERE hide is null '; # only select non-hidden entries
		}
		$stat1.=' ORDER BY epoch DESC LIMIT ?,?';
		$posts=$k->getAll($stat1, array($offset,$pack['max']));
		if (!empty($posts)) { # can get at least one post (doesnt count hidden post when there's no admin access)
			$baseurl_p=$pack['url'].'?page=';
			$navibar=mk_navi_bar(1,$totalpgs,$pack['max'],$curr,$step,$baseurl_p);
		} else {
			show_response(403);
		}
	}
	$pobj->title=$page_title??$pack['title'];
	$pobj->navi['pair']=$navipair??null;
	$pobj->navi['bar']=$navibar??null;
	$pobj->data=$posts; // is array()
}
function print_post_single($p,$entry) {
	$posturl=POC_DB['POST']['url'].'?id='.$entry['id'];
	$p->html_open(2);
	?>
<div class="datetime"><a href="<?php echo $posturl ?>"><?php echo clock27( $entry['epoch'],4,$entry['gmt']) ?></a></div>
<h3><a href="<?php echo $posturl ?>"><?php echo rand_deco_symbol(), ' ',$entry['title']; ?></a></h3>
<article>
<?php
# convert for lightbox. need custom flag if not want to use it?
$entry2=add_lightbox_tag($entry['content'], $entry['id']);
echo $entry2,"\n";
print_edit_button(POC_DB['POST']['edit'].'?id='.$entry['id']);
?>
</article>
<?php
	$p->html_close(2);
} // close print_post_single()
function add_lightbox_tag ($content='', $tag="uniq_string") { # convert <img ...> not wrapped by <a> with lightbox tag. treat all images in one post as a set
	# find pattern as: <a href=...><img src...></a>; <a> pair is optional
	$content = preg_replace_callback ('/(<a href=.+?>)?<img\s+src="(.+?)"(.*?)>(<\/a\s*>)?/', function ($matches) use ($tag) {
		if (!preg_match('/<a/', $matches[1])) { # only add lightbox if no <a href...> defined outside, and target URL should be inside alt=""
			$url=$matches[2];
			$rest=$matches[3];
			if (preg_match('/alt=["\'](.S+)["\']/', $rest, $zz)) { # alt="" is defined non-empty, use that as original img link <<< UNTESTED as of 2022-oct-27
				$url2=$zz[1];
			} else {
				$url2=$url;
			}
			$x=sprintf ('<a href="%s" data-lightbox="pid%s"><img src="%s"%s></a>', $url2, $tag, $url, $rest);
			return ($x);
		} else {
			return $matches[0];
		}
	}, $content);
	return ($content);
}

?>