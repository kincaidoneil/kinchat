<?php

error_reporting(E_ALL);

session_start();
$included = true;

$mysql_host = "localhost";
$mysql_user = "root";
$mysql_password = "";

$connection = mysql_connect($mysql_host, $mysql_user, $mysql_password);

if (!$connection) {
	
	redirect_to_login();

}

mysql_select_db("users");

if (isset($_SESSION['logged_in']) == true && isset($_SESSION['username']) == true) {
	
	// Create New Session ID, Prevent Hacks
	session_regenerate_id();

} elseif (isset($_POST['email']) == true && isset($_POST['password']) == true) {
	
	$email = $_POST['email'];
	$password = md5($_POST['password']);
	
	$escaped_email = mysql_real_escape_string($email);
	$escaped_password = mysql_real_escape_string($password);
	
	$query_user = mysql_query("SELECT * FROM users WHERE email = '$escaped_email' AND password = '$escaped_password' AND verified = 1");
	$accounts_queried = mysql_num_rows($query_user);
	
	if ($accounts_queried == 1) {
		
		$row = mysql_fetch_array($query_user);
		
		$_SESSION['username'] = $row['username'];
		$_SESSION['email'] = $email;
		
		$_SESSION['logged_in'] = true;
		
	} else {
		
		redirect_to_login();
		
	}
	
} else {
	
	redirect_to_login();
	
}

function redirect_to_login() {
	
	global $login_redirect;
	$login_redirect = "<script>window.location = '../index.html';</script>";
	
	die($login_redirect);
	
}

mysql_close($connection);

?>