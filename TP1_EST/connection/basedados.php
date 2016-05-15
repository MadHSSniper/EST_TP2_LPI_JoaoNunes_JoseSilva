<?php
	$dbhost = 'localhost';
	$dbuser = 'root';
	$dbpass = '';
	
	$dbname = 'tb1_associacao_est';
	
	$conn = mysqli_connect($dbhost, $dbuser, $dbpass);
	if(! $conn ){
		die('Could not connect: ' . mysqli_error($conn));
	}
	
	$useBD = mysqli_select_db($conn,$dbname);

	mysqli_set_charset($conn, "UTF8");
?>