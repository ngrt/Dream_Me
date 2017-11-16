<?php
session_start();

include_once("Product.php");
include_once("Form.php");
include_once("Form_Product.php");
require("bdd_pdo.php");

if ($_POST)
{
	//var_dump($_POST);
	$form_create_product = new Form_Product(array('name', 'price', 'category_id', 'imgurl'));
	$subError = $form_create_product->checkErrors($_POST);

	if (count($subError) == 0)
	{	
		$name = $_POST["name"];
		$price = $_POST["price"];
		$category_id = $_POST["category_id"];
		$imgurl = $_POST["imgurl"];

		$newproduct = new Product($bdd, $name, $price, $category_id, $imgurl);
		$newproduct->insert();
		$_SESSION["message-creation-product"] = "The product has been created";
		unset($_POST);
		header("Location: admin.php", true, 301);
		exit();
	}
	else
	{
		$_SESSION['errors'] = $subError;
		header("Location: admin.php", true, 301);
		exit();
	}
}

?>