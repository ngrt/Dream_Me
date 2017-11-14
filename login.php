<?php  
	require "bdd_pdo.php";
	include_once("User.php");
	include_once("Form.php");
	session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Login Dream</title>
</head>
<body>
	<?php 

		$loginform = new Form(array(
			'email', 'password')
		);

		if (isset($_COOKIE["username"]))
		{
			header("Location:./index.php");
		}
		if (isset($_POST["email"]) && isset($_POST["password"]))
		{
			//var_dump($_POST);
			$req = $bdd->query('SELECT 
						username
					FROM 
						users 
					WHERE email ="'.$_POST["email"].'"');
			$data = $req->fetch();
			$username = $data["username"];
			$email = isset($_POST["email"]);
			$user = new User($bdd, $username, $_POST["password"], $_POST["email"]);

			$checkpass = $user->checkPassword($_POST["password"]);

			if ($checkpass == true)
			{
				$name = $user->get_username();
				$_SESSION["name"] = $name;

				if (isset($_POST["remember_me"]))
				{
					setcookie("username", $name, time() + 365*24*3600, null, null, false, true);
				}
				//echo "tru3";
				header("Location:./index.php", true, 301);
				exit;
			}
			else
			{
				echo "Incorrect email/password";
			}
		}
	?>

	<p>If you have an account, log in:</p>
	<form action="login.php" method="post">
		<?php 
			echo $loginform->input_text('email', $_POST['email']);
			echo $loginform->input_password('password');
			echo $loginform->input_checkbox('remember_me', isset($_POST['remember_me']));
			echo $loginform->submit('Envoyer');
		?>
	</form>


	<p>New customer</p>
	<p>Register now for faster shopping next time you order. You can also manage your wish list, redeem e-vouchers and select preferences.</p>
	<a href="./subscription.php">Register now</a>

</body>
</html>

