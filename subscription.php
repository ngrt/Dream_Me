<?php
	session_start();
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
	<title>Dream subscribe</title>
</head>
<body class="subsc-background">

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

<div class="container">
<div class="row sign-in">
	<div class="content col2 push2 sheet sheet-page">
		<div class="row">
			<div class="content col4 text-center">
				<a href="#.php" class="brand-logo">Dream.me</a>
			</div>
		</div>
		<div class="row">
			<div class="content col4 text-center">
            <span class="sign-up-head">Fill the fields to create your account</span>
         	</div>
        </div>
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
		echo "<div><button class='btn waves-effect waves-light' type='submit' name='action'>Sign in
			</button></div>";
	?>
</form>
</div>
</div>
</div>
<?php include_once("footer.php"); ?>