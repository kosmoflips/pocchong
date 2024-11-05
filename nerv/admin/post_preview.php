<?php
$PAGE=new PocPage;
$PAGE->html_open();
?>
<div style="z-index:20;position:fixed;background:rgba(0,0,0,0.7);padding:20px 0;text-align:center;display:block;width:100%;left:-100px;top:50px;font-size:30px;font-weight:bold;color:white;transform: rotate(-20deg)">PREVIEW</div>
<div class="datetime"><?php echo clock27( time(),0,POC_META['default_gmt'],0); ?></div>
<h3>* <?php echo $_POST['entry']['title']??'No Title'; ?> *</h3>
<article>
<?php echo $_POST['entry']['content']??'no content . . .'; ?>
</article>
<?php
$PAGE->html_close();
?>
