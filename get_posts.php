<?php

include('common.php');

// get all blog posts
$posts = array();

$search = false;
$terms = array();

// Parameters for the search
if (!empty($_GET['contains']))
{
	$terms['text'] = $_GET['contains'];
}

if (!empty($_GET['author']))
{
	$terms['screen_name'] = $_GET['author'];
}
// Creating the search query
if (count($terms) == 1)
{
	foreach ($terms as $k => $v)
	{
		$search = '{
			"query" : {
				"term" : { "'.$k.'": "'.$v.'" }
			}
		}';
	}
}
else if (count($terms) > 1)
{
	$first = true;

	$search = '{
		"query" : {
			"terms" : {';

	foreach ($terms as $k => $v)
	{
		if (!$first)
			$search .= ', ';
		else
			$first = false;

		$search .= '"'.$k.'": "'.$v.'"';
	}

	$search .=  '}
			}
		}';

	echo $search;
}
else
{
	$search = '{
		"query" : {
			"match_all" : {}
		}
	}';
}

// Get the search result
if ($search)
{
	$rawData = $http->get($baseUri.'/tweets/_search?size=100', $search);
}
else
{
	$rawData = $http->get($baseUri.'/tweets/_search?q=*&size=100');
}


$data = json_decode($rawData);

if (!empty($data))
{
	foreach ($data->hits->hits as $hit)
	{
		$posts[$hit->_id] = $hit->_source;
	}
}

?>

<ul>
<?php foreach ($posts as $post) { ?>
	<li><strong><?php echo $post->user->screen_name; ?></strong>: <?php echo $post->text; ?></li>
<? } ?>
</ul>
