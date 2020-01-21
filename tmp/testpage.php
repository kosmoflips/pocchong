<?php # UTF8 anchor (´・ω・｀)
echo $_SERVER['PHP_SELF']
// echo $_SERVER['DOCUMENT_ROOT']
?>


<!DOCTYPE html>
<html>
<head>
<title>Calendar Archiv</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<style>

</style>

<script>
$(document).ready(function() {
	$(".day-link").click(function() {
		var id = $(this).attr('href');//.replace(/#/, '');
		$('li.post-item').css({
			'background': 'none'
		});
		$(id).css({
			'background': 'red'
		});
	});
});
</script>

</head>
<body>
<div align="center">

<div style="max-height: 80%; border: green 1px solid;">
<div style="display:inline-block; width: 50%; overflow:hidden; vertical-align:top; border: red 1px solid">

<table>
<tr><td><table border="1">
<tr><th colspan="7">Oct</th></tr>
<tr><th>S</th><th>M</th><th>T</th><th>W</th><th>T</th><th>F</th><th>S</th></tr>
<tr><td></td><td></td><td></td><td></td><td>1</td><td>2</td><td>3</td></tr>
<tr>

<td><b><a href="#post100" class="day-link">4</a></b></td>

<td>5</td><td>6</td><td>7</td><td>8</td><td>9</td><td>10</td></tr>
<tr><td>11</td><td>12</td><td>13</td><td>14</td><td>15</td><td>16</td><td>17</td></tr>
<tr><td>18</td><td>19</td><td>20</td><td>21</td><td>22</td><td>23</td><td>24</td></tr>
<tr><td>25</td><td>26</td><td>27</td><td>28</td><td>29</td><td>30</td><td>31</td></tr>
</table></td>
<td><table border="1">
<tr><th colspan="7">Nov</th></tr>
<tr><th>S</th><th>M</th><th>T</th><th>W</th><th>T</th><th>F</th><th>S</th></tr>
<tr><td>1</td>

<td><a href="#post99" class="day-link">2</a></td>

<td>
3</td><td>4</td><td>5</td><td>6</td><td>7</td></tr>
<tr><td>8</td><td>9</td><td>10</td><td>11</td><td>12</td><td>13</td><td>14</td></tr>
<tr><td>15</td><td>16</td><td>17</td><td>18</td><td>19</td><td>20</td><td>21</td></tr>
<tr><td>22</td><td>23</td><td>24</td><td>25</td><td>26</td><td>27</td><td>28</td></tr>
<tr><td>29</td><td>30</td><td></td><td></td><td></td><td></td><td></td></tr>
</table></td>
<td><table border="1">
<tr><th colspan="7">Dec</th></tr>
<tr><th>S</th><th>M</th><th>T</th><th>W</th><th>T</th><th>F</th><th>S</th></tr>
<tr><td></td><td></td><td>1</td><td>2</td><td>3</td><td>4</td><td>5</td></tr>
<tr><td>6</td><td>7</td><td>8</td><td>9</td><td>10</td><td>11</td><td>12</td></tr>
<tr><td>13</td><td>14</td><td>15</td><td>16</td><td>17</td><td>18</td><td>19</td></tr>
<tr><td>20</td><td>21</td><td>22</td><td>23</td><td>24</td><td>25</td><td>26</td></tr>
<tr><td>27</td><td>28</td><td>29</td><td>30</td><td>31</td><td></td><td></td></tr>
</table></td>
</tr>
</table>
</div>

<div style="display:inline-block; max-height: inherit !important;overflow-y:scroll; border: 1px solid blue">
<div style="overflow-y:scroll" class="section">
<ul class="wrap">
<li><a href="/days/656"><span id="post1" class="archivdate">Oct-14</span> redevelop</a></li>
<li><a href="/days/655"><span class="archivdate">Oct-04</span> 1m 07s 16 ~ nice boat!</a></li>
<li><a href="/days/654"><span class="archivdate">Sep-15</span> Cloudy days' stimuli</a></li>
<li><a href="/days/653"><span class="archivdate">Aug-29</span> 青い瞳に惹かれて</a></li>
<li><a href="/days/652"><span class="archivdate">Aug-16</span> 集中力☆</a></li>
<li><a href="/days/651"><span class="archivdate">Aug-03</span> Moving to SQLite</a></li>
<li><a href="/days/650"><span class="archivdate">Jul-30</span> xx shades of dark blue and pink</a></li>
<li><a href="/days/649"><span class="archivdate">Jul-25</span> 指を痛めるほどノートちゃんのお掃除♪</a></li>
<li><a href="/days/648"><span class="archivdate">Jul-07</span> 遅れた七夕</a></li>
<li><a href="/days/647"><span class="archivdate">Jun-19</span> bootloader unlock allowed: no (# ﾟДﾟ)</a></li>
<li><a href="/days/646"><span class="archivdate">Jun-02</span> Summer horror time? and the moon</a></li>
<li><a href="/days/645"><span class="archivdate">May-27</span> Yorlga II Inspiration</a></li>
<li><a href="/days/644"><span class="archivdate">May-21</span> 悪意しかないよぉぉ</a></li>
<li><a href="/days/643"><span class="archivdate">May-13</span> Layers of Neuroimaging</a></li>
<li><a href="/days/642"><span class="archivdate">Apr-30</span> Bye-bye Presets!</a></li>
<li><a href="/days/641"><span class="archivdate">Apr-23</span> 2D nerd;</a></li>
<li><a href="/days/640"><span class="archivdate">Apr-08</span> Ahi esta! ありえんな～(,,ﾟДﾟ)！</a></li>
<li><a href="/days/639"><span class="archivdate">Mar-30</span> Artistically grotesque</a></li>
<li><a href="/days/638"><span class="archivdate">Mar-18</span> Revive Remake Reincarnation</a></li>
<li><a href="/days/637"><span class="archivdate">Mar-05</span> Let Dr. Sense-of-Direction going AKUMU</a></li>
<li><a href="/days/636"><span class="archivdate">Feb-20</span> Black Tea Hell</a></li>
<li><a href="/days/635"><span class="archivdate">Feb-10</span> からっぽい？</a></li>
<li><a href="/days/634"><span class="archivdate">Feb-06</span> Hello PHP!</a></li>
<li><a href="/days/633"><span class="archivdate">Jan-25</span> New Char: Haizora Shizuku (lies)</a></li>
<li><a href="/days/632"><span class="archivdate">Jan-19</span> 近状の収束したページ</a></li>
<li><a href="/days/656"><span class="archivdate">Oct-14</span> redevelop</a></li>
<li><a href="/days/655"><span class="archivdate">Oct-04</span> 1m 07s 16 ~ nice boat!</a></li>
<li><a href="/days/654"><span class="archivdate">Sep-15</span> Cloudy days' stimuli</a></li>
<li><a href="/days/653"><span class="archivdate">Aug-29</span> 青い瞳に惹かれて</a></li>
<li><a href="/days/652"><span class="archivdate">Aug-16</span> 集中力☆</a></li>
<li><a href="/days/651"><span class="archivdate">Aug-03</span> Moving to SQLite</a></li>
<li><a href="/days/650"><span class="archivdate">Jul-30</span> xx shades of dark blue and pink</a></li>
<li><a href="/days/649"><span class="archivdate">Jul-25</span> 指を痛めるほどノートちゃんのお掃除♪</a></li>
<li><a href="/days/648"><span class="archivdate">Jul-07</span> 遅れた七夕</a></li>
<li><a href="/days/647"><span class="archivdate">Jun-19</span> bootloader unlock allowed: no (# ﾟДﾟ)</a></li>
<li><a href="/days/646"><span class="archivdate">Jun-02</span> Summer horror time? and the moon</a></li>
<li><a href="/days/645"><span class="archivdate">May-27</span> Yorlga II Inspiration</a></li>
<li><a href="/days/644"><span class="archivdate">May-21</span> 悪意しかないよぉぉ</a></li>
<li><a href="/days/643"><span class="archivdate">May-13</span> Layers of Neuroimaging</a></li>
<li><a href="/days/642"><span class="archivdate">Apr-30</span> Bye-bye Presets!</a></li>
<li><a href="/days/641"><span class="archivdate">Apr-23</span> 2D nerd;</a></li>
<li><a href="/days/640"><span class="archivdate">Apr-08</span> Ahi esta! ありえんな～(,,ﾟДﾟ)！</a></li>
<li><a href="/days/639"><span class="archivdate">Mar-30</span> Artistically grotesque</a></li>
<li><a href="/days/638"><span class="archivdate">Mar-18</span> Revive Remake Reincarnation</a></li>
<li><a href="/days/637"><span class="archivdate">Mar-05</span> Let Dr. Sense-of-Direction going AKUMU</a></li>
<li><a href="/days/636"><span class="archivdate">Feb-20</span> Black Tea Hell</a></li>
<li><a href="/days/635"><span class="archivdate">Feb-10</span> からっぽい？</a></li>
<li><a href="/days/634"><span class="archivdate">Feb-06</span> Hello PHP!</a></li>
<li id="post99" class="post-item"><a href="/days/632"><span class="archivdate">Jan-19</span> 近状の収束したページ</a></li>
<li id="post100" class="post-item"><a href="/days/633"><b><span class="archivdate">Jan-25</span> New Char: Haizora Shizuku (lies)</b></a></li>
</ul>
</div>
</div>
</div>
</body>
</html>
