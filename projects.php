<?php
include('header.php');
?>
	
	<div class="wrapper">
	<div class="content">
	<?php
	require_once "scripts/functions.php";
		//echo $_SESSION["usersType"];
	if(!isset($_SESSION["usersId"]))
	{
			header("location: ./index.php");
	}

	?>
	<div class='projectWindow'>
			<img class='projectIcon' onclick=toggleWindow('newProjectWindow','inline-block') src='images/icons/add.svg' alt='Create Project' width = 10%>
			<p>New Project</p>
	</div>
		
		
		
		<div class='projectField' id='newProjectWindow'>
		<form action = 'scripts/manageProject-script.php' method='post'>
			<input class='bigger-custom-input' type='text' name='projectTitle' style='margin-left:0.5ex;' placeholder = 'Project Title'><br>
			<textarea class='newProjectDetails' name='projectDetails' rows='5'  placeholder = 'Details'></textarea><br>
			<input type='hidden' name='targetPlace' value='Create_Project'>
			<button class='create_button' type='text' name='submit'>Create</button>
				<?php
				if(isset($_GET["error"]) && $_GET["error"] == "projectCreated")
				{
					callJavascript("toggleWindow('newProjectWindow','inline-block')");
					echo "<p class='sign-upSuccess' style='display: inline-block; margin-left:-20ex;'>Success</p>";
				}
				if(isset($_GET["error"]) && $_GET["error"] == "emptyTitle")
				{
					callJavascript("toggleWindow('newProjectWindow','inline-block')");
					echo "<p class='error' style='display: inline-block; margin-left:-24ex;'>Title is Empty</p>";
				}
				?>

			</form>
		</div>


		
	<?php
	require_once 'scripts/database-handler.php';
	require_once 'scripts/functions.php';
	
	function displayProjects($projectsDbResult)
	{
		while($row = mysqli_fetch_array($projectsDbResult))
		{
			$projectId = $row["projectId"];
			$projectName = $row["projectName"];
			$projectCode = $row["projectCode"];
			$projectDetails = $row["projectDetails"];
			
			echo "<div style='margin-top: 2ex;' class='projectWindow'>";
				echo isMobileDev() ? "<a href='todo.php?project=".$projectId."'>" : "<a href='kanban.php?project=".$projectId."'>";
				echo "<img class='projectIcon' src='images/icons/proj.svg' alt='Browse Project' width = 10%></a>";
				echo "<p>".shortenDisplay($projectName,10)."</p>";
			echo "</div>";
			
			echo "<div class='projectDetailsBox' id='project".$projectId."'>";
					echo "<p>".$projectDetails."</p>";
			echo "</div>";
			
		}
	}


	$projects = listProjectsForManager($con,$_SESSION["usersName"]);
	
	displayProjects($projects);
	
	?>
	<br>
	<div class='projectWindow'>
			<img class='projectIcon' onclick=toggleWindow('joinProjectWindow','inline-block') src='images/icons/add.svg' alt='Join Project' width = 10%>
			<p>Join Project</p>
	</div>
			
		<div class='projectField' id='joinProjectWindow'>
		<form action = 'scripts/manageProject-script.php' method='post'>
			<input class='bigger-custom-input' type='text' name='projectCode' style='margin-left:0.5ex;' placeholder = 'Project Code'><br>
			<input type='hidden' name='targetPlace' value="Join_Project">
			<button class='create_button' type='text' name='submit'>Join</button>
				<?php
				if(isset($_GET["error"]) && $_GET["error"] == "projectJoined")
				{
					callJavascript("toggleWindow('joinProjectWindow','inline-block')");
					echo "<p class='sign-upSuccess' style='display: inline-block; margin-left:-20ex;'>Success</p>";
				}
				else if(isset($_GET["error"]) && $_GET["error"] == "projectNotFound")
				{
					callJavascript("toggleWindow('joinProjectWindow','inline-block')");
					echo "<p class='error' style='display: inline-block; margin-left:-25ex;'>Invalid Code</p>";
				}
				?>
			</form>
		</div>
		
	<?php
	$projects = listProjectsForDeveloper($con,$_SESSION["usersName"]);
	
	displayProjects($projects);
		
	?>
	</div>
	
	
	
	</div>
	</div>
	</body>
	
<html>