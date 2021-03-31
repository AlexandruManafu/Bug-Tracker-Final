<?php
include('header.php');
?>
	session_start();
	<div class="wrapper">
	<div class="content">
	<?php
		require_once "scripts/functions.php";
		require_once "scripts/database-handler.php";
		if(isset($_SESSION["usersType"]) && $_SESSION["usersType"]=="manager")
		{
			if(isset($_GET["project"]) && isOwnerProject($con,$_GET["project"],$_SESSION["usersName"]) )
			{
				$code = $_GET["project"];
				$projectCode = $_SESSION['projectCode'];
			}
			else
			{
				header("location: ./projects.php?error=projectNotOwned");
				exit();
			}
			
		}
		else if(isset($_SESSION["usersType"]) && $_SESSION["usersType"]=="developer")
		{
			if(isset($_GET["project"]) && isDevInProject($con,$_GET["project"],$_SESSION["usersName"]) )
			{
				$code = $_GET["project"];
				$projectCode = $_SESSION['projectCode'];
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
		
		require_once 'scripts/database-handler.php';
		require_once 'scripts/functions.php';
		
		function displayIssue($issue,$projectCode,$size,$maxTextLen)
		{
			if($issue["issuePriority"]==1)
			{
				$color = "green";
			}
			else if($issue["issuePriority"]==2)
			{
				$color = "orange";
			}
			else if($issue["issuePriority"]==3)
			{
				$color = "red";
			}
			else
			{
				$color = "grey";
			}
				
			echo "<a class='issueButton' href='project.php"."?project=".$projectCode."&selectedIssue=".$issue["issueId"].
						
				"'>
				<img class='issuePriority' src='images/icons/circle-".$color.".png' alt='Issue Priority' width =".$size."%> 
				<p>".shortenDisplay($issue['issueTitle'],$maxTextLen)."</p>
				</a>";
				
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
	
	
	//require_once "projectIssueOptions.php";
	
	echo "<div class='row'>";
		echo "<div class='leftCol'>";
			echo "<img class='addIssue' onclick=toggleWindow('newIssueWindow','inline-block');changeFlexValue('rightCol','newIssueWindow',3.5,2);changeMarginValue('deleteProject','newIssueWindow',3.7,25); src='images/icons/add.svg' alt='Create Project' width = 10%>";
			
			
			echo "<div class='confirm' id='newIssueWindow' style='display: none;'>";
			echo "<form action = 'scripts/createIssue-script.php' method='post'>";
				echo "<input type='hidden' name='projectCode' value=".$code.">";
				echo "<input class='bigger-custom-inputIssues' type='text' style='margin-left:-0.5ex;' name='issueTitle' placeholder = 'Issue Title'><br>";
				echo "<p style='margin-bottom: 1ex;'>Issue Priority: </p>";
				echo "<select name='issuePriority' id='account_type'>";
						echo "<option value=''>Select an option</option>";
						echo "<option value='1'>Low</option>";
						echo "<option value='2'>Medium</option>";
						echo "<option value='3'>High</option>";
						

				echo "</select>";
				echo "<br><br>";
				echo "<textarea class='details' name='issueDetails' rows='5'  placeholder = 'Details'></textarea><br>";
				
				
				echo "<button class='create_button' type='text' name='submit'>Create</button>";
				
				
				echo "</form>";
			echo "</div>";
			if(isset($_GET["error"]) && $_GET["error"]=="emptyInput" )
			{	
				echo "<p class=error style='margin-top:3ex;' = error>No inputs can be empty</p>";
			}
			if(isset($_GET["error"]) && $_GET["error"]=="invalidDate" )
			{	
				echo "<p class=error style='margin-top:3ex;' = error>Date entered is invalid</p>";
			}
			if($_SESSION["usersType"] == "manager")
			{
				displaySpecialButton("deleteProject","exclusiveToggleWindow('confirm','deleteProjectWindow','block');","Delete Project");
				displayButton("exclusiveToggleWindow('confirm','displayCode','block');","Display Project Join Code");
			}
			echo "<div class=confirm id=displayCode style='display:none;'>
					<p>The join code for the current project is:</p><br>
					<p>".$projectCode."</p>
				</div>
			";
			displayConfirmationWindow("deleteProjectWindow","scripts/updateIssue-script.php",NULL,$code,
			"Are you sure you want to delete the project with all its issues ?","targetPlace","Delete_Project");
			if(isset($_GET["error"]) && $_GET["error"]=="existActiveIssues" )
				{	
					echo "<p class = error>Projects with active issues cannot be deleted.</p>";
				}
			echo "</div>";
			
			require_once "projectIssueOptions.php";
		?>
	
	
	</div>
	</div>
	</body>
	
<html>