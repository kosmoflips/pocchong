<?php // ----- class for page layout; especially for vars relies on internal process by other included files -----
class PocPage {
public $head=array(
		'css'=>array(), // css path
		'js'=>array(), // js path
		'extra'=>array(), // complete closed html tag
	);
public $title=null;
public $navi=array(
	'pair'=>null,
	'bar'=>null,
	);
public $data=null;

# assign css/js/extra (e.g. style) to $this->head
public function add_css ($list=array()) { # each item is link to css file
	$x='css';
	$this->_head(array($x=>$list));
}
public function add_js ($list=array()) { # each item is link to js file
	$x='js';
	$this->_head(array($x=>$list));
}
public function add_extra ($list=array()) { # full block of code to place in <head>
	$x='extra';
	$this->_head(array($x=>$list));
}
protected function _head ($data=array()) { // add css/js/other tags into <head>
	foreach ($data as $k=>$v) {
		foreach ($v as $c) {
			$this->head[$k][]=$c;
		}
	}
}

// write html subs
public function html_open ($sel=0) { #  sel=2, entry open; sel=1; master open; entry=0, entry + master open
	if ($sel!=2) {
		include (POC_LAYOUT.'/site_master1.php');
	}
	if ($sel!=1) {
		include (POC_LAYOUT.'/entry_open.html');
	}
}
public function html_close ($sel=0) { # sel =2, entry close; sel=1 , master close, sel=0, entry+master close
	if ($sel!=1) {
		include (POC_LAYOUT.'/entry_close.html');
	}
	if ($sel!=2) {
		include (POC_LAYOUT.'/site_master2.php');
	}
}
public function html_head_title () {
	if ($this->title) {
		echo $this->title,' | ';
	}
	echo POC_META['alias'];
}
public function html_head_stuff () { // custom head stuff, used in layout/meta.php
	if (!empty($this->head['js'])) {//extra js in array
		foreach ($this->head['js'] as $js) {
			echo '<script src="', $js, '"></script>',"\n";
		}
	}
	if (!empty($this->head['css'])) {//extra css array
		foreach ($this->head['css'] as $css) {
			echo '<link rel="stylesheet" type="text/css" href="', $css, '" />',"\n";
		}
	}
	if (!empty($this->head['extra'])) {//other, inline css/js
		foreach ($this->head['extra'] as $line) {
			echo $line,"\n";
		}
	}
}

// admin navi bar
public function html_admin_navi () {
	include (POC_LAYOUT.'/navi.php');
}
static function html_admin ($close=0) {
	if (!$close) {
		include (POC_LAYOUT.'/layout_admin1.php');
	} else {
		include (POC_LAYOUT.'/layout_admin2.php');
	}
}

} // class closing bracket
?>
<?php // ----- css file related -----
// ----- layout related functions but doesnt require global var
function show_theme_selector() { // css seletor. use in footer to show a drop down menu of available themes, define in db-ini file
	$curr_theme=$_COOKIE['theme'] ?? '';
	echo '<select id="css-chooser" onchange="changeCSS()">', "\n";
	if (!$curr_theme or $curr_theme == '_default_' or !array_key_exists($curr_theme, POC_DB['THEME'])) { # cookie recorded theme doesn't exist or isn't found in defined css list
		$curr_theme=POC_DB['THEME']['_default_'];
	}
	# loop defined themes and print drop down menu
	echo '<option value="" disabled>theme</option>',"\n";
	foreach (POC_DB['THEME'] as $fcss=>$v) {
		if ($fcss == '_default_') {
			continue;
		}
		printf ('<option value="%s"%s>%s</option>%s',
			$fcss,
			$curr_theme == $fcss? ' selected':'',
			$fcss, "\n" );
	}
	// print reset button to use server default theme
	echo '<option value="_default_">reset</option>',"\n";
	echo '</select>',"\n";
}
function mk_css_file_path ($csstag='', $showdefault=0) {
	$cssfile='/deco/css/theme_'.$csstag.'.css';
	$cssfile2=ROOT.'/dendron'.$cssfile; // real path on disk
	if (file_exists($cssfile2)) {
		return ($cssfile);
	} else {
		return ('/deco/css/theme_'.POC_DB['THEME']['_default_'].'.css');
	}
}
?>