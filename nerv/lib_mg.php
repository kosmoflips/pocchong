<?php
function mk_mg_img_url ($path='') { # convert stored in db img path to site-defined img path
# made on 2022-oct-28, since i switch to host image on localhost instead of google
	return ('/img/'.$path);
}
function mk_url_google_img ($url='',$size='') { // input  has no https://
# as of 2022-Mar-5 , new url format on blogger: https://blogger.googleusercontent.com/img/a/a_super_long_string=s320
# for old googleusercontent link [https://lh4.googleusercontent.com/string-for-this-img/may-contain-multiple-slashes/s500/], keep as is unless they stop working.
	if (!$url) { return ''; }
	if (!$size) {
		$size='s800';
	}
	if (preg_match('/blogger.googleusercontent.com/',$url)) {
		$url=preg_replace('/=[swh]\d+\/?$/i', '', $url);
		$url2=sprintf ('https://%s=%s', $url,$size);
	}
	else { # old url style
		$t=explode ('/', $url);
		$fname=array_pop($t);
		array_pop($t); #remove size << supposed to always be there, didn't tested!
		// if (!$size or !preg_match('/^[hws]\d+/i', $size)) { #simple check. should work for most cases
		# url has no defined size. OR user doesn't give new size
			// $size='s800';
		// }
		$url2=sprintf ('https://%s/%s/%s', (implode ( '/', $t)), $size, $fname);
	}
	return $url2;
}
function mk_url_da($url='') { #feed in string after ../art/. uses my dA account
	if ($url) {
		// return "http://kosmoflips.deviantart.com/art/".$url; // old url format
		return "https://www.deviantart.com/kosmoflips/art/".$url;
	} else {
		return '';
	}
}

?>