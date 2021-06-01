<?php

if(!isset($inProjectFile))
{
	header("location: projects.php");
}

			if(isset($_GET["error"])){
				createIssueDisplay($currentPage,$code,$_GET["error"],$userRole);
			}
			else{
				createIssueDisplay($currentPage,$code,NULL,$userRole);
			}

echo "<div id='rightCol'>";
		//$backlog="Backlog";
		if(!isset($_GET["selectedIssue"]))
		{
			echo "<p class='noneSelected'>No issue selected</p>";
		}
		else if(isset($_GET["selectedIssue"]) && issueBelongsToProject($con, $_GET["selectedIssue"], $projectCode))
		{
			//$issue = getIssue($con,$_GET["selectedIssue"]);
			echo "<p class='issueSelected'>Selected Issue:</p>";
			
			$issue = getIssue($con,$_GET["selectedIssue"]);
			
			echo displayIssue($issue,$code,30,50);
			echo "<br>";
			
			
			displayButton("exclusiveToggleWindow('confirm','infoWindow','block');","View Issue Info");
			
			if( $userRole == "manager" )
			{
				if(issueInPlace($con,$_GET["selectedIssue"],"Backlog"))
				{
					displayButton("exclusiveToggleWindow('confirm','issueDelete','block');","Delete Issue");
				}
				displayButton("exclusiveToggleWindow('confirm','editWindow','block');","Edit Issue");
			}
			else if($userRole == "developer" && issuePermissions($con))
			{
				displayButton("exclusiveToggleWindow('confirm','editWindow','block');","Edit Issue");
			}
			
			if(issueInPlace($con,$_GET["selectedIssue"],"Backlog") && $userRole == "manager")
			{
				displayButton("exclusiveToggleWindow('confirm','issueMove','block');","Move Issue");
			}
			else if(issueInPlace($con,$_GET["selectedIssue"],"Backlog") && $userRole == "developer" && isIssueCreatedBy($con,$_GET["selectedIssue"],$_SESSION["usersName"]) )
			{
				displayButton("exclusiveToggleWindow('confirm','issueDelete','block');","Delete Issue");
			}
			
			if(issueInPlace($con,$_GET["selectedIssue"],"To Do") && isDevInProject($con,$projectCode,$_SESSION["usersName"]) )
			{
				displayButton("exclusiveToggleWindow('confirm','issueUpdate','block');", "Update Status");
				echo "<br>";
			}
			if(issueInPlace($con,$_GET["selectedIssue"],"To Do") && $userRole == "manager")
			{
				displayButton("exclusiveToggleWindow('confirm','issuePostpone','block');", "Postpone Issue");
				displayButton("exclusiveToggleWindow('confirm','issueAssignTo','block');", "Assign Issue");
			}			
			
			
			if(issueInPlace($con,$_GET["selectedIssue"],"In Progress") && isDevInProject($con,$projectCode,$_SESSION["usersName"]) )
			{
				displayButton("exclusiveToggleWindow('confirm','issueTesting','block');", "Update Status");
				echo "<br>";
			}
			if(issueInPlace($con,$_GET["selectedIssue"],"In Progress") && $userRole == "manager")
			{
				displayButton("exclusiveToggleWindow('confirm','issueAssignIn','block');", "Assign Issue");
				displayButton("exclusiveToggleWindow('confirm','issueAbandon','block');", "Abandon");
				displayButton("exclusiveToggleWindow('confirm','issueComplete','block');", "Mark as Completed");
			}
			
			if(issueInPlace($con,$_GET["selectedIssue"],"Testing") && $userRole == "manager")
			{
				displayButton("exclusiveToggleWindow('confirm','issueAbandon','block');", "Abandon");
				displayButton("exclusiveToggleWindow('confirm','issueComplete','block');", "Mark as Completed");
			}
			
			if(issueInPlace($con,$_GET["selectedIssue"],"Completed") && $userRole == "manager")
			{
				displayButton("exclusiveToggleWindow('confirm','issuePostpone','block');", "Back to Backlog");
			}
			
			if($userRole == "manager")
			{
				if(issueInPlace($con,$_GET["selectedIssue"],"Backlog"))
				{
				displayConfirmationWindow("issueMove","scripts/updateIssue-script.php",$currentPage,$issue,$code,
				"Are you sure you want to move the selected issue to 'To Do' ?","To_Do",null,null);
				}
				if(issueInPlace($con,$_GET["selectedIssue"],"To Do"))
				{
					displayConfirmationWindow("issuePostpone","scripts/updateIssue-script.php",$currentPage,$issue,$code,
					"Are you sure you want to move the selected issue to 'Backlog' ?","Backlog",null,null);
				}
				if(issueInPlace($con,$_GET["selectedIssue"],"To Do") || issueInPlace($con,$_GET["selectedIssue"],"In Progress") )
				{
					displayAssignWindow("issueAssignTo","scripts/updateIssue-script.php",$currentPage,$issue,$code,
					"Are you sure you want to assign the issue moving it to In Progress?","Assign_To",$con);
					
					displayAssignWindow("issueAssignIn","scripts/updateIssue-script.php",$currentPage,$issue,$code,
					"Are you sure you want to assign the issue moving it to Testing?","Assign_In",$con);
				}
				if(issueInPlace($con,$_GET["selectedIssue"],"Testing") || issueInPlace($con,$_GET["selectedIssue"],"In Progress"))
				{
				
					displayConfirmationWindow("issueAbandon","scripts/updateIssue-script.php",$currentPage,$issue,$code,
					"Are you sure you want to abandon the selected issue ?","Abandoned",null,null);
					
					displayConfirmationWindow("issueComplete","scripts/updateIssue-script.php",$currentPage,$issue,$code,
					"Are you sure you want to mark the issue as Completed ?","Completed",null,null);
				}
				if(issueInPlace($con,$_GET["selectedIssue"],"Completed"))
				{
					displayConfirmationWindow("issuePostpone","scripts/updateIssue-script.php",$currentPage,$issue,$code,
					"Are you sure you want to move the selected issue to 'Backlog' ?","Backlog",null,null);
				}
				
				
			}
			
			if(issuePermissions($con))
			{
				editIssueDisplay($currentPage,$code,$issue,$userRole);
			}
				
			if(issueInPlace($con,$_GET["selectedIssue"],"Backlog"))
			{
				displayConfirmationWindow("issueDelete","scripts/updateIssue-script.php",$currentPage,$issue,$code,
				"Are you sure you want to delete the selected issue?","Delete",null,null);
			}
			
			if(isDevInProject($con,$projectCode,$_SESSION["usersName"]) &&
			(issueInPlace($con,$_GET["selectedIssue"],"To Do") || issueInPlace($con,$_GET["selectedIssue"],"In Progress")) )
			{
				displayConfirmationWindow("issueUpdate","scripts/updateIssue-script.php",$currentPage,$issue,$code,
				"Are you sure you want to move the selected issue to 'In Progress' ?","In_Progress",null,null);
				
				displayConfirmationWindow("issueTesting","scripts/updateIssue-script.php",$currentPage,$issue,$code,
				"Are you sure you want to move the selected issue to 'Testing' ?","Testing",null,null);
			}
			
		
					
						
			
			
			echo "<div id='infoWindow' >";
				echo"<p class='issueInfoFields'>Status <br> ".$issue["issueStatus"]."</p>";
				echo "<p class='issueInfoFields'>Created by <br>".$issue['issueCreatedBy']."</p>"; 
				echo "<p class='issueInfoFields'>Developed by <br> ".$issue["issueDevelopedBy"]."</p>";
				if($issue["issueDeadline"] != "0000-00-00")
					echo "<p class='issueInfoFields'>Deadline <br> ".dateDisplay($issue["issueDeadline"])."</p>";
				else
					echo "<p class='issueInfoFields'>Deadline <br> ".'None'."</p>";
					
				if($issue["issuePlace"]=="Completed")
					echo "<p class='issueInfoFields'>Completed At <br> ".dateDisplay($issue["issueCompletedAt"])."</p>";
					
				if(!isMobileDev())
					$maxWidth = 75;
				else
					$maxWidth = 40;
				echo "<div class = 'issueText'>";
				echo "<pre class='issueDetail'>Details: <br> ".wordwrap($issue["issueDetails"],$maxWidth,"\n",true)."<pre>";
				echo "</div>";
			echo "</div>";
		}
		if(isset($_GET["selectedIssue"]) && !issueBelongsToProject($con, $_GET["selectedIssue"], $projectCode))
		{
			echo "<p class='noneSelected'>Issue with id=".$_GET["selectedIssue"]." not found.</p>";
			
		}
		echo "</div>";
		
	
	function issuePermissions($con)
	{
		if(isIssueDevelopedBy($con, $_GET["selectedIssue"],$_SESSION["usersName"]) ||
		isIssueCreatedBy($con,$_GET["selectedIssue"],$_SESSION["usersName"]) )
			return true;
		return false;
	}
		
		
	function createIssueDisplay($currentPage,$projectId,$error,$userRole)
	{
		echo "<div class='row'>";
		echo "<div class='leftCol'>";
			echo "<img class='addIssue' onclick=toggleWindow('newIssueWindow','inline-block');changeFlexValue('rightCol','newIssueWindow',3.5,2);changeMarginValue('deleteProject','newIssueWindow',3.7,25); src='images/icons/add.svg' alt='Create Project' width = 10%>";
			
			
			echo "<div class='confirm' id='newIssueWindow' style='display: none;'>";
			echo "<form action = 'scripts/createIssue-script.php' method='post'>";
			
				echo "<input type='hidden' name='previousPage' value=".$currentPage.">";
				echo "<input type='hidden' name='projectId' value=".$projectId.">";
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
			if($userRole == "manager")
			{
				echo "<br><br>";
				echo "<a class='menu_button' id='manageProjectB' href='manage.php?project=".$projectId."'>Manage Project</a>";
				
			}
			if(isset($error) && $error=="emptyInput" )
			{	
				echo "<p class=error style='margin-top:3ex;' = error>No inputs can be empty</p>";
			}
			if(isset($error) && $error=="invalidDate" )
			{	
				echo "<p class=error style='margin-top:3ex;' = error>Date entered is invalid</p>";
			}
			displayConfirmationWindow("deleteProjectWindow","scripts/updateIssue-script.php",$currentPage,NULL,$projectId,
			"Are you sure you want to delete the project with all its issues ?","Delete_Project");
			if(isset($error) && $error=="existActiveIssues" )
			{	
				echo "<p class = error>Projects with active issues cannot be deleted.</p>";
			}
			
			echo "</div>";
		}
		
		function editIssueDisplay($currentPage,$projectCode,$issue,$userRole)
		{
			echo "<div class='confirm' id='editWindow' style= 'display:none;'>";
			echo "<form action = 'scripts/editIssue-script.php' method='post'>";
			
				echo "<input type='hidden' name='previousPage' value=".$currentPage.">";
				echo "<input type='hidden' name='issueId' value=".$issue["issueId"].">";
				echo "<input type='hidden' name='projectCode' value=".$projectCode.">";
				
				echo "<input class='bigger-custom-inputIssues' type='text' style='margin-left:-0.5ex;' name='issueTitle' value='".$issue['issueTitle']."' placeholder = 'Issue Title'><br>";
				echo "<p style='margin-top: 1ex;margin-bottom: 1ex;'>Issue Priority: </p>";
				echo "<select name='issuePriority' id='account_type'>";
						echo "<option value=''>Select an option</option>";
						if($issue["issuePriority"]==1)
						{
							echo "<option selected='selected' value='1'>Low</option>";
							echo "<option value='2'>Medium</option>";
							echo "<option value='3'>High</option>";
						}
						else if($issue["issuePriority"]==2)
						{
							echo "<option value='1'>Low</option>";
							echo "<option selected='selected' value='2'>Medium</option>";
							echo "<option value='3'>High</option>";
						}
						else if($issue["issuePriority"]==3)
						{
							echo "<option value='1'>Low</option>";
							echo "<option value='2'>Medium</option>";
							echo "<option selected='selected' value='3'>High</option>";
						}
						

				echo "</select>";
				if(isset($_SESSION["usersId"]) && $userRole=="manager")
				{
					echo "<p style='margin-top: 1ex;margin-bottom: 1ex;'>Deadline (day-month-year): </p>";

					if(strcmp($issue['issueDeadline'],"0000-00-00")==0)
						echo "<input class='bigger-custom-inputIssues' type='text' style='margin-left:-0.5ex;' name='issueDeadline' value='' placeholder = ''><br>";
					else
						echo "<input class='bigger-custom-inputIssues' type='text' style='margin-left:-0.5ex;' name='issueDeadline' value='".dateDisplay($issue['issueDeadline'])."' placeholder = ''><br>";
				}
				else
				{
					echo "<input type='hidden' name='issueDeadline' value=".dateDisplay($issue['issueDeadline']).">";
					echo"<br>";
				}
				echo "<br>";
				echo "<textarea class='details' name='issueDetails' rows='5' placeholder = 'Details'>".$issue["issueDetails"]."</textarea><br>";
				
				echo "<button class='create_button' type='text' name='submit'>Apply</button>";
				echo "</form>";
					
			echo "</div>";	
			
		}
	 