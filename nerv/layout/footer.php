<?php // subs
function show_theme_selector() { // css seletor. use in footer to show a drop down menu of available themes, define in db-ini file
	$curr_theme=$_COOKIE['theme'] ?? '';
	echo '<select id="css-chooser" onchange="changeCSS()">', "\n";
	if (!$curr_theme or !in_array($curr_theme, POC_THEME)) { # cookie recorded theme doesn't exist or isn't found in defined css list
		$curr_theme=POC_THEME[0];
	}
	# loop defined themes and print drop down menu
	echo '<option value="" disabled>theme</option>',"\n";
	foreach (POC_THEME as $i=>$fcss) {
		if ($i==0) {
			continue; # skip 0/default
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
function get_git_version () {
	$gver='master';
	$gver1='master';
	$headfile=$_SERVER['DOCUMENT_ROOT'].'/.git/HEAD';
	if (file_exists($headfile)) {
		$headref=file_get_contents($headfile);
		preg_match('/ref:\s*(.+?)$/', $headref,$loc1);
		$gvfile=$_SERVER['DOCUMENT_ROOT'].'/.git/'.($loc1[1]??'refs/heads/master'); # current head hash or master (default)
		if (file_exists($gvfile)) {
			$gver=file_get_contents($gvfile);
			$gver1=substr($gver,0,7);
		}
	}
	return array($gver,$gver1);
}
$gitinfo=get_git_version();
?>
<div class='credit'><?php
echo '<a href="/about">';
printf ('%04d-%04d %s', POC_YEAR_START, date('Y'), POC_META['credit']);
echo '</a> | ';
printf ('<a href="https://github.com/kosmoflips/pocchong/tree/%s target="_blank ">version: %s</a> | ', $gitinfo[0], $gitinfo[1]);
show_theme_selector();
?></div>
