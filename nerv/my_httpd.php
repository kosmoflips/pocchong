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
URI <code><u><?php echo $_SERVER['REQUEST_URI']; ?></u></code> 
<?php
if ($code==403) {
?>
requires to log in before viewing.
<?php
}
elseif ($code==404) {
?>
does not exist.
<?php
}
elseif ($code==500) {
?>
has caused the server to die.
<?php
}
elseif ($code==503) {
?>
is temporarily not available.
<?php
}
?>
</div>
<div style="margin-top:100px"><a href="/">&#128760; return home</a></div>
<?php
$p->httpd_close();
?>
