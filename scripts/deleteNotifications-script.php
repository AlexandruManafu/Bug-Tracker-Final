<?php
if(isset($_POST["no"]))
{
	$prevPage = $_POST["previousPage"];
	header("location: ../".$prevPage);
	exit();
}
else if(isset($_POST["yes"]))
{
	$prevPage = $_POST["previousPage"];
		
	session_start();
	require_once 'database-handler.php';
	require_once 'functions.php';
	
	deleteNotifications($con,$_SESSION["usersName"]);
	
	header("location: ../".$prevPage);
	exit();	
}
else //redirect user back if he tries to access this page by inputing it on the bar
{
	header("location: ../projects.php");
	exit();
}


