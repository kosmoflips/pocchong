<?php
$db=new PocDB;
$line=$db->getOne('SELECT line FROM '.POC_DB['ONELINER']['table'].' ORDER BY RANDOM() LIMIT 1');
echo $line??'(._.)';
?>