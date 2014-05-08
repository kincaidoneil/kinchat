<?php

echo date('U') . "<br />";

error_reporting(0);

global $valid_user;
$included = false;

include("validate_request.php");
	
if ($valid_user == true) {
	
	if (isset($_REQUEST['hour']) == true && isset($_REQUEST['minute']) == true && isset($_REQUEST['half']) == true && isset($_REQUEST['month']) == true && isset($_REQUEST['date']) == true && isset($_REQUEST['year']) == true && isset($_REQUEST['username']) == true && isset($_REQUEST['msg']) == true) {
		
		echo date('U') . "<br />";
		
		$mysql_host = "localhost";
		$mysql_user = "root";
		$mysql_password = "";
		
		$connection = mysql_connect($mysql_host, $mysql_user, $mysql_password);
		
		if (!$connection) {
			
			header("HTTP/1.0 500 Internal Server Error");
			
		} else {
			
			$username = $_REQUEST['username'];
			
			$hour = mysql_real_escape_string($_REQUEST['hour']);
			$minute = mysql_real_escape_string($_REQUEST['minute']);
			$half = mysql_real_escape_string($_REQUEST['half']);
			$month = mysql_real_escape_string($_REQUEST['month']);
			$date = mysql_real_escape_string($_REQUEST['date']);
			$year = mysql_real_escape_string($_REQUEST['year']);
			$msg = mysql_escape_string($_REQUEST['msg']);
			
			echo date('U') . "<br />";
			
			mysql_select_db("messages");
			
			$table_name = mysql_real_escape_string($_SESSION['username'] . "\$" . $username);
			mysql_query("INSERT INTO $table_name (hour, minute, half, month, date, year, sender, msg) VALUES ('$hour', '$minute', '$half', '$month', '$date', '$year', 1, '$msg')");
			
			$table_name = mysql_real_escape_string($username . "\$" . $_SESSION['username']);
			mysql_query("INSERT INTO $table_name (hour, minute, half, month, date, year, sender, msg) VALUES ('$hour', '$minute', '$half', '$month', '$date', '$year', 0, '$msg')");
			
			mysql_close($connection);
			
			echo date('U') . "<br />";
			
			echo "success";
			
		}
		
	} else {
		
		header("HTTP/1.0 500 Internal Server Error");
		
	}
	
} else {
	
	header("HTTP/1.0 403 Forbidden");
	
}

?>