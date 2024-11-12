<?php 	// ------------ system config, keep on top----------------
// https://stackoverflow.com/questions/2447791/php-define-vs-const
// Unless you need any type of conditional or expressional definition, use consts instead of define()s - simply for the sake of readability!

const POC_META = array(
	'title' => "音時雨 ～Regentropfen～",
	'domain' => 'http://www.pocchong.de',
	'alias' => 'pocchong.de',
	'credit' => 'kiyo@otoshigure',
	'default_gmt'=>-6
);
const POC_YEAR_START = 2006; # first posted year
const POC_IMG_PLACEHOLDER = 'placeholder.png';

// -------- db stuff -----------
const POC_DB_POST = array(
	'title' => 'Days',
	// 'table' => 'post',
	'url' => '/days/',
	'max' => 3, # show N posts per page
	'admin_list'=>'/a/list_table/?sel=1',
	'new' => '/a/post_new/',
	'edit' => '/a/post_edit/',
	'save' => '/a/post_save/'
);
const POC_DB_MG = array(
	'title' => 'MyGirls',
	'title2' => "幻想調和",
	// 'table' => 'mygirls',
	// 'table_link' => 'mygirls_link',
	// 'table_pcs' => 'mygirls_pcs',
	'url' => '/mygirls/',
	'admin_list'=>'/a/list_table/?sel=2',
	'new' => '/a/mg_new/',
	'edit' => '/a/mg_edit/',
	'save' => '/a/mg_save/',
	'max_gallery' => 12
);
const POC_DB_ARCHIV = array(
	'title' => 'Archiv',
	'table' => 'post',
	'url' => '/archiv/',
	'max' => 50,
	'yr_max' => 3
);
const POC_DB_STATIC = array(
	'title' => 'Backyard',
	'url_index' => '/backyard/',
	'url' => '/s',
	'dir' => '/pages/static', # for indexed static pages
	'dir2' => '/pages/static2', # for non-indexed 'private' pages
);

// ----------- layout stuff ----------
const POC_LAYOUT = NERV.'/layout';
const POC_NAVI_STEP = 2;
# css theme files
const POC_THEME = array(
	'shinkai', # use [0] for default
	'ajisai', 'kirisame', 'natsugusa', 'sakura', 'shinkai', 'xmas'
);
?>
