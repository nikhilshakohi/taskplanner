<?php

$host = "localhost";
$username = "root";
$password = ""; /*Blank for XAMPP Application*/
$databaseName = "taskmanager"; /*The one we have created*/

$conn = mysqli_connect($host, $username, $password, $databaseName) or die("Unable to connect to Database");
/*$conn - This parameter can be used for calling the database while using mySQL*/

/*In Main Database*/
/*$username = "id18124330_root";
$password = "nS@149118912"; /*Blank for XAMPP Application*/
/*$databaseName = "id18124330_taskmanager";*/ /*The one we have created*/

?>