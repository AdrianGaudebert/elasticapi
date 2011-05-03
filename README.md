ElasticAPI
==========

Some PHP scripts to play with the [ElasticSearch](http://www.elasticsearch.org/) API, through an HttpClient based on cURL.

You need to have an instance of ElasticSearch installed and running to use this.
The REST API must be available at the URI [http://localhost:9200/].
You also need to index some data by your own.
The scripts in /scripts can help automating this indexing process, but I do not provide the data.
Check Twitter for some simple data to play with.

Request tester
--------------

The request-tester.php script makes it easy to test your json queries by sending them to ES and displaying the result.
It uses [Ace](http://ace.ajax.org/) (formerly Bespin / Skywriter) to help writing your json code.
It also generates the command line to execute with cURL, for debugging purposes.

Socorro
-------

The folder /socorro contains some functionalities of the [Socorro UI](https://crash-stats.mozilla.com/) written using ES. Data is not provided.
