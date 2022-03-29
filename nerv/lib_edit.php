<?php // -------------- print html element --------------
function write_preview_sash () {
	?>
<div style="z-index:20;position:fixed;background:rgba(0,0,0,0.7);padding:20px 0;text-align:center;display:block;width:100%;left:-100px;top:50px;font-size:30px;font-weight:bold;color:white;transform: rotate(-20deg)">PREVIEW</div>
<?php
}
function print_system_msg ($msg='') { // admin submit-page edit only
	if (!empty($msg)) {
		?>
<div class="system-msg">system message: <?php echo $msg ?></div>
<?php
	}
}

?>

