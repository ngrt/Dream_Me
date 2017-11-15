<?php
	session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Dream subscribe</title>
</head>
<body>
<?php 
include_once("User.php");
include_once("Form_User.php");
require("bdd_pdo.php");


	if (isset($_COOKIE["username"]))
	{
		$_SESSION["username"] = $_COOKIE["username"];
	}
	if (isset($_SESSION["username"]) && isset($_COOKIE["username"]))
	{
		header("Location: ./index.php");
		exit;
	}

$subscform = new Form_User(array(
	'username', 'email', 'password', 'password_confirmation'));

if ($_POST)
{
	$subError = $subscform->checkErrors($_POST);

	if (count($subError) == 0)
	{	
		$username = $_POST["username"];
		$password = $_POST["password"];
		$email = $_POST["email"];
		$newuser = new User($bdd, $username, $password, $email);
		$newuser->subscription();
		header("Location: index.php", true, 301);
		$_SESSION["message"] = "Your account has been created";
	}
}

?>
<form action="subscription.php" method="post">
	<?php 
		echo $subscform->input_text('username', isset($_POST["username"]) ? $_POST["username"] : null) ;
		if (isset($subError['username']))
			echo $subError['username'];
		echo $subscform->input_text('email', isset($_POST["email"]) ? $_POST["email"] : null);
		if (isset($subError['email']))
			echo $subError['email'];
		echo $subscform->input_password('password');
		echo $subscform->input_password('password_confirmation');
		if (isset($subError['password']))
			echo $subError['password'];
		if (isset($subError['password_confirmation']))
			echo $subError['password_confirmation'];
		echo $subscform->submit('Envoyer');
	?>
</form>


</body>
</html>