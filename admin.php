<?php 
include_once("User.php");
include_once("Form.php");
require("bdd_pdo.php");


$form_create_user = new Form(array(
	'username', 'email', 'password', 'password_confirmation'));

if ($_POST)
{
	$subError = $form_create_user->checkErrors($_POST);

	if (count($subError) == 0)
	{	
		$username = $_POST["username"];
		$password = $_POST["password"];
		$email = $_POST["email"];
		$newuser = new User($bdd, $username, $password, $email);
		$newuser->subscription();
		$_SESSION["message"] = "Your account has been created";
		header("Location: index.php", true, 301);
	}
}

$sql = 'SELECT * FROM users';

$request_all_users = $bdd->query($sql);
?>


<!DOCTYPE html>
<html>
<head>
	<title>Admin - Dream.me</title>
</head>
<body>

<div class="create-user">
	<form action="admin.php" method="post">
		<?php 
			echo $form_create_user->input_text('username', $_POST);
			if (isset($subError['username']))
				echo $subError['username'];
			echo $form_create_user->input_text('email', $_POST);
			if (isset($subError['email']))
				echo $subError['email'];
			echo $form_create_user->input_password('password', $_POST);
			echo $form_create_user->input_password('password_confirmation', $_POST);
			if (isset($subError['password']))
				echo $subError['password'];
			if (isset($subError['password_confirmation']))
				echo $subError['password_confirmation'];
			echo $form_create_user->submit('Envoyer');
		?>
	</form>
</div>

<div class="table-user">
	<table>
		<tr>
			<th>ID</th>
			<th>Username</th>
			<th>Email</th>
			<th>Modify</th>
			<th>Delete</th>
		</tr>
		<?php
			while ($data = $request_all_users->fetch())
			{
		?>
		<tr>
			<td><?php echo $data["id"]; ?></td>
			<td><?php echo $data["username"]; ?></td>
			<td><?php echo $data["email"]; ?></td>
			<td><a href="modify_user.php">X</a></td>
			<td><a href="delete_user.php">X</a></td>
		</tr>

		<?php
			}
		?>
	</table>


</div>



	
</body>
</html>