<?php

session_start();
include_once("User.php");
include_once("Form_User.php");
require("bdd_pdo.php");

if (isset($_GET["id"]))
{
	$id = $_GET["id"];

	$sql = "SELECT * FROM users WHERE id=" . $id;
	$req = $bdd->query($sql);

	$data = $req->fetch();

	$user = new User($bdd, $data["username"], $data["password"], $data["email"], $data["admin"]);

	$form_modify_user = new Form_User(array('username', 'email', 'password', 'password_confirmation', 'admin'));

	if ($_POST)
	{
		//var_dump($_POST);
		$subError = $form_modify_user->checkErrors($_POST);

		if (count($subError) == 0)
		{	
			$user->set_username($_POST["username"]);
			$user->set_password($_POST["password"]);
			$user->set_email($_POST["email"]);
			$user->update($id);
			header("Location: admin.php", true, 301);
			$_SESSION["message-crud-user"] = "Your account has been updated";
			exit();
		}
	}
}
else
{
	$_SESSION["message-crud-user"] = "Your account has not been updated";
	header("Location : admin.php", true, 301);
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Modify User - Dream.me</title>
		<!-- CDN Materialize -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">

	<!--Import Google Icon Font + google font (police for logo)-->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	
	<link href="https://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet">


	<link rel="stylesheet" type="text/css" href="css/admin_style.css">
</head>
<body>

	<header>
		<div class="navbar-fixed">
		<nav>
		<div class="nav-wrapper">
			<a href="#" class="brand-logo">Dream.me</a>

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


	<div class="container">
		<div class="row">
			<div class="col s8 offset-s2">
				<h3>Modify user</h3>
				<div class="modify-user">
					<form action="" method="post">
						<?php 
							echo $form_modify_user->input_text('username', $user->get_username());
							if (isset($subError['username']))
								echo $subError['username'];
							echo $form_modify_user->input_text('email', $user->get_email());
							if (isset($subError['email']))
								echo $subError['email'];
							echo $form_modify_user->input_password('password');
							echo $form_modify_user->input_password('password_confirmation');
							if (isset($subError['password']))
								echo $subError['password'];
							if (isset($subError['password_confirmation']))
								echo $subError['password_confirmation'];
							echo $form_modify_user->input_checkbox('admin', $user->is_admin());
							echo $form_modify_user->submit('Envoyer');
						?>
					</form>
				</div>
			</div>
		</div>
	</div>
</body>
</html>