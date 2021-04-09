<?php
include('header.php');
?>
	<div class="wrapper">
	<div class="content">
	<?php
		require_once "scripts/functions.php";
		require_once "scripts/database-handler.php";
		
		if(isset($_SESSION["usersId"]) && isset($_GET["project"]) )
		{
			$currentPage = basename(__FILE__);
			$_SESSION["currentPage"] = $currentPage;
			$inProjectFile = true;
			$code = $_GET["project"];
			$projectCode = getProjectCode($con,$code);
			if( isOwnerProject($con,$_GET["project"],$_SESSION["usersName"]) )
			{
				$userRole = "manager";
			}
			else if( isDevInProject($con,$projectCode,$_SESSION["usersName"]) )
			{
				$code = $_GET["project"];
				$projectCode = getProjectCode($con,$code);
				$userRole = "developer";
			}
			else
			{
				header("location: ./projects.php?error=projectNotJoined");
				exit();
			}
		}
		else
		{
			header("location: ./index.php");
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		if(isset($_GET["error"]))
		{
			createIssueDisplay($currentPage,$code,$projectCode,$_GET["error"],$userRole);
		}
			else
		{
			createIssueDisplay($currentPage,$code,$projectCode,NULL,$userRole);
		}
				
		require_once "projectIssueOptions.php";
			
		?>
	
	
	</div>
	</div>
	</body>
	
<html>