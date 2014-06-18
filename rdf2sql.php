<?php
/* 	#################################################################################
	# Name: rdf2sql.php																#
	# 																				#
	# Project: WebProg Assignment2													#
	#																				#
	# Author: Tim Skehel															#
	#																				#	
	# Purpose: Use SPARQL to extract data from RDF docs and add to mySQL database.	#
	#################################################################################
*/


// Only show serious errors.
error_reporting(E_ERROR | E_WARNING | E_PARSE);

//import RAP library
require_once 'rdfapi-php/test/config.php';
include(RDFAPI_INCLUDE_DIR . "RDFAPI.php");

//set time and memory limit so script doesn't stop running
set_time_limit(99999); // http://www.php.net/manual/en/function.set-time-limit.php 
ini_set('memory_limit',-1);

//create a new MemModel and load the documents
$mf = new ModelFactory();
$friends = $mf->getDefaultModel();
//create SPARQL query
	$querystring = '
	PREFIX foaf: <http://xmlns.com/foaf/0.1/>
	SELECT ?x ?name
	WHERE { ?x foaf:name ?name }
	';

//set up the mysql connection
$mysqli = new mysqli("localhost", "root", "","foaf");
$insert = $mysqli->prepare("INSERT INTO friends VALUES (?, ?)");



//while loop to loop through the seperated NT files (with incrementing filenames)
$i = 1;
while ($i<669) {
	//load current document into the memodel	
	$friends->load('C:/xampp/htdocs/assignment2/for_students/person_data'.$i.'.nt');
	//run the SPARQL query on the current document
	$result = $friends->sparqlQuery($querystring); 
	
	// for each row in the results...
	foreach ($result as $row) {
		// get the node for the "name" variable:
		$name = $row['?name'];
		//get the value of the node
		$actualName = $name->label;
	
		$resource_uri = $row['?x']->uri;
	
		
		// Insert into mysql...
		$insert->bind_param("ss", $resource_uri, $actualName);
		$insert->execute();
		}
	
	echo "Finished document" . $i . "of 668";
	echo.;
	$i++;
}
//close mysql connection
$mysqli->close();


?>