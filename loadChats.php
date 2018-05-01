<?php

$mysql_host = "trykinchatcom.fatcowmysql.com";
$mysql_user = "kinchat_user";
$mysql_password = "password";

$connection = mysql_connect($mysql_host, $mysql_user, $mysql_password);	// Connect To MySQL Server

if (!$connection) {				// If There Is No Connection, Send Error To Client
	die("kinchat could not connect to the server.");
}

$mysql_database = "messages";
mysql_select_db($mysql_database);											// Open The Messages Database

$result = mysql_query("SELECT * FROM messages ORDER BY id");				// Query Chats
$rowsQueried = mysql_num_rows($result);										// Get Number Of Rows Selected

$arrays = 0;
$arr = array();

while ($arrays < $rowsQueried) {
	
	$row = mysql_fetch_array($result);
	$sub_arr = array("id" => $row[0], "body" => $row[7]);
	$arr[$arrays] = $sub_arr;
	$arrays = $arrays + 1;
	
}

echo json_encode($arr);

mysql_close($connection);				// Disconnect From MySQL Server

?>