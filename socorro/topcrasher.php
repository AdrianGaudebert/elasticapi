<?php

include('common.php');

$product = 'firefox';

$command = '{
	"size" : 0,
    "fields" : [ "signature" ],
    "query" : {
        "term" : {
            "product" : "'.$product.'"
        }
    },
    "facets" : {
        "signature" : {
            "terms" : {
				"size" : 299,
                "script_field" : "_source.signature"
            }
        }
    }
}';

list($hits, $facets) = doRequest($http, $baseUri, $command);

$signatures = array();
$rank = 0;
$emptySignIsTreated = false;

foreach ($facets->signature->terms as $s)
{
	// If there are more missing signature that this one, let's add it first
	if (!$emptySignIsTreated && $facets->signature->missing >= $s->count)
	{
		// get the data about the missing signatures
		$command = '{
    "query" : {
        "filtered" : {
            "query" : {
            	"term" : {
                    "product" : "'.$product.'"
                }
            },
            "filter" : {
                "missing" : { "field" : "signature" }
            }
        }
    },
    "facets" : {
        "windows" : {
            "query" : {
                "term" : { "os_name" : "windows" }
            }
        },
        "mac" : {
            "query" : {
                "term" : { "os_name" : "mac" }
            }
        },
        "linux" : {
            "query" : {
                "term" : { "os_name" : "linux" }
            }
        }
    }
}';

		list($emptyHits, $emptyFacets) = doRequest($http, $baseUri, $command);

		$sign = array(
			'rank' => ++$rank,
			'count' => $emptyHits->total,
			'signature' => '(empty signature)',
			'windows' => $emptyFacets->windows->count,
			'mac' => $emptyFacets->mac->count,
			'linux' => $emptyFacets->linux->count,
			'date' => '',
		);

		$signatures[] = $sign;
		$emptySignIsTreated = true;
	}

	$sign = array(
		'rank' => ++$rank,
		'signature' => $s->term,
	);

	// Get data about this precise signature
	$command = '
{
    "size" : 1,
    "sort" : [
        { "client_crash_date" : "asc" }
    ],
    "query" : {
        "bool" : {
            "must" : [
                {
                    "term" : {
                        "signature.full" : "'.$sign['signature'].'"
                    }
                },
                {
                    "term" : {
                        "product" : "'.$product.'"
                    }
                }
            ]
        }
    },
    "facets" : {
        "windows" : {
            "query" : {
                "term" : { "os_name" : "windows" }
            }
        },
        "mac" : {
            "query" : {
                "term" : { "os_name" : "mac" }
            }
        },
        "linux" : {
            "query" : {
                "term" : { "os_name" : "linux" }
            }
        }
    }
}';

	list($currentHits, $currentFacets) = doRequest($http, $baseUri, $command);

/*
	echo '<pre>',var_dump($currentHits->hits),'</pre>';
	die();
*/

	$sign['count'] = $currentHits->total;
	$sign['date'] = $currentHits->hits[0]->_source->client_crash_date;

	$sign['windows'] = $currentFacets->windows->count;
	$sign['mac'] = $currentFacets->mac->count;
	$sign['linux'] = $currentFacets->linux->count;


	$signatures[] = $sign;
}

// Display this page
$vars = array();
foreach (get_defined_vars() as $name => $value) {
	if (substr($name, 0, 1) != '_') {
		$vars[$name] = $value;
	}
}
render('topcrasher', $vars);
