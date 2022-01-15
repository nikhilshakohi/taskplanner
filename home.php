<?php
include 'db.php';
include 'conditions.php';
/*session_start();*/
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
<body onload="toggleDiv('todaySchedule')">
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
	<!--Header-->
	<div id="header">
		<div><button class="basicButton hideSmallScreen" style="font-size: small;" onclick="window.location.href='about.php'">ABOUT</button></div>
		<div class="headingName" id="siteName"><a href="home.php">Task Planner</a></div>
		<div><button class="redButton hideSmallScreen"  style="font-size: small;"onclick="window.location.href='logout.php'">LOGOUT</button></div>
	</div><br>

	<!--Add Task-->
	<div id="addTask" onclick="showAddTask()">+</div>
	<!--Quick Notes-->
	<div id="addNotes" onclick="showNotes()">&#x2630;</div>
	<?php if($currentUsername=='nikhil'){ ?>
	<div id="expenseManager"><a target="_blank" href="https://expensemanager14911.000webhostapp.com">&#8377;</a></div>
	<?php }	?>
	<!--Display Later Divs-->
	<div id="editTaskDiv"></div>
	<div id="deleteTaskDiv"></div>
	<div id="profileDiv"></div>
	<div id="completeTaskDiv"></div>
	<div id="notesDiv"></div>
	<div id="backgroundDiv" onclick="closeDiv()"></div>
	<div id="addTaskDiv">
		<div class="outerDiv">
			<div class="innerDiv">
				<br><div class="subHeadingName">Add New Task: </div><hr><br><br>
				<!--Task Name-->
				<input id="addTaskNameInput" class="addTaskInput" type="text" onfocus="focusInput('addTaskName')" onfocusout="noFocusInput('addTaskName')">		
				<div class="addTaskInputHelper" id="addTaskNameHelper" onclick="focusInput('addTaskName')">Task</div><br><br><br>
				<div class="flexDisplay">
					<div style="margin: 5px;">
						<!--Task Date-->
						Start Time:<br>
						<input id="startTime" type="time" <?php echo 'value="'.date('H:i',time()).'"'; ?> onchange="changeEndTime()">
					</div>	
					<div style="margin: 5px;">
						<!--Task End Date-->
						Finish By:<br>
						<input id="endTime" type="time">	
					</div>
				</div>
				<div class="flexDisplay">
					<div style="margin: 5px;">
						<!--Task Date-->
						Start Date:<br>
						<input id="startDate" type="date" <?php echo 'value="'.date('Y-m-d',time()).'"'; ?>>
					</div>	
					<div style="margin: 5px;">
						<!--Task End Date-->
						Last Date:<br>
						<input id="endDate" type="date">	
					</div>
				</div>
				<!--Task Recurssion-->
				<div>Repeat every:</div><br>
				<div class="flexDisplay">
					<input class="selectTaskWeek" value="1" type="checkBox" id="mondayTask"><label title="Monday" class="selectTaskWeekLabel" id="mondayTaskLabel" for="mondayTask">M</label>
					<input class="selectTaskWeek" value="2" type="checkBox" id="tuesdayTask"><label title="Tuesday" class="selectTaskWeekLabel" id="tuesdayTaskLabel" for="tuesdayTask">T</label>
					<input class="selectTaskWeek" value="3" type="checkBox" id="wednesdayTask"><label title="Wednesday" class="selectTaskWeekLabel" id="wednesdayTaskLabel" for="wednesdayTask">W</label>
					<input class="selectTaskWeek" value="4" type="checkBox" id="thursdayTask"><label title="Thursday" class="selectTaskWeekLabel" id="thursdayTaskLabel" for="thursdayTask">T</label>
					<input class="selectTaskWeek" value="5" type="checkBox" id="fridayTask"><label title="Friday" class="selectTaskWeekLabel" id="fridayTaskLabel" for="fridayTask">F</label>
					<input class="selectTaskWeek" value="6" type="checkBox" id="saturdayTask"><label title="Saturday" class="selectTaskWeekLabel" id="saturdayTaskLabel" for="saturdayTask">S</label>
					<input class="selectTaskWeek" value="7" type="checkBox" id="sundayTask"><label title="Sunday" class="selectTaskWeekLabel" id="sundayTaskLabel" for="sundayTask">S</label>
				</div>
				<br>
				<!--Task Priority-->
				<select id="addTaskPriorityInput" class="addTaskInput" type="text" onfocus="focusInput('addTaskPriority')" onfocusout="noFocusInput('addTaskPriority')">
					<option value=""></option><!--Default-->
					<option value="high">High</option>
					<option value="medium">Medium</option>
					<option value="low">Low</option>
				</select>
				<div class="addTaskInputHelper" id="addTaskPriorityHelper" onclick="focusInput('addTaskPriority')">Task Priority</div><br><br><br><br>
				<!--Task Details-->
				More Details:<br>
				<textarea id="addTaskDetailsInput" placeholder="Additional details regarding the task"></textarea><br><br>
				<br>
				<div id="addTaskErrorMessage"></div>
				<button id="addTaskButton" class="basicButton" onclick="addTask()">ADD</button>
				<button class="redButtonOuter" onclick="closeDiv()">CLOSE</button><br><br><br>

			</div>
		</div>
	</div>
	<!--Check Schedule-->
	<div id="checkScheduleDiv">
		<div class="outerDiv">
			<div class="innerDiv">
				<br><div class="subHeadingName">Check Plans: </div><hr><br><br>
				<div style="margin: 5px;">
					<!--Task Date-->
					When should I check the schedule?<br>
					<input id="scheduleDate" type="date"><br><br>
					OR <button id="checkScheduleTomorrowButton" class="basicButtonOuter" onclick="toggleDiv('checkScheduleTomorrow')">tomorrow</button>
					<input id="checkScheduleTomorrow" type="hidden" <?php echo'value="'.date('Y-m-d',strtotime('tomorrow')).'"'; ?>>
				</div>	<br>
				<button id="checkScheduleButton" class="basicButton" onclick="toggleDiv('checkSchedule')">CHECK</button>
				<button class="redButtonOuter" onclick="closeDiv()">CLOSE</button><br><br>
				<span class="sideHeading">Today is <?php echo date('d-M-Y (l)',strtotime('today')); ?></span>
				<br>
			</div>
		</div>
	</div>

	<div id="content">
		<div class="contentCenter" id="menuDiv"><button id="menuButton" class="basicButton smallButton" onclick="toggleMenu()">Menu</button></div>
		<div class="contentCenter" id="menuSubDivOuter">
			<div id="menuSubDiv" onclick="toggleMobileMenu()">
				<button id="allTasksButton" class="basicButton smallButton fade" onclick="toggleDiv('allTasks')">All Plans</button>
				<button id="todayScheduleButton" class="basicButton smallButton fade" onclick="toggleDiv('todaySchedule')">Today's Schedule</button>
				<button id="completedTodayTasksButton" class="basicButton smallButton fade" onclick="toggleDiv('completedTodayTasks')">Completed Tasks</button>
				<button id="checkScheduleButton" class="basicButton smallButton fade" onclick="showCheckSchedule()">Check Schedule</button>
				<button id="profileButton" class="basicButton smallButton fade" onclick="showProfile()">profile</button>
				<span class="hideBigScreen">
					<button class="basicButton smallMobileButton fade" onclick="window.location.href='about.php'">about</button>
					<button class="redButton smallMobileButton fade" onclick="window.location.href='logout.php'">logout</button>	
				</span>
			</div>
		</div>

		<div class="contentCenter">
			<div class="subHeadingName" style="display: inline-flex;">
				<?php
					echo'<div>';
						if(date('H', time())<12){echo 'Good Morning ';}
						if((date('H', time())>=12)&&(date('H', time())<16)){echo 'Good Afternoon ';}
						if((date('H', time())>=16)&&(date('H', time())<24)){echo 'Good Evening ';}
						if($_SESSION['gender']=='male'){echo'sir ';}
						else if($_SESSION['gender']=='female'){echo'Madam';}
					echo '</div>
					<div>
						<div class="smile"></div>
						<div class="smileDotA"></div>
						<div class="smileDotB"></div>
					</div>';
				?>
			</div>
			<div id="todaySchedule">
				
			</div>
		</div>
	</div>

	<?php
	}else{
		echo '<div class="loaderButton"></div>
		<script>window.location.href="index.php"</script>';
	}
	?>

</body>
</html>