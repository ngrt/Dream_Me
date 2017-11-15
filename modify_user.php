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
</head>
<body>
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
</body>
</html>