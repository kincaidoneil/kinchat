<?php

error_reporting(E_ALL);

global $valid_user;
$valid_user = false;

include("validate_request.php");

if ($valid_user == true) {
	
	if (isset($_REQUEST['username']) == true) {
		
		$mysql_host = "localhost";
		$mysql_user = "root";
		$mysql_password = "";
		
		$connection = mysql_connect($mysql_host, $mysql_user, $mysql_password);
		
		if (!$connection) {
			
			header("HTTP/1.0 500 Internal Server Error");
			
		} else {
			
			mysql_select_db("messages");
			
			$username = $_REQUEST['username'];
			
			$table_name = mysql_real_escape_string($_SESSION["username"] . "\$" . $username);
			$result = mysql_query("SELECT * FROM $table_name ORDER BY id");
			
			if ($result == true) {
				
				$msgs = array();
				$msg_num = mysql_num_rows($result);
				
				for ($i = 0; $i < $msg_num; $i++) {
					
					$row = mysql_fetch_array($result);
					
					// If message is one of the last 20, prepare to return it to client.
					if ($row[0] > ($msg_num - 20) && $row[0] <= $msg_num) {
						array_push($msgs, array("id" => $row[0], "hour" => $row[1], "minute" => $row[2], "half" => $row[3], "month" => $row[4], "date" => $row[5], "year" => $row[6], "sender" => $row[7], "msg" => $row[8]));
					}
					
				}
				
			} else {
				
				$msgs = null;
				
			}
			
			echo json_encode($msgs);
			
			mysql_close($connection);
			
		}
		
	} else {
		
		header("HTTP/1.0 500 Internal Server Error");
		
	}
	
} else {
	
	header("HTTP/1.0 403 Forbidden");
	
}

?>