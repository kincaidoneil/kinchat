<?php

error_reporting(E_STRICT);

global $included;
global $valid_user;
include("validate_request.php");

if ($included == true) {
	
	if ($valid_user = true) {
		
		$mysql_host = "localhost";
		$mysql_user = "root";
		$mysql_password = "";
		
		$connection = mysql_connect($mysql_host, $mysql_user, $mysql_password);
		
		if (!$connection) {
			
			header("HTTP/1.0 500 Internal Server Error");
			die();
			
		}
		
		mysql_select_db("users");
		output_data();
	
	} else {
		
		header("HTTP/1.0 403 Forbidden");
		die();
		
	}
	
} else {
	
	header("HTTP/1.0 500 Internal Server Error");
	die();
	
}

function fetch_kin() {
	
	mysql_select_db("kin");
	
	$table_name = mysql_real_escape_string($_SESSION['username']);
	$get_kin = mysql_query("SELECT * FROM $table_name");
	
	$kin_num = mysql_num_rows($get_kin);
	
	$arr = array();
	
	for ($i = 0; $i < $kin_num; $i++) {
		
		$row = mysql_fetch_array($get_kin);
		
		// If the status isn't pending, add kin to array.
		if ($row[1] == 0) {
			$sub_arr = array("username" => $row[0]);
			$arr[$i] = $sub_arr;
		}
		
	}
	
	mysql_select_db("status");
	
	for ($i = 0; $i < count($arr); $i++) {
		
		// Query Status of kin
		$table_name = mysql_real_escape_string($arr[$i]['username']);
		$get_kin_info = mysql_query("SELECT * FROM $table_name");
		$row = mysql_fetch_array($get_kin_info);
		
		// Append status msg to array.
		$arr[$i]["status_msg"] = $row[0];
		
		// Calculate how long ago data was updated.
		$time_diff = date('U') - $row[2];
		
		// If data hasn't been updated for over 10 seconds, set active to offline.
		if ($time_diff >= 10 && $row[1] == 1) {
			$current_time = date('U');
			mysql_query("UPDATE $table_name SET active = 0, last_updated = '$current_time'");
			$arr[$i]["active"] = 0;
		} else {
			$arr[$i]["active"] = $row[1];
		}
		
	}
	
	return $arr;
	
}

function fetch_my_info() {
	
	mysql_select_db("status");
	
	$table_name = mysql_real_escape_string($_SESSION['username']);
	$result = mysql_query("SELECT * FROM $table_name");
	$row = mysql_fetch_array($result);
	
	$arr = array("username" => $_SESSION['username'], "email" => $_SESSION['email'], "status_msg" => $row[0], "active" => $row[1]);
	
	return $arr;
	
}

function output_data() {
	
	echo json_encode(array("kin" => fetch_kin(), "user" => fetch_my_info()));
	
}

?>