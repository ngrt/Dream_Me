<?php
session_start();

$key = array_search($_GET["id"], $_SESSION["cart"]);

if (false !== $key) {
    unset($_SESSION["cart"][$key]);
}

$_SESSION["cart"] = array_values($_SESSION["cart"]);

if (count($_SESSION["cart"]) == 0)
{
	unset($_SESSION["cart"]);
}

// var_dump($_SESSION);

// var_dump(count($_SESSION["cart"]));
header('Location: ' . $_SERVER['HTTP_REFERER']);

?>