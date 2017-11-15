<?php 
session_start();
include_once("User.php");
include_once("Product.php");
include_once("Form.php");
include_once("Form_Product.php");
require("bdd_pdo.php");

if (isset($_COOKIE["username"]))
{
	$_SESSION["username"] = $_COOKIE["username"];
}
if (!isset($_SESSION["username"]) && !isset($_COOKIE["username"]))
{
	header("Location: ./index.php");
	exit;
}

$form_create_user = new Form(array('username', 'email', 'password', 'password_confirmation', 'admin'));

$form_create_product = new Form_Product(array('name', 'price', 'category_id'));

$sql = 'SELECT * FROM users WHERE username != "' . $_SESSION["username"] . '"';

$request_all_users = $bdd->query($sql);

$sql = 'SELECT * FROM products';

$request_all_products = $bdd->query($sql);

?>


<!DOCTYPE html>
<html>
<head>
	<title>Admin - Dream.me</title>
</head>
<body>

<div class="users-management">
	<h1>Users Management</h1>
	<div class="create-user">
		<h2>Create a new user</h2>
		<?php 
			if (isset($_SESSION["message-creation"]))
			{
				echo $_SESSION["message-creation"];
				unset($_SESSION["message-creation"]);
				unset($_SESSION["errors"]);
			}
		?>
		<form action="create_user.php" method="post">
			<?php 
				echo $form_create_user->input_text('username', isset($_POST['username']) ? $_POST['username'] : null);
				if (isset($_SESSION['errors']['username']))
					echo $_SESSION['errors']['username'];
				echo $form_create_user->input_text('email', isset($_POST['email']) ? $_POST['email'] : null);
				if (isset($_SESSION['errors']['email']))
					echo $_SESSION['errors']['email'];
				echo $form_create_user->input_password('password');
				echo $form_create_user->input_password('password_confirmation');
				if (isset($_SESSION['errors']['password']))
					echo $_SESSION['errors']['password'];
				if (isset($_SESSION['errors']['password_confirmation']))
					echo $_SESSION['errors']['password_confirmation'];
				echo $form_create_user->input_checkbox('admin', isset($_POST['admin']));
				echo $form_create_user->submit('Envoyer');
			?>
		</form>
	</div>

	<div class="table-user">
		<h2>List of all the members</h2>
		<?php 
			if (isset($_SESSION["message-creation"]))
			{
				echo $_SESSION["message-creation"];
				unset($_SESSION["message-creation"]);
				unset($_SESSION["errors"]);
			}
			if (isset($_SESSION["message-crud-user"]))
			{
				echo $_SESSION["message-crud-user"];
				unset($_SESSION["message-crud-user"]);
				unset($_SESSION["errors"]);
			}
		?>
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
				<td><a href="modify_user.php?id=<?php echo $data["id"]; ?>">X</a></td>
				<td><a href="delete_user.php?id=<?php echo $data["id"]; ?>">X</a></td>
			</tr>

			<?php
				}
			?>
		</table>
	</div>
</div>

<div class="products-management">
	<h1>Products Management</h1>
	<div class="create-product">
		<h2>Create a new product</h2>
		<?php 
			echo isset($_SESSION["message-creation-product"]) ? $_SESSION["message-creation-product"] : null; 
			unset($_SESSION["message-creation-product"])
		?>
		<form action="create_product.php" method="post">
			<?php 
				echo $form_create_product->input_text('name', isset($_POST['name']) ? $_POST['name'] : null);
				if (isset($_SESSION['errors']['name']))
					echo $_SESSION['errors']['name'];
				echo $form_create_product->input_text('price', isset($_POST['price']) ? $_POST['price'] : null);
				if (isset($_SESSION['errors']['price']))
					echo $_SESSION['errors']['price'];
				echo $form_create_product->input_text('category_id', isset($_POST['category_id']) ? $_POST['category_id'] : null);
				if (isset($_SESSION['errors']['category_id']))
					echo $_SESSION['errors']['category_id'];
				echo $form_create_product->submit('Envoyer');
			?>
		</form>
	</div>

	<div class="table-producs">
		<h2>List of all the products</h2>
		<?php 
			echo isset($_SESSION["message-crud-product"]) ? $_SESSION["message-crud-product"] : null; 
			unset($_SESSION["message-crud-product"])
		?>
		<table>
			<tr>
				<th>ID</th>
				<th>Name</th>
				<th>Price ($)</th>
				<th>Category_id</th>
				<th>Modify</th>
				<th>Delete</th>
			</tr>
			<?php
				while ($data = $request_all_products->fetch())
				{
			?>
			<tr>
				<td><?php echo $data["id"]; ?></td>
				<td><?php echo $data["name"]; ?></td>
				<td><?php echo $data["price"]; ?></td>
				<td><?php echo $data["category_id"]; ?></td>
				<td><a href="modify_product.php?id=<?php echo $data["id"]; ?>">X</a></td>
				<td><a href="delete_product.php?id=<?php echo $data["id"]; ?>">X</a></td>
			</tr>

			<?php
				}
			?>
		</table>
	</div>
</div>

	
</body>
</html>