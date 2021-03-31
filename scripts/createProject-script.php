<?php
if(isset($_POST["submit"]))
{
	session_start();
	$title = $_POST["projectTitle"];
	$user = $_SESSION["usersName"];
	if(empty($title))
	{
		header("location: ../projects.php?error=emptyTitle");
		exit();
	}
	
	require_once 'database-handler.php';
	require_once 'functions.php';
	
	insertInProjects($con,$title,$user);
	//echo "here";
	header("location: ../projects.php?error=projectCreated");
	
}
else //redirect user back if he tries to access this page by inputing it on the bar
{
	header("location: ../projects.php");
	exit();
}

