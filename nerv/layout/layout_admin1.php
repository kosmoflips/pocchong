<!DOCTYPE html>
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/synapse.php'); ?>
<html>
<head>
<meta charset="utf-8" />
<?php
$fname=basename($_SERVER['SCRIPT_FILENAME'], '.php');
?>
<title><?php echo 'pocchong::admin::',$fname; ?></title>
<link href="/deco/css/admin.css" type="text/css" rel="stylesheet" />
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
</head>
<body>
<div class="main">
