<?php 
session_start();
require "bdd_pdo.php";
	if (isset($_COOKIE["username"]))
	{
		$_SESSION["username"] = $_COOKIE["username"];
	}
	if (!isset($_SESSION["username"]) && !isset($_COOKIE["username"]))
	{
		header("Location: ./index.php", true, 301);
		exit;
	}
?>

<!DOCTYPE html>
<html>
<head>
	<!-- CDN Materialize -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">

	<!--Import Google Icon Font + google font (polices)-->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	
	<link href="https://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Roboto:300" rel="stylesheet">
	<!-- css local -->
	<link rel="stylesheet" href="css/index_style.css">
	<link rel="stylesheet" href="css/account_style.css">

	
	<!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

	<title>My Account - Dream.me</title>
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
<div class="container">
	<?php 
	if (isset($_SESSION["username"]))
	{
		$username = $_SESSION["username"];

		$req = $bdd->query('SELECT email, id FROM users WHERE username = "' . $username . '"');
		$data = $req->fetch();
		//var_dump($data);
		$email = $data["email"];
		$id = $data["id"];
		$html = "
		<div class='container'>
		<div class='row sign-in'>
			<div class='content col2 push2 sheet sheet-page'>
			<div class='row'>
			<div class='content col4 text-center'>
				<h4 class='title'>My account</h4>
			</div>
		</div>
				<div class='row'>
					<div class='content col4 text-center'>
		            <span>Username : $username</span>
		       		</div>
		    	</div>
		    	<div class='row'>
					<div class='content col4 text-center'>
		            	<span>Email : $email</span>
		        	</div>
		    	</div>
		    	<div class='row'>
					<div class='content col4 text-center'>
		            	<span>Password : *********</span>
		        	</div>
		    	</div>
		    	<button id='new-acc' class='btn'>
		    	<a id='new-acc' href='modify_account.php?id=" . $id . "'>Modify my informations</a></button></div>";

	}

	echo $html; ?>
	</div>
</body>
</html>