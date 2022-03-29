<?php
require_once(NERV.'/lib_mg.php');
$pic=get_random_mg();
?>
<h4><?php echo rand_deco_symbol(); ?> <a href="<?php echo $pic['url']?>">[<?php echo clock27($pic['epoch'], 5, $pic['gmt']); ?>] <?php echo $pic['title']; ?></a></h4>
<div>
<a href="<?php echo $pic['url']; ?>"><img style="width:100%" src="<?php echo mk_url_google_img($pic['img_url'],'w350'); ?>" alt="" /></a>
</div>
<?php
function get_random_mg () {
	$k=new PocDB;
	$idmin=38; // do not retrieve id lower than this. non-inclusive
	$idmin2=136; // the 1st FACL. inclusive
	$cut1=3; // FACL
	$cut2=2; // all
	$group=array();
	for ($i=0; $i<$cut1; $i++) {
		$group[]=$k->getRow('SELECT id,title,gmt,epoch FROM '.POC_DB['MYGIRLS']['table'].' WHERE id>=? ORDER BY RANDOM() LIMIT 1', array($idmin2) );
	}
	for ($i=0; $i<$cut2; $i++) {
		$group[]=$k->getRow('SELECT id,title,gmt,epoch FROM '.POC_DB['MYGIRLS']['table'].' WHERE id>? and id<? ORDER BY RANDOM() LIMIT 1', array($idmin,$idmin2) );
	}
	$pic=$group[(rand(1,sizeof($group))-1)];
	$pic2=$k->getOne('SELECT img_url FROM '.POC_DB['MYGIRLS']['table_pcs'].' WHERE title_id=? and stdalone=1 ORDER BY RANDOM() LIMIT 1', array($pic['id']) );
	$pic['img_url']=$pic2;
	$pic['url']=sprintf ('%s/%s', POC_DB['MYGIRLS']['url'], $pic['id']);
	return $pic;
}
?>
