<?php

if(!isset($inProjectFile))
{
	header("location: projects.php");
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
			
			displayIssue($issue,$projectCode,4,50);
			
			
			displayButton("exclusiveToggleWindow('confirm','infoWindow','block');","View Issue Info");
			
			if( $userRole == "manager" )
			{
				displayButton("exclusiveToggleWindow('confirm','issueDelete','block');","Delete Issue");
				displayButton("exclusiveToggleWindow('confirm','editWindow','block');","Edit Issue");
			}
			else if($userRole == "developer" &&
					((isIssueDevelopedBy($con, $_GET["selectedIssue"],$_SESSION["usersName"]) || isIssueCreatedBy($con,$_GET["selectedIssue"],$_SESSION["usersName"])) 
					&& !issueInPlace($con,$_GET["selectedIssue"],"Completed")))
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
			
			
			if(issueInPlace($con,$_GET["selectedIssue"],"To Do") && $userRole == "manager")
			{
				displayButton("exclusiveToggleWindow('confirm','issuePostpone','block');", "Postpone Issue");
				displayButton("exclusiveToggleWindow('confirm','issueAssignTo','block');", "Assign Issue");
			}			
			else if(issueInPlace($con,$_GET["selectedIssue"],"To Do") && $userRole == "developer")
			{
				displayButton("exclusiveToggleWindow('confirm','issueUpdate','block');", "Update Status");
			}
			
			
			if(issueInPlace($con,$_GET["selectedIssue"],"In Progress") && $userRole == "developer")
			{
				displayButton("exclusiveToggleWindow('confirm','issueTesting','block');", "Update Status");
			}
			else if(issueInPlace($con,$_GET["selectedIssue"],"In Progress") && $userRole == "manager")
			{
				echo"<br>";
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
				
			displayConfirmationWindow("issueDelete","scripts/updateIssue-script.php",$currentPage,$issue,$code,$projectCode,
			"Are you sure you want to delete the selected issue?","Delete",null,null);
			
			displayConfirmationWindow("issueMove","scripts/updateIssue-script.php",$currentPage,$issue,$code,$projectCode,
			"Are you sure you want to move the selected issue to 'To Do' ?","To_Do",null,null);
			
			displayConfirmationWindow("issuePostpone","scripts/updateIssue-script.php",$currentPage,$issue,$code,$projectCode,
			"Are you sure you want to move the selected issue to 'Backlog' ?","Backlog",null,null);
			
			displayConfirmationWindow("issueUpdate","scripts/updateIssue-script.php",$currentPage,$issue,$code,$projectCode,
			"Are you sure you want to move the selected issue to 'In Progress' ?","In_Progress",null,null);
			
			displayConfirmationWindow("issueTesting","scripts/updateIssue-script.php",$currentPage,$issue,$code,$projectCode,
			"Are you sure you want to move the selected issue to 'Testing' ?","Testing",null,null);
			
			displayConfirmationWindow("issueAbandon","scripts/updateIssue-script.php",$currentPage,$issue,$code,$projectCode,
			"Are you sure you want to abandon the selected issue ?","Abandoned",null,null);
			
			displayConfirmationWindow("issueComplete","scripts/updateIssue-script.php",$currentPage,$issue,$code,$projectCode,
			"Are you sure you want to mark the issue as Completed ?","Completed",null,null);
			
			displayAssignWindow("issueAssignTo","scripts/updateIssue-script.php",$currentPage,$issue,$code,$projectCode,
			"Are you sure you want to assign the issue moving it to In Progress?","Assign_To",$con);
			
			displayAssignWindow("issueAssignIn","scripts/updateIssue-script.php",$currentPage,$issue,$code,$projectCode,
			"Are you sure you want to assign the issue moving it to Testing?","Assign_In",$con);
					
						
			echo "<div class='confirm' id='editWindow' style= 'display:none;'>";
			echo "<form action = 'scripts/editIssue-script.php' method='post'>";
			
				echo "<input type='hidden' name='previousPage' value=".$currentPage.">";
				echo "<input type='hidden' name='issueId' value=".$_GET["selectedIssue"].">";
				echo "<input type='hidden' name='projectCode' value=".$code.">";
				
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
					echo"<br>";
				echo "<br>";
				echo "<textarea class='details' name='issueDetails' rows='5' placeholder = 'Details'>".$issue["issueDetails"]."</textarea><br>";
				
				echo "<button class='create_button' type='text' name='submit'>Apply</button>";
				echo "</form>";
					
			echo "</div>";
			
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
					
				echo "<div style='max-width: 600px;'>";
				echo "<pre class='issueDetail'>Details: <br> ".wordwrap($issue["issueDetails"])."<pre>";
				echo "</div>";
			echo "</div>";
		}
		if(isset($_GET["selectedIssue"]) && !issueBelongsToProject($con, $_GET["selectedIssue"], $projectCode))
		{
			echo "<p class='noneSelected'>Issue with id=".$_GET["selectedIssue"]." not found.</p>";
			
		}
		echo "</div>";
	 