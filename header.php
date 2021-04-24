<?php
	session_start(); //make it so that the user is loged in on all pages
?>


<!DOCTYPE html>
<html>
<head>
	 <meta name="viewport" content="width=device-width, initial-scale=1.0">
	 <title>Your trusted Bug-Tracker</title>
	 
	 <link id="pagestyle" rel="stylesheet" type="text/css" href="./style/style.css">
	 <link rel = "stylesheet" href = "./style/capbox.css"> 

	 <script src="scripts/javascript.js"></script>
	 <link rel="shortcut icon" type="image/png" href="img/icons/favicon.png">
	 <link rel = "stylesheet" href="./style/todo.css">
	</head>
	<body onload="toggleWindow('newProjectWindow','inline-block');
	toggleWindow('infoWindow','block');toggleWindow('newIssueWindow','inline-block');changeFlexValue('rightCol','newIssueWindow',3.5,2);">

	
	<div class="menu_logo">
		 <img src="images/icons/logo.svg" alt="Website menu logo">
	</div>
	<nav class= "menu">	 
		<ul>
	     <li><a class="menu_button" href="index.php">About</a></li>
		 <?php
			if(isset($_SESSION["usersId"])===false)
			{
				echo "<li><a class='menu_button' href='register.php'>Sign-Up</a></li>";
				echo "<li style='visibility: hidden;' class='rightSpace'>.</li>";
				echo "<li> <button class='toggle_button' onclick=toggleWindow('loginForm','block') class='menu_button'> Login </button>  </li>";
			}
			else
			{
				echo "<li><a class='menu_button' href='projects.php'>Projects</a></li>";
				?>
					<li>
						<?php
							$url = $_SERVER['REQUEST_URI']; 
							$projectNr = $_GET['project'];
							if(strpos($url, 'todo.php') !== false)
								echo '<a class="menu_button" href="kanban.php?project='.$projectNr.'">Kanban</a>';
							else if (strpos($url, 'kanban.php') !== false)
								echo '<a class="menu_button" href="todo.php?project='.$projectNr.'">Todo</a>';
		 				?>
		 			</li>	
				<?php
				echo "<li style='visibility: hidden;' class='rightSpace'>.</li>";
				echo "<li><a class='menu_button' style='margin-left: auto;' href='scripts/logout-script.php'>Log Out</a></li>";
			}
			?>
		 <li>
			<div id="loginForm">
				
			</div>
		 </li>
		 
		</ul>
		<ul>

		</ul>
	</nav>
