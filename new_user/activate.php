<!DOCTYPE html>

<html>
	<head>
		<title>kinchat</title>
		<link rel="stylesheet" type="text/css" href="../stylesheets/page-style.css" />
		<link rel="Shortcut Icon" href="../images/favicon.ico" />
	</head>
	<body>
		<a href="../"><img id="logo" src="../images/logo.png" /></a>
		<div id="container">
			
			<?php
			
			error_reporting(0);
			
			$verification_code = $_GET['verification_code'];
			
			$mysql_host = "localhost";
			$mysql_user = "root";
			$mysql_password = "password";
			
			$connection = mysql_connect($mysql_host, $mysql_user, $mysql_password);
			
			if (!$connection) {
			
				echo "Error with account activation.";
				
			}
			
			mysql_select_db("users");

			$result = mysql_query("SELECT * FROM users WHERE verification_code = '$verification_code'");
			
			if (mysql_num_rows($result) == 1) {
				
				mysql_query("UPDATE users SET verified = 1 WHERE verification_code = '$verification_code'");
				echo "Account activated. Click <a href=\"../chat/\">here</a> to begin using kinchat.";
				
			}
			
			else {
				
				echo "Error with account activation.";
				
			}
			
			mysql_close($connection);
			
			?>
								
		</div>
	</body>
</html>