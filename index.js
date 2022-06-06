
/*Input focus*/
function focusInput(type){
	if(document.getElementById(type+"Input")){
		document.getElementById(type+"Input").focus();
		if(document.getElementById(type+"Input").value==''){
			document.getElementById(type+"Helper").style.marginTop="-20px";
			document.getElementById(type+"Helper").style.opacity="1";
			document.getElementById(type+"Input").style.marginTop='10px';
		}
	}
}

/*No focus*/
function noFocusInput(type){
	if(document.getElementById(type+"Input").value==''){
		document.getElementById(type+"Helper").style.marginTop="0px";
		document.getElementById(type+"Helper").style.opacity="0.5";
		document.getElementById(type+"Input").style.marginTop='0';
	}else{
		document.getElementById(type+"Helper").style.marginTop="-20px";
		document.getElementById(type+"Helper").style.opacity="1";
		document.getElementById(type+"Input").style.marginTop='10px';
	}
}

/*Show Password*/
function showPassword(type){
	if(document.getElementById("password"+type+"Input").type=="text"){
		document.getElementById("password"+type+"Input").type="password";
	}else{
		document.getElementById("password"+type+"Input").type="text";
	}
}

/*Toggle Signup, Login forms*/
function toggleSignIn(type){
	document.getElementById("loginDiv").style.display = "none";
	document.getElementById("signupDiv").style.display = "none";
	/*Div which is to be displayed*/
	document.getElementById(type+"Div").style.display = "block";
	if(type=='login'){document.getElementById("usernameLoginInput").focus();}
	else if(type=='signup'){document.getElementById("fullNameSignupInput").focus();}
}

/*Signup*/
function signup(){
	var username = document.getElementById("usernameSignupInput").value;
	var password = document.getElementById("passwordSignupInput").value;
	var confirmPassword = document.getElementById("confirmpasswordSignupInput").value;
	var fullName = document.getElementById("fullNameSignupInput").value;
	var gender = document.getElementById("genderSignupInput").value;
	var email = document.getElementById("emailSignupInput").value;
	document.getElementById("signupButton").innerHTML = "<div class='loaderButton'></div>";

	/*Remove Previous Validation error messges*/
	document.getElementById("usernameSignupInput").style.border ="none";
	document.getElementById("passwordSignupInput").style.border ="none";
	document.getElementById("confirmpasswordSignupInput").style.border ="none";
	document.getElementById("fullNameSignupInput").style.border ="none";
	document.getElementById("emailSignupInput").style.border ="none";
	document.getElementById("signupErrorMessage").innerHTML = "";

	/*Validations*/
	if(username == '' || password == '' || confirmPassword == '' || fullName == '' || email == ''){ /*Empty Fields*/
		document.getElementById("signupErrorMessage").innerHTML = "<span class='errorMessage'>Please Fill in all the fields</span>";
		document.getElementById("fullNameSignupInput").focus();
	}else if(!/^[a-zA-Z0-9 ]+$/.test(username)){ /*Validate expressions*/
		document.getElementById("signupErrorMessage").innerHTML = "<span class='errorMessage'>Invalid charecters in Username</span>";
		document.getElementById("usernameSignupInput").style.border = "1px inset red";
		document.getElementById("usernameSignupInput").focus();
	}else if(!/^[a-zA-Z0-9!@#$%^&*]{4,15}$/.test(password)){ /*Validate expressions*/
		document.getElementById("signupErrorMessage").innerHTML = "<span class='errorMessage'>Invalid charecters in Password / Minimum 4 charecters are required</span>";
		document.getElementById("passwordSignupInput").style.border = "1px inset red";
		document.getElementById("passwordSignupInput").focus();
	}else if(!/^[a-zA-Z0-9.-_]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/.test(email)){ /*Validate expressions*/
		document.getElementById("signupErrorMessage").innerHTML = "<span class='errorMessage'>Email not valid</span>";
		document.getElementById("emailSignupInput").style.border = "1px inset red";
		document.getElementById("emailSignupInput").focus();
	}else if(!/^[a-zA-Z ]+$/.test(fullName)){ /*Validate expressions*/
		document.getElementById("signupErrorMessage").innerHTML = "<span class='errorMessage'>Invalid charecters in Name</span>";
		document.getElementById("fullNameSignupInput").style.border = "1px inset red";
		document.getElementById("fullNameSignupInput").focus();
	}else if(password != confirmPassword){ /*If password, confirm password do not match */
		document.getElementById("signupErrorMessage").innerHTML = "<span class='errorMessage'>Password and confirm Password do not match!</span>";
		document.getElementById("passwordSignupInput").style.border = "1px inset red";
		document.getElementById("confirmpasswordSignupInput").style.border = "1px inset red";
		document.getElementById("passwordSignupInput").focus();
	}else{ /*If all above conditions are valid, User is signed up!*/
		/*AJAX Functionality*/
		/*Declare variables*/
		var signup = "RandomInput";
		var data = "signup="+signup+"&username="+username+"&password="+password+"&fullName="+fullName+"&email="+email+"&gender="+gender;
		/*Declare XML*/
		if(window.XMLHttpRequest){var xhr = new XMLHttpRequest();}
		else if(window.ActiveXObject){var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
		/*AJAX Methods*/
		xhr.open("POST","conditions.php",true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send(data);
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4){
				if(xhr.status == 200){
					document.getElementById("signupErrorMessage").innerHTML = this.responseText;
				}
			}
		}
	}
	document.getElementById("signupButton").innerHTML = "SIGNUP";
}

/*Login*/
function login(){
	var username = document.getElementById("usernameLoginInput").value;
	var password = document.getElementById("passwordLoginInput").value;
	document.getElementById("loginButton").innerHTML = "<div class='loaderButton'></div>";
	document.getElementById("loginErrorMessage").innerHTML = "";/*To remove any previous error Messages*/
	if(document.getElementById("rememberMeLoginBox").checked!=false){var autoLogin = 'enabled';}else{var autoLogin = 'disabled';}
	/*Validation*/
	if(username == '' || password == ''){ /*Empty Fields*/
		document.getElementById("loginErrorMessage").innerHTML = "<span class='errorMessage'>Please Fill in all the fields</span>";
	}else{
		/*AJAX Functionality*/
		/*Declare variables*/
		var login = "RandomInput";
		var data = "login="+login+"&username="+username+"&password="+password+"&autoLogin="+autoLogin;
		/*Declare XML*/
		if(window.XMLHttpRequest){var xhr = new XMLHttpRequest();}
		else if(window.ActiveXObject){var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
		/*AJAX Methods*/
		xhr.open("POST","conditions.php",true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send(data);
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4){
				if(xhr.status == 200){
					if(this.responseText == "loginSuccess"){
						window.location.href = "home.php";
						document.getElementById("loginButton").innerHTML = "<div class = 'loaderButton'></div>";
					}else{
						document.getElementById("loginErrorMessage").innerHTML = this.responseText;
					}
				}
			}
		}
	}
	document.getElementById("loginButton").innerHTML = "LOGIN";
}

/*Show Add Task Div*/
function showAddTask(){
	document.getElementById("backgroundDiv").style.display="block";
	document.getElementById("addTaskDiv").style.display="block";
	document.getElementById("addTaskNameInput").focus();
	var t = new Date(); var hrs = t.getHours(); var mnts = t.getMinutes(); var sec = t.getSeconds();var dys=t.getDay();var yr=t.getFullYear();var mnths=t.getMonth();var newMnths=mnths+1;var dte=checkTime(t.getDate());
	var h = checkTime(hrs);var m = checkTime(mnts);var se = checkTime(sec);var mnthschecktime=checkTime(mnths+1);
	if(h!=23){var nexth=parseInt(h)+1;}else{var nexth=0;}
	var nh=checkTime(nexth);

	//document.getElementById("startDate").value=dte+'-'+newMnths+'-'+yr;
	/*document.getElementById("endDate").value=yr+'-'+newMnths+'-'+dte;*/
	document.getElementById("startTime").value=h+':'+m;
	document.getElementById("endTime").value=nh+':'+m;
}

/*To add zero Before*/
function checkTime(i) { if (i<10) {i="0"+i}; return i; }

/*Hide Divs*/
function closeDiv(){
	document.getElementById("backgroundDiv").style.display="none";
	document.getElementById("addTaskDiv").style.display="none";
	document.getElementById("editTaskDiv").style.display="none";
	document.getElementById("deleteTaskDiv").style.display="none";
	document.getElementById("profileDiv").style.display="none";
	document.getElementById("checkScheduleDiv").style.display="none";
	document.getElementById("completeTaskDiv").style.display="none";
	document.getElementById("notesDiv").style.display="none";
}

/*Toggle Divs*/
function toggleDiv(type){
	document.getElementById("todaySchedule").innerHTML="<div class='loaderButton'></div>";
	var username=document.getElementById("username").value;
	if(type=='checkScheduleTomorrow'){
		var scheduleDate=document.getElementById("checkScheduleTomorrow").value;
		type='checkSchedule';
	}else{
		var scheduleDate=document.getElementById("scheduleDate").value;
	}
	/*AJAX Functionality*/
	/*Declare variables*/
	var toggleTask = "RandomInput";
	var data = "toggleTask="+toggleTask+"&type="+type+"&username="+username+"&scheduleDate="+scheduleDate;
	/*Declare XML*/
	if(window.XMLHttpRequest){var xhr = new XMLHttpRequest();}
	else if(window.ActiveXObject){var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
	/*AJAX Methods*/
	xhr.open("POST","conditions.php",true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(data);
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				closeDiv();
				var ouput=this.responseText;
				setTimeout(function(){document.getElementById("todaySchedule").innerHTML=ouput;},1000);
			}
		}
	}
}

/*Add Tasks*/
function addTask(){
	var username=document.getElementById("username").value;
	var taskName = document.getElementById("addTaskNameInput").value;
	var startTime = document.getElementById("startTime").value;
	var endTime = document.getElementById("endTime").value;
	var startDate = document.getElementById("startDate").value;
	var endDate = document.getElementById("endDate").value;
	var repeater = '';
	if(document.getElementById("mondayTask").checked){repeater=repeater+'1';}
	if(document.getElementById("tuesdayTask").checked){repeater=repeater+'2';}
	if(document.getElementById("wednesdayTask").checked){repeater=repeater+'3';}
	if(document.getElementById("thursdayTask").checked){repeater=repeater+'4';}
	if(document.getElementById("fridayTask").checked){repeater=repeater+'5';}
	if(document.getElementById("saturdayTask").checked){repeater=repeater+'6';}
	if(document.getElementById("sundayTask").checked){repeater=repeater+'7';}
	var priority = document.getElementById("addTaskPriorityInput").value;
	var details = document.getElementById("addTaskDetailsInput").value;

	/*If user did not give values*/
	if(priority==''){priority='medium';}
	if(repeater==''){repeater='8';}/*Not recurring*/

	document.getElementById("addTaskButton").innerHTML = "<div class = 'loaderButton'></div>";
	/*Validation*/
	document.getElementById("addTaskNameInput").style.border="none";
	document.getElementById("startTime").style.border="none";
	document.getElementById("startDate").style.border="none";
	document.getElementById("endTime").style.border="none";
	document.getElementById("endDate").style.border="none";
	if((taskName == '') || (startTime == '')){
		document.getElementById("addTaskErrorMessage").innerHTML = "<span class='errorMessage'>Fill in the required fields</span>";
		if(taskName==''){document.getElementById("addTaskNameInput").style.border="1px solid red";document.getElementById("addTaskNameInput").focus();}
		else if(startTime==''){document.getElementById("startTime").style.border="1px solid red";document.getElementById("startTime").focus();}
	}else if((endTime<startTime)&&(endTime!='')){
		document.getElementById("addTaskErrorMessage").innerHTML = "<span class='errorMessage'>Closing Time cannot be before Starting Time!</span>";
		document.getElementById("startTime").style.border="1px solid red";document.getElementById("startTime").focus();
		document.getElementById("endTime").style.border="1px solid red";
	}else if((endDate<startDate)&&(endDate!='')){
		document.getElementById("addTaskErrorMessage").innerHTML = "<span class='errorMessage'>Closing Date cannot be before Starting Date!</span>";
		document.getElementById("startDate").style.border="1px solid red";document.getElementById("startDate").focus();
		document.getElementById("endDate").style.border="1px solid red";
	}else{
		if(endDate==''){
			endDate='0000-00-00';
			if(repeater!=''&&repeater!='8'){}/*To repeat task till completed;*/
			else{repeater='1234567';}
		}
		/*AJAX Functionality*/
		/*Declare variables*/
		var addTask = "RandomInput";
		var data = "addTask="+addTask+"&taskName="+taskName+"&username="+username+"&startTime="+startTime+"&endTime="+endTime+"&startDate="+startDate+"&endDate="+endDate+"&repeater="+repeater+"&priority="+priority+"&details="+details;
		/*Declare XML*/
		if(window.XMLHttpRequest){var xhr = new XMLHttpRequest();}
		else if(window.ActiveXObject){var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
		/*AJAX Methods*/
		xhr.open("POST","conditions.php",true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send(data);
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4){
				if(xhr.status == 200){
					eachResponse = this.responseText.split("-period-");/*Get two Messages from server*/
					document.getElementById("addTaskErrorMessage").innerHTML = eachResponse[0];
					document.getElementById("addTaskButton").innerHTML = "<div class = 'loaderButton'></div>";
					setTimeout(function(){document.getElementById("addTaskButton").innerHTML = "ADD";},500);
					setTimeout(function(){document.getElementById("addTaskErrorMessage").innerHTML = "";},1500);
					setTimeout(function(){closeDiv();},1500);
					setTimeout(function(){document.getElementById("todaySchedule").innerHTML="<div class='loaderButton'></div>";},1500);
					setTimeout(function(){document.getElementById("todaySchedule").innerHTML=eachResponse[1];},2500);
					/*Normalize values*/
					document.getElementById("addTaskNameInput").value='';
					document.getElementById("startTime").value='';
					document.getElementById("endTime").value='';
					document.getElementById("startDate").value=startDate;
					document.getElementById("endDate").value='';
					document.getElementById("addTaskPriorityInput").value='';
					document.getElementById("addTaskDetailsInput").value='';
					repeater='';
					document.getElementById("mondayTask").checked=false;document.getElementById("tuesdayTask").checked=false;
					document.getElementById("wednesdayTask").checked=false;document.getElementById("thursdayTask").checked=false;
					document.getElementById("fridayTask").checked=false;document.getElementById("saturdayTask").checked=false;
					document.getElementById("sundayTask").checked=false;
				}
			}
		}	
	}
	document.getElementById("addTaskButton").innerHTML = "ADD";
}

/*Show Individual Task Details*/
function showTaskDetails(id){
	if(document.getElementById("todayTaskDetails"+id).style.display=="block"){
		document.getElementById("todayTaskDetails"+id).style.display="none";
		document.getElementById("todayTaskDetails"+id).style.maxHeight='0px';
		document.getElementById("todayTask"+id).style.backgroundColor="rgba(230,230,230,1)";
	}else{
		document.getElementById("todayTask"+id).style.backgroundColor="rgba(215,215,215,1)";
		document.getElementById("todayTaskDetails"+id).style.display="block";
		document.getElementById("todayTaskDetails"+id).style.maxHeight=document.getElementById("todayTaskDetails"+id).scrollHeight+'px';
	}
}

/*Clicking Done as task completed*/
function taskCompleted(id){
	var username=document.getElementById("username").value;
	document.getElementById("doneButtonTask"+id).innerHTML = "<div class='loaderButton'></div>";
	/*AJAX Functionality*/
	/*Declare variables*/
	var taskCompleted = "RandomInput";
	var data = "taskCompleted="+taskCompleted+"&id="+id+"&username="+username;
	/*Declare XML*/
	if(window.XMLHttpRequest){var xhr = new XMLHttpRequest();}
	else if(window.ActiveXObject){var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
	/*AJAX Methods*/
	xhr.open("POST","conditions.php",true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(data);
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				eachResponse=this.responseText.split("-period-");
				if(eachResponse[0]='UpdateDone'){
					document.getElementById("todayTaskDetails"+id).style.display="none";
					document.getElementById("todayTask"+id).style.pointerEvents="none";
					document.getElementById("todayTask"+id).classList.add("singleTaskBackground");
					setTimeout(function(){document.getElementById("todayTask"+id).style.backgroundColor="lightgreen";document.getElementById("todayTask"+id).innerHTML="<div style='margin-left:auto;margin-right:auto;animation: fadeReverse 2s ease-in-out'>Great Work Boss!</div>";},1000);
					setTimeout(function(){document.getElementById("todaySchedule").innerHTML="<div class='loaderButton'></div>";},3000);
					setTimeout(function(){document.getElementById("todaySchedule").innerHTML=eachResponse[1];},3000);
				}
			}
		}
	}	
}

/*Show Edit Task*/
function editTask(id){
	document.getElementById("editTaskDiv").style.display="block";
	var username=document.getElementById("username").value;
	document.getElementById("editTask"+id).innerHTML = "<div class = 'loaderButton'></div>";
	/*AJAX Functionality*/
	/*Declare variables*/
	var editTask = "RandomInput";
	var data = "editTask="+editTask+"&id="+id+"&username="+username;
	/*Declare XML*/
	if(window.XMLHttpRequest){var xhr = new XMLHttpRequest();}
	else if(window.ActiveXObject){var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
	/*AJAX Methods*/
	xhr.open("POST","conditions.php",true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(data);
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				eachResponse=this.responseText.split("-period-");
				document.getElementById("backgroundDiv").style.display="block";
				document.getElementById("editTaskDiv").innerHTML=eachResponse[0];
				/*Set current values*/
				document.getElementById(id+"addTaskNameInput").value=eachResponse[1];
				if(eachResponse[1]!=''){noFocusInput(id+"addTaskName");}
				document.getElementById("startTime"+id).value=eachResponse[2];
				document.getElementById("endTime"+id).value=eachResponse[3];
				document.getElementById("startDate"+id).value=eachResponse[4];
				document.getElementById("endDate"+id).value=eachResponse[5];
				document.getElementById(id+"addTaskPriorityInput").value=eachResponse[7];
				if(eachResponse[7]!=''){noFocusInput(id+"addTaskPriority");}
				document.getElementById("addTaskDetailsInput"+id).value=eachResponse[8];
				if(eachResponse[6].includes('1')){document.getElementById("mondayTask"+id).checked="true";}
				if(eachResponse[6].includes('2')){document.getElementById("tuesdayTask"+id).checked="true";}
				if(eachResponse[6].includes('3')){document.getElementById("wednesdayTask"+id).checked="true";}
				if(eachResponse[6].includes('4')){document.getElementById("thursdayTask"+id).checked="true";}
				if(eachResponse[6].includes('5')){document.getElementById("fridayTask"+id).checked="true";}
				if(eachResponse[6].includes('6')){document.getElementById("saturdayTask"+id).checked="true";}
				if(eachResponse[6].includes('7')){document.getElementById("sundayTask"+id).checked="true";}
				document.getElementById(id+"addTaskNameInput").focus();
			}
		}
	}	
	document.getElementById("editTask"+id).innerHTML = "Edit";
}

/*Edit Task*/
function confirmEditTask(id){
	var username=document.getElementById("username").value;
	var taskName = document.getElementById(id+"addTaskNameInput").value;
	var startTime = document.getElementById("startTime"+id).value;
	var endTime = document.getElementById("endTime"+id).value;
	var startDate = document.getElementById("startDate"+id).value;
	var endDate = document.getElementById("endDate"+id).value;
	var repeater = '';
	if(document.getElementById("mondayTask"+id).checked){repeater=repeater+'1';}
	if(document.getElementById("tuesdayTask"+id).checked){repeater=repeater+'2';}
	if(document.getElementById("wednesdayTask"+id).checked){repeater=repeater+'3';}
	if(document.getElementById("thursdayTask"+id).checked){repeater=repeater+'4';}
	if(document.getElementById("fridayTask"+id).checked){repeater=repeater+'5';}
	if(document.getElementById("saturdayTask"+id).checked){repeater=repeater+'6';}
	if(document.getElementById("sundayTask"+id).checked){repeater=repeater+'7';}
	var priority = document.getElementById(id+"addTaskPriorityInput").value;
	var details = document.getElementById("addTaskDetailsInput"+id).value;

	/*If user did not give values*/
	if(priority==''){priority='medium';}
	if(repeater==''){repeater='8';}/*Not recurring*/

	document.getElementById("addTaskButton"+id).innerHTML = "<div class = 'loaderButton'></div>";
	/*Validation*/
	document.getElementById(id+"addTaskNameInput").style.border="none";
	document.getElementById("startTime"+id).style.border="none";
	document.getElementById("startDate"+id).style.border="none";
	document.getElementById("endTime"+id).style.border="none";
	document.getElementById("endDate"+id).style.border="none";
	if((taskName == '') || (startTime == '')){
		document.getElementById("addTaskErrorMessage"+id).innerHTML = "<span class='errorMessage'>Fill in the required fields</span>";
		if(taskName==''){document.getElementById(id+"addTaskNameInput").style.border="1px solid red";document.getElementById(id+"addTaskNameInput").focus();}
		else if(startTime==''){document.getElementById("startTime"+id).style.border="1px solid red";document.getElementById("startTime"+id).focus();}
	}else if((endTime<startTime)&&(endTime!='')){
		document.getElementById("addTaskErrorMessage"+id).innerHTML = "<span class='errorMessage'>Closing Time cannot be before Starting Time!</span>";
		document.getElementById("startTime"+id).style.border="1px solid red";document.getElementById("startTime"+id).focus();
		document.getElementById("endTime"+id).style.border="1px solid red";
	}else if((endDate<startDate)&&(endDate!='')){
		document.getElementById("addTaskErrorMessage"+id).innerHTML = "<span class='errorMessage'>Closing Date cannot be before Starting Date!</span>";
		document.getElementById("startDate"+id).style.border="1px solid red";document.getElementById("startDate"+id).focus();
		document.getElementById("endDate"+id).style.border="1px solid red";
	}else{
		if(endDate==''){endDate='0000-00-00';}
		/*AJAX Functionality*/
		/*Declare variables*/
		var confirmEditTask = "RandomInput";
		var data = "confirmEditTask="+confirmEditTask+"&taskName="+taskName+"&username="+username+"&startTime="+startTime+"&endTime="+endTime+"&startDate="+startDate+"&endDate="+endDate+"&repeater="+repeater+"&priority="+priority+"&details="+details+"&id="+id;
		/*Declare XML*/
		if(window.XMLHttpRequest){var xhr = new XMLHttpRequest();}
		else if(window.ActiveXObject){var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
		/*AJAX Methods*/
		xhr.open("POST","conditions.php",true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send(data);
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4){
				if(xhr.status == 200){
					eachResponse = this.responseText.split("-period-");/*Get two Messages from server*/
					document.getElementById("addTaskErrorMessage"+id).innerHTML = eachResponse[0];
					document.getElementById("addTaskButton"+id).innerHTML = "<div class = 'loaderButton'></div>";
					setTimeout(function(){document.getElementById("addTaskButton"+id).innerHTML = "DONE";},500);
					setTimeout(function(){document.getElementById("addTaskErrorMessage"+id).innerHTML = "";},1500);
					setTimeout(function(){closeDiv();},1500);
					setTimeout(function(){document.getElementById("todayTask"+id).innerHTML="<div class='loaderButton'></div>";},1500);
					setTimeout(function(){document.getElementById("todaySchedule").innerHTML=eachResponse[1];},2500);
					setTimeout(function(){document.getElementById("todaySchedule").innerHTML=eachResponse[1];},3500);
				}
			}
		}	
	}
	document.getElementById("addTaskButton"+id).innerHTML = "DONE";
}

/*Close Task*/
function closeTask(id){
	var id=id;
	editTask(id);
	setTimeout(function(){document.getElementById("endDate"+id).focus();document.getElementById("endDate"+id).style.border="1px solid orange";},1500);
}

/*Mark Task Completed*/
function markTaskCompleted(id){
	var id=id;
	var username=document.getElementById("username").value;
	document.getElementById("closeButtonTask"+id).innerHTML = "<div class = 'loaderButton'></div>";
	/*AJAX Functionality*/
	/*Declare variables*/
	var markTaskCompleted = "RandomInput";
	var data = "markTaskCompleted="+markTaskCompleted+"&id="+id+"&username="+username;
	/*Declare XML*/
	if(window.XMLHttpRequest){var xhr = new XMLHttpRequest();}
	else if(window.ActiveXObject){var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
	/*AJAX Methods*/
	xhr.open("POST","conditions.php",true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(data);
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				document.getElementById("completeTaskDiv").style.display="block";
				eachResponse=this.responseText.split("-period-");
				document.getElementById("backgroundDiv").style.display="block";
				document.getElementById("completeTaskDiv").innerHTML=eachResponse[0];
			}
		}
	}	
	document.getElementById("closeButtonTask"+id).innerHTML = "Mark Task as Completed";
}

function confirmTaskCompleted(id){
	var username=document.getElementById("username").value;
	var completedDate = document.getElementById("completedDate"+id).value;
	
	document.getElementById("completedButton"+id).innerHTML = "<div class = 'loaderButton'></div>";
	/*AJAX Functionality*/
	/*Declare variables*/
	var confirmCompletedTask = "RandomInput";
	var data = "confirmCompletedTask="+confirmCompletedTask+"&id="+id+"&username="+username+"&completedDate="+completedDate;
	/*Declare XML*/
	if(window.XMLHttpRequest){var xhr = new XMLHttpRequest();}
	else if(window.ActiveXObject){var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
	/*AJAX Methods*/
	xhr.open("POST","conditions.php",true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(data);
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				eachResponse = this.responseText.split("-period-");/*Get two Messages from server*/
				document.getElementById("completeTaskErrorMessage"+id).innerHTML = eachResponse[0];
				document.getElementById("completedButton"+id).innerHTML = "<div class = 'loaderButton'></div>";
				setTimeout(function(){document.getElementById("completedButton"+id).innerHTML = "DONE";},500);
				setTimeout(function(){document.getElementById("completeTaskErrorMessage"+id).innerHTML = "";},1500);
				setTimeout(function(){closeDiv();},1500);
				setTimeout(function(){document.getElementById("todayTask"+id).innerHTML="<div class='loaderButton'></div>";},1500);
				setTimeout(function(){document.getElementById("todaySchedule").innerHTML=eachResponse[1];},2500);
				setTimeout(function(){document.getElementById("todaySchedule").innerHTML=eachResponse[1];},3500);
			}
		}
	}
	document.getElementById("completedButton"+id).innerHTML = "DONE";
}


/*Show delete Task*/
function deleteTask(id){
	document.getElementById("todayTaskDetails"+id).style.maxHeight="5000px";
	document.getElementById("confirmDeleteTask"+id).innerHTML="<br>Are you sure you want to delete this task?<br><br>You can edit the timings instead or delete it permanently!<br><br><button id='confirmdeleteTask"+id+"' class='redButton smallButton' onclick='confirmDeleteTask("+id+")'>Delete Task</button> <button class='basicButtonOuter smallButton' onclick='closeDeleteDiv("+id+")'>Cancel</button><br>";
}

/*Close Delete Div*/
function closeDeleteDiv(id){
	document.getElementById("confirmDeleteTask"+id).innerHTML="";
}

/*Delete Task*/
function confirmDeleteTask(id){
	var username=document.getElementById("username").value;
	document.getElementById("confirmdeleteTask"+id).innerHTML="<div class='loaderButton'></div>";
	/*AJAX Functionality*/
	/*Declare variables*/
	var deleteTask = "RandomInput";
	var data = "deleteTask="+deleteTask+"&id="+id+"&username="+username;
	/*Declare XML*/
	if(window.XMLHttpRequest){var xhr = new XMLHttpRequest();}
	else if(window.ActiveXObject){var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
	/*AJAX Methods*/
	xhr.open("POST","conditions.php",true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(data);
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				eachResponse=this.responseText.split("-period-");
				if(eachResponse[0]=='taskDeleted'){
					setTimeout(function(){document.getElementById("todayTaskDetails"+id).innerHTML="<div class='loaderButton'></div>";},500);
					setTimeout(function(){document.getElementById("todayTaskDetails"+id).style.display="none";},1000);
					document.getElementById("todayTask"+id).style.pointerEvents="none";
					setTimeout(function(){document.getElementById("todayTask"+id).innerHTML="<div class='loaderButton'></div>";},1000);
					setTimeout(function(){document.getElementById("todayTask"+id).style.backgroundColor="mediumvioletred";document.getElementById("todayTask"+id).innerHTML="<div style='margin-left:auto;margin-right:auto;animation: fadeReverse 2s ease-in-out;color:white'>Task has been Removed successfully boss!</div>";},2000);
					setTimeout(function(){document.getElementById("todaySchedule").innerHTML=eachResponse[1];},4000);
				}
			}
		}
	}	
}

/*Redo Task*/
function redoTask(id){
	var username=document.getElementById("username").value;
	document.getElementById("redoTask"+id).innerHTML="<div class='loaderButton'></div>";
	/*AJAX Functionality*/
	/*Declare variables*/
	var redoTask = "RandomInput";
	var data = "redoTask="+redoTask+"&id="+id+"&username="+username;
	/*Declare XML*/
	if(window.XMLHttpRequest){var xhr = new XMLHttpRequest();}
	else if(window.ActiveXObject){var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
	/*AJAX Methods*/
	xhr.open("POST","conditions.php",true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(data);
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				eachResponse=this.responseText.split("-period-");
				if(eachResponse[0]='UpdateDone'){
					document.getElementById("todayTaskDetails"+id).style.display="none";
					document.getElementById("todayTask"+id).innerHTML="<div class='loaderButton'></div>";
					document.getElementById("todayTask"+id).style.pointerEvents="none";
					document.getElementById("todayTask"+id).classList.add("singleTaskBackground");
					setTimeout(function(){document.getElementById("todayTask"+id).style.backgroundColor="lightgreen";document.getElementById("todayTask"+id).innerHTML="<div style='margin-left:auto;margin-right:auto;animation: fadeReverse 2s ease-in-out'>Task has been Re-Scheduled succesfully boss!</div>";},1000);
					setTimeout(function(){document.getElementById("todaySchedule").innerHTML="<div class='loaderButton'></div>";},3000);
					setTimeout(function(){document.getElementById("todaySchedule").innerHTML=eachResponse[1];},3000);
				}
			}
		}
	}	
}

/*Get Profile Details*/
function showProfile(){
	var username = document.getElementById("username").value;
	document.getElementById("profileButton").innerHTML = "<div class = 'loaderButton' style='height:10px;width:10px'></div>";
	document.getElementById("backgroundDiv").style.display="block"
	document.getElementById("profileDiv").style.display="block"
	/*AJAX Functionality*/
	/*Declare variables*/
	var getProfileDetails = "RandomInput";
	var data = "getProfileDetails="+getProfileDetails+"&username="+username;
	/*Declare XML*/
	if(window.XMLHttpRequest){var xhr = new XMLHttpRequest();}
	else if(window.ActiveXObject){var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
	/*AJAX Methods*/
	xhr.open("POST","conditions.php",true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(data);
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				document.getElementById("profileDiv").innerHTML = this.responseText;
				setTimeout(function(){document.getElementById("profileButton").innerHTML = "Profile";},1000)
			}
		}
	}
}

/*Edit Profile*/
function editProfile(type){
	var typeValue = type.split("-period-");
	/*AJAX Functionality*/
	/*Declare variables*/
	var editProfile = "RandomInput";
	var data = "editProfile="+editProfile+"&type="+typeValue[0]+"&value="+typeValue[1]+"&id="+typeValue[2];
	/*Declare XML*/
	if(window.XMLHttpRequest){var xhr = new XMLHttpRequest();}
	else if(window.ActiveXObject){var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
	/*AJAX Methods*/
	xhr.open("POST","conditions.php",true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(data);
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				document.getElementById("profileDiv").innerHTML = this.responseText;
				document.getElementById("newValueEditProfileDetail"+typeValue[0]).innerHTML = typeValue[1];
			}
		}
	}	
}

/*Confirm Edit Details*/
function confirmEditProfileDetails(type, id){
	var newValue = document.getElementById("newValueEditProfileDetail"+type).value;
	var oldValue = document.getElementById("oldValueEditProfileDetail"+type).value;
	document.getElementById("confirmEditProfileDetailsButton").innerHTML = "<div class = 'loaderButton'></div>";

	var error = "";
	/*Validation*/
	if(newValue == ''){
		var error = "<span class = 'errorMessage'>Please Fill the required details.</span>";
	}else if(type == 'email'){
		if(!/^[a-zA-Z0-9.-_]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/.test(newValue)){ /*Validate expressions*/
			var error = "<span class = 'errorMessage'>Enter a valid Email ID.</span>";
		}
	}
	document.getElementById("confirmEditProfileDetailsButton").innerHTML = "Done";
	document.getElementById("editProfileStatus").innerHTML = error;
	/*If valid input*/
	if(error == ''){
		/*AJAX Functionality*/
		/*Declare variables*/
		var confirmEditProfile = "RandomInput";
		var data = "confirmEditProfile="+confirmEditProfile+"&type="+type+"&newValue="+newValue+"&oldValue="+oldValue+"&id="+id;
		/*Declare XML*/
		if(window.XMLHttpRequest){var xhr = new XMLHttpRequest();}
		else if(window.ActiveXObject){var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
		/*AJAX Methods*/
		xhr.open("POST","conditions.php",true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send(data);
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4){
				if(xhr.status == 200){
					if(this.responseText == 'updateDone'){
						document.getElementById("editProfileStatus").innerHTML = "<span class = 'successMessage'>Details have been edited succesfully boss!</span>";	
						setTimeout(function(){document.getElementById("editProfileStatus").innerHTML = "<span class = 'successMessage'>Edited Successfully</span><div class = 'loaderButton'></div>";},400);
						setTimeout(function(){document.getElementById("profileDiv").innerHTML = "";document.getElementById("backgroundDiv").style.display = "none";},1000);
						if(type == 'username'){
							document.getElementById("username").value = newValue;
						}
					}else{
						document.getElementById("editProfileStatus").innerHTML = "<span class = 'errorMessage'>Username / Email Already Exists, Try Again.</span>";
					}
				}
			}
		}
	}	
}

/*Check Schedule*/
function showCheckSchedule(){
	document.getElementById("backgroundDiv").style.display="block";
	document.getElementById("checkScheduleDiv").style.display="block";
	document.getElementById("scheduleDate").focus();	
}

/*Show Menu*/
function toggleMenu(){
	if(document.getElementById("menuSubDiv").style.display=="block"){
		document.getElementById("menuSubDiv").style.display="none";
	}else{
		document.getElementById("menuSubDiv").style.display="block";
	}
}

/*Show Task Completed History*/
function showTaskDetailsHistory(id){
	if(document.getElementById("showTaskDetailsHistory"+id).style.display=="block"){
		document.getElementById("showTaskDetailsHistory"+id).style.display="none";
		document.getElementById("taskHistoryCell"+id).style.verticalAlign="middle";
		document.getElementById("showTaskHistoryButton"+id).innerHTML="<div class='loaderButton'></div>";
		document.getElementById("todayTaskDetails"+id).style.maxHeight=document.getElementById("todayTaskDetails"+id).scrollHeight+'px';
		setTimeout(function(){document.getElementById("showTaskHistoryButton"+id).innerHTML="Show History";document.getElementById("showTaskHistoryButton"+id).classList.add('basicButtonOuter');document.getElementById("showTaskHistoryButton"+id).classList.remove('redButtonOuter');},700);
	}else{
		document.getElementById("showTaskDetailsHistory"+id).style.display="block";
		document.getElementById("taskHistoryCell"+id).style.verticalAlign="top";
		document.getElementById("showTaskHistoryButton"+id).innerHTML="<div class='loaderButton'></div>";
		document.getElementById("todayTaskDetails"+id).style.maxHeight=document.getElementById("todayTaskDetails"+id).scrollHeight+'px';
		setTimeout(function(){document.getElementById("showTaskHistoryButton"+id).innerHTML="Hide History";document.getElementById("showTaskHistoryButton"+id).classList.remove('basicButtonOuter');document.getElementById("showTaskHistoryButton"+id).classList.add('redButtonOuter');},700);
	}
}

/*While Adding task, change end time automatically*/
function changeEndTime(){
	var startTime=document.getElementById("startTime").value;
	var endTime=startTime.split(':');
	if(parseInt(endTime[0])>22){newHour=0;}
	else{newHour=parseInt(endTime[0])+1;}
	newHour=checkTime(newHour);
	document.getElementById("endTime").value=newHour+':'+endTime[1];
}

/*Toggle menu for mobile devices*/
function toggleMobileMenu(){
	var w=window.innerWidth;
	if(w<960){
		toggleMenu();
	}
}

/*Show Notes*/
function showNotes(){
	document.getElementById("backgroundDiv").style.display="block";
	document.getElementById("notesDiv").style.display="block";
	var username=document.getElementById("username").value;
	/*AJAX Functionality*/
	/*Declare variables*/
	var checkNotes = "RandomInput";
	var data = "checkNotes="+checkNotes+"&username="+username;
	/*Declare XML*/
	if(window.XMLHttpRequest){var xhr = new XMLHttpRequest();}
	else if(window.ActiveXObject){var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
	/*AJAX Methods*/
	xhr.open("POST","conditions.php",true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(data);
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				document.getElementById("notesDiv").innerHTML=this.responseText;
			}
		}
	}	
}

/*Add Notes*/
function addNote(){
	var username=document.getElementById("username").value;
	var noteInput=document.getElementById("noteInput").value;
	if(noteInput!=''){
		/*AJAX Functionality*/
		/*Declare variables*/
		var addNotes = "RandomInput";
		var data = "addNotes="+addNotes+"&username="+username+"&noteInput="+noteInput;
		/*Declare XML*/
		if(window.XMLHttpRequest){var xhr = new XMLHttpRequest();}
		else if(window.ActiveXObject){var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
		/*AJAX Methods*/
		xhr.open("POST","conditions.php",true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send(data);
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4){
				if(xhr.status == 200){
					var ouput=this.responseText;
					if(ouput=='Done'){
						document.getElementById("notesErrorMessage").innerHTML="<div class='successMessage'>Note taken Boss.</div>";
						showNotes();
					}else{
						document.getElementById("notesErrorMessage").innerHTML="<div class='errorMessage'>Something seems to be wrong boss.</div>";
						showNotes();
					}
					setTimeout(function(){document.getElementById("notesErrorMessage").innerHTML="";},3000);
				}
			}
		}
	}else{
		document.getElementById("notesErrorMessage").innerHTML="<div class='errorMessage'>No input was given..</div>";
		setTimeout(function(){document.getElementById("notesErrorMessage").innerHTML="";},3000);
	}
	document.getElementById("noteInput").value='';	
}

/*Note mark as noted*/
function markNoted(id){
	var username=document.getElementById("username").value;
	var id=id;
	/*AJAX Functionality*/
	/*Declare variables*/
	var markNoted = "RandomInput";
	var data = "markNoted="+markNoted+"&username="+username+"&id="+id;
	/*Declare XML*/
	if(window.XMLHttpRequest){var xhr = new XMLHttpRequest();}
	else if(window.ActiveXObject){var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
	/*AJAX Methods*/
	xhr.open("POST","conditions.php",true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(data);
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var ouput=this.responseText;
				if(ouput=='Done'){
					document.getElementById("notesErrorMessage").innerHTML="<div class='successMessage'>Note Closed Boss!</div>";
					setTimeout(showNotes,1000);
				}
			}
		}
	}
}

/*Undo Note*/
function markUnNoted(id){
	var username=document.getElementById("username").value;
	var id=id;
	/*AJAX Functionality*/
	/*Declare variables*/
	var markUnNoted = "RandomInput";
	var data = "markUnNoted="+markUnNoted+"&username="+username+"&id="+id;
	/*Declare XML*/
	if(window.XMLHttpRequest){var xhr = new XMLHttpRequest();}
	else if(window.ActiveXObject){var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
	/*AJAX Methods*/
	xhr.open("POST","conditions.php",true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(data);
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var ouput=this.responseText;
				if(ouput=='Done'){
					document.getElementById("notesErrorMessage").innerHTML="<div class='successMessage'>Note Re-Added Boss!</div>";
					setTimeout(showNotes,1000);
				}
			}
		}
	}
}

/*Show Delete Note*/
function showDeleteNote(id){
	var id=id;
	document.getElementById("eachNote"+id).style.border="1px solid";
	document.getElementById("noteDeleteConfirmation"+id).innerHTML="<div class='errorMessage'>Are you sure you wanna delete this note boss?<br><button class='redButton smallButton' onclick='deleteNote("+id+")'>YES</button><button class='redButtonOuter smallButton' onclick='showNotes()'>CANCEL</button></div>";
}

/*Delete Note*/
function deleteNote(id){
	var username=document.getElementById("username").value;
	var id=id;
	/*AJAX Functionality*/
	/*Declare variables*/
	var deleteNote = "RandomInput";
	var data = "deleteNote="+deleteNote+"&username="+username+"&id="+id;
	/*Declare XML*/
	if(window.XMLHttpRequest){var xhr = new XMLHttpRequest();}
	else if(window.ActiveXObject){var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
	/*AJAX Methods*/
	xhr.open("POST","conditions.php",true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(data);
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var ouput=this.responseText;
				if(ouput=='Done'){
					document.getElementById("notesErrorMessage").innerHTML="<div class='successMessage'>Note Deleted Boss!</div>";
					setTimeout(showNotes,1000);
				}
			}
		}
	}
}

/*Task Completed Previously*/
function completePreviousTask(id){
	var id=id;
	var username=document.getElementById("username").value;
	document.getElementById("completePreviousTask"+id).innerHTML = "<div class = 'loaderButton'></div>";
	/*AJAX Functionality*/
	/*Declare variables*/
	var completePreviousTask = "RandomInput";
	var data = "completePreviousTask="+completePreviousTask+"&id="+id+"&username="+username;
	/*Declare XML*/
	if(window.XMLHttpRequest){var xhr = new XMLHttpRequest();}
	else if(window.ActiveXObject){var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
	/*AJAX Methods*/
	xhr.open("POST","conditions.php",true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(data);
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				document.getElementById("completeTaskDiv").style.display="block";
				eachResponse=this.responseText.split("-period-");
				document.getElementById("backgroundDiv").style.display="block";
				document.getElementById("completeTaskDiv").innerHTML=eachResponse[0];
			}
		}
	}	
	document.getElementById("completePreviousTask"+id).innerHTML = "Previously Done";
}

/*Confirm Task Completed Previously*/
function confirmTaskCompletedPreviously(id){
	var username=document.getElementById("username").value;
	var completedDate = document.getElementById("completedDate"+id).value;
	
	document.getElementById("completedButton"+id).innerHTML = "<div class = 'loaderButton'></div>";
	/*AJAX Functionality*/
	/*Declare variables*/
	var confirmTaskCompletedPreviously = "RandomInput";
	var data = "confirmTaskCompletedPreviously="+confirmTaskCompletedPreviously+"&id="+id+"&username="+username+"&completedDate="+completedDate;
	/*Declare XML*/
	if(window.XMLHttpRequest){var xhr = new XMLHttpRequest();}
	else if(window.ActiveXObject){var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
	/*AJAX Methods*/
	xhr.open("POST","conditions.php",true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(data);
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				eachResponse=this.responseText.split("-period-");
				document.getElementById("completeTaskErrorMessage"+id).innerHTML = eachResponse[1];
				document.getElementById("completedButton"+id).innerHTML = "<div class = 'loaderButton'></div>";
				setTimeout(function(){document.getElementById("completedButton"+id).innerHTML = "DONE";},500);
				setTimeout(function(){document.getElementById("completeTaskErrorMessage"+id).innerHTML = "";},2000);
				if(eachResponse[0]=='UpdateDone'){
					setTimeout(function(){closeDiv();},2000);
					setTimeout(function(){document.getElementById("todayTask"+id).innerHTML="<div class='loaderButton'></div>";},2000);
					setTimeout(function(){document.getElementById("todaySchedule").innerHTML=eachResponse[2];},3000);
				}
			}
		}
	}
	document.getElementById("completedButton"+id).innerHTML = "DONE";
}