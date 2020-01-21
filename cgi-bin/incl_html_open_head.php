<?php global $POCCHONG ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type"/><meta name="generator" content="Method_Kiyoism_Remaster/." />
<link href="<?php echo $POCCHONG['SITE']['domain'] ?>" rel="canonical" />
<?php
	print_link_in_head($POCCHONG['FILE']['js'],$POCCHONG['FILE']['css']);
	if ($title) {
		$title.=' | '.$POCCHONG['SITE']['maintitle'];
	} else {
		$title=$POCCHONG['SITE']['maintitle'];
	}
	echo '<title>',$title,'</title>', "\n";
//</head> will be printed in "include-body"
?>
