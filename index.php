<?php

include('common.php');

if (count($_POST))
{
	if (isset($_POST['add-blog-post']))
	{
		$data = '
		{
			"title": "'.$_POST['title'].'",
			"text": "'.$_POST['content'].'",
			"date": "'.date().'"
		}';
		$http->post($baseUri.'/post', $data);
	}
}

include('template.php');
