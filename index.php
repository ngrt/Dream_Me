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
		echo "<p><a href='./login.php'>Log in - Sign in</a></p>";
	?>


</body>
</html>









