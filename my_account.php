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
	<link rel="stylesheet" href="css/login_style.css">

	
	<!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

	<title>My Account - Dream.me</title>
</head>
<body class="acc-background">
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
				<span class='title'>My account</span>
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
		    	<a id='new-acc' href='modify_account.php?id=" . $id . "'>Modify my informations</a></div>";

	}

	echo $html; ?>
<div class='container'>
		<div class='row sign-in'>
			<div class='content col2 push2 sheet sheet-page'>
			<div class='row'>
			<div class='content col4 text-center'>
				<span class='title'>My account</span>
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
		    	<a id='new-acc' href='modify_account.php?id=" . $id . "'>Modify my informations</a></div></div></div>
</body>
</html>