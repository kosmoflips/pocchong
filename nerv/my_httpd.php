<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/synapse.php');

$p=new PocPage;

# common codes
$codeidx=array(
	403=>'Forbidden',
	404=>'Not Found',
	500=>'Internal Server Error',
	503=>'Service Unavailable'
);

$code=$_GET['code'];
if (array_key_exists($code, $codeidx)) {
	$p->title=$code.' '.$codeidx[$code];
} else {
	$p->title='HTTP Error '.$code;
}
$p->httpd_open();
?>
<h1><?php echo $p->title; ?></h1>
<div style="margin-top:50px">
URI <code><u><?php echo $_SERVER['REQUEST_URI']; ?></u></code> <?php
if ($code==403) {
	echo 'requires to log in before viewing.';
}
elseif ($code==404) {
	echo 'does not exist.';
}
elseif ($code==500) {
	echo 'has caused the server to die.';
}
elseif ($code==503) {
	echo 'is temporarily not available.';
}
?>
</div>
<div style="margin-top:100px"><a href="/">&#128760; return home</a></div>
<?php
$p->httpd_close();
?>
