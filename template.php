<!doctype html>
<html>
	<head>
		<meta charset=utf-8>
		<title>ElasticSearch</title>
	</head>

	<body>
		<form method=post action="">
			<input type=hidden name=add-blog-post>
			<fieldset>
				<legend>Add a new blog post</legend>
			<p>
				<label>
					Title
					<input type=text name=title>
				</label>
			</p>
			<p>
				<label>
					Content
					<textarea name=content></textarea>
				</label>
			</p>
			<p>
				<button type=submit>Add blog post</button>
			</p>
			</fieldset>
		</form>

		<h1>All the posts</h1>

		<div>
			<p>Refine the results:</p>
			<p>
				Author: <input type=text id=search-author>
				Contains: <input type=text id=search-contains>
			</p>
		</div>

		<div id=posts>
			<p>Processing...</p>
		</div>

		<div>
		<?php Logger::display_logs(); ?>
		</div>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
		<script>
		$(document).ready(function() {
			$('#posts').load('get_posts.php');
			$('input').change(function() {
				$('#posts').load('get_posts.php?author=' + $('#search-author').val() + '&contains=' + $('#search-contains').val());
			});
		});
		</script>
	</body>
</html>
