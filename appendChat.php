<?php

// Get Chat Fields From Client

$hour = $_GET['hour'];
$minute = $_GET['minute'];
$half = $_GET['half'];
$day = $_GET['day'];
$month = $_GET['month'];
$date = $_GET['date'];
$body = $_GET['body'];

$mysql_host = "trykinchatcom.fatcowmysql.com";
$mysql_user = "kinchat_user";
$mysql_password = "password";

$connection = mysql_connect($mysql_host, $mysql_user, $mysql_password);	// Connect To MySQL Server

if (!$connection) {				// If There Is No Connection, Send Error To Client
	die("kinchat could not enter your chat.");
}

mysql_select_db("messages");								// Open The Messages Database

$result = mysql_query("SELECT id FROM messages");			// Query All IDs From Messages
$rowsQueried = mysql_num_rows($result);						// Get Number Of Chats/Rows
$numberOfChats = $rowsQueried + 1;

mysql_query("INSERT INTO messages VALUES ('$numberOfChats', '$hour', '$minute', '$half', '$day', '$month', '$date', '$body')");		// Write New Chat

mysql_close($connection);									// Disconnect From MySQL Server

?>