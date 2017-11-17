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
	<!-- CDN Materialize -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">

	<!--Import Google Icon Font + google font (polices)-->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	
	<link href="https://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Roboto:300" rel="stylesheet">
	<!-- css local -->
	<link rel="stylesheet" href="css/index_style.css">
	<link rel="stylesheet" href="css/modif_acc_style.css">

	
	<!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<meta charset="UTF-8">
	<title>Modify account - Dream.me</title>
</head>
<body class="acc-background">
	<header>
		<div class="navbar-fixed">
		<nav>
		<div class="nav-wrapper">
			<a href="index.php" class="brand-logo">Dream.me</a>

<!-- MENU NORMAL-->
		<ul class="right hide-on-med-and-down">
	<!-- ICONE CLIQUABLE SEARCH NAVBAR-->
		<li><a href="search.php"><i class="material-icons">search</i></a></li>
	<!-- ICONE CLIQUABLE PANIER-->
		<li><a href="cart.php"><i class="material-icons">shopping_cart</i></a></li>

		<?php
		if (isset($_COOKIE["username"]) || isset($_SESSION["username"]))
		{
			if (!isset($_SESSION["username"]))
			{
				$_SESSION["username"] = $_COOKIE["username"];
			}
			$isadmin = $bdd->prepare("SELECT admin FROM users WHERE username = :username");
			$isadmin->execute(array(
				'username' => $_SESSION["username"]));
			$res = $isadmin->fetch();

		?></li><li><?php
			if ($res["admin"] == '1')
			{
				echo "<a href='./admin.php'>Settings [Admin mode]</a>";
			}
		?></li><li><?php
			echo "<a href='./my_account.php'>My account</a>";
		?></li><li><?php
			echo "<a href='./logout.php'>Log out</a>";
		}
		else 
		{
			?></li><li><?php
			echo "<a href='./login.php' class='waves-effect waves-light btn'>Login</a>";
		}

	?></li></ul>
<!-- UTILISATION DE L'ID mobile-demo POUR ACTIVER LE MENU (en dessous du menu normal)-->
	<a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
<!-- DEBUT MENU HAMBURGER MOBILE-->
		<ul class="side-nav" id="mobile-demo">
	<!-- ICONE CLIQUABLE SEARCH NAVBAR-->
		<li><a href="search.php"><i class="material-icons">search</i>Search your dream</a></li>
	<!-- ICONE CLIQUABLE PANIER-->
		<li><a href="cart.php"><i class="material-icons">shopping_cart</i>Your shopping cart</a></li>

		<?php
		if (isset($_COOKIE["username"]) || isset($_SESSION["username"]))
		{
			if (!isset($_SESSION["username"]))
			{
				$_SESSION["username"] = $_COOKIE["username"];
			}
			$isadmin = $bdd->prepare("SELECT admin FROM users WHERE username = :username");
			$isadmin->execute(array(
				'username' => $_SESSION["username"]));
			$res = $isadmin->fetch();

		?>><li><?php
			if ($res["admin"] == '1')
			{
				echo "<a href='./admin.php'>Settings [Admin mode]</a>";
			}
		?></li><li><?php
			echo "<a href='./my_account.php'>My account</a>";
		?></li><li><?php
			echo "<a href='./logout.php'>Log out</a>";
		}
		else 
		{
			?></li><li><?php
			echo "<a href='./login.php' class='waves-effect waves-light btn'>Login</a>";
		}
		?>
	</li></ul>
<!-- FIN MENU HAMBURGER MOBILE-->
	</div></nav></div>
	</header>

<div class="row sign-in">
	<!-- <div class="content col2 push2 sheet sheet-page"> -->

	<div class="col s12 l6 col2 push2 content sheet sheet-page" id="space-grid">
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
	</div>
	<div class="col s12 l6 col2 push2 content sheet sheet-page" id="space-grid">
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
</div>



</body>
</html>