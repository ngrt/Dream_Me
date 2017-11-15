<?php
session_start();
include_once("Product.php");
include_once("Form.php");
require("bdd_pdo.php");

if (isset($_GET["id"]))
{
	$id = $_GET["id"];

	$sql = "SELECT * FROM products WHERE id=" . $id;
	$req = $bdd->query($sql);

	$data = $req->fetch();

	$product = new Product($bdd, $data["name"], $data["price"], $data["category_id"]);
	if ($product->delete())
	{
		$_SESSION["message-crud-prod"] = "This product has been deleted";
		header("Location: admin.php", true, 301);
		exit();
	}
	else
	{
		$_SESSION["message-crud-prod"] = "Error: this product has not been deleted";
		header("Location: admin.php", true, 301);
		exit();
	}
}	
?>