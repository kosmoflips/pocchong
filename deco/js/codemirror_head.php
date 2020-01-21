<?php
/* ----------notes------------
include this file in <head>

current default settings. remember to modify codemirror.js

=== in lib/codemirror.js
	lineNumbers: true,
	indentWithTabs: true,
	smartIndent: false,
	lineWrapping:true,
	theme: lucario, // remember to include css
=== in addon/edit/matchbrackets.js
	matchBrackets: true,

========= usage in <body>==========
<textarea id=demotext>
............
</textarea>
<script>
var editor = CodeMirror.fromTextArea(document.getElementById("demotext"), {
	mode: "text/html",
	...
});
editor.setSize(1300,700);
</script>

*/
?>
<link rel=stylesheet href="/deco/js/cm/lib/codemirror.css" />
<link rel=stylesheet href="/deco/js/cm/theme/lucario.css" />
<script src="/deco/js/cm/lib/codemirror.js"></script>
<script src="/deco/js/cm/mode/xml/xml.js"></script>
<script src="/deco/js/cm/mode/css/css.js"></script>
<script src="/deco/js/cm/mode/javascript/javascript.js"></script>
<script src="/deco/js/cm/mode/htmlmixed/htmlmixed.js"></script>
<script src="/deco/js/cm/addon/edit/matchbrackets.js"></script>
<style>
.CodeMirror {
	/*font-size:115%;*/
	width: 700px;
	height: 500px;
	font-family: "Yu Gothic UI", Meiryo, Verdana, Tahoma, Arial,sans-serif;
}
</style>
