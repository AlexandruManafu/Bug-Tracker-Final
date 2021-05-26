<?php
include('header.php');
?>
	<div class="wrapper">
	<div class="content">
	<?php
		require_once "scripts/functions.php";
		require_once "scripts/database-handler.php";
		
		if(isset($_SESSION["usersId"]) && isset($_GET["project"]) && isOwnerProject($con,$_GET["project"],$_SESSION["usersName"]) )
		{
			$currentPage = basename(__FILE__);
			$_SESSION["currentPage"] = $currentPage;
			$id = $_GET["project"];
			$projectCode = getProjectCode($con,$id);
			$projectFields = listProjectById($con,$id);
		}
		else
		{
			header("location: ./projects.php?error=projectNotJoined");
		}
		
				
		displayButton("exclusiveToggleWindow('confirm','editProject','block');","Edit Project Fields");
			
		displayButton("exclusiveToggleWindow('confirm','displayCode','block');","Display Join Code");
	
		?>
		<div class='confirm' id='editProject' style= 'display:none;margin-left:3ex;'>
			<form action = 'scripts/manageProject-script.php' method='post'>
			<?php
				echo "<input type='hidden' name='previousPage' value=".$currentPage.">";
				echo "<input type='hidden' name='projectId' value=".$id.">";
				echo "<input type='hidden' name='projectCode' value=".$projectCode.">";
				echo "<input type='hidden' name='targetPlace' value='Edit_Project'>";
				
				echo "<input class='bigger-custom-inputIssues' type='text' style='margin-left:-0.5ex;' name='projectName' value=".$projectFields["projectName"]." placeholder = 'Issue Title'><br>";
				echo "<textarea class='details' name='projectDetails' rows='5' placeholder = 'Details'>".$projectFields["projectDetails"]."</textarea><br>";
			?>
				<button class='create_button' type='text' name='submit'>Apply</button>
			</form>
		</div>
		<br>
		<div class='confirm' id='displayCode' style='display:none;'>
			<p>The join code for the current project is:</p><br>
			<?php
				echo "<p>".$projectCode."</p>";
			?>
		</div>
		<br>
	<?php 
		displayButton("exclusiveToggleWindow('confirm','addDeveloperWindow','inline');","Add Developer");
		
		displayButton("exclusiveToggleWindow('confirm','removeDevWindow','block');","Remove Developer");
		
	?>
		<div class='confirm' class='projectField' id='addDeveloperWindow' style='display:none;'>
			<form action = 'scripts/manageProject-script.php' method='post'>
				<?php
					echo "<input type='hidden' name='previousPage' value=".$currentPage.">";
					echo "<input type='hidden' name='projectId' value=".$id.">";
					echo "<input type='hidden' name='projectCode' value=".$projectCode.">";
				?>
				<input class='bigger-custom-input' type='text' name='userName' style='margin-left:0.5ex;' placeholder = 'User Name'><br>
				<input type='hidden' name='targetPlace' value='Add_Developer'>
				<button class='add_button' type='text' name='submit'>Confirm</button>
			</form>
		</div>
	<?php
		
		displayAssignWindow("removeDevWindow","scripts/manageProject-script.php",$currentPage,null,$id,$projectCode,
		"Are you sure you want to remove the selected developer from the project?","Remove_Developer",$con);
		
		displaySpecialButton("deleteProject","exclusiveToggleWindow('confirm','deleteProjectWindow','block');","Delete Project");
		displayConfirmationWindow("deleteProjectWindow","scripts/manageProject-script.php",$currentPage,NULL,$id,$projectCode,
			"Are you sure you want to delete the project with all its issues ?","Delete_Project");
			
		if(isset($_GET["error"]))
		{
			echo "<br>";
			$error = $_GET["error"];
			$errors = array();
			$errors["existActiveIssues"] = "Projects with active issues cannot be deleted.";
			$errors["emptyTitle"] = "The project title cannot be empty.";
			$errors["noSuchUser"] = "User not found.";
			$errors["noSelection"] = "No user selected.";
			
			if(isset($errors[$error]))
				echo "<p class='error' style='display: inline-block;margin-left:3ex;'>".$errors[$error]."</p>";
			
			$errors = array();
			$errors["devAdded"] = "User Added to project.";
			$errors["projectEdited"] = "Project Fields updated.";
			$errors["devRemoved"] = "User removed from project.";
			
			if(isset($errors[$error]))
				echo "<p class='sign-upSuccess' style='display: inline-block; margin-left:3ex;'>".$errors[$error]."</p>";
			
		}
		
	?>
	
		
			
	
	
	
	</div>
	</div>
	</body>
	
<html>