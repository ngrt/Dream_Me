<?php 
require "bdd_pdo.php";
include_once("User.php");
include_once("Form_User.php");
session_start();

//Checks if connected or not
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

if (isset($_GET["id"]))
{
	$id = $_GET["id"];

	$modify_password = new Form_User(array(
				'new_password', 'new_password_confirmation', 'password'));



	// if (isset($_POST["password"]) && isset($_POST['new_email']))
	// {
	// 	//var_dump($_POST);
	// 	$subError = $modify_email->checkErrors($_POST);
		

	// 	if (count($subError) == 0)
	// 	{	
	// 		$user->set_email($_POST["email"]);
	// 		$user->update($id);
	// 		$_SESSION["message-update-mail-user"] = "Your email has been updated";
	// 		exit();
	// 	}
	// }

	if (isset($_POST['new_password_confirmation']) && isset($_POST['new_password']) && isset($_POST['password']))
	{

		$passErrors = $modify_password->checkPassUpdateErrors($_POST);

		if (count($passErrors) == 0)
		{	
				$checkpass = $user->checkPassword($_POST["password"]);
				if ($checkpass == true)
				{
					$user->set_password($_POST["password"]);
					$user->update($id);
					echo "Your password has been updated";
				}
				else
					$_SESSION["message-wrong-password"] = "Wrong password";
		}
	}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Modify account - Dream.me</title>
</head>
<body>

	<!-- <form action="modify_account.php" method='post'>
		<?php 
			$modify_email = new Form_User(array(
				'new_email', 'password'));
	

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
						$user->set_email($_POST['new_email']);
						$user->update($id);
						$user->update('email', $_POST['new_email'], $user->get_email()); //$email défini dans $user plus haut
						echo $_SESSION["message-update-mail-user"];
						header("Location: my_account.php", true, 301);
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


	</form> -->
	
	<form action="modify_account.php?id=<?php echo $id; ?>" method='post'>
		
		<?php 
			

			echo $modify_password->input_password('new_password');
			if (isset($passErrors['new_password']))
					echo $passErrors['new_password'];
			echo $modify_password->input_password('new_password_confirmation');
			if (isset($passErrors['new_password_confirmation']))
					echo $passErrors['new_password_confirmation'];
			echo $modify_password->input_password('password');
			if (isset($_SESSION["message-wrong-password"]))
					echo $_SESSION["message-wrong-password"];

			// if (isset($_POST['new_password_confirmation']) && isset($_POST['new_password']))
			// {
				// if ($_POST['new_password_confirmation'] == $_POST['new_password'])
				// {
				// 	$checkpass = $user->checkPassword($_POST["password"]);
				// 	if ($checkpass == true)
				// 	{
				// 		$hash = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
				// 		echo $_SESSION["message-update-password-user"];
				// 	}
				// }
			// }
			echo $modify_password->submit('Modify password');
		 ?>
	</form>




</body>
</html>