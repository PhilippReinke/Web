<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION["name"]))
{
	$_SESSION["name"]="notSet";
}
if (!isset($_SESSION["password"]))
{
	$_SESSION["password"]="notSet";
} 

// get session variables
$name = $_SESSION["name"];
$passoword = $_SESSION["password"];

echo json_encode(["name" => $name, "password" => $passoword]);
?>