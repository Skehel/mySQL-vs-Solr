<?php
#################################################################################
# Name: sorlTesting.php															#
# 																				#
# Project: WebProg Assignment2													#
#																				#
# Author: Tim Skehel															#
#																				#	
# Purpose: Run a series of queries 10 times each, print out the times to a .csv	#
#################################################################################

//error reporting on 
error_reporting(E_ALL);
//import bootstrap for solr settings
include "bootstrap.php";

//solr login details	
$options = array
(
    'hostname' => SOLR_SERVER_HOSTNAME,
    'login'    => SOLR_SERVER_USERNAME,
    'password' => SOLR_SERVER_PASSWORD,
    'port'     => SOLR_SERVER_PORT,
);
//connect to solr
$client = new SolrClient($options);

//Queries
$query1 = new SolrQuery();
$query1->setQuery('"Katy Perry"');
$query2 = new SolrQuery();
$query2->setQuery('"Jimi Hendrix"');
$query3 = new SolrQuery();
$query3->setQuery('jones');
$query4 = new SolrQuery();
$query4->setQuery('giles');
$query5 = new SolrQuery();
$query5->setQuery('mohamed');

$queries = array( 	1 => $query1,
					2 => $query2,
					3 => $query3,
					4 => $query4,
					5 => $query5,
					);

$q=1;
foreach ($queries as $query) {
	//add query parameters
	$query->setStart(0);
	$query->addField('name')->addField('uri')->addField('id');
	
	echo "Query".$q.PHP_EOL;
	$i = 1;
	while($i<11) {		
		//start timer
		$start = microtime(true);
		//run query
		$result = $client->query($query);
		//end timer
		$duration = microtime(true) - $start;
		echo 'Runtime'.$i.', '.$duration. PHP_EOL;
		$i++;
	}
	//get number of results and print 
	$resultArray = $result->getResponse();
	echo "NoOfResults, ".$resultArray["response"]["numFound"].PHP_EOL;
	
	$q++;
}

?>
