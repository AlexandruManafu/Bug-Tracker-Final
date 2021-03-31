<?php

if(isset($_POST["submit"]))
{
	//pass the data from the form int othis script
	$user = $_POST["user"];
	$email = $_POST["email"];
	$password = $_POST["pwd"];
	$passwordAgain = $_POST["pwdrepeat"];
	$type = $_POST["type"];
	
	//echo($type); works
	
	require_once 'database-handler.php';
	require_once 'functions.php';
	
	if(isEmpty($user,$email,$password,$passwordAgain,$type)===true)
	{
		header("location: ../register.php?error=emptyField");
	}
	elseif(invalidUsername($user)===true)
	{
		header("location: ../register.php?error=invalidUsername");
	}
	elseif(usernameExists($con,$user)!==false)
	{
		header("location: ../register.php?error=usernameExists");
	}
	elseif(invalidEmail($email)===true)
	{
		header("location: ../register.php?error=invalidEmail");
	}
	elseif(invalidPassword($password,$passwordAgain)===true)
	{
		header("location: ../register.php?error=invalidPassword");
	}
	else createUser($con,$user,$email,$password,$type);
	
}
else //redirect user back if he tries to access this page by inputing it on the bar
{
	header("location: ../register.php");
	exit();
}