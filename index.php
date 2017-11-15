<?php  
session_start();
require "bdd_pdo.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Home - Dream.me</title>
</head>
<body>
	<?php 
	if (isset($_COOKIE["username"]) || isset($_SESSION["username"]))
	{
		if (!isset($_SESSION["username"]))
		{
			$_SESSION["username"] = $_COOKIE["username"];
		}
	?>
	<form method="post" action="search.php">
		<label>Search</label>
		<input type="text" name="keywords" placeholder="Type the dream name">
		<input type="submit" value="Search">
	</form>
	<?php
		echo "<p><a href='./logout.php'>Log out</a></p>";
		echo "<p><a href='./my_account.php'>My account</a></p>";
		$isadmin = $bdd->prepare("SELECT admin FROM users WHERE username = :username");
		$isadmin->execute(array(
			'username' => $_SESSION["username"]));
		$res = $isadmin->fetch();

		if ($res["admin"] == '1')
		{
			echo "<p><a href='./admin.php'>Settings [Admin mode]</a></p>";
		}

	}
	else 
	{
		echo "<p><a href='./login.php'>Log in - Sign in</a></p>";
	}

?>


</body>
</html>









