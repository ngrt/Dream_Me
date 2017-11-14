<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Dream subscribe</title>
</head>
<body>
	<?php 
	include_once("User.php");
	include_once("Form.php");
	require("bdd_pdo.php");


$subscform = new Form(array(
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
	}
}

?>
<form action="subscription.php" method="post">
	<?php 
		echo $subscform->input_text('username');
		if (isset($subError['username']))
			echo $subError['username'];
		echo $subscform->input_text('email');
		if (isset($subError['email']))
			echo $subError['email'];
		echo $subscform->input_password('password');
		echo $subscform->input_password('password_confirmation');
		if (isset($subError['password']))
			echo $subError['password'];
		if (isset($subError['password_confirmation']))
			echo $subError['password_confirmation'];
		echo $subscform->submit('Envoyer');

		
		// $user1 = new User();
		// $username = "Catherine";
		// $password = "root";
		// $email = "catherine@dream.me";
		// $admin = 1;
		// var_dump($user1->checkExist($bdd, $username, $email));
		// $user1->subscription($bdd, $username, $password, $email, $admin);
	?>
</form>


</body>
</html>