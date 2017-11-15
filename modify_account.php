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

	$modify_email = new Form_User(array(
				'new_email', 'password'));

	$modify_password = new Form_User(array(
				'new_password', 'new_password_confirmation', 'password'));


if (isset($_GET["id"]))
{
	$id = $_GET["id"];

	if (isset($_POST["password"]) && isset($_POST['new_email']))
	{
		//var_dump($_POST);
		
		$sql = "SELECT EXISTS (SELECT * FROM users WHERE email = :email) AS email_exists";
		$result = $bdd->prepare($sql);
        $result->execute(array('email' => $_POST['new_email']));

        $req = $result->fetch();

        if ($req["email_exists"])
        {
        	echo "This email is already used";
        }

		$mailErrors = $modify_email->checkMailUpdateErrors($_POST);
		if (count($mailErrors) == 0)
		{	
			$checkpass = $user->checkPassword($_POST["password"]);
			if ($checkpass == true)
			{
				$user->set_email($_POST['new_email']);
				$user->update($id);
				echo "Your e-mail has been updated";
			}
			else
			{
				$_SESSION["mail-wrong-password"] = "Wrong password";
			}
		}
	}
//var_dump($_POST);
	if (isset($_POST['new_password_confirmation']) && isset($_POST['new_password']) && isset($_POST['old_password']))
	{

		$passErrors = $modify_password->checkPassUpdateErrors($_POST);

		if (count($passErrors) == 0)
		{	
				$checkpass = $user->checkPassword($_POST["old_password"]);
				if ($checkpass == true)
				{
					var_dump($user);
					$user->set_password($_POST["new_password"]);
					$user->update($id);
					echo "Your password has been updated";
				}
				else
					$_SESSION["pass-wrong-password"] = "Wrong password";
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

	<form action=<?php echo "modify_account.php?id=" . $id; ?> method='post'>
		<?php 	
			echo $modify_email->input_text('new_email', isset($_POST['new_email']) ? $_POST['new_email'] : $email);
			if (isset($mailErrors['new_email']))
					echo $mailErrors['new_email'];
			echo $modify_email->input_password('password');
			if (isset($_SESSION["mail-wrong-password"]))
			{
				echo $_SESSION["mail-wrong-password"];
				unset($_SESSION["mail-wrong-password"]);
			}
			
			echo $modify_email->submit('Modify email');
		?>
	</form> 


	<form action=<?php echo "modify_account.php?id=" . $id; ?> method='post'>
		
		<?php 
			
			echo $modify_password->input_password('new_password');
			if (isset($passErrors['new_password']))
					echo $passErrors['new_password'];
			if (isset($passErrors['password_syntax']))
					echo $passErrors['password_syntax'];
			echo $modify_password->input_password('new_password_confirmation');
			if (isset($passErrors['new_password_confirmation']))
					echo $passErrors['new_password_confirmation'];
			echo $modify_password->input_password('old_password');
			if (isset($_SESSION["pass-wrong-password"]))
			{
				echo $_SESSION["pass-wrong-password"];
				unset($_SESSION["pass-wrong-password"]);
			}

			echo $modify_password->submit('Modify password');
		 ?>
	</form>




</body>
</html>