<?php
	include_once("User.php");
	include_once("Form.php");
	require("bdd_pdo.php");

	$sql = "SELECT * FROM users WHERE id=" . $_GET['id'];
	$req = $bdd->query($sql);

	$data = $req->fetch();

	$user = new User($bdd, $data["username"], $data["password"], $data["email"], $data["admin"]);

	//var_dump($user->is_admin());

	$form_modify_user = new Form(array('username', 'email', 'password', 'password_confirmation', 'admin'));

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