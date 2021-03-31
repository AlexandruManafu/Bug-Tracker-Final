<?php
if(isset($_POST["no"]) && isset($_POST["issueId"]))
{
	$code = $_POST["projectCode"];
	$issueId = $_POST["issueId"];
	header("location: ../project.php?project=".$code."&selectedIssue=".$issueId);
	exit();
}
else if(isset($_POST["no"]))
{
	$code = $_POST["projectCode"];
	header("location: ../project.php?project=".$code);
	exit();
}
else if(isset($_POST["yes"]))
{
	session_start();
	
	$user = $_SESSION["usersName"];
	$issueId = $_POST["issueId"];
	$code = $_POST["projectCode"];
	$newPlace = $_POST["targetPlace"];
	
	require_once 'database-handler.php';
	require_once 'functions.php';
	
	if($newPlace == "Backlog")
	{
		postponeFromToDo($con,$issueId);
	}
	else if($newPlace == "To_Do")
	{
		moveFromBacklog($con,$issueId);
	}
	else if($newPlace == "In_Progress")
	{
		updateIssue($con, "In Progress", "In Progress", $user, $issueId);
	}
	else if($newPlace == "Testing")
	{
		updateIssue($con, $newPlace, $newPlace, $user, $issueId);
	}
	else if($newPlace == "Abandoned")
	{
		$currentDate = date('Y-m-d');
		
		$issue = getIssue($con,$issueId);
		$lastDev = $issue["issueDevelopedBy"];
		abandonIssue($con, "Completed", "Abandoned", $lastDev, $issueId);
		
		addCompletionTime($con, $currentDate, $issueId);
	}
	else if($newPlace == "Completed")
	{
		$currentDate = date('Y-m-d');
		
		$issue = getIssue($con,$issueId);
		$lastDev = $issue["issueDevelopedBy"];
		updateIssue($con, "Completed", "Completed", $lastDev, $issueId);
		
		addCompletionTime($con, $currentDate, $issueId);
	}
	else if($newPlace == "Delete")
	{	
		deleteIssue($con,$issueId);
		header("location: ../project.php?project=".$code);
		exit();
	}
	else if($newPlace == "Delete_Project")
	{
		if(existsIssueInPlace($con, "To Do", $code) || existsIssueInPlace($con, "In Progress", $code) || existsIssueInPlace($con, "Testing", $code))
		{
			header("location: ../project.php?project=".$code."&error=existActiveIssues");
			exit();
		}
		deleteProject($con, $code);
		header("location: ../projects.php");
		exit();
	}
	
	//echo($newPlace);
	header("location: ../project.php?project=".$code."&selectedIssue=".$issueId);
	exit();	
}
else //redirect user back if he tries to access this page by inputing it on the bar
{
	header("location: ../projects.php");
	exit();
}


