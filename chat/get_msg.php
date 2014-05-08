<?php

error_reporting(0);

global $included;
$included = false;

global $valid_user;
$included = false;

include("validate_request.php");

if ($included == true) {
	
	if ($valid_user == true) {
		
		if (isset($_REQUEST['username']) == true && isset($_REQUEST['id']) == true) {
			
			$mysql_host = "localhost";
			$mysql_user = "root";
			$mysql_password = "";
			
			$connection = mysql_connect($mysql_host, $mysql_user, $mysql_password);
			
			if (!$connection) {
				
				header("HTTP/1.0 500 Internal Server Error");
				
			} else {
				
				$id = mysql_real_escape_string($_REQUEST['id'] + 1);
				$username = mysql_real_escape_string($_REQUEST['username']);
				
				$new_msg = false;
				
				mysql_select_db("messages");
				
				while ($new_msg == false) {
					
					usleep(10000);
					
					$table_name = mysql_real_escape_string($_SESSION['username'] . "\$" . $username);
					$query = mysql_query("SELECT * FROM $table_name WHERE id = '$id'");
					$row = mysql_fetch_array($query);
					
					if (mysql_num_rows($query) > 0) {
						
						$new_msg = true;
						
						$json = array("id" => $row[0], "hour" => $row[1], "minute" => $row[2], "half" => $row[3], "month" => $row[4], "date" => $row[5], "year" => $row[6], "sender" => $row[7], "msg" => $row[8]);
						echo json_encode($json);
						
						mysql_close($connection);
						
					}
					
				}
				
			}
			
		} else {
			
			header("HTTP/1.0 500 Internal Server Error");
			
		}
		
	} else {
		
		header("HTTP/1.0 403 Forbidden");
		
	}

} else {
	
	header("HTTP/1.0 500 Internal Server Error");
	
}

?>