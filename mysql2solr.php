<?php
#################################################################################
# Name: mySqltoSolr.php															#
#																				#
# Project: WebProg Assignment2													#
#																				#	
# Author: Tim Skehel															#
#																				#	
# Purpose: Extract the data from mySql database and index with SOLR				#
#################################################################################

//only show serious errors
error_reporting(E_ERROR | E_WARNING | E_PARSE);	
//import bootstrap for solr settings and the RAP library
include "bootstrap.php";
require_once 'rdfapi-php/test/config.php';
include(RDFAPI_INCLUDE_DIR . "RDFAPI.php");

//set time and memory limit so script doesn't stop running
set_time_limit(99999); 
ini_set('memory_limit',-1);

//commit function to commit all changes to SOLR
function commit() {
	$solrAddress = 'http://' . SOLR_SERVER_HOSTNAME . ':' . SOLR_SERVER_PORT . '/solr/collection1';
	$response = file_get_contents($solrAddress . '/update?commit=true');
	}

//solr login details	
$details = array
(
    'hostname' => SOLR_SERVER_HOSTNAME,
    'login'    => SOLR_SERVER_USERNAME,
    'password' => SOLR_SERVER_PASSWORD,
    'port'     => SOLR_SERVER_PORT,
);
//connect to solr
$client = new SolrClient($details);

//SQL QUERY 
$mysqli = new mysqli("localhost", "root", "","foaf");
$query = "SELECT *
FROM `friends`";

if ($result = $mysqli->prepare($query)) {  
    $result->execute();
    $result->bind_result($uri, $name);

	$i=1;
	//loop through results adding each one to solr
    while ($result->fetch()) {
        $doc = new SolrInputDocument();
		$doc->addField('id', $i);
		$doc->addField('uri', $uri);
		$doc->addField('name', $name);

		$updateResponse = $client->addDocument($doc);
		$i++;
    }

    
    $result->close();
}

//close mysql connection
$mysqli->close();



commit();
?>