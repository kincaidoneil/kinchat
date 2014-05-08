<?php

error_reporting(E_ALL);

global $included;
$included = false;

global $valid_user;
$valid_user = false;

include("validate_request.php");

if ($included == true) {
	
	if ($valid_user == true) {
		
		$mysql_host = "localhost";
		$mysql_user = "root";
		$mysql_password = "";
		
		$connection = mysql_connect($mysql_host, $mysql_user, $mysql_password);
		
		if (!$connection) {
			
			header("HTTP/1.0 500 Internal Server Error");
			die();
			
		}
		
		output_data();
	
	} else {
		
		header("HTTP/1.0 403 Forbidden");
		die();
		
	}
	
} else {
	
	header("HTTP/1.0 500 Internal Server Error");
	die();
	
}

function update_data() {
	
	if ($_POST['status_msg']) {
		
		$table_name = mysql_real_escape_string($_SESSION['username']);
		$status_msg = mysql_real_escape_string(htmlspecialchars($_POST['status_msg'], ENT_QUOTES));
		
		$current_time = mysql_real_escape_string(date('U'));
		
		// Update status table.
		mysql_select_db("status");
		$result = mysql_query("UPDATE $table_name SET status_msg = '$status_msg', active = 1, last_updated = '$current_time'");
		
		// If query isn't successful, return error.
		if (!$result) {
			return false;
		} else {
			return true;
		}
			
	} else {
		
		return false;
		
	}
		
}

function fetch_kin() {
	
	mysql_select_db("kin");
	
	$kin_table = mysql_real_escape_string($_SESSION['username']);
	$get_kin = mysql_query("SELECT * FROM $kin_table");
	
	$kin_num = mysql_num_rows($get_kin);
	
	$i = 0;
	$arr = array();
	
	while ($i < $kin_num) {
		
		$row = mysql_fetch_array($get_kin);
		
		// If the status isn't pending, add kin to array.
		if ($row[1] == 0) {
			$sub_arr = array("username" => $row[0]);
			$arr[$i] = $sub_arr;
		}
		
		$i++;
		
	}
	
	mysql_select_db("status");
	
	for ($i = 0; $i < count($arr); $i++) {
		
		// Query Status of kin
		$status_table = mysql_real_escape_string($arr[$i]['username']);
		$get_kin_info = mysql_query("SELECT * FROM $status_table");
		$row = mysql_fetch_array($get_kin_info);
		
		// Append status msg to array.
		$arr[$i]["status_msg"] = $row[0];
		
		// Calculate how long ago data was updated.
		$time_diff = date('U') - $row[2];
		
		// If data hasn't been updated for over 20 seconds, set active to offline.
		if ($time_diff >= 20 && $row[1] == 1) {
			$current_time = date('U');
			mysql_query("UPDATE $status_table SET active = 0, last_updated = '$current_time'");
			$arr[$i]["active"] = 0;
		} else {
			$arr[$i]["active"] = $row[1];
		}
		
	}
	
	$new_arr = array();
	$i = 0;
	
	while ($i < count($arr)) {
		$new_arr[$arr[$i]['username']] = $arr[$i];
		$i++;
	}
	
	return $new_arr;
	
}

function output_data() {
	
	echo json_encode(array("updated" => update_data(), "kin" => fetch_kin()));
	
}

mysql_close($connection);

?>