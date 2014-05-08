<?php

error_reporting(0);

global $included;
include("validate_request.php");

if ($included == true) {
	
	if ($valid_user == true) {
		
		$mysql_host = "localhost";
		$mysql_user = "root";
		$mysql_password = "password";
		
		$connection = mysql_connect($mysql_host, $mysql_user, $mysql_password);
		
		if (!$connection) {
			
			write_error();
			
		} else {
			
			$username = $_POST['username'];
			delete_kin();
			
		}
		
	} else {
		
		write_error();
		
	}
	
}

else {
	
	write_error();
	
}

function write_error() {
	
	echo json_encode(array("msg" => "We're sorry, an error occured when kinchat attempted to remove @" . $username . " as your kin."));
	
}

function delete_kin() {
	
	if ($username != $_SESSION['username']) {
			
		$escaped_username = mysql_real_escape_string($username);
		
		$table_name = $escaped_username . "_kin";
		
		mysql_select_db("users");
		if (mysql_query("DELETE FROM '$table_name' WHERE username = '$escaped_username'") == true) {
			
			if (mysql_affected_rows() == 1) {
				
				echo json_encode(array("msg" => "@" . $username . " was successfully removed as your kin."));
				
			} else {
				
				write_error();
				
			}
			
		} else {
			
			write_error();
			
		}
		
	} else {
		
		write_error();
			
	}
	
}

?>