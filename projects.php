<?php
include('header.php');
?>
	
	<div class="wrapper">
	<div class="content">
	<?php
	require_once "scripts/functions.php";
		//echo $_SESSION["usersType"];
	if(!isset($_SESSION["usersType"]))
	{
			header("location: ./index.php");
	}
	else if(strcmp($_SESSION["usersType"],"manager")==0)
	{
			//echo "<p>yes</p>";
	echo "<div class='projectWindow'>";
			echo "<img class='projectIcon' onclick=toggleWindow('newProjectWindow','inline-block') src='images/icons/add.svg' alt='Create Project' width = 10%>";
			echo "<p>New Project</p>";
		
		
		echo "<div id='newProjectWindow'>";
		echo "<form action = 'scripts/createProject-script.php' method='post'>";
			echo "<input class='bigger-custom-input' type='text' name='projectTitle' style='margin-left:0.5ex;' placeholder = 'Project Title'><br>";
			echo "<button class='create_button' type='text' name='submit'>Create</button>";

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

			echo "</form>";
		echo "</div>";
	}
	else if(strcmp($_SESSION["usersType"],"developer")==0)
	{
		echo "<div class='projectWindow'>";
			echo "<img class='projectIcon' onclick=toggleWindow('newProjectWindow','inline-block') src='images/icons/add.svg' alt='Join Project' width = 10%>";
			echo "<p>Join Project</p>";
		
		
		echo "<div id='newProjectWindow'>";
		echo "<form action = 'scripts/joinProject-script.php' method='post'>";
			echo "<input class='bigger-custom-input' type='text' name='projectCode' style='margin-left:2.5ex;' placeholder = 'Project Code'><br>";
			echo "<button class='create_button' type='text' style='margin-left: 17ex;' name='submit'>Join Project</button>";

				if(isset($_GET["error"]) && $_GET["error"] == "projectJoined")
				{
					callJavascript("toggleWindow('newProjectWindow','inline-block')");
					echo "<p class='sign-upSuccess' style='display: inline-block; margin-left:-20ex;'>Success</p>";
				}
				else if(isset($_GET["error"]) && $_GET["error"] == "projectNotFound")
				{
					callJavascript("toggleWindow('newProjectWindow','inline-block')");
					echo "<p class='error' style='display: inline-block; margin-left:-25ex;'>Invalid Code</p>";
				}

			echo "</form>";
		echo "</div>";
		
	}
		
		
	echo "</div>";

	require_once 'scripts/database-handler.php';
	require_once 'scripts/functions.php';
	if(strcmp($_SESSION["usersType"],"manager")==0)
	{
		$projects = listProjectsForManager($con,$_SESSION["usersName"]);
	}
	else if(strcmp($_SESSION["usersType"],"developer")==0)
	{
		$projects = listProjectsForDeveloper($con,$_SESSION["usersName"]);
	}
	
	while($row = mysqli_fetch_array($projects))
	{
		$projectId = $row["projectId"];
		$projectName = $row["projectName"];
		$projectCode = $row["projectCode"];
		$_SESSION['projectCode'] = $projectCode;
		
		echo "<div class='projectWindow'>";
			echo "<a href='project.php?project=".$projectId."'> <img class='projectIcon' src='images/icons/proj.svg' alt='Browse Project' width = 10%></a>";
			echo "<p>".shortenDisplay($projectName,10)."</p>";
		echo "</div>";
	}
	?>
	
	
	
	</div>
	</div>
	</body>
	
<html>