<?php

$servername = 'localhost';
$DBuser = "root";
$DBpassword = "";
$DBname = "bugtracker";

$con=mysqli_connect($servername,$DBuser,$DBpassword,$DBname);

if(!$con)
{
	die("Connection Failed ". mysqli_connect_error());
}

require_once 'functions.php';

/*
if(isDbEmpty($con,$DBname)===true)
{
	createDefualtTables($con);
}
*/



