<?php
#################################################################################
# Name: mySqlTesting.php														#
# 																				#
# Project: WebProg Assignment2													#
#																				#
# Author: Tim Skehel															#
#																				#	
# Purpose: Run a series of queries 10 times each, print out the times to a .csv	#
#################################################################################

//error reporting on 
error_reporting(E_ALL);	

//The Queries
$query1 = "SELECT * FROM friends WHERE name LIKE 'Katy Perry'";
$query2 = "SELECT * FROM friends WHERE name LIKE 'Jimi Hendrix'";
$query3 = "SELECT * FROM friends WHERE name LIKE '%jones%'";
$query4 = "SELECT * FROM friends WHERE name LIKE '%giles%'";
$query5 = "SELECT * FROM friends WHERE name LIKE '%mohamed%'";
//Save Queries to an array
$queries = array( 	1 => $query1,
					2 => $query2,
					3 => $query3,
					4 => $query4,
					5 => $query5);

//set up mySQL connection
$mysqli = new mysqli("localhost", "root", "","foaf");
//initate counter
$q = 1;
foreach ($queries as $query) {
	$i=1;
	echo "Query".$q.PHP_EOL;
	//echo "<br>";
	
	while ($i<11) {
		//clear cache
		$mysqli->query("RESET QUERY CACHE");
		//initiate timer
		$start = microtime(true);
		//run query
		$result = $mysqli->query($query);
		//end timer
		$duration = microtime(true) - $start;
		echo 'Runtime'.$i.', '.$duration. PHP_EOL;
		//echo "<br>";
		$i++;
	}
	
	//get number of results and print
	$noOfResults = $result->num_rows;
	echo "NoOfResults, ".$noOfResults.PHP_EOL;
	
	//close $result
	$result->close();
	//echo "<br><br>";
	$q++;
}

//close mysql connection
$mysqli->close();
?>