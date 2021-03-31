<?php
if(isset($_POST["submit"]))
{
	session_start();
	$code = $_POST["projectCode"];
	$user = $_SESSION["usersName"];
	
	require_once 'database-handler.php';
	require_once 'functions.php';
	
	if(projectExists($con,$code)===true)
	{
		echo "yes";
		addDevToProject($con,$code,$user);
		header("location: ../projects.php?error=projectJoined");
	}
	else
	{
		header("location: ../projects.php?error=projectNotFound");
	}
	
}
else //redirect user back if he tries to access this page by inputing it on the bar
{
	header("location: ../projects.php");
	exit();
}
