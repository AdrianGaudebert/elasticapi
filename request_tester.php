<?php

include('common.php');

$result = '';
$command = '';
$curlCommand = '';

if (count($_POST))
{
	$command = $_POST['command'];

	$curlCommand = "curl -XPOST '$baseUri/".$_SESSION['type']."/_search?pretty=true' -d '$command'";

	$result = htmlentities($http->get($baseUri.'/'.$_SESSION['type'].'/_search?pretty=true', $command));

	if (!empty($_POST['save']))
	{
		header('Content-Type: text/json; charset=utf-8');
		header('Content-Disposition: attachment; filename=command.json');
		echo $command;
		exit();
	}
}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset=utf-8>
		<title>ElasticSearch</title>

  <style type="text/css" media="screen">
    body {
        overflow: hidden;
        padding-top: 400px;
    }

    #editor {
        margin: 0;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        width: 100%;
        height: 400px;
    }

    .hidden {
		display: none;
	}
  </style>
	</head>

	<body>
		<pre id=editor><?php echo $command; ?></pre>
		<form method=post action="">
			<p>
				Config:
				<input type=text name=es_index value="<?php echo $_SESSION['index']; ?>">
				<input type=text name=es_type value="<?php echo $_SESSION['type']; ?>">
			</p>
			<p class=hidden>
				<textarea name=command cols=150 rows=20 id=command><?php echo $command; ?></textarea>
			</p>
			<p>
				<button type=submit id=submit>Execute</button>
				|
				<input type=submit name=save value="Save this command">
			</p>
		</form>

		<div style="float: left; width: 49%;">
			<h2>Result</h2>
			<pre style="overflow: scroll;">
				<?php echo $result; ?>
			</pre>
		</div>

		<div style="float: left; width: 49%;">
			<h2>cURL command</h2>
			<textarea cols=100 rows=30><?php echo $curlCommand; ?></textarea>
		</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<script src="ace/ace.js" type="text/javascript" charset="utf-8"></script>
<script src="ace/theme-twilight.js" type="text/javascript" charset="utf-8"></script>
<script src="ace/mode-javascript.js" type="text/javascript" charset="utf-8"></script>
<script>
window.onload = function() {
    var editor = ace.edit("editor");
    editor.setTheme("ace/theme/twilight");

    var JavaScriptMode = require("ace/mode/javascript").Mode;
    editor.getSession().setMode(new JavaScriptMode());

    $('#submit').click(function(e) {
		$('#command').val( editor.getSession().getValue() );
	});
};
</script>

	</body>
</html>
