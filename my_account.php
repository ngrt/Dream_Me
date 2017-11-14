<?php 
session_start();
require "bdd_pdo.php";
	if (isset($_COOKIE["username"]))
	{
		$_SESSION["username"] = $_COOKIE["username"];
	}
	if (!isset($_SESSION["username"]) || !isset($_COOKIE["username"]))
		{
			header("Location:http://localhost/pool_php_d10/ex_05/index.php");
			exit;
		}
	if (isset($_SESSION["username"]))
	{
		$username = $_SESSION["username"];

		$req = $bdd->query('SELECT email FROM users WHERE username = "'.$username.'"');
		$data = $req->fetch();
		$email = $data["email"];
		echo "
			<p>My account</p>
			<p>Username : $username</p>
			<p>Email : $email</p>
			<p>Password : *********</p>
			<a href='modify_account.php'>Modify my informations</a>
			";

	}
	
	



	

?>