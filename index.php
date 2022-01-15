<?php
include 'db.php';
session_start();
date_default_timezone_set('Asia/Kolkata');
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="index.css">
	<script type="text/javascript" src="index.js"></script>
	<title>Task Planner</title>
	<style type="text/css">
		.basicButton,.basicButtonOuter{animation: fade 1s ease-in-out;}/*Only animate in this page*/
	</style>
</head>
<body onload="focusInput('username')">
	
	<!--Header-->
		<div id="header">
			<div><button class="basicButton hideSmallScreen" style="font-size: small;" onclick="window.location.href='about.php'">ABOUT</button></div>
			<div class="headingName">Task Planner</div>
			<!--Check Session-->
			<?php 
			if(isset($_SESSION['id'])){
			?>
				<div><button class="basicButton hideSmallScreen" style="font-size: small;" onclick="window.location.href='logout.php'">LOGOUT</button></div>
			<!--Check Session-->
			<?php 
			}else{
				?><div><button class="basicButton hideSmallScreen" style="font-size: small;" onclick="window.location.href='index.php'">LOGIN</button></div><?php
			}
			?>
		</div><br>

	<div id="content">
		<!--Check Session-->
		<?php 
		if(!isset($_SESSION['id'])){
		?>
			<!--Signup Div-->
			<div id="signupDiv">
				<div id="signupArea">
				<div class="headingName">Let's get Started!</div><hr class="hrDiv"><br><br>
				<!--Full Name-->
				<select class="signupInput signupInputTitle" id="genderSignupInput">
					<option value="">Title</option>
					<option value="male">Mr</option>
					<option value="female">Ms</option>
				</select><br><br><br>
				<input id="fullNameSignupInput" class="signupInput" type="text" onfocus="focusInput('fullNameSignup')" onfocusout="noFocusInput('fullNameSignup')">			
				<div class="signupInputHelper" id="fullNameSignupHelper" onclick="focusInput('fullNameSignup')">Full Name</div><br><br><br>
				<!--Username-->
				<input id="usernameSignupInput" class="signupInput" type="username" onfocus="focusInput('usernameSignup')" onfocusout="noFocusInput('usernameSignup')">			
				<div class="signupInputHelper" id="usernameSignupHelper" onclick="focusInput('usernameSignup')">Username</div><br><br><br>
				<!--Email-->
				<input id="emailSignupInput" class="signupInput" type="email" onfocus="focusInput('emailSignup')" onfocusout="noFocusInput('emailSignup')">			
				<div class="signupInputHelper" id="emailSignupHelper" onclick="focusInput('emailSignup')">E-Mail</div><br><br><br>
				<!--Password-->
				<input id="passwordSignupInput" class="signupInput" type="password" onfocus="focusInput('passwordSignup')" onfocusout="noFocusInput('passwordSignup')">			
				<div class="signupInputHelper" id="passwordSignupHelper" onclick="focusInput('passwordSignup')">Password</div><br><br><br>
				<!--Confirm Password-->
				<input id="confirmpasswordSignupInput" class="signupInput" type="password" onfocus="focusInput('confirmpasswordSignup')" onfocusout="noFocusInput('confirmpasswordSignup')">			
				<div class="signupInputHelper" id="confirmpasswordSignupHelper" onclick="focusInput('confirmpasswordSignup')">Re-Enter Password</div>
				<br><br><br>
				<input class="CheckBox" type="checkBox" id="showSignupPasswordBox" onclick="showPassword('Signup')">
				<label class="CheckBox" for="showSignupPasswordBox">Show Password</label>
				<br><br>
				<div id="signupErrorMessage"></div>
				<button id="signupButton" class="basicButton" onclick="signup()">SIGNUP</button><br>
				</div>
				<button class="basicButtonOuter" onclick="toggleSignIn('login')">LOGIN</button><br><br><br>
			</div>
			<!--Login Div-->
			<div id="loginDiv">
				<div id="loginArea">
				<div class="headingName">
					<?php 
						if(date('H', time())<12){echo 'Good Morning!';}
						if((date('H', time())>=12)&&(date('H', time())<16)){echo 'Good Afternoon!';}
						if((date('H', time())>=16)&&(date('H', time())<24)){echo 'Good Evening!';}
					?>
				</div><hr class="hrDiv"><br><br>
				<!--Username-->
				<input id="usernameLoginInput" class="loginInput" type="username" onfocus="focusInput('usernameLogin')" onfocusout="noFocusInput('usernameLogin')" autofocus>
				<div class="loginInputHelper" id="usernameLoginHelper" onclick="focusInput('usernameLogin')">Username / E-Mail</div><br><br><br>
				<!--Password-->
				<input id="passwordLoginInput" class="loginInput" type="password" onfocus="focusInput('passwordLogin')" onfocusout="noFocusInput('passwordLogin')">			
				<div class="loginInputHelper" id="passwordLoginHelper" onclick="focusInput('passwordLogin')">Password</div>
				<br><br><br>
				<input class="CheckBox" type="checkBox" id="showLoginPasswordBox" onclick="showPassword('Login')">
				<label class="CheckBox" for="showLoginPasswordBox">Show Password</label>
				<br><br>
				<div id="loginErrorMessage"></div>
				<button id="loginButton" class="basicButton" onclick="login()">LOGIN</button><br>
				</div>
				<button class="basicButtonOuter" onclick="toggleSignIn('signup')">SIGNUP</button><br><br><br>
			</div>

		<!--Check Session Closer-->
		<?php 
		}else if(isset($_SESSION['id'])){
			echo '<div class="loaderButton"></div>
			<script>window.location.href="home.php"</script>';
		}
		?>
		
	</div>

</body>
<script type="text/javascript">
	var loginEnter = document.getElementById("loginArea");
	loginEnter.addEventListener("keyup", function(event){
		if(event.keyCode == 13){event.preventDefault();login();}
	})
	var signupEnter = document.getElementById("signupArea");
	signupEnter.addEventListener("keyup", function(event){
		if(event.keyCode == 13){event.preventDefault();signup();}
	})
</script>
</html>