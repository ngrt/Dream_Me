<?php 
session_start();
require "bdd_pdo.php";
	if (isset($_COOKIE["username"]))
	{
		$_SESSION["username"] = $_COOKIE["username"];
	}
	if (!isset($_SESSION["username"]) || !isset($_COOKIE["username"]))
	{
		header("Location: ./index.php", true, 301);
		exit;
	}
	if (isset($_SESSION["username"]))
	{
		var_dump($_COOKIE);

		$username = $_SESSION["username"];

		$req = $bdd->query('SELECT email FROM users WHERE username = "' . $username . '"');
		$data = $req->fetch();
		//var_dump($data);
		$email = $data["email"];
		$html = "
			<p>My account</p>
			<p>Username : $username</p>
			<p>Email : $email</p>
			<p>Password : *********</p>
			<a href='modify_account.php'>Modify my informations</a>
			";

	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>My Account - Dream.me</title>
</head>
<body>
	<?php echo $html; ?>
</body>
</html>