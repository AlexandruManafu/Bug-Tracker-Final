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
		
		function displayIssues($con,$issues,$projectCode)
		{
			if(isset($issues))
			{
				while($row = mysqli_fetch_array($issues))
				{
					displayIssue($row,$projectCode,10,15);
					
				}
			}
		}
		
		function displayIssuesAll($con, $place,$projectCode)
		{
			$issues = listIssuesDeadlines($con, $place, $projectCode);
			displayIssues($con,$issues,$projectCode);
			
			$issues = listIssuesPriorities($con, $place, $projectCode);
			displayIssues($con,$issues,$projectCode);
		}
		
		function displayColumn($con,$columnName,$projectCode)
		{
			echo "<div class='column'><h2 class='columnTitle'>".$columnName."</h2>";
			echo "<br>";
		
			displayIssuesAll($con,$columnName,$projectCode);

			echo "</div>";
		}
		echo "<div class='row'>";
		
			displayColumn($con,"Backlog",$code);
			
			displayColumn($con,"To Do",$code);
		
			displayColumn($con,"In Progress",$code);
			
			displayColumn($con,"Testing",$code);
			
			displayColumn($con,"Completed",$code);
			
		echo "</div>"; 
	
	
		if(isset($_GET["error"]))
		{
			createIssueDisplay($currentPage,$projectCode,$_GET["error"]);
		}
			else
		{
			createIssueDisplay($currentPage,$projectCode,NULL);
		}
				
		require_once "projectIssueOptions.php";
			
	?>
	
	
	</div>
	</div>
	</body>
	
<html>