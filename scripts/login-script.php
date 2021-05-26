<?php

if(isset($_POST["submit"]))
{
	//pass the data from the form into this script
	$user = $_POST["user"];
	$password = $_POST["password"];
	
	require_once 'database-handler.php';
	require_once 'functions.php';
	
	if(isEmptyLogin($user,$password)===true)
	{
		header("location: ..?error=emptyLoginField");
		/*
		echo '<script type="text/javascript">',
			'toggleLoginWindow()',
			'</script>';
		*/
		
		exit();
	}
	
	loginUser($con,$user,$password);
	
	$data = getNotifications($con,$_SESSION["usersName"]);
	
	if($data->num_rows==0)
		header("location: ..?error=loginSuccess");
	else
		header("location: ../notifications.php");
}
else //redirect user back if he tries to access this page by inputing it on the bar
{
	header("location: ../index.php");
	exit();
}
