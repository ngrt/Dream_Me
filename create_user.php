<?php
session_start();
include_once("User.php");
include_once("Form_User.php");
require("bdd_pdo.php");

if ($_POST)
{

	$form_create_user = new Form_User(array('username', 'email', 'password', 'password_confirmation', 'admin'));
	$subError = $form_create_user->checkErrors($_POST);

	if (count($subError) == 0)
	{	
		$username = $_POST["username"];
		$password = $_POST["password"];
		$email = $_POST["email"];
		isset($_POST["admin"])? $admin = 1 : $admin = 0;
		$newuser = new User($bdd, $username, $password, $email, $admin);
		$newuser->subscription();
		$_SESSION["message-creation"] = "Your account has been created";
		unset($_POST);
		unset($_SESSION['errors']);
		header("Location: admin.php", true, 301);
		exit();
	}
	else
	{
		$_SESSION['errors'] = $subError;
		header("Location: admin.php", true, 301);
		exit();
	}
}

?>