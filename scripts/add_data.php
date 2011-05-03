<?php

include('../common.php');

if (!isset($_GET['data_file']) || empty($_GET['data_file']))
{
	die('You must provide a data_file name: add_data.php?data_file=xxx');
}

if (!isset($_GET['where']) || empty($_GET['where']))
{
	die('You must provide a location for your data: add_data.php?where=xxx');
}

$location = $_GET['where'];
$dataFile = '../data/' . $_GET['data_file'];
$fileContent = file_get_contents($dataFile);
$data = json_decode($fileContent);

var_dump($fileContent);

/*
if ($data)
{
	foreach ($data as $d)
	{
		echo 'Adding data... <br />';
		$http->post($baseUri . '/' . $location, json_encode($d) );
	}
}
*/
