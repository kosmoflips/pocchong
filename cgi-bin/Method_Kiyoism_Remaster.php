<?php // -------------- print html element --------------
function write_preview_sash () {
	echo '<div style="z-index:20;position:fixed;background:rgba(0,0,0,0.7);padding:20px 0;text-align:center;display:block;width:100%;left:-100px;top:50px;font-size:30px;font-weight:bold;color:white;transform: rotate(-20deg)">PREVIEW</div>',"\n";
}
function print_system_msg ($msg='') { // admin submit-page edit only
	if (!empty($msg)) {
		echo '<div class="system-msg">system message: '.$msg.'</div>';
	}
}

?>
<?php //-------------- misc -------------
function rand_array($total=2) {
	if ($total<2) { return array(0); } // 1 elem
	$numbers = range(0, ($total-1));
	shuffle($numbers);
	return $numbers;
}
?>
