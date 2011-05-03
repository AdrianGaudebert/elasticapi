<?php

function debug($var)
{
	echo '<pre>',var_dump($var),'</pre>';
}

function render($tpl, $vars = array())
{
	extract((array)$vars);

	include('templates/_head.phtml');
	include('templates/'.$tpl.'.phtml');
	include('templates/_foot.phtml');
}

function doRequest($http, $baseUri, $command)
{
	$results = $http->get($baseUri.'/crashes/_search', $command);
	$results = json_decode($results);

	return array(
		$results->hits,
		(isset($results->facets)) ? $results->facets : null
	);
}

include('../lib/HttpClient.php');

$http = new HttpClient();
$baseUri = 'http://localhost:9200/socorro';
