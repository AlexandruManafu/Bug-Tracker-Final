<?php
if(isset($_POST["submit"]))
{
	session_start();
	
	require_once 'database-handler.php';
	require_once 'functions.php';
	
	$title = $_POST["issueTitle"];
	$details = $_POST["issueDetails"];
	$priority = $_POST["issuePriority"];
	$user = $_SESSION["usersName"];
	$projectId = $_POST["projectId"];
	$projectCode = getProjectCode($con,$projectId);
	$prevPage = $_POST["previousPage"];
	
	
	if(empty($title) || empty($details) || empty($priority))
	{
		header("location: ../".$prevPage."?project=".$projectId."&error=emptyInput");
		exit();
	}
	
	
	
	createIssue($con,$title,$details,$priority,$user,$projectCode);
	header("location: ../".$prevPage."?project=".$projectId);
	exit();
	
	
}
else //redirect user back if he tries to access this page by inputing it on the bar
{
	header("location: ../projects.php");
	exit();
}

