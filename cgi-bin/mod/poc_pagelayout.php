<?php
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

public function head ($data=array()) { // add css/js/other tags into <head>
	foreach ($data as $k=>$v) {
		if (array_key_exists($k,$this->head)) {
			if (!empty($v)) {
				foreach ($v as $c) {
					$this->head[$k][]=$c;
				}
			}
		}
	}
}

// write html subs
public function html_open ($sel=0) {
	if ($sel!=2) {
		include (POCCHONG['PATH']['layout'].'/site_master1.php');
	}
	if ($sel!=1) {
		include (POCCHONG['PATH']['layout'].'/entry_open.html');
	}
}
public function html_close ($sel=0) {
	if ($sel!=1) {
		include (POCCHONG['PATH']['layout'].'/entry_close.html');
	}
	if ($sel!=2) {
		include (POCCHONG['PATH']['layout'].'/site_master2.php');
	}
}
public function html_head_title () {
	if ($this->title) {
		echo $this->title,' | ';
	}
	echo POCCHONG['domain-alias'];
}
public function html_head_stuff () { // custom head stuff
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

public function html_admin_navi () {
	include (POCCHONG['PATH']['layout'].'/navi.php');
}
static function html_admin ($close=0) {
	if (!$close) {
		include (POCCHONG['PATH']['layout'].'/layout_admin1.php');
	} else {
		include (POCCHONG['PATH']['layout'].'/layout_admin2.php');
	}
}

} // class closing bracket

?>