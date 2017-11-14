<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Dream subscribe</title>
</head>
<body>
	<?php 
	include_once("User.php");

		try {
			$bdd = new PDO("mysql:host=localhost;dbname=pool_php_rush", "root", "root");
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
		$user1 = new User();
		$username = "Nouf";
		$password = "root";
		$email = "noufel@dream.me";
		$admin = 1;
		var_dump($user1->checkExist($bdd, $username, $email));
		$user1->subscription($bdd, $username, $password, $email, $admin);


	 ?>
</body>
</html>