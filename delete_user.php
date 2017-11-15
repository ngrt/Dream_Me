<?php
session_start();
include_once("User.php");
include_once("Form.php");
require("bdd_pdo.php");

if (isset($_GET["id"]))
{
	$id = $_GET["id"];

	$sql = "SELECT * FROM users WHERE id=" . $id;
	$req = $bdd->query($sql);

	$data = $req->fetch();

	$user = new User($bdd, $data["username"], $data["password"], $data["email"], $data["admin"]);
	if ($user->delete())
	{
		$_SESSION["message-crud-user"] = "This account has been deleted";
		header("Location: admin.php", true, 301);
		exit();
	}
	else
	{
		$_SESSION["message-crud-user"] = "Error: this account has not been deleted";
		header("Location: admin.php", true, 301);
		exit();
	}
}	
?>