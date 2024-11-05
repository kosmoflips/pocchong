<?php
$p->title='Cute Souls Tarot Deck';
$p->static_open(1);
?>
<style>
table {
	border: none;
	margin:auto auto 50px;
	text-align: center;
}
td {
	border: none;
	font-size: 90%;
	vertical-align: top;
	margin: 0;
	padding: 0;
	width:140px !important;
}
td.card:hover {
	background: rgba(0,0,0,0.1);
	opacity: 0.5;
}
.mincardnum {
	background: rgba(0,0,0,0.1);
	font-weight:bold;
}
td img {
	border: 4px double #999 !important;
	width: 115px;
	max-width: 100% !important;
}
</style>
<?php
$p->static_open(2);
?>

<?php # define id/card link
$cardlink=array(
	'gros'=>array(
		# 0-5
		0, '湿った長杖', '逆棘の杖槍', 'Fil, デュナシャンドラ＋鎌 FACE RIGHT', 172, 0,
		# 6-11
		'ciara + artrias doll', 162,0,150,0, 0,
		# 12-17
		169, 'ivy', 'lyra, redesign', 'らんらん？', 0,154,
		# 18-21
		151, 'ER sunflower/shadow flowers (water reflection)', 0, 0
	),
	'stae'=>array( -1,
		'marie ブラボ松明＋教会黒セット',0,158,0,0,
		'lumi, spirit worms',165,173,'lotte, use 1s design','創星雨+星見セット',
		155,'caria piercer magic',171,0
	),
	'kelc'=>array( -1,
		'teri ER 星見台',167,'flame butterfly bin','blood vial x3. Iosefka Blood Vial.',157,
		'marie, Hintertomb, Pthumeru Ihyll, Lower Pthumeru, Central Pthumeru, Isz, Loran','sere',166,'nata. ER pots','ari. cup tower 1 3 5 + one in hand',
		174,'kyri 秘薬瓶','トリーナさんのお花＋花びら盃',159
	),
	'schw'=>array(-1,
		170,'不死斬りx2',0,164,0,
		'DS3 dancer x2, ER fire/mag x4','moonlight swords ルド聖、月光、DS3,2,1,古き、ER in BB costume 武器工房','use 9 design','ER sword grave + white noble set',153,
		'teri',0,161,'storm ruler/nameless sword-spear'
	),
	'muen'=>array(-1,
		160,175,'sunekosuri x3, one in dark realm','lyra. bb blood-gem','teri. mibu-ballon x5, 桜竜まえの人の着物',
		156,'lumi hang on ice tree','ari 儚い小宇宙x 8',163,'mia. golden tree seeds',
		'lumi hammer+ball magic OR 神託のBIGシャボン',168,'nata, DS3 DLC huge broken egg? / ブラボ ランタン',0
	)
);

function print_card_html($card_id, $card_key) {
	global $cardlink;
	global $k;
	$gx=$cardlink[$card_key][$card_id];
	if (preg_match('/^\d+$/', $gx)) {
		$gid=$gx;
		$txt='';
	} else {
		$txt=$gx;
		$gid=0;
	}
	$gid2=0;
	$img=sprintf ('cstmp/%s%02d.jpg', $card_key, $card_id);
	$data=$k->getAll("select img_url from mygirls_pcs where title_id=?", array($gid));
	foreach ($data as $link) { # find 'card' img link
		if (preg_match('/_card/', $link['img_url'])) {
			$img=$link['img_url'];
			$gid2=$gid;
			break;
		}
	}
	printf ('<a href="/mygirls/?id=%d"%s><img src="/img/%s" alt="%s-%d" /></a>%s', $gid2, ($gid2==0? ' onclick="return false"' : ''), $img, $card_key, $card_id, $txt);
}
function numberToRomanRepresentation($number) {
	# https://stackoverflow.com/questions/14994941/numbers-to-roman-numbers-with-php
    $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
    $returnValue = '';
    while ($number > 0) {
        foreach ($map as $roman => $int) {
            if($number >= $int) {
                $number -= $int;
                $returnValue .= $roman;
                break;
            }
        }
    }
    return $returnValue;
}
?>

<table>
<?php # ----- major -----
$ntd=6;
$ncol=ceil(22/$ntd);

$k=new PocDB();

for ($row1=0; $row1<$ncol; $row1++) {
	for ($rep1=1; $rep1<=2; $rep1++) {
		if ($rep1==1) {
			echo '<tr class="mincardnum">',"\n";
		} else {
			echo "<tr>\n";
		}
		for ($col1=0; $col1<$ntd; $col1++) {
			$ci=$col1+$row1*$ntd;
			if ($rep1==1) {
				echo "<th>";
			} else {
				echo "<td>";
			}
			if (array_key_exists($ci, $cardlink['gros'])) {
				if ($rep1==1) {
					if ($ci==0) {
						$num='O';
					} elseif ($ci<0) {
						$num='';
					} else {
						$num=numberToRomanRepresentation($ci);
					}
					echo $num;
				} else {
					if ($ci>=0) {
						 print_card_html($ci, 'gros');
						 echo "\n";
					} else {
						echo "<td></td>";
					}
				}
			} else {
				if ($rep1==1) {
					echo "</th>\n";
				} else {
					echo "</td>\n";
				}
			}
		}
	}
	echo "</tr>\n";
}
?>
</table>


<!-- minor card -->
<table>
<tr>
<th>Stäbe</th>
<th>Kelche</th>
<th>Schwerer</th>
<th>Münzen</th>
</tr>
<?php
for ($row1=1; $row1<=14; $row1++) {
	echo '<tr><td colspan="4" class="mincardnum">', $row1,"</td></tr>\n";
	echo "<tr>\n";
	foreach (array('stae','kelc','schw','muen') as $cardname) {
		echo "<td>";
		print_card_html($row1, $cardname);
		echo "</td>\n";
	}
	echo "</tr>\n";
}
?>
</table>

