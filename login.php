<?php  
session_start();
require "bdd_pdo.php";
include_once("User.php");
include_once("Form_User.php");
	
$loginform = new Form_User(array(
	'email', 'password')
);

if (isset($_COOKIE["username"]) || isset($_SESSION["username"]))
{
	header("Location:./index.php");
}
if (isset($_POST["email"]) && isset($_POST["password"]))
{
	$req = $bdd->query('SELECT 
				username
			FROM 
				users 
			WHERE email ="'.$_POST["email"].'"');
	$data = $req->fetch();
	$email = isset($_POST["email"]);
	$username = $data["username"];
	$user = new User($bdd, $username, $_POST["password"], $_POST["email"]);

	$checkpass = $user->checkPassword($_POST["password"]);

	if ($checkpass == true)
	{
		header("Location: index.php", true, 301);

		$username = $user->get_username();
		$_SESSION["username"] = $username;

		if (isset($_POST["remember_me"]))
		{
			setcookie("username", $username, time() + 365*24*3600, null, null, false, true);
		}
		exit;
	}
	else
	{
		echo "Incorrect email/password";
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<!-- CDN Materialize -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">

	<!--Import Google Icon Font + google font (police for logo)-->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	
	<link href="https://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet">
	<!-- css local -->
	<link rel="stylesheet" href="css/index_style.css">
	<link rel="stylesheet" href="css/form_style.css">
	<link rel="stylesheet" href="css/login_style.css">

	<!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title>Login Dream</title>
</head>
<body class="login login-background">
<div class="container">
<div class="row sign-in">
	<div class="content col2 push2 sheet sheet-page">
		<div class="row">
			<div class="content col4 text-center">
				<a href="#" class="brand-logo">Dream.me</a>
			</div>
		</div>
		<div class="row">
			<div class="content col4 text-center">
            <span class="sign-up-head">Login to your account</span>
         	</div>
        </div>
		<form action="login.php" method="post">
			<?php 
				echo $loginform->input_text('email', isset($_POST['email']) ? $_POST['email'] : null);
				echo $loginform->input_password('password');
				echo $loginform->input_checkbox('remember_me', isset($_POST['remember_me']));
				// echo $loginform->submit('Login');
				echo "<div><button class='btn waves-effect waves-light' type='submit' name='action'>Login
			    </button></div>";
			?>
		</form>


	</div>
</div>
</div>
<div class="row sign-in">
	<div class="content col2 push2">
		<button id="new-acc" class="btn create-account-switch">
		<a href="./subscription.php" id="new-acc">Create a new account!</a></button>
	</div>
</div>

</body>
</html>