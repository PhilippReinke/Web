<?php
// Fetch data
header('Content-Type: application/json');
$name = trim($_GET['name'] ?? '');
$password = trim($_GET['password'] ?? '');

// Start the session
session_start();

// Set session variables
$_SESSION["name"] = $name;
$_SESSION["password"] = $password;

// check if session variables have been set
$checkName = True;
if (!isset($_SESSION["name"])) 
{
	$checkName = False;
}
$checkPassword = True;
if (!isset($_SESSION["name"])) 
{
	$checkPassword = False;
}

// Everything ok
echo json_encode(["name ok" => $checkName, "password ok" => $checkPassword]);
?>