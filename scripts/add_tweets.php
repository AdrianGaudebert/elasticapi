<?php

include('../common.php');

$user = $_GET['user'] or die('No user.');

$dataFile = 'http://api.twitter.com/1/statuses/user_timeline.json?screen_name='.$user.'&count=200';
$data = json_decode(file_get_contents($dataFile));

if ($data)
{
	echo '<p>Adding data from ' . $user;

	$i = 1;
	foreach ($data as $d)
	{
		echo ' ' . $i;
		echo ' ' . $http->post($baseUri . '/tweets', json_encode($d) );
		$i++;
	}

	echo '</p>';
}
