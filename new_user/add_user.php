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
			$mysql_password = "";
			
			$connection = mysql_connect($mysql_host, $mysql_user, $mysql_password);
			
			if (!$connection) {
				
				echo "Error with validating new user.";
				
			} else {
				
				validate_input();
				
			}
			
			function validate_input() {
				
				$username = $_POST['username'];
				$email = $_POST['email'];
				$password = $_POST['password'];
				$verify_password = $_POST['verify_password'];
				
				// Fields were empty.
				if (isset($username) == false || $username == null || isset($email) == false || $email == null || isset($password) == false || $password == null || isset($verify_password) == false || $verify_password == null) {
					echo "Field(s) were empty.";
				}
				
				// Passwords do not match.
				else if ($password != $verify_password) {
					echo "Passwords don't match.";
				}
				
				// Password is incorrect in length.
				else if (strlen($password) < 8 || strlen($password) > 20) {
					echo "Your password must be between 8 and 20 characters long.";
				}
				
				// Username is incorrect in length.
				else if (strlen($username) < 4 || strlen($username) > 20) {
					echo "Your username must be between 4 and 20 characters long.";
				}
				
				else {
					
					if (filter_var($email, FILTER_VALIDATE_EMAIL) == true) {
						
						mysql_select_db("users");
						
						// Escape Bad Characters
						$email = mysql_real_escape_string($email);
						
						$find_email = mysql_query("SELECT * FROM users WHERE email = '$email'");
						
						if (mysql_num_rows($find_email) == 0) {
							
							// Escape Bad Characters
							$username = mysql_real_escape_string($username);
							
							$find_username = mysql_query("SELECT * FROM users WHERE username = '$username'");
							
							if (mysql_num_rows($find_username) == 0) {
								
								if (preg_match("/[\W]/", $username) == 0) {
									
									// If there are no special characters, create account.
									create_account($username, $email, $password);
									
								} else {
									
									echo "Your username contains invalid characters. It may only contain letters, numbers, and underscores.";
									
								}
								
							} else {
								
								echo "Username is already registered. Please find a different one.";
								
							}
						
						} else {
							
							echo "E-mail is already registered.";	
							
						}
					
					} else {
						
						echo "Invalid e-mail address.";
						
					}
					
				}
				
			}
			
			function create_account($username, $email, $password) {
				
				// Escape Bad Characters
				$username = mysql_real_escape_string($username);
				$password = md5(mysql_real_escape_string($password));
				$email = mysql_real_escape_string($email);
				$verification_code = create_verification_code();
				
				// Add user to database.
				mysql_select_db("users");
				mysql_query("INSERT INTO users VALUES ('$username', '$password', '$email', 0, '$verification_code')");
				
				// Create kin table for new user.
				mysql_select_db("kin");
				$query = mysql_query("CREATE TABLE $username (username TEXT NOT NULL, pending VARCHAR(1) NOT NULL)");
				
				// Create status table for new user.
				mysql_select_db("status");
				mysql_query("CREATE TABLE $username (status_msg TEXT, active INT NOT NULL, last_updated INT NOT NULL)");
				$current_time = mysql_real_escape_string(date('U'));
				mysql_query("INSERT INTO $username (status_msg, active, last_updated) VALUES ('Click to Set Status Message', 0, '$current_time')");
				
				// Include PHPMailer
				require_once("/../PHPMailer/class.phpmailer.php");
				
				$subject = "kinchat Account Activation";
				$message = "Hello " . $username . ",<br /><br />You are receiving this e-mail to verify your e-mail address and activate your new kinchat account. If you did not anticipate this e-mail, please disregard it. To activate your kinchat account, please click <a href=\"http://localhost/new_user/activate.php?verification_code=" . $verification_code . "\">here</a>. If you have any questions, please e-mail <a href=\"mailto:support@trykinchat.com\">support@trykinchat.com</a>.<br /><br />Enjoy!";
				
				// Send Verification E-mail
				if (send_email($email, $subject, $message) == true) {
					
					echo "Account created. An e-mail was sent to you with a link to activate your account. You must click the link before using kinchat.";
					
				} else {
					
					echo "Error sending verification e-mail. Please try again.";
					
				}
				
			}
			
			function create_verification_code() {
				
				global $str;
				$characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
				
				for ($str_pos = 0; $str_pos < 32; $str_pos++) {
					$str .= $characters[rand(0, strlen($characters) - 1)];
				}
				
				$duplicate_verification_codes = mysql_query("SELECT * FROM users WHERE verification_code = '$str'");
				
				if (mysql_num_rows($duplicate_verification_codes) > 0) {
					create_verification_code();
				} else {
					return $str;
				}
				
			}
			
			function send_email($to, $subject, $message) {
				
				$email = new PHPMailer();
				
				$email->IsSMTP();
				$email->SMTPAuth = true;
				$email->Host = "smtp.trykinchat.com";
				$email->Hostname = "smtp.trykinchat.com";
				$email->Port = 587;
				$email->Username = "info@trykinchat.com";
				$email->Password = "!yeah4kcht";
				
				$email->SetFrom('info@trykinchat.com', 'kinchat');
				$email->Sender = "info@trykinchat.com";
				$email->AddAddress($to);
				$email->Subject = $subject;
				$email->MsgHTML($message);
				
				if (!$email->Send()) {
					return false;
				} else {
					return true;
				}
				
			}
			
			mysql_close($connection);
			
			?>
						
		</div>
	</body>
</html>