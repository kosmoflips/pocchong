<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/synapse.php');

// ------------ data process --------------
chklogin(1);

$gitremote='https://github.com/kosmoflips/pocchong.git';
function rungithubcode ($cmd='', $info='') {
	if (!$cmd) {
		return 'nothing is run';
	}
	echo "<div>";
	if ($info) {
		echo '# <b>', $info, "</b><br />";
	}
	echo '<h4>', $cmd, "</h4>";
	echo '<pre>';
	system($cmd, $run1);
	echo '</pre>';
	echo 'exit code:' , $run1;
	echo "</div>";
}
?>
<!doctype html>
<html>
<head>
<style>
div {
	border-bottom: dashed 1px blue;
	padding-bottom: 20px;
	margin-bottom: 20px;
}
</style>
</head>
<body>
<h3>Pull from github's origin</h3>
<div>current origin is: <code><?php echo $gitremote; ?></code></div>

<hr />
<?php
if ($_SERVER['SERVER_NAME'] != 'localhost') { # only pull from github when it's under real server. Note if localhost config is using 127.x.x.x this won't work
# because my localhost likely can't access gitbash, this block is tested directly from remote
	# this php file is under /admin, so no need to change working dir to root and git will still work (hopefully)
	// $cmd_fetch='git fetch origin';
	$cmd_fetch='git log --oneline -4';
	rungithubcode($cmd_fetch, 'before the pull');
	
	rungithubcode('git fetch origin');
	rungithubcode('git pull');
	
	rungithubcode($cmd_fetch, 'after the pull');
} else {
	echo "you're on /localhost, the pull isn't necessary.";
}
?>

<hr />

__END__

</body>
</html>