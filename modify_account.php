<?php 
require "bdd_pdo.php";
include_once("User.php");
include_once("Form.php");
session_start();

	if (isset($_COOKIE["username"]))
	{
		$_SESSION["username"] = $_COOKIE["username"];
	}
	if (!isset($_SESSION["username"]) && !isset($_COOKIE["username"]))
	{
		header("Location: ./index.php");
		exit;
	}
	if (isset($_SESSION["username"]))
	{
		$username = $_SESSION["username"];

		$req = $bdd->query('SELECT * FROM users WHERE username = "' . $username . '"');
		$data = $req->fetch();
		$password = $data["password"];
		$email = $data["email"];
		$admin = $data["admin"];

		$user = new User($bdd, $username, $password, $email, $admin);
	}

	$modify_email = new Form(array(
		'new_email', 'password'));
	$modify_password = new Form(array(
		'new_password', 'new_password_confirmation', 'password'));

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Modify account - Dream.me</title>
</head>
<body>

	<form action="modify_account.php" method='post'>
		<?php 
			echo $modify_email->input_text('new_email', isset($_POST['new_email']) ? $_POST['new_email'] : $email);

			$sql = 'SELECT EXISTS (SELECT * FROM users WHERE email = :email) AS email_exists';
			$result = $bdd->prepare($sql);
			$req = $result->fetch();
			
			echo $modify_email->input_password('password');
			if (isset($_POST["password"]) && isset($_POST['new_email']))
			{
				if ($req["email_exists"] == false)
				{
					$checkpass = $user->checkPassword($_POST["password"]);
					if ($checkpass == true)
					{
						$user->update('email', $_POST['new_email'], $user->get_email()); //$email défini dans $user plus haut
						$_SESSION["message"] = "Email successfully modified";
						header("Location: admin.php", true, 301);
						exit();
					}
					else
					{
						echo "Wrong password";
					}
				}
			}
			echo $modify_email->submit('Modify email');
		?>


	</form>
	
	<form action="modify_account.php" method='post'>
		
		<?php 
			echo $modify_password->input_password('new_password');
			echo $modify_password->input_password('new_password_confirmation');
			echo $modify_password->input_password('password');

			if (isset($_POST['new_password_confirmation']) && isset($_POST['new_password']))
			{
				if ($_POST['new_password_confirmation'] == $_POST['new_password'])
				{
					$checkpass = $user->checkPassword($_POST["password"]);
					if ($checkpass == true)
					{
						$hash = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
						$user->update('password', $hash, $password); //$email défini dans $user plus haut
						echo "Password successfully modified";
					}
				}
			}
			echo $modify_password->submit('Modify password');
		 ?>
	</form>




</body>
</html>