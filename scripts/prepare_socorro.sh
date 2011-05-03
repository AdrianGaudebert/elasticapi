#!/bin/sh

curl -XDELETE 'http://localhost:9200/socorro'
curl -XPUT 'http://localhost:9200/socorro'
curl -XPUT 'http://localhost:9200/socorro/crashes/_mapping' -d @../data/socorro_crashes_mapping.json
curl -XGET 'http://localhost:9200/socorro/crashes/_mapping?pretty=true'
php populate.php
