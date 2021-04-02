<?php
if(isset($_POST["submit"]))
{
	session_start();
	$title = $_POST["issueTitle"];
	$details = $_POST["issueDetails"];
	$priority = $_POST["issuePriority"];
	$issueId = $_POST["issueId"];
	$projectCode = $_POST["projectCode"];
	$deadline = $_POST["issueDeadline"];
	$prevPage = $_POST["previousPage"];
	
	
	if(empty($title) || empty($details) || empty($priority))
	{
		header("location: ../".$prevPage."?project=".$projectCode."&error=emptyInput");
		exit();
	}
	
	require_once 'database-handler.php';
	require_once 'functions.php';
	
	if(!isset($deadline) || $deadline == "")
	{
		$deadline = "0-0-0";
	}	
	else if(isset($deadline) && $deadline != "")
	{
		$deadlineArr = explode("-",$deadline);
		if(!checkdate($deadlineArr[1],$deadlineArr[0],$deadlineArr[2]))
		{
			header("location: ../".$prevPage."?project=".$projectCode."&error=invalidDate");
			exit();
		}
		$deadline = $deadlineArr[2]."-".$deadlineArr[1]."-".$deadlineArr[0]; //now its YYYY-MM-DD
	}
	
	
	editIssue($con,$title,$priority,$details,$issueId,$deadline);
	header("location: ../".$prevPage."?project=".$projectCode."&selectedIssue=".$issueId);
	exit();
	
	
}
else //redirect user back if he tries to access this page by inputing it on the bar
{
	header("location: ../projects.php");
	exit();
}

