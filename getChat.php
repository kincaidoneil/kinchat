<?php

$clientId = $_GET['id'];		// Get Latest Chat Client Obtains

$mysql_host = "trykinchatcom.fatcowmysql.com";
$mysql_user = "kinchat_user";
$mysql_password = "password";

$connection = mysql_connect($mysql_host, $mysql_user, $mysql_password);	// Connect To MySQL Server

if (!$connection) {				// If There Is No Connection, Send Error To Client
	die("kinchat could not receive any new chats.");
}

mysql_select_db("messages");												// Open The Messages Database

$newChat = false;
$id = $clientId + 1;														// Increment ID

while ($newChat == false) {
	
	usleep(10000);
	
	$result = mysql_query("SELECT * FROM messages WHERE id = '$id'");		// Query New Chats
	$row = mysql_fetch_array($result);										// Get Array of Table Fields
	$rowsQueried = mysql_num_rows($result);									// Get Number Of Rows Selected
	
	if ($rowsQueried > 0) {													// If There Is A New Chat, Write It
		
		// Return Chat Fields As JSON Data
		
		$json = array("id" => $row[0], "body" => $row[7]);
		echo json_encode($json);
		
		$newChat = true;
				
	}
	
}

mysql_close($connection);							// Disconnect From MySQL Server

?>