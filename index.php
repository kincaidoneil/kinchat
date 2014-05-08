<!DOCTYPE html>
<html>
	<head>
		<title>kinchat</title>
		<meta name="description" content="kinchat is a free, online, rich-text chat client. The next and soon to be released version 1.2 will include private chatting and a new interface, among many other features." />
		<meta name="keywords" content="kinchat, kin, chat, trykinchat, try kinchat, trykinchat.com, online chat, chat client, rich-text chat, rich text chat" />
		<link rel="Shortcut Icon" href="images/favicon.ico" /> 
		<link rel="stylesheet" href="stylesheets/nivo-slider/nivo-slider.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="stylesheets/nivo-slider/default.css" type="text/css" media="screen" />
		<link rel="stylesheet" type="text/css" href="stylesheets/main-style.css" />
		<style type="text/css">
			
			#container {
				background: transparent;
				border-radius: none;
				-moz-border-radius: none;
				-webkit-border-radius: none;
				box-shadow: none;
				-moz-box-shadow: none;
				-webkit-box-shadow: none;
			}
			
		</style>
		<script type="text/javascript" src="scripts/jquery.min.js"></script>
		<script type="text/javascript" src="scripts/jquery.nivo.slider.min.js"></script>
		<script type="text/javascript">
			
			/*
			
			$(window).load(function() {
				$('#slider').nivoSlider({
					effect: 'fade',
					animSpeed: 500,
					pauseTime: 3000,
					startSlide: 0,
					directionNav: false,
					captionOpacity: 0.6,
				});
			});
			
			*/
			
			$(document).ready(function() {
				$('body').height($(document).height() - 50);
			});
			
		</script>
	</head>
	<body>
		<div id="nav_bar">
			<a href="index.html" title="Home"><img id="logo" src="images/logo.png" alt="" /></a>
			<div id="button_container">
				<a href="index.html"><div class="button">Home</div></a>
				<a href="register.html"><div class="button">Sign-Up</div></a>
				<a href="http://www.facebook.com/pages/Kinchat/190740614297028" target="_blank"><div class="button">Facebook</div></a>
				<a href="mailto:support@trykinchat.com" style="margin: 0;"><div class="button">Contact Us</div></a>
			</div>
		</div>
		<div id="container">
			<div id="content">
				
				<iframe width="560" height="315" src="http://www.youtube.com/embed/rdCnedrxslk?showinfo=0" frameborder="0" allowfullscreen style="margin-top: 50px; box-shadow: 0 0 15px dimgray;"></iframe>
				
			</div>
			<div id="form_container" style="text-align: center">
				
				<?php
				
				error_reporting(E_ALL);
				
				global $valid_user;
				$valid_user = false;
				
				include("chat/validate_request.php");
				
				if ($valid_user == true) {
					
					echo '<h1 style="line-height: 20px">@' . $_SESSION['username'] . '<br /><span style="font-size: 12pt;">Logged In</span></h1><img src="images/default.png" style="text-align: center; box-shadow: 0 0 15px gray; -moz-box-shadow: 0 0 15px gray; -webkit-box-shadow: 0 0 15px gray;" /><br /><br /><a href="chat" style="text-decoration: none;"><div class="button chat_button" style="width: 80px; margin: 0; float: left;">Chat!</div></a><a href="logout.php" style="text-decoration: none;"><div class="button logout_button" style=" width: 80px; margin: 0; float: right;" title="Talk to you later ~">Logout</div></a>';
					
				} else {
					
					echo '<form method="POST" action="chat/index.php"><h1>Login to kinchat</h1><div class="label">E-mail</div><br/><div class="field_container"><input class="field" name="email" type="text"/></div><br/><div class="label">Password</div><br/><div class="field_container"><input class="field" name="password" type="password"/></div><br/><div id="submit_button_container"><input id="submit_button" class="button" type="submit" value="Login"/></div><br/><p style="text-align:center;font-family:Colaborate Light;font-size:11pt;">Not yet a member? <a href="register.html">Sign-up</a>.</p></form>';
					
				}
				
				?>
				
			</div>
		</div>
	</body>
</html>