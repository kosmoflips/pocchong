<?php // ----- class for page layout, init -----
class PocPage {
public $title=null;
public $navi=array(
	'pair'=>null,
	'bar'=>null,
	);
public $data=null;


// ----- layout builders-----
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
			printf ("<h2>%s %s %s</h2>\n", $symbol, $this->title??'no title', $symbol);
			echo "<article>\n";
		}
	}
}
// admin navi bar
static function html_admin ($close=0) {
	if (!$close) {
		include (POC_LAYOUT.'/layout_admin1.php');
	} else {
		include (POC_LAYOUT.'/layout_admin2.php');
	}
}


// ------ include page elements ------
public function show_navi_bar () {
	if (isset($this->navi)) {
		include (POC_LAYOUT.'/navi.php');
	}
}
public function show_footer() {
	include(POC_LAYOUT.'/footer.php');
}
public function show_menu() {
	include(POC_LAYOUT.'/menu.php');
}
public function show_meta() {
	include(POC_LAYOUT.'/meta.php');
}
public function show_style() {
	include(POC_LAYOUT.'/style.php');
}
public function show_headerline() {
	include(POC_LAYOUT.'/headerline.php');
}

} // class closing bracket
?>
