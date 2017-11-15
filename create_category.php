<?php
session_start();
include_once("Category.php");
include_once("Form_Category.php");
require("bdd_pdo.php");

if ($_POST)
{
	$form_create_category = new Form_Category(array('name', 'parent_id'));
	$subError = $form_create_category->checkErrors($_POST);

	if (count($subError) == 0)
	{	
		$name = $_POST["name"];
		if ($_POST["parent_id"] == "")
		{
			$parent_id = null;
		}
		else{
			$parent_id = $_POST["parent_id"];
		} 
		$newproduct = new Category($bdd, $name, $parent_id);
		$newproduct->insert();
		$_SESSION["message-creation-cat"] = "Your category has been created";
		unset($_POST);
		unset($_SESSION['errors']);
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