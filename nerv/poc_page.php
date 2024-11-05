<?php // ----- class for page layout; especially for vars relies on internal process by other included files -----
class PocPage {
// public $head=array();
public $title=null;
public $navi=array(
	'pair'=>null,
	'bar'=>null,
	);
public $data=null;

/*
# assign css/js/extra (e.g. style) to $this->head
public function add_html_head_block ($list=array()) { # input array, each element is a complete block of code to be placed in <head>, e.g. <script ...> <style>
	foreach ($list as $elem) {
		$this->head[]=$elem;
	}
}
*/

// write html subs
public function httpd_open () {
	include (POC_LAYOUT.'/layout_httpd1.php');
}
public function httpd_close () {
	include (POC_LAYOUT.'/layout_httpd2.php');
}
public function html_open ($mode=0) { #  mode=2, entry open (e.g. for one single post); mode=1; master open (does NOT have entry open); mode=0, entry + master open (page with one post block)
	if ($mode!=2) {
		include (POC_LAYOUT.'/layout_master1.php');
	}
	if ($mode!=1) {
		include (POC_LAYOUT.'/entry_open.html');
	}
}
public function html_close ($mode=0) { # mode same as html_open
	if ($mode!=1) {
		include (POC_LAYOUT.'/entry_close.html');
	}
	if ($mode!=2) {
		include (POC_LAYOUT.'/layout_master2.php');
	}
}
public function static_open ($half=0, $hide_title_bar=0) {
	if ($half==0 or $half==1) {
		include (POC_LAYOUT.'/layout_master1a.php');
		// custom style in html file
	}
	if ($half==0 or $half==2) { // print title h2 line
		include (POC_LAYOUT.'/layout_master1b.php');
		include (POC_LAYOUT.'/entry_open.html');
		if (!$hide_title_bar) {
			$symbol=rand_deco_symbol();
?>
<h2><?php echo $symbol,' ', $this->title??'no title',' ',$symbol; ?></h2>
<article>
<?php
		}
	}
}
/*
public function print_html_head_block () { // custom head stuff, used in layout/meta.php
	if (sizeof($this->head)>0) {
		echo "<!-- user given head blocks: start-->\n";
		foreach ($this->head as $line) {
			echo $line,"\n";
		}
		echo "<!-- user given head blocks: end-->\n";
	}
}
*/

// admin navi bar
static function html_admin ($close=0) {
	if (!$close) {
		include (POC_LAYOUT.'/layout_admin1.php');
	} else {
		include (POC_LAYOUT.'/layout_admin2.php');
	}
}

} // class closing bracket
?>
