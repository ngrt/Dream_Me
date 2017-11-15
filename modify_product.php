<?php

session_start();
include_once("Product.php");
include_once("Form_Product.php");
require("bdd_pdo.php");

if (isset($_GET["id"]))
{
	$id = $_GET["id"];

	$sql = "SELECT * FROM products WHERE id=" . $id;
	$req = $bdd->query($sql);

	$data = $req->fetch();

	$product = new Product($bdd, $data["name"], $data["price"], $data["category_id"]);

	$form_modify_product = new Form_Product(array('name', 'price', 'category_id'));

	if ($_POST)
	{
		//var_dump($_POST);
		$subError = $form_modify_product->checkErrors($_POST);

		if (count($subError) == 0)
		{	
			$product->set_name($_POST["name"]);
			$product->set_price($_POST["price"]);
			$product->set_category_id($_POST["category_id"]);
			$product->update($id);
			$_SESSION["message-crud-product"] = "The product has been updated";
			unset($_POST);
			header("Location: admin.php", true, 301);
			exit();
		}
	}
}
else
{
	$_SESSION["message-crud-product"] = "The product has not been updated";
	header("Location : admin.php", true, 301);
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Modify User - Dream.me</title>
</head>
<body>
	<div class="modify-product">
		<form action="" method="post">
			<?php 
				echo $form_modify_product->input_text('name', $product->get_name());
				if (isset($subError['name']))
					echo $subError['name'];
				echo $form_modify_product->input_text('price', $product->get_price());
				if (isset($subError['price']))
					echo $subError['price'];
				echo $form_modify_product->input_text('category_id', $product->get_category_id());
				if (isset($subError['category_id']))
					echo $subError['category_id'];
				echo $form_modify_product->submit('Envoyer');
			?>
		</form>
	</div>
</body>
</html>
