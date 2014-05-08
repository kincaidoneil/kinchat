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
			
			$mysql_host = "localhost";
			$mysql_user = "root";
			$mysql_password = "password";
			
			$connection = mysql_connect($mysql_host, $mysql_user, $mysql_password);
			
			if (!$connection) {
				
				echo "Error occurred when accepting invitation.";
					
			} else {
				
				check_user();
				
			}
			
			function check_user() {
				
				// User who was invited to be kin.
				$username = mysql_real_escape_string($_GET['username']);
				// Username of person who invited the user to be their kin.
				$kin_username = mysql_real_escape_string($_GET['kin_username']);
				
				if (isset($username) == false || isset($kin_username) == false || $username == null || $kin_username == null) {
					
					echo "Error occurred when accepting invitation.";
					
				} else {
					
					mysql_select_db("kin");
					$query = mysql_query("SELECT * FROM $kin_username WHERE username = '$username'") or die(mysql_error());
					
					$row = mysql_fetch_array($query);
					
					if ($row[1] == 1) {
						
						accept_invitation($username, $kin_username);
						
					} else {
						
						echo "@" . $kin_username . " is already kin with you.";
						
					}
					
				}
				
			}
			
			function accept_invitation($username, $kin_username) {
				
				mysql_select_db("kin");
				
				// Add user to kin of user who invited them.
				mysql_query("UPDATE $kin_username SET pending = '0' WHERE username = '$username'") or die(mysql_error());
				
				// Add user who invited the other to the user's kin.
				mysql_query("INSERT INTO $username VALUES ('$kin_username', 0)") or die(mysql_error());
				
				// Add messages table for the users.
				mysql_select_db("messages");
				
				$table_name = $username . "\$" . $kin_username;
				mysql_query("CREATE TABLE $table_name (id INT NOT NULL AUTO_INCREMENT, PRIMARY KEY(id), hour INT NOT NULL, minute VARCHAR(2) NOT NULL, half VARCHAR(2) NOT NULL, month INT NOT NULL, date INT NOT NULL, year INT NOT NULL, sender VARCHAR(1) NOT NULL, msg TEXT NOT NULL)");
				
				$table_name = $kin_username . "\$" . $username;
				mysql_query("CREATE TABLE $table_name (id INT NOT NULL AUTO_INCREMENT, PRIMARY KEY(id), hour INT NOT NULL, minute VARCHAR(2) NOT NULL, half VARCHAR(2) NOT NULL, month INT NOT NULL, date INT NOT NULL, year INT NOT NULL, sender VARCHAR(1) NOT NULL, msg TEXT NOT NULL)");
				
				echo "@" . $kin_username . " is now kin with you.";
				
			}
			
			mysql_close($connection);
			
			?>
			
		</div>
	</body>
</html>