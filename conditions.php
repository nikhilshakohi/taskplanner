<?php 

include_once 'db.php';
session_start();
date_default_timezone_set('Asia/Kolkata');


/*Signup*/
if(isset($_POST['signup'])){
	$username = mysqli_real_escape_string($conn,$_POST['username']);
	$password = mysqli_real_escape_string($conn,$_POST['password']);
	$email = mysqli_real_escape_string($conn,$_POST['email']);
	$gender = mysqli_real_escape_string($conn,$_POST['gender']);
	$fullName = mysqli_real_escape_string($conn,$_POST['fullName']);
	/*Check if user already created*/
	$checkUser = mysqli_query($conn,"SELECT * FROM users WHERE username = '$username' OR email = '$email'");
	if(mysqli_num_rows($checkUser)>0){
		echo '<span class="errorMessage">Username or E-Mail already available! Try with a new one / Login</span>';
	}else{
		$name = ucwords($fullName);/*To convert name to first letter Capital Name*/
		$addUser = mysqli_query($conn,"INSERT INTO users (username, password, email, gender, name) VALUES ('$username', '$password', '$email', '$gender', '$name')");
		echo '<span class="successMessage">User added successfully! Login to continue..</span>';
	}
}

/*Login*/
if(isset($_POST['login'])){
	$username = mysqli_real_escape_string($conn,$_POST['username']);
	$password = mysqli_real_escape_string($conn,$_POST['password']);
	/*Check for user*/
	$checkUser = mysqli_query($conn,"SELECT * FROM users WHERE (username = '$username' OR email = '$username')");
	if(mysqli_num_rows($checkUser)>0){
		while($rowUser = mysqli_fetch_assoc($checkUser)){
			$checkPassword = $rowUser['password'];
			$passwordChecked="notVerified";
			$encID=(149118912*$rowUser['id'])+149118912;
			if(isset($_COOKIE['userID'])){
				if($_COOKIE['userID']==$encID){$passwordChecked="verified";}
				else if($checkPassword == $password){$passwordChecked="verified";}
				else{$passwordChecked="notVerified";}
			}else{
				if($checkPassword == $password){$passwordChecked="verified";}
				else{$passwordChecked="notVerified";}
			}
			if($passwordChecked=="verified"){
				$_SESSION['id'] = $rowUser['id'];
				$_SESSION['username'] = $rowUser['username'];
				$_SESSION['name'] = $rowUser['name'];
				$_SESSION['gender'] = $rowUser['gender'];
				if($_SESSION['gender']=='male'){$_SESSION['genderCall'] = 'sir';}
				else if($_SESSION['gender']=='female'){$_SESSION['genderCall'] = 'madam';}
				if($_POST['autoLogin']=='enabled'){
					setcookie('autoLogin','yes',time()+3600*5,'/');
					setcookie('username',$rowUser['username'],time()+3600*5,'/');
					$encID=(149118912*$rowUser['id'])+149118912;
					setcookie('userID',$encID,time()+3600*5,'/');
				}
				echo 'loginSuccess';
				exit();
			}else{
				echo '<span class="errorMessage">Invalid Credentials</span>';
			}	
		}
	}else{
		echo '<span class="errorMessage">No User Found! Signup to create an account</span>';
	}
}

/*Add Tasks*/
if(isset($_POST['addTask'])){
	$username = mysqli_real_escape_string($conn,$_POST['username']);
	$taskName = mysqli_real_escape_string($conn,$_POST['taskName']);
	$startTime = mysqli_real_escape_string($conn,$_POST['startTime']);
	$endTime = mysqli_real_escape_string($conn,$_POST['endTime']);
	$startDate = mysqli_real_escape_string($conn,$_POST['startDate']);
	$endDate = mysqli_real_escape_string($conn,$_POST['endDate']);
	$repeater = mysqli_real_escape_string($conn,$_POST['repeater']);
	$priority = mysqli_real_escape_string($conn,$_POST['priority']);
	$details = mysqli_real_escape_string($conn,$_POST['details']);
	$addTask = mysqli_query($conn,"INSERT INTO tasks (username, task, startTime, endTime, startDate, endDate, repeater, priority, details) VALUES ('$username', '$taskName', '$startTime', '$endTime', '$startDate', '$endDate', '$repeater', '$priority', '$details')");
	echo '<span class="successMessage">Task has been added succesfully '.$_SESSION['genderCall'].'!</span>
	-period-';
	$currentUsername=$username;
	$todayDate=date('Y-m-d',time());
	$todayDayNumber=date('N',time());
	$todayDateCheck=date('Ymd',time());
	$taskQuery=todayScheduleQuery($conn,$currentUsername);
	getTasks($conn,$currentUsername,$taskQuery,'todaySchedule');
	$newData = $taskName." as taskName, ".$username." as username, ".$startTime." as startTime, ".$endTime." as endTime, ".$startDate." as startDate, ".$endDate." as endDate, ".$repeater." as repeater, ".$priority." as priority, ".$details." as details.";
	echo'-period-'.$newData;
}

/*Task Completed*/
if(isset($_POST['taskCompleted'])){
	$id=$_POST['id'];
	$currentUsername=$_POST['username'];
	$todayDateCheck=date('Ymd',time());
	$newStatus=$todayDateCheck.'-';
	$updateCompletion=mysqli_query($conn,"UPDATE tasks SET completionStatus='$todayDateCheck', completionDates=CONCAT(completionDates, '$newStatus') WHERE id='$id'");
	echo 'UpdateDone-period-';
	$todayDate=date('Y-m-d',time());
	$todayDayNumber=date('N',time());
	$todayDateCheck=date('Ymd',time());
	$taskQuery=todayScheduleQuery($conn,$currentUsername);
	getTasks($conn,$currentUsername,$taskQuery,'todaySchedule');
}

/*Show Edit Task*/
if(isset($_POST['editTask'])){
	$id=$_POST['id'];
	$currentUsername=$_POST['username'];
	$checkTask=mysqli_query($conn,"SELECT * FROM tasks WHERE id='$id'");
	if(mysqli_num_rows($checkTask)>0){
		while($rowTask=mysqli_fetch_assoc($checkTask)){
			echo '
			<div class="outerDiv">
				<div class="innerDiv">
					<br><div class="subHeadingName">Update Task:</div><hr><br><br>
					<!--Task Name-->
					<input id="'.$id.'addTaskNameInput" class="addTaskInput" type="text" onfocus="focusInput(\''.$id.'addTaskName'.'\')" onfocusout="noFocusInput(\''.$id.'addTaskName'.'\')">		
					<div class="addTaskInputHelper" id="'.$id.'addTaskNameHelper" onclick="focusInput(\''.$id.'addTaskName'.'\')">Task</div><br><br><br>
					<div class="flexDisplay">
						<div style="margin: 5px;">
							<!--Task Date-->
							Start Time:<br>
							<input id="startTime'.$id.'" type="time">
						</div>	
						<div style="margin: 5px;">
							<!--Task End Date-->
							Finish By:<br>
							<input id="endTime'.$id.'" type="time">	
						</div>
					</div>
					<div class="flexDisplay">
						<div style="margin: 5px;">
							<!--Task Date-->
							Start Date:<br>
							<input id="startDate'.$id.'" type="date">
						</div>	
						<div style="margin: 5px;">
							<!--Task End Date-->
							Last Date:<br>
							<input id="endDate'.$id.'" type="date">	
						</div>
					</div>
					<!--Task Recurssion-->
					<div>Repeat every:</div><br>
					<div class="flexDisplay">
						<input class="selectTaskWeek" value="1" type="checkBox" id="mondayTask'.$id.'"><label title="Monday" class="selectTaskWeekLabel" id="mondayTaskLabel'.$id.'" for="mondayTask'.$id.'">M</label>
						<input class="selectTaskWeek" value="2" type="checkBox" id="tuesdayTask'.$id.'"><label title="Tuesday" class="selectTaskWeekLabel" id="tuesdayTaskLabel'.$id.'" for="tuesdayTask'.$id.'">T</label>
						<input class="selectTaskWeek" value="3" type="checkBox" id="wednesdayTask'.$id.'"><label title="Wednesday" class="selectTaskWeekLabel" id="wednesdayTaskLabel'.$id.'" for="wednesdayTask'.$id.'">W</label>
						<input class="selectTaskWeek" value="4" type="checkBox" id="thursdayTask'.$id.'"><label title="Thursday" class="selectTaskWeekLabel" id="thursdayTaskLabel'.$id.'" for="thursdayTask'.$id.'">T</label>
						<input class="selectTaskWeek" value="5" type="checkBox" id="fridayTask'.$id.'"><label title="Friday" class="selectTaskWeekLabel" id="fridayTaskLabel'.$id.'" for="fridayTask'.$id.'">F</label>
						<input class="selectTaskWeek" value="6" type="checkBox" id="saturdayTask'.$id.'"><label title="Saturday" class="selectTaskWeekLabel" id="saturdayTaskLabel'.$id.'" for="saturdayTask'.$id.'">S</label>
						<input class="selectTaskWeek" value="7" type="checkBox" id="sundayTask'.$id.'"><label title="Sunday" class="selectTaskWeekLabel" id="sundayTaskLabel'.$id.'" for="sundayTask'.$id.'">S</label>
					</div>
					<br>
					<!--Task Priority-->
					<select id="'.$id.'addTaskPriorityInput" class="addTaskInput" type="text" onfocus="focusInput(\''.$id.'addTaskPriority'.'\')" onfocusout="noFocusInput(\''.$id.'addTaskPriority'.'\')">
						<option value=""></option><!--Default-->
						<option value="high">High</option>
						<option value="medium">Medium</option>
						<option value="low">Low</option>
					</select>
					<div class="addTaskInputHelper" id="'.$id.'addTaskPriorityHelper" onclick="focusInput(\''.$id.'addTaskPriority'.'\')">Task Priority</div><br><br><br><br>
					<!--Task Details-->
					More Details:<br>
					<textarea id="addTaskDetailsInput'.$id.'" placeholder="Additional details regarding the task"></textarea><br><br>
					<br>
					<div id="addTaskErrorMessage'.$id.'"></div>
					<button id="addTaskButton'.$id.'" class="basicButton" onclick="confirmEditTask(\''.$id.'\')">DONE</button>
					<button class="redButtonOuter" onclick="closeDiv()">CLOSE</button><br><br><br>

				</div>
			</div>
			-period-';
			/*Send current values to JS*/
			echo $rowTask['task'].'-period-'.$rowTask['startTime'].'-period-'.$rowTask['endTime'].'-period-'.$rowTask['startDate'].'-period-'.$rowTask['endDate'].'-period-'.$rowTask['repeater'].'-period-'.$rowTask['priority'].'-period-'.$rowTask['details'];
		}
	}
}

/*Edit Task*/
if(isset($_POST['confirmEditTask'])){
	$id = mysqli_real_escape_string($conn,$_POST['id']);
	$username = mysqli_real_escape_string($conn,$_POST['username']);
	$taskName = mysqli_real_escape_string($conn,$_POST['taskName']);
	$startTime = mysqli_real_escape_string($conn,$_POST['startTime']);
	$endTime = mysqli_real_escape_string($conn,$_POST['endTime']);
	$startDate = mysqli_real_escape_string($conn,$_POST['startDate']);
	$endDate = mysqli_real_escape_string($conn,$_POST['endDate']);
	$repeater = mysqli_real_escape_string($conn,$_POST['repeater']);
	$priority = mysqli_real_escape_string($conn,$_POST['priority']);
	$details = mysqli_real_escape_string($conn,$_POST['details']);
	$addTask = mysqli_query($conn,"UPDATE tasks SET task='$taskName', startTime='$startTime', endTime='$endTime', startDate='$startDate', endDate='$endDate', repeater='$repeater', priority='$priority', details='$details' WHERE id='$id'");
	echo '<span class="successMessage">Task has been Edited succesfully '.$_SESSION['genderCall'].'!</span>
	-period-';
	$currentUsername=$username;
	$todayDate=date('Y-m-d',time());
	$todayDayNumber=date('N',time());
	$todayDateCheck=date('Ymd',time());
	$taskQuery=todayScheduleQuery($conn,$currentUsername);
	getTasks($conn,$currentUsername,$taskQuery,'todaySchedule');
	$todayDayNumber=date('N',time());
	echo '-period-';
	if(strpos($repeater, $todayDayNumber)){echo'todayYes';}
}

/*Show Delete Task*/
if(isset($_POST['deleteTask'])){
	$id=$_POST['id'];
	$currentUsername=$_POST['username'];
	$deleteTask=mysqli_query($conn,"DELETE FROM tasks WHERE id='$id'");
	echo'taskDeleted-period-';
	$todayDate=date('Y-m-d',time());
	$todayDayNumber=date('N',time());
	$todayDateCheck=date('Ymd',time());
	$taskQuery=todayScheduleQuery($conn,$currentUsername);
	getTasks($conn,$currentUsername,$taskQuery,'todaySchedule');
}

/*Toggle Task Div*/
if(isset($_POST['toggleTask'])){
	$type=$_POST['type'];
	$scheduleDate=$_POST['scheduleDate'];
	$currentUsername=$_POST['username'];
	$todayDate=date('Y-m-d',time());
	$todayDayNumber=date('N',time());
	$todayDateCheck=date('Ymd',time());
	
	if($type=='todaySchedule'){
		$taskQuery=todayScheduleQuery($conn,$currentUsername);
		getTasks($conn,$currentUsername,$taskQuery,'todaySchedule');
	}else if($type=='allTasks'){
		$taskQuery=mysqli_query($conn,"SELECT * FROM tasks WHERE (username='$currentUsername') ORDER BY startDate ASC,startTime ASC");
		getTasks($conn,$currentUsername,$taskQuery,'allTasks');
	}else if($type=='completedTodayTasks'){
		$taskQuery=mysqli_query($conn,"SELECT * FROM tasks WHERE (username='$currentUsername') AND (completionStatus != '0') ORDER BY completionStatus DESC, startDate DESC, startTime ASC");
		getTasks($conn,$currentUsername,$taskQuery,'completedTodayTasks');
	}else if($type=='checkSchedule'){
		$todayDate=date('Y-m-d',strtotime($scheduleDate));
		$todayDayNumber=date('N',strtotime($scheduleDate));
		$todayDateCheck=date('Ymd',strtotime($scheduleDate));
		$taskQuery=mysqli_query($conn,"SELECT * FROM tasks WHERE (username='$currentUsername') AND ( ((startDate<'$todayDate') AND (endDate!='0000-00-00') AND (endDate>'$todayDate')) || (startDate='$todayDate') || (endDate='$todayDate') || ((endDate='0000-00-00') AND (startDate<='$todayDate') AND (repeater LIKE '%$todayDayNumber%')) ) ORDER BY startTime ASC");
		$combinedVarSch='checkSchedule-period-'.$scheduleDate;
		getTasks($conn,$currentUsername,$taskQuery,$combinedVarSch);
	}
}

/*Today Schedule Query*/
function todayScheduleQuery($conn,$currentUsername){
	$todayDate=date('Y-m-d',time());
	$todayDayNumber=date('N',time());
	$todayDateCheck=date('Ymd',time());
	$taskQuery=mysqli_query($conn,"SELECT * FROM tasks WHERE (username='$currentUsername') AND (completionStatus != '1' AND completionStatus != '$todayDateCheck') AND ( ((startDate<'$todayDate') AND (endDate!='0000-00-00') AND (endDate>'$todayDate')) || (startDate='$todayDate') || (endDate='$todayDate') || ((endDate='0000-00-00') AND (startDate<='$todayDate') AND (repeater LIKE '%$todayDayNumber%')) ) ORDER BY startTime ASC");
	return $taskQuery;
		
}

/*Redo Task*/
if(isset($_POST['redoTask'])){
	$id = mysqli_real_escape_string($conn,$_POST['id']);
	$currentUsername = mysqli_real_escape_string($conn,$_POST['username']);
	$redoTask = mysqli_query($conn,"UPDATE tasks SET completionStatus='0' WHERE id='$id'");
	echo 'UpdateDone-period-';
	$todayDate=date('Y-m-d',time());
	$todayDayNumber=date('N',time());
	$todayDateCheck=date('Ymd',time());
	$taskQuery=todayScheduleQuery($conn,$currentUsername);
	getTasks($conn,$currentUsername,$taskQuery,'todaySchedule');
}

/*Profile Details*/
if(isset($_POST['getProfileDetails'])){
	$username = $_POST['username'];
	$getDetails = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
	if(mysqli_num_rows($getDetails)>0){
		while($rowDetails = mysqli_fetch_assoc($getDetails)){
			echo'<div class="outerDiv">
				<div class="innerDiv">
					<br><br><div class = "subHeadingName">Profile Details</div><hr><br>
					<table style="margin-left:auto;margin-right:auto">
						<tr>
							<td class="sideHeading">Username : </td>
							<td><b> '.$rowDetails['username'].'</b></td>
							<td><button id = "editProfileusername" class = "basicButtonOuter smallButton" onclick = "editProfile(\''.'username'.'-period-'.$rowDetails['username'].'-period-'.$rowDetails['id'].'\')">edit</button></td>
						</tr>
						<tr>
							<td class="sideHeading">Password : </td>
							<td><b> ********</b></td>
							<td><button id = "editProfilepassword" class = "basicButtonOuter smallButton" onclick = "editProfile(\''.'password'.'-period-'.$rowDetails['password'].'-period-'.$rowDetails['id'].'\')">edit</button></td>
						</tr>
						<tr>
							<td class="sideHeading">Email ID : </td>
							<td><b> '.$rowDetails['email'].'</b></td>
							<td><button id = "editProfileemail" class = "basicButtonOuter smallButton" onclick = "editProfile(\''.'email'.'-period-'.$rowDetails['email'].'-period-'.$rowDetails['id'].'\')">edit</button></td>
						</tr>
						<tr>
							<td class="sideHeading">Name : </td>
							<td><b>'.ucwords($rowDetails['name']).'</b></td>
							<td><button id = "editProfilename" class = "basicButtonOuter smallButton" onclick = "editProfile(\''.'name'.'-period-'.$rowDetails['name'].'-period-'.$rowDetails['id'].'\')">edit</button></td>
						</tr>
					</table>
					<button class="redButton" onclick = "closeDiv()">CLOSE</button>
				</div>
			</div>';
		}
	}
}

/*Edit Profile*/
if(isset($_POST['editProfile'])){
	$type = $_POST['type'];
	$value = $_POST['value'];
	$id = $_POST['id'];
	echo '<div class="outerDiv">
		<div class="innerDiv"><br><br>
			Edit the '.ucwords($type).'<br><br>
			<input type = "hidden" id = "oldValueEditProfileDetail'.$type.'" value = "'.$value.'">
			<textarea type = "text" id = "newValueEditProfileDetail'.$type.'" placeholder="Edit '.$type.'"></textarea>
			<div id = "editProfileStatus"></div><br><br><br>
			<button class = "greenButton" id="confirmEditProfileDetailsButton" onclick = "confirmEditProfileDetails(\''.$type.'\',\''.$id.'\')">DONE</button>
			<button class = "redButtonOuter" onclick = "closeDiv()">CLOSE</button>
		</div>
	</div>';
}

if (isset($_POST['confirmEditProfile'])) {
	$type = $_POST['type'];
	$id = $_POST['id'];
	$newValue = mysqli_real_escape_string($conn, $_POST['newValue']);
	$oldValue = $_POST['oldValue'];
	$updateCheck = 0;
	if($type == 'email'){
		$checkEmail = mysqli_query($conn, "SELECT * FROM users WHERE email = '$newValue'");
		if(mysqli_num_rows($checkEmail)<1){
			$updateCheck = 1;
		}else{
			echo 'AlreadyExists';
		}	
	}else if($type == 'username'){
		$checkUsername = mysqli_query($conn, "SELECT * FROM users WHERE username = '$newValue'");
		if(mysqli_num_rows($checkUsername)<1){
			$updateCheck = 1;
		}else{
			echo 'AlreadyExists';
		}	
	}else if($type == 'password' || $type == 'name'){
		$updateCheck = 1;
	}
	if($updateCheck == 1){
		if($type == 'username'){
			$updater = mysqli_query($conn, "UPDATE users SET username = '$newValue' WHERE id = '$id'");	
			$updater = mysqli_query($conn, "UPDATE tasks SET username = '$newValue' WHERE username = '$oldValue'");	
			echo 'updateDone';	
		}else if($type == 'email'){
			$updater = mysqli_query($conn, "UPDATE users SET email = '$newValue' WHERE id = '$id'");	
			echo 'updateDone';	
		}else if($type == 'name'){
			$updater = mysqli_query($conn, "UPDATE users SET name = '$newValue' WHERE id = '$id'");	
			echo 'updateDone';	
		}else if($type == 'password'){
			$updater = mysqli_query($conn, "UPDATE users SET password = '$newValue' WHERE id = '$id'");	
			echo 'updateDone';	
		}
	}
}

/*Show Complete Task*/
if(isset($_POST['markTaskCompleted'])){
	$id=$_POST['id'];
	$currentUsername=$_POST['username'];
	$checkTask=mysqli_query($conn,"SELECT * FROM tasks WHERE id='$id'");
	if(mysqli_num_rows($checkTask)>0){
		while($rowTask=mysqli_fetch_assoc($checkTask)){
			echo '
			<div class="outerDiv">
				<div class="innerDiv">
					<br><div class="subHeadingName">Mark this Task completed on:</div><hr><br><br>
						<span class="sideHeading">Task Name: </span>'.$rowTask['task'].'<br><br>
						<span class="sideHeading">Started on: </span>'.$rowTask['startDate'].'<br><br>
						<span class="sideHeading">End Date: </span>'.$rowTask['endDate'].'<br><br>';
						if($rowTask['details']!=''){echo'<span class="sideHeading">Details: </span>'.$rowTask['details'].'<br><br>';}
						echo'Task Completed on: <input id="completedDate'.$id.'" type="date" value="'.$rowTask['endDate'].'">	
					<br><br>
					<br>
					<div id="completeTaskErrorMessage'.$id.'"></div>
					<button id="completedButton'.$rowTask['id'].'" class="basicButton" onclick="confirmTaskCompleted(\''.$id.'\')">DONE</button>
					<button class="redButtonOuter" onclick="closeDiv()">CLOSE</button><br><br><br>
				</div>
			</div>
			-period-';
			/*Send current values to JS*/
			echo $rowTask['task'].'-period-'.$rowTask['startTime'].'-period-'.$rowTask['endTime'].'-period-'.$rowTask['startDate'].'-period-'.$rowTask['endDate'].'-period-'.$rowTask['repeater'].'-period-'.$rowTask['priority'].'-period-'.$rowTask['details'];
		}
	}
}

/*Complete Task*/
if(isset($_POST['confirmCompletedTask'])){
	$id = mysqli_real_escape_string($conn,$_POST['id']);
	$currentUsername=$_POST['username'];
	$completedDate = mysqli_real_escape_string($conn,$_POST['completedDate']);
	$completedDateNew=date('Ymd',strtotime($completedDate));
	$newStatus=$completedDateNew.'-';
	$addTask = mysqli_query($conn,"UPDATE tasks SET completionStatus='$completedDateNew', completionDates=CONCAT(completionDates, '$newStatus') WHERE id='$id'");
	echo '<div class="successMessage">Task Marked as completed Boss!</div>
	-period-';
	$taskQuery=mysqli_query($conn,"SELECT * FROM tasks WHERE (username='$currentUsername') ORDER BY startDate ASC,startTime ASC");
	getTasks($conn,$currentUsername,$taskQuery,'allTasks');
}

/*Get Notes*/
if(isset($_POST['checkNotes'])){
	$username=mysqli_real_escape_string($conn,$_POST['username']);
	$newUsername=$username.'notes';
	$newCompletedUsername=$username.'noted';
	$checkNotes=mysqli_query($conn,"SELECT * FROM tasks WHERE (username='$newUsername') OR (username='$newCompletedUsername') ORDER BY username DESC, startDate DESC, startTime DESC");
	echo '
	<div class="outerDiv">
		<div class="innerDiv">
			<div class="subHeadingName">Quick Notes:</div><hr>';
			if(mysqli_num_rows($checkNotes)>0){
				echo'<div class="notesContent">';
				while($rowNotes=mysqli_fetch_assoc($checkNotes)){
					echo '<div id="eachNote'.$rowNotes['id'].'" class="eacNoteDiv">';
						if($rowNotes['username']==$newUsername){
							echo '<div class="noteDetail">&#x2605; '.$rowNotes['task'].'</div>
							<div class="flexHelperButton">
								<button class="greenButton smallButton" onclick="markNoted(\''.$rowNotes['id'].'\')">DONE</button>
								<button class="redButtonOuter smallButton" onclick="showDeleteNote(\''.$rowNotes['id'].'\')">DELETE</button>
								<span id="noteDeleteConfirmation'.$rowNotes['id'].'"></span>
							</div>';
						}else if($rowNotes['username']==$newCompletedUsername){
							echo '<div class="crossed noteDetail">&#x2605; '.$rowNotes['task'].'</div>
							<div class="flexHelperButton">
								<button class="basicButtonOuter smallButton" onclick="markUnNoted(\''.$rowNotes['id'].'\')">UNDO</button>
								<button class="redButtonOuter smallButton" onclick="showDeleteNote(\''.$rowNotes['id'].'\')">DELETE</button>
								<span id="noteDeleteConfirmation'.$rowNotes['id'].'"></span>
							</div>';
						}
					echo'</div>';
				}
				echo'</div>';
			}else{
				echo'<div>No tasks have been added boss..!</div>';
			}
			echo'
				<textarea id="noteInput" placeholder="Add a new point to remember"></textarea><br>
				<div id="notesErrorMessage"></div>
				<button class="basicButton smallButton" onclick="addNote()">ADD</button>
				<button class="redButtonOuter smallButton" onclick="closeDiv()">CLOSE</button>
		</div>
	</div>
	';
}

/*Add Notes*/
if(isset($_POST['addNotes'])){
	$username=mysqli_real_escape_string($conn,$_POST['username']);
	$noteInput=mysqli_real_escape_string($conn,$_POST['noteInput']);
	$newUsername=$username.'notes';
	$todayDate=date('Y-m-d',time());
	$todayTime=date('H:i:s',time());
	/*Declare Null values*/
	$endDate='0000-00-00';$endTime='00:00:00';$repeater='';$priority='';$details='';$completionStatus='';$completionDates='';
	mysqli_query($conn,"INSERT INTO tasks (username,task,startDate,startTime,endDate,endTime,repeater,priority,details,completionStatus,completionDates) VALUES ('$newUsername','$noteInput','$todayDate','$todayTime','$endDate','$endTime','$repeater','$priority','$details','$completionStatus','$completionDates')");
	echo'Done';
}

/*Note mark as noted*/
if(isset($_POST['markNoted'])){
	$username=$_POST['username'];
	$id=$_POST['id'];
	$newUsername=$username.'noted';
	$modifyNote=mysqli_query($conn,"UPDATE tasks SET username='$newUsername' WHERE id='$id'");
	echo'Done';
}

/*Note mark as noted*/
if(isset($_POST['markUnNoted'])){
	$username=$_POST['username'];
	$id=$_POST['id'];
	$newUsername=$username.'notes';
	$modifyNote=mysqli_query($conn,"UPDATE tasks SET username='$newUsername' WHERE id='$id'");
	echo'Done';
}

/*Note mark as noted*/
if(isset($_POST['deleteNote'])){
	$username=$_POST['username'];
	$id=$_POST['id'];
	$modifyNote=mysqli_query($conn,"DELETE FROM tasks WHERE id='$id'");
	echo'Done';
}

/*Show Previous Task to be Done*/
if(isset($_POST['completePreviousTask'])){
	$id=$_POST['id'];
	$todayDate=date('Y-m-d',time());
	$currentUsername=$_POST['username'];
	$checkTask=mysqli_query($conn,"SELECT * FROM tasks WHERE id='$id'");
	if(mysqli_num_rows($checkTask)>0){
		while($rowTask=mysqli_fetch_assoc($checkTask)){
			echo '
			<div class="outerDiv">
				<div class="innerDiv">
					<br><div class="subHeadingName">Mark this Recurrent Task as completed on:</div><hr><br><br>
						<span class="sideHeading">Task Name: </span>'.$rowTask['task'].'<br><br>';
						if($rowTask['details']!=''){echo'<span class="sideHeading">Details: </span>'.$rowTask['details'].'<br><br>';}
						echo'<span class="sideHeading">Started on: </span>'.date('d-M-Y',strtotime($rowTask['startDate'])).'<br><br>';
						if($rowTask['endDate']!='0000-00-00'){echo'<span class="sideHeading">End Date: </span>'.$rowTask['endDate'].'<br><br>';}
						if($rowTask['endDate']!='0000-00-00'&&$rowTask['endDate']>$todayDate){$lastDate=$rowTask['endDate'];}
						else{$lastDate=$todayDate;}
						echo'<span class="sideHeading">Mark Task as Completed on: </span><input id="completedDate'.$id.'" type="date" value="'.$todayDate.'" min=""'.$rowTask['startDate'].'"" max="'.$lastDate.'"><br><br>';
						if($rowTask['completionDates']!=''){
							echo'<span class="sideHeading">Task Previously Completed on: </span><br>';
							$getDates=explode('-', $rowTask['completionDates']);
							$getDatesCount=count($getDates)-1;
							for($i=$getDatesCount-1;$i>=0;$i--){
								$historyCompletedYr=substr($getDates[$i], 0, 4);
								$historyCompletedMt=substr($getDates[$i], 4, 2);
								$historyCompletedDt=substr($getDates[$i], 6, 2);
								$combinedhistoryDt=$historyCompletedYr.'-'.$historyCompletedMt.'-'.$historyCompletedDt;
								$historyDt=date('d-M-Y(l) ', strtotime($combinedhistoryDt));
								echo $historyDt.'<br>';
							}	
						}
						echo'
					<br><br>
					<br>
					<div id="completeTaskErrorMessage'.$id.'"></div>
					<button id="completedButton'.$rowTask['id'].'" class="basicButton" onclick="confirmTaskCompletedPreviously(\''.$id.'\')">DONE</button>
					<button class="redButtonOuter" onclick="closeDiv()">CLOSE</button><br><br><br>
				</div>
			</div>
			-period-';
			/*Send current values to JS*/
			echo $rowTask['task'].'-period-'.$rowTask['startTime'].'-period-'.$rowTask['endTime'].'-period-'.$rowTask['startDate'].'-period-'.$rowTask['endDate'].'-period-'.$rowTask['repeater'].'-period-'.$rowTask['priority'].'-period-'.$rowTask['details'];
		}
	}
}

/*Update Previously Completed task Date*/
if(isset($_POST['confirmTaskCompletedPreviously'])){
	$id=$_POST['id'];
	$currentUsername=$_POST['username'];
	$completedDate=$_POST['completedDate'];
	$completedDateInTime=date('Ymd',strtotime($completedDate));
	$checkDateAvail=0;
	$checkTask=mysqli_query($conn,"SELECT * FROM tasks WHERE id='$id'");
	if(mysqli_num_rows($checkTask)>0){
		while($rowTask=mysqli_fetch_assoc($checkTask)){
			$allDates=$rowTask['completionDates'];
			$currentCompletionDate=$rowTask['completionStatus'];
		}
		$allDatesArray=explode('-', $allDates);
		foreach ($allDatesArray as $eachDate) {
			if($eachDate==$completedDateInTime){
				$checkDateAvail=1;
			}
		}
		if($checkDateAvail==1){
			echo'AlreadyAddedError-period-<div class="errorMessage">'.date('d-M-Y',strtotime($completedDate)).' was already marked as done '.$_SESSION['genderCall'].'!</div>';
		}else if($checkDateAvail==0){
			$newStatus=$completedDateInTime.'-';
			if($completedDateInTime>$currentCompletionDate){$newCompletionDate=$completedDateInTime;}
			else{$newCompletionDate=$currentCompletionDate;}
			$updatePreviousCompletedDate=mysqli_query($conn,"UPDATE tasks SET completionStatus='$newCompletionDate', completionDates=CONCAT(completionDates, '$newStatus') WHERE id='$id'");
			echo'UpdateDone-period-
			<div class="successMessage">'.date('d-M-Y',strtotime($completedDate)).' is added succesfully in the task History Boss!</div>';
			echo'-period-';
			$taskQuery=todayScheduleQuery($conn,$currentUsername);
			getTasks($conn,$currentUsername,$taskQuery,'todaySchedule');
		}
	}else{
		echo'<div class="errorMessage">Something went fishy '.$_SESSION['genderCall'].'!</div>';
	}
}

function getTasks($conn,$currentUsername,$taskQuery,$type){
	$typeCheck=explode('-period-', $type);
	if($typeCheck[0]=='checkSchedule'){
		$todayDate=date('Y-m-d',strtotime($typeCheck[1]));
		$todayDayNumber=date('N',strtotime($typeCheck[1]));
		$todayDateCheck=date('Ymd',strtotime($typeCheck[1]));
		$type=$typeCheck[0];
	}else{
		$todayDate=date('Y-m-d',time());
		$todayDayNumber=date('N',time());
		$todayDateCheck=date('Ymd',time());
	}
	/*Get all Tasks*/
	$checkTasks=$taskQuery;
	if(mysqli_num_rows($checkTasks)>0){
		if($type=='todaySchedule'){
			echo'<h3>Here is Today'."'".'s Schedule:</h3><hr>';
		}else if($type=='allTasks'){
			echo '<div style="display:flex;justify-content:space-between">
				<div><button class="redButton smallButton" style="opacity:0;pointer-events:none">CLOSE</button></div>
				<div class="headingName">Here are all the Plans<br></div>
				<div><button class="redButton smallButton" onclick="toggleDiv(\''.'todaySchedule'.'\')">CLOSE</button></div>
			</div><hr>';
		}else if($type=='completedTodayTasks'){
			echo '<div style="display:flex;justify-content:space-between">
				<div><button class="redButton smallButton" style="opacity:0;pointer-events:none">CLOSE</button></div>
				<div class="headingName">List of Completed Tasks<br></div>
				<div><button class="redButton smallButton" onclick="toggleDiv(\''.'todaySchedule'.'\')">CLOSE</button></div>
			</div><hr>';
		}else if($type=='checkSchedule'){
			echo '<div style="display:flex;justify-content:space-between">
				<div><button class="redButton smallButton" style="opacity:0;pointer-events:none">CLOSE</button></div>
				<div class="headingName">Here is the Schedule on '.date('d-M-Y (l)',strtotime($todayDate)).'<br></div>
				<div><button class="redButton smallButton" onclick="toggleDiv(\''.'todaySchedule'.'\')">CLOSE</button></div>
			</div><hr>';
		}
		while($rowTask=mysqli_fetch_assoc($checkTasks)){
			echo '<div id="todayTask'.$rowTask['id'].'" onclick="showTaskDetails(\''.$rowTask['id'].'\')" class="singleTask ripple" style="border-left: 4px solid ';
			/*Check Priority*/
			if($rowTask['priority']=='low'){echo ' green;';}
			if($rowTask['priority']=='medium'){echo ' orange;';}
			if($rowTask['priority']=='high'){echo ' red;';}
			/*Check Current Time*/
			if(date('H',strtotime($rowTask['startTime']))>date('H',time())){echo 'border-right:4px solid rgba(210, 210, 250, 1.0);';}
			echo '">
				<div class="sideHeading singleTaskTime">';
					if($type=='allTasks'){echo '<div class="sideHeading">'.date('d-M-Y',strtotime($rowTask['startDate']));
						if($rowTask['endDate']!=''&&$rowTask['endDate']!='0000-00-00'){
							echo' to '.date('d-M-Y',strtotime($rowTask['endDate']));
						}
						echo'</div>';
					}else if($type=='completedTodayTasks'){echo '<div class="sideHeading">'.date('d-M-Y',strtotime($rowTask['startDate']));
						if($rowTask['endDate']!=''&&$rowTask['endDate']!='0000-00-00'){
							echo' to '.date('d-M-Y',strtotime($rowTask['endDate']));
						}
						echo'</div>';
					}
					echo date('H:i',strtotime($rowTask['startTime'])).' - '.date('H:i',strtotime($rowTask['endTime'])).
				'</div>
				<div class="singleTaskName">'.ucwords($rowTask['task']).'</div>
				<div class="singleTaskDone">';
					if(strpos($rowTask['repeater'], $todayDayNumber)){echo'<span class="sideHeading" title="This task repeats Daily">&#8635;</span>';}
					if($type!='checkSchedule'){
						if($type!='allTasks'){
							if($type=='completedTodayTasks'){
								if($rowTask['completionStatus']==$todayDateCheck){
									echo'<button id="doneButtonTask'.$rowTask['id'].'" class="greenButtonOuter smallButton" >Completed Today</button>';
								}else{ 
									$recentCompletedYr=substr($rowTask['completionStatus'], 0, 4);
									$recentCompletedMt=substr($rowTask['completionStatus'], 4, 2);
									$recentCompletedDt=substr($rowTask['completionStatus'], 6, 2);
									$combinedRecentDt=$recentCompletedYr.'-'.$recentCompletedMt.'-'.$recentCompletedDt;
									$recentDt=date('d-M-Y', strtotime($combinedRecentDt));
									echo'<button id="doneButtonTask'.$rowTask['id'].'" class="greenButtonOuter smallButton" >Last Completed on '.$recentDt.'</button>';
								}
							}else if($type=='todaySchedule'){
								if($rowTask['completionStatus']!=$todayDateCheck){echo'<button id="doneButtonTask'.$rowTask['id'].'" class="greenButton smallButton" onclick="taskCompleted(\''.$rowTask['id'].'\')">Done</button>';}
							}
						}else{
							if( ($rowTask['endDate']>$todayDate) || ($rowTask['endDate']=='0000-00-00') ){
								echo'<button onclick="closeTask('.$rowTask['id'].')" id="closeButtonTask'.$rowTask['id'].'" class="greenButton smallButton" >Close Task</button>';
							}else if($rowTask['completionStatus']==0){
								echo'<button onclick="markTaskCompleted('.$rowTask['id'].')" id="closeButtonTask'.$rowTask['id'].'" class="basicButton smallButton" >Mark Task as Completed</button>';
							}else{
								echo'<button id="closeButtonTask'.$rowTask['id'].'" class="greenButtonOuter smallButton" >Task Completed</button>';
							}
						}
					}
				echo'</div>';
			echo'</div>
			<div id="todayTaskDetails'.$rowTask['id'].'" class="singleTaskDetails">';
				echo '<table>
					<tr>
						<td><span class="sideHeading">Task: </span></td>
						<td>'.ucwords($rowTask['task']).'</td>
					</tr>
					<tr>
						<td><span class="sideHeading">Details: </span></td>
						<td>';
						if($rowTask['details']!=''){echo $rowTask['details'];}else{echo '-';}
						echo'</td>
					</tr>
					<tr>
						<td><span class="sideHeading">Priority: </span></td>
						<td>'.strtoupper($rowTask['priority']).'</td>
					</tr>
					<tr>
						<td><span class="sideHeading">Type: </span></td>
						<td>';
						if(strpos($rowTask['repeater'], '8')!==false) {
							echo 'Non-Recurring / One-Time Task';
						}else{
							echo 'Recurring Task <span class="sideHeading">( ';
							if(strpos($rowTask['repeater'], '1')!==false){echo 'Mon ';}
							if(strpos($rowTask['repeater'], '2')!==false){echo 'Tue ';}
							if(strpos($rowTask['repeater'], '3')!==false){echo 'Wed ';}
							if(strpos($rowTask['repeater'], '4')!==false){echo 'Thu ';}
							if(strpos($rowTask['repeater'], '5')!==false){echo 'Fri ';}
							if(strpos($rowTask['repeater'], '6')!==false){echo 'Sat ';}
							if(strpos($rowTask['repeater'], '7')!==false){echo 'Sun ';}
							echo' )</span>';
						}
						echo '</td>
					<tr>
						<td><span class="sideHeading">Start Time: </span></td>
						<td>'.date('H:i',strtotime($rowTask['startTime'])).'</td>
					</tr>';
					if(strtotime($rowTask['endTime'])!=0){echo '
					<tr>
						<td><span class="sideHeading">End Time: </span></td>
						<td>'.date('H:i',strtotime($rowTask['endTime'])).'</td>
					</tr>';}
					echo'<tr>
						<td><span class="sideHeading">Start Date: </span></td>
						<td>'.date('d-M-Y (l)',strtotime($rowTask['startDate'])).'</td>
					</tr>';
					if($rowTask['endDate']!='0000-00-00'){echo '
					<tr>
						<td><span class="sideHeading">End Date: </span></td>
						<td>'.date('d-M-Y (l)',strtotime($rowTask['endDate'])).'</td>
					</tr>';}
					if($rowTask['completionDates']!=''){echo '
					<tr>
						<td id="taskHistoryCell'.$rowTask['id'].'"><span class="sideHeading">Task History: </span></td>
						<td>
							<div>
								<button id="showTaskHistoryButton'.$rowTask['id'].'" class="smallButton basicButtonOuter" onclick="showTaskDetailsHistory(\''.$rowTask['id'].'\')">Show History</button>
							</div>
							<div id="showTaskDetailsHistory'.$rowTask['id'].'" style="display:none">
								Task was previously completed on: <br>';
								$getDates=explode('-', $rowTask['completionDates']);
								$getDatesCount=count($getDates)-1;
								for($i=$getDatesCount-1;$i>=0;$i--){
									$historyCompletedYr=substr($getDates[$i], 0, 4);
									$historyCompletedMt=substr($getDates[$i], 4, 2);
									$historyCompletedDt=substr($getDates[$i], 6, 2);
									$combinedhistoryDt=$historyCompletedYr.'-'.$historyCompletedMt.'-'.$historyCompletedDt;
									$historyDt=date('d-M-Y(l) ', strtotime($combinedhistoryDt));
									echo $historyDt.'<br>';
								}
							echo'</div>';	
						echo'</td>
					</tr>';}
				echo'</table>
				<br><button id="editTask'.$rowTask['id'].'" class="basicButtonOuter smallButton" onclick="editTask(\''.$rowTask['id'].'\')">Edit</button>
				<button id="deleteTask'.$rowTask['id'].'" class="redButtonOuter smallButton" onclick="deleteTask(\''.$rowTask['id'].'\')">Delete</button>';
				if($type=='todaySchedule'){
					echo '<button onclick="closeTask('.$rowTask['id'].')" id="closeButtonTask'.$rowTask['id'].'" class="greenButtonOuter smallButton" >Close Task</button>';
				}
				if($rowTask['completionStatus']==$todayDateCheck){echo'<button id="redoTask'.$rowTask['id'].'" class="greenButtonOuter smallButton" onclick="redoTask(\''.$rowTask['id'].'\')">Redo Task</button>';}
				if($rowTask['repeater']!='8'&&$rowTask['repeater']!=''){
					echo'<button id="completePreviousTask'.$rowTask['id'].'" class="greenButtonOuter smallButton" onclick="completePreviousTask(\''.$rowTask['id'].'\')">Previously Done</button>';
				}
				echo'<br>
				<div id="confirmDeleteTask'.$rowTask['id'].'"></div>';
			echo'</div>';
		}
	}else{
		if($type=='todaySchedule'){
			echo '<hr><h3><span style="animation:fade 1s ease-in-out">No Other Planned work Today Boss! </span><br><br><span style="animation:fade 3s ease-in-out">Chill out Relax..</span></h3>';
		}else if($type=='allTasks'){
			echo '<hr><h3>No records present.. <br><br>Shall I add any task?<br><br></h3>
			<button class="basicButton" onclick="showAddTask()">ADD</button>
			<button class="redButtonOuter" onclick="toggleDiv(\''.'todaySchedule'.'\')">NOPE</button>';
		}else if($type=='completedTodayTasks'){
			echo '<hr><h3>No record of any completed works have been found.</h3>
			<br><button class="redButtonOuter" onclick="toggleDiv(\''.'todaySchedule'.'\')">CLOSE</button>';
		}else if($type=='checkSchedule'){
			echo '<hr><h3>No data has been found on this date '.$_SESSION['genderCall'].'.</h3>
			<br><button class="redButtonOuter" onclick="toggleDiv(\''.'todaySchedule'.'\')">CLOSE</button>';
		}
	}
}

?>