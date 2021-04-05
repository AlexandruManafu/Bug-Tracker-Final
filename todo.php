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
			if( isOwnerProject($con,$_GET["project"],$_SESSION["usersName"]) )
			{
				$code = $_GET["project"];
				$projectCode = $_SESSION['projectCode'];
				$userRole = "manager";
			}
			else if( isDevInProject($con,$_GET["project"],$_SESSION["usersName"]) )
			{
				$code = $_GET["project"];
				$projectCode = $_SESSION['projectCode'];
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
			createIssueDisplay($currentPage,$code,$_GET["error"]);
		}
		else
		{
			createIssueDisplay($currentPage,$code,NULL);
		}
			
			require_once "projectIssueOptions.php";
			
		?>
	
	
	</div>
	</div>
	</body>
	
<html>
