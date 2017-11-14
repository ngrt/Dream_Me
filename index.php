<?php  
session_start();
require "bdd_pdo.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Dream Homepage</title>
</head>
<body>
	<?php 
	if (isset($_COOKIE["username"]) || isset($_SESSION["username"]))
	{
		echo "<p><a href='./logout.php'>Log out</a></p>";
		echo "<p><a href='./my_account.php'>My account</a></p>";
	}
	else 
	{
		echo "<p><a href='./login.php'>Log in - Sign in</a></p>";
	}

	$isadmin = $bdd->prepare("SELECT is_admin FROM users WHERE name = :name");
	$isadmin->execute(array(
		'name' => $_SESSION["name"]));
	$res = $isadmin->fetch();

	if ($res["is_admin"] == '1')
	{
		echo "<p><a href='./admin.php'>Settings</a></p>";
	}
	?>


</body>
</html>









