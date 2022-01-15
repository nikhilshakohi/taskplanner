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
</head>
<body>
	
	<!--Check Session-->
	<?php 
	if(isset($_SESSION['id'])){
		$uid=$_SESSION['id'];
		$getUsername=mysqli_query($conn,"SELECT * FROM users WHERE id='$uid'");
		if(mysqli_num_rows($getUsername)>0){
			while($rowUser=mysqli_fetch_assoc($getUsername)){$currentUsername=$rowUser['username'];}
		}
		echo '<input type="hidden" id="username" value="'.$currentUsername.'">';
	?>
	<h1>About Task Planner!</h1>
	<?php
	}else{
		echo '<div class="loaderButton"></div>
		<script>window.location.href="index.php"</script>';
	}
	?>
</body>
</html>