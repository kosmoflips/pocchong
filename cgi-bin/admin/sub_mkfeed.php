<?php #UTF8 anchor (´・ω・｀)
require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/'.'Method_Kiyoism_Remaster.php');
?>
<?php
$k=new PocDB();

$rfile=$_SERVER['DOCUMENT_ROOT'].$POCCHONG['SITE']['rss'];
$fh=fopen($rfile,'w') or die('failed to open file');

$posts=$k->getAll('SELECT id,title,epoch,content FROM post ORDER BY id DESC LIMIT 50');
$arts=$k->getAll('SELECT mygirls.id,mygirls.epoch,mygirls.title,mygirls.notes,mygirls_pcs.img_url FROM mygirls JOIN mygirls_pcs ON mygirls.rep_id=mygirls_pcs.id ORDER BY mygirls.id DESC LIMIT 20');

fprintf ($fh, '<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
<title>%s</title>
<link>%s</link>
<atom:link rel="self" type="application/rss+xml" href="%s%s"/>
<description>%s</description>
<generator>Method_Kiyoism_Remaster/.</generator>',
	$POCCHONG['SITE']['maintitle'],
	$POCCHONG['SITE']['domain'],
	$POCCHONG['SITE']['domain'],
	$POCCHONG['SITE']['rss'],
	$POCCHONG['SITE']['subtitle']  );

$max=50;
$ic=0;
$items;
while ($ic<=$max) {
	$ta=empty($arts[0]['epoch'])?0:$arts[0]['epoch'];
	$tp=empty($posts[0]['epoch'])?0:$posts[0]['epoch'];
	if ($ta>$tp) {
		fprintf ($fh, '<item><title>[Art]%s</title><guid isPermaLink="true">http://%s%s/%s</guid><link>http://%s%s/%s</link><pubDate>%s</pubDate><description><![CDATA[<img src="https://%s" alt="" />%s]]></description></item>%s',
			$arts[0]['title'],
			$POCCHONG['SITE']['domain'],
			$POCCHONG['MYGIRLS']['url'], $arts[0]['id'],
			$POCCHONG['SITE']['domain'],
			$POCCHONG['MYGIRLS']['url'], $arts[0]['id'],
			date('r',$arts[0]['epoch']),
			$arts[0]['img_url'],
			(empty($arts[0]['notes'])?'<br />inspiration: '.$arts[0]['notes']:''),
			"\n");
		array_shift($arts);
	} else {
		strip_tags($posts[0]['content'],'<br>');
		$posts[0]['content']=mb_substr($posts[0]['content'],0,500);
		fprintf ($fh, '<item><title>[Diary]%s</title><guid isPermaLink="true">http://%s%s/%s</guid><link>http://%s%s/%s</link><pubDate>%s</pubDate><description><![CDATA[%s]]></description></item>%s', 
			$posts[0]['title'],
			$POCCHONG['SITE']['domain'],
			$POCCHONG['POST']['url'], $posts[0]['id'],
			$POCCHONG['SITE']['domain'],
			$POCCHONG['POST']['url'], $posts[0]['id'],
			date('r',$posts[0]['epoch']),
			$posts[0]['content'],
			"\n");
		array_shift($posts);
	}
	$ic++;
}
fprintf ($fh,"\t</channel>\n</rss>");
echo 'done';
?>
