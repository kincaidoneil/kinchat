<?php

// echo date('U') . "<br />";

error_reporting(0);

// echo date('U') . "<br />";

$included = true;

// echo date('U') . "<br />";

session_start();
session_write_close();

// echo date('U') . "<br />";

if (isset($_SESSION['logged_in']) == true && isset($_SESSION['username']) == true) {
	
	if ($_SESSION['logged_in'] == true) {
		
		$valid_user = true;
		session_regenerate_id();
		
	} else {
		
		$valid_user = false;
		
	}
	
} else {
	
	$valid_user = false;
	
}

// echo date('U') . "<br />";

?>