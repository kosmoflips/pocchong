<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/synapse.php');

chklogin(1);
$k=new PocDB();

# get all columns from table
$info=$k->prepNew('mygirls');

# format selected keys
$info['insert']=1;
$info['id']=$k->nextID('mygirls');
$info['art_id']=($k->getOne('SELECT art_id FROM mygirls ORDER BY epoch DESC LIMIT 1') +1);
$info['epoch']=time();
$info['year']=date('Y')-2000;
$info['gmt']=POC_META['default_gmt'];

include(NERV.'/admin/incl_mygirlseditor.php');
?>