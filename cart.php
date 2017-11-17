<?php

session_start();

if (isset($_SESSION["cart"]))
{
	//echo "cookie déja crée";
	array_push($_SESSION["cart"], $_GET["id"]);

	//var_dump($_SESSION["cart"]);
}
else
{
	//echo "cookie pas créer";
	//var_dump($_GET["id"]);
	$_SESSION["cart"] = array();
	array_push($_SESSION["cart"], $_GET["id"]);
	//var_dump($_SESSION["cart"]);
}

header("Location: index.php");


//echo arrayToRange($_SESSION["cart"]);


?>