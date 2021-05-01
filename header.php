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
				<button class="login_close" type="button" onclick="toggleWindow('loginForm','block')" name="close_login">x</button>
				<form action = "scripts/login-script.php" method="post">
				
				
					<input class="custom-input" type="text" name="user" placeholder = "User-name"><br>
					<input class="custom-input" type="password" name="password" placeholder = "Password"><br>
					
					<button class="login_button" type="text" name="submit">Sign-In</button>
					<?php
					if(isset($_GET['error']) && isset($_SESSION["usersId"])===false)
						{
							require_once 'scripts/functions.php';
							if($_GET['error']!='loginSuccess')
							{
								callJavascript("toggleWindow('loginForm','block')");
							}
							if($_GET['error']=='emptyLoginField')
							{
								echo "<p class='error'>No fields can be empty.</p>";
							}
							else if($_GET['error']=='invalidUser')
							{
								echo "<p class='error'>Username does not exist</p>";
							}
							else if($_GET['error']=='invalidPassword')
							{
								echo "<p class='error'>Invalid password</p>";
							}
						}	
					?>
					
					<p>Don't have an account?</p>
					<a class="register_button" href="register.php">Sign-up</a><br>
					
				
				</form>
	
			</div>
		 </li>
		 
		</ul>
		<ul>

		</ul>
	</nav>
