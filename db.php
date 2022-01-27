<?php 
	$servername = "localhost";
	$username = "root";
	$password = "iHelpBD@2017";
	$db = "ihelp_crm";
	$conn = new mysqli($servername, $username, $password,$db);
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
 ?>