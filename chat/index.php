<!DOCTYPE html>
<html>
	<head>
		<title>kinchat</title>
		<link rel="stylesheet" type="text/css" href="../stylesheets/style.css" />
		<link rel="Shortcut Icon" href="../images/favicon.ico" /> 
			
			<?php
			
			error_reporting(E_ALL);
			
			global $included;
			$included = false;
			
			include("validate_user.php");
			
			if ($included == false) {
				die("<script>window.location = '../error.html';</script>");
			} else {
				$included = false;
			}
			
			?>
		
		<script type="text/javascript" src="../scripts/jquery.min.js"></script>
		<script type="text/javascript" src="../scripts/jquery-ui.custom.min.js"></script>
		<script type="text/javascript" src="../scripts/tinyscrollbar.js"></script>
		<script type="text/javascript" src="../scripts/script.js"></script>
	</head>
	<body>
		<div id="container">
			<div id="nav_bar">
				<a href="../index.html"><img id="logo" src="../images/logo.png" alt="" /></a>
				<div id="nav_button_container">
					<a href="../index.php"><div class="nav_button">Home</div></a>
					<a href="http://www.facebook.com/pages/Kinchat/190740614297028" target="_blank"><div class="nav_button">Facebook</div></a>
					<a href="mailto:support@trykinchat.com" style="margin: 0;"><div class="nav_button">Contact Us</div></a>
					<a href="../logout.php"><div class="nav_button logout_button">Logout</div></a>
				</div>
			</div>
			<div id="content">
				<div id="kin_pane_container">
					<div id="kin_pane_background"></div>
					<div id="kin_pane">
						<div id="tool_bar">
							<a id="add_button" class="link" onClick="kin.add()">Add kin</a>
							<a id="hide_button" class="link" onClick="kin_pane.toggle()">Hide</a>
						</div>
						<div id="info_pane">
							<img id="kin_status_pic" src="../images/default.png" alt="" />
							<div id="kin_username"></div>
							<div id="kin_status_msg" title="Set Status Message"></div>
						</div>
						<div id="kin_list"></div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>