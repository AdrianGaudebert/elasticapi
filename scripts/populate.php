<?php

include('../common.php');

$location = 'crashes';
$dataFile = '../data/processed/';

$dir = opendir($dataFile);

while ($file = readdir($dir))
{
	echo $file . ': ';
	$fileContent = file_get_contents($dataFile . $file);
	$data = json_decode($fileContent);

	if ($data)
	{
		echo $http->post($baseUri . '/' . $location, json_encode($data) );
	}
	echo '<br />';
}
