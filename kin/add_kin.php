<?php

error_reporting(E_ALL);

global $included;
include("../chat/validate_request.php");

if ($included == true) {
	
	if ($valid_user == true) {
		
		$mysql_host = "localhost";
		$mysql_user = "root";
		$mysql_password = "password";
		
		$connection = mysql_connect($mysql_host, $mysql_user, $mysql_password);
		
		if (!$connection) {
			
			write_error();
			
		} else {
			
			$email = $_POST['email'];
			add_kin($email);
			
		}
		
	} else {
		
		write_error();
		
	}
	
}

else {
	
	write_error();
	
}

function write_error() {
	
	echo json_encode(array("msg" => "We're sorry, an error occured when kinchat tried to send the invitation."));
	
}

function add_kin($email) {
	
	$escaped_email = mysql_real_escape_string($email);
	
	mysql_select_db("users");
	$query = mysql_query("SELECT * FROM users WHERE email = '$escaped_email'");
	
	// If the person is a current kinchat user, send invitation to become kin.
	if (mysql_num_rows($query) == 1) {
		
		$row = mysql_fetch_array($query);
		$username = $row[0];
		
		if ($username != $_SESSION['username']) {
			
			$username = mysql_real_escape_string($username);
			
			mysql_select_db("kin");
			$table_name = mysql_real_escape_string($_SESSION['username']);
			mysql_query("INSERT INTO $table_name VALUES ('$username', 1)");
			
			include("../PHPMailer/class.phpmailer.php");
			
			$subject = "@" . $_SESSION['username'] . " Wants to be kin on kinchat";
			$message = "<!DOCTYPE html><html><head></head><body style=\"font-family: Trebuchet MS; font-size: 12pt; color: gray\"><a href=\"http://trykinchat.com/\" target=\"_blank\"><img src=\"http://trykinchat.com/images/kinchat.png\" /></a><h3><span style=\"color: dodgerblue\">@" . $_SESSION['username'] . "</span> Wants to be kin with you on kinchat</h3><p>To accept the request, please click <a href=\"http://localhost/kin/accept_invitation.php?username=" . $row[0] . "&kin_username=" . $_SESSION['username'] . "\">here</a>. By default, the request will be denied, and no further action would be necessary.<br /><br />Best Regards,<br /><i>kinchat</i></p></body></html>";
			
			if (send_email($email, $subject, $message) != true) {
				
				write_error();
				echo $email;
				
			} else {
				
				echo json_encode(array("msg" => "Invitation successfully sent."));
				
			}
			
		} else {
			
			// E-mail entered is that user's e-mail.
			write_error();
			
		}
	
	// If the person is not a current kinchat user, send invitation to join kinchat.	
	} else {
		
		$subject =  "@" . $_SESSION["username"] . " Invited you to kinchat";
		$message = "<!DOCTYPE html><html><head></head><body style=\"font-family: Trebuchet MS; font-size: 12pt;\"><a href=\"http://trykinchat.com/\" target=\"_blank\"><img src=\"http://trykinchat.com/images/kinchat.png\" /></a><br /><h3><span style=\"color: dodgerblue\">@" . $_SESSION['username'] . "</span> Invited you to join kinchat</h3><p><b>What is kinchat?</b> kinchat is a free, online chat client. Prominent features include a very dynamic, fluid user interface, rich-text-chatting, and much more.<br /><br />To join kinchat, please click <a href=\"http://trykinchat.com/new_user/register.php\" target=\"_blank\">here</a>.<br /><br />Best Regards,<br /><i>kinchat</i></p></body></html>";
		
		if (send_email($email, $subject, $message) != true) {
			
			write_error();
			
		} else {
			
			echo json_encode(array("msg" => "Invitation successfully sent."));
			
		}
		
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
		
	}
	
	else {
			
		return true;
		
	}
	
}

mysql_close($connection);

?>