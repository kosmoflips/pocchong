<ul>
<li><a href="<?php echo POC_DB_POST['new']; ?>">new post</a> | <a href="<?php echo POC_DB_POST['admin_list']; ?>">list posts</a></li>
<li><a href="<?php echo POC_DB_MG['new']; ?>">new art</a> | <a href="<?php echo POC_DB_MG['admin_list']; ?>">list artworks</a></li>
<li>for static pages, go to <code>/static</code> and edit manually</a></li>
</ul>

<hr />

<h4>Tools</h4>
<ul>
	<li><a href="https://www.epochconverter.com" target="_blank">Epoch converter</a></li>
	<li><a href="https://texteditor.com/emoji/" target="_blank">emoji picker</a></li>
</ul>

<hr />

<div>
<form action="/a/dosql/" method="post" target="">
<h4>do SQL</h4>
<ul>
	<li>split each SQL statement with semicolon ";",</li>
	<li><b>if semicolon is required in statement, will cause problem!</b></li>
	<li>in that case just use DB browser and do edits there.</li>
	<li>use leading "#" for comment</li>
</ul>
<textarea class="lined" rows="15" cols="70" name="sql">
SELECT * FROM sqlite_master WHERE type='table';
# SELECT id, title FROM post ORDER BY id DESC LIMIT 4;
SELECT id , title FROM mygirls ORDER BY id DESC LIMIT 1;
</textarea><br />
<input type="submit" name="dosql" value="doSQL" onclick="this.form.target='_blank'">
</form>
</div>
