<?php

include('../common.php');

echo $http->post($baseUri . '/stuff', '{"user": "adrian"}' );
