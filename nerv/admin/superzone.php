<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/synapse.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/nerv/admin/adminfunc.php');

chklogin(1);

PocPage::html_admin();
?>
logged in as: <?php echo $_SESSION['username'] ?><br />
session timeout: <?php echo clock27($_SESSION["time_out"],0,POC_META['default_gmt'],1) ?><br />
<hr />
<?php
include(NERV.'/admin/incl_controlpanel.html');
PocPage::html_admin(1);
?>
