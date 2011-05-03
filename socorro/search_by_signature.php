<?php

include('common.php');

$signature = '';
$product = 'firefox';
$version = '4.0.1';
$os = 'windows';

if (count($_POST))
{
	$signature = $_POST['signature'];
	$product = $_POST['product'];
	$version = $_POST['version'];
	$os = $_POST['os'];
}


$command = '{
    "query" : {
        "term" : {
            "signature" : "'.$signature.'"
        }
    },
    "filter" : {
        "and" : [
            {
                "term" : {
                    "product" : "'.$product.'"
                }
            },
            {
                "term" : {
                    "version" : "'.$version.'"
                }
            },
            {
                "term" : {
                    "os_name" : "'.$os.'"
                }
            }
        ]
    }
}';

list($hits, $facets) = doRequest($http, $baseUri, $command);

$crashes = array();

foreach ($hits->hits as $crash)
{
	$crashes[] = $crash->_source;
}

// Display this page
$vars = array();
foreach (get_defined_vars() as $name => $value) {
	if (substr($name, 0, 1) != '_') {
		$vars[$name] = $value;
	}
}
render('search_by_signature', $vars);
