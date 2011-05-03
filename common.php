<?php

function debug($var)
{
	echo '<pre>',var_dump($var),'</pre>';
}

session_start();

// Default values for the index and type to request to
if (!isset($_SESSION['index']) || empty($_SESSION['index']))
{
	$_SESSION['index'] = 'socorro';
}
if (!isset($_SESSION['type']) || empty($_SESSION['type']))
{
	$_SESSION['type'] = 'crashes';
}

// Changing the index and type if asked to
if (!empty($_POST['es_index']))
{
	$_SESSION['index'] = $_POST['es_index'];
}
if (!empty($_POST['es_type']))
{
	$_SESSION['type'] = $_POST['es_type'];
}


// Creating the connection to ES
include('lib/HttpClient.php');

$http = new HttpClient();
$baseUri = 'http://localhost:9200/' . $_SESSION['index'];
