<?php
if(isset($_POST["no"]) && isset($_POST["issueId"]))
{
	$code = $_POST["projectId"];
	$projectCode = $_POST["projectCode"];
	$issueId = $_POST["issueId"];
	$prevPage = $_POST["previousPage"];
	header("location: ../".$prevPage."?project=".$code."&selectedIssue=".$issueId);
	exit();
}
else if(isset($_POST["no"]))
{
	$code = $_POST["projectId"];
	$prevPage = $_POST["previousPage"];
	header("location: ../".$prevPage."?project=".$code);
	exit();
}
else if(isset($_POST["yes"]))
{
	session_start();
	
	$user = $_SESSION["usersName"];
	$issueId = $_POST["issueId"];
	
	$code = $_POST["projectId"];
	$projectCode = $_POST["projectCode"];
	
	$newPlace = $_POST["targetPlace"];
	$prevPage = $_POST["previousPage"];
	
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
	else if($newPlace == "Assign_To")
	{
		$target = $_POST["targetDeveloper"];
		if(!empty($target))
			updateIssue($con, "In Progress", "In Progress", $target, $issueId);
	}
	else if($newPlace == "Assign_In")
	{
		$target = $_POST["targetDeveloper"];
		if(!empty($target))
			updateIssue($con, "Testing", "Testing", $target, $issueId);
	}
	else if($newPlace == "Testing")
	{
		updateIssue($con, $newPlace, $newPlace, $user, $issueId);
	}
	else if($newPlace == "Completed" || $newPlace == "Abandoned")
	{
		$currentDate = date('Y-m-d');
		
		$issue = getIssue($con,$issueId);
		$lastDev = $issue["issueDevelopedBy"];
		updateIssue($con, "Completed", $newPlace, $lastDev, $issueId);
		
		addCompletionTime($con, $currentDate, $issueId);
	}
	else if($newPlace == "Delete")
	{	
		deleteIssue($con,$issueId);
		header("location: ../".$prevPage."?project=".$code);
		exit();
	}
	
	if($newPlace == "Assign_To" || $newPlace == "Assign_In" )
	{
		if(empty($target))
		{
			header("location: ../".$prevPage."?project=".$code."&error=emptyInput");
			exit();
		}
		if($target != $_SESSION["usersName"])
		{
			$name = getProjectName($con,$projectCode);
			$issue = getIssue($con, $issueId);
				
			$content = $_SESSION["usersName"] ." assigned you the issue ".$issue["issueTitle"]." from the project ". $name;
				
			addNotification($con,$target,$content);
		}
	}
	
	//echo $target;
	//echo($newPlace);
	header("location: ../".$prevPage."?project=".$code."&selectedIssue=".$issueId);
	exit();	
}
else //redirect user back if he tries to access this page by inputing it on the bar
{
	header("location: ../projects.php");
	exit();
}


