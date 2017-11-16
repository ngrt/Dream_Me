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

	$product = new Product($bdd, $data["name"], $data["price"], $data["category_id"], $data["imgurl"]);

	$form_modify_product = new Form_Product(array('name', 'price', 'category_id', 'imgurl'));

	if ($_POST)
	{
		//var_dump($_POST);
		$subError = $form_modify_product->checkErrors($_POST);

		if (count($subError) == 0)
		{	
			$product->set_name($_POST["name"]);
			$product->set_price($_POST["price"]);
			$product->set_category_id($_POST["category_id"]);
			$product->set_imgurl($_POST["imgurl"]);
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

$sql = "SELECT * FROM categories";

$categories_req = $bdd->query($sql);

$data = $categories_req->fetchAll();

function buildTree(array &$elements, $parentId = 0) {

    $branch = array();

    foreach ($elements as &$element) {

        if ($element['parent_id'] == $parentId) {
            $children = buildTree($elements, $element['id']);
            if ($children) {
                $element['children'] = $children;
            }
            $branch[$element['id']] = $element;
            unset($element);
        }
    }
    return $branch;
}

function printTree($tree, $r = 0, $p = null) {

    foreach ($tree as $i => $t) {
        $dash = ($t['parent_id'] == 0) ? '' : str_repeat('-', $r) .' ';

        printf("\t<option value='%d'>%s%s</option>\n", $t['id'], $dash, $t['name']);

        if (isset($t['children'])) {
            printTree($t['children'], $r+1, $t['parent_id']); 
        }
    }
}

function recursiveCategories($array) {

    if (count($array)) {
            echo "\n<ul>\n";
        foreach ($array as $vals) {

                    echo "<li id=\"".$vals['name']."\">".$vals['name'];
                    if (isset($vals['children'])) {
                            recursiveCategories($vals['children']);
                    }
                    echo "</li>\n";
        }
            echo "</ul>\n";
    }
} 

$tree = buildTree($data);

?>
<!DOCTYPE html>
<html>
<head>
	<title>Modify Product - Dream.me</title>
			<!-- CDN Materialize -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">

	<!--Import Google Icon Font + google font (police for logo)-->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	
	<link href="https://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet">


	<link rel="stylesheet" type="text/css" href="css/admin_style.css">
</head>
<body>


	<header>
		<div class="navbar-fixed">
		<nav>
		<div class="nav-wrapper">
			<a href="#" class="brand-logo">Dream.me</a>

<!-- MENU NORMAL-->
		<ul class="right hide-on-med-and-down">
	<!-- ICONE CLIQUABLE SEARCH NAVBAR-->
		<li><a href="search.php"><i class="material-icons">search</i></a></li>
	<!-- ICONE CLIQUABLE PANIER-->
		<li><a href="cart.php"><i class="material-icons">shopping_cart</i></a></li>

		<?php
		if (isset($_COOKIE["username"]) || isset($_SESSION["username"]))
		{
			if (!isset($_SESSION["username"]))
			{
				$_SESSION["username"] = $_COOKIE["username"];
			}
			$isadmin = $bdd->prepare("SELECT admin FROM users WHERE username = :username");
			$isadmin->execute(array(
				'username' => $_SESSION["username"]));
			$res = $isadmin->fetch();

		?></li><li><?php
			if ($res["admin"] == '1')
			{
				echo "<a href='./admin.php'>Settings [Admin mode]</a>";
			}
		?></li><li><?php
			echo "<a href='./my_account.php'>My account</a>";
		?></li><li><?php
			echo "<a href='./logout.php'>Log out</a>";
		}
		else 
		{
			?></li><li><?php
			echo "<a href='./login.php' class='waves-effect waves-light btn'>Login</a>";
		}

	?></li></ul>
<!-- UTILISATION DE L'ID mobile-demo POUR ACTIVER LE MENU (en dessous du menu normal)-->
	<a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
<!-- DEBUT MENU HAMBURGER MOBILE-->
		<ul class="side-nav" id="mobile-demo">
	<!-- ICONE CLIQUABLE SEARCH NAVBAR-->
		<li><a href="search.php"><i class="material-icons">search</i>Search your dream</a></li>
	<!-- ICONE CLIQUABLE PANIER-->
		<li><a href="cart.php"><i class="material-icons">shopping_cart</i>Your shopping cart</a></li>

		<?php
		if (isset($_COOKIE["username"]) || isset($_SESSION["username"]))
		{
			if (!isset($_SESSION["username"]))
			{
				$_SESSION["username"] = $_COOKIE["username"];
			}
			$isadmin = $bdd->prepare("SELECT admin FROM users WHERE username = :username");
			$isadmin->execute(array(
				'username' => $_SESSION["username"]));
			$res = $isadmin->fetch();

		?>><li><?php
			if ($res["admin"] == '1')
			{
				echo "<a href='./admin.php'>Settings [Admin mode]</a>";
			}
		?></li><li><?php
			echo "<a href='./my_account.php'>My account</a>";
		?></li><li><?php
			echo "<a href='./logout.php'>Log out</a>";
		}
		else 
		{
			?></li><li><?php
			echo "<a href='./login.php' class='waves-effect waves-light btn'>Login</a>";
		}
		?>
	</li></ul>
<!-- FIN MENU HAMBURGER MOBILE-->
	</div></nav></div>
	</header>

	<div class="container">
		<div class="row">
			<div class="col s8 offset-s2">
				<h3>Modify product</h3>
				<form action="" method="post">
					<?php 
						echo $form_modify_product->input_text('name', $product->get_name());
						if (isset($subError['name']))
							echo $subError['name'];
						echo $form_modify_product->input_text('price', $product->get_price());
						if (isset($subError['price']))
							echo $subError['price'];
						echo $form_modify_product->input_text('imgurl', $product->get_imgurl());
						?>
						<label>Category id</label>
						<select name="category_id" class="browser-default"> 
					<?php
								printTree($tree);
					?>
						</select>
					<?php
						if (isset($subError['category_id']))
							echo $subError['category_id'];
						echo $form_modify_product->submit('Modify');
					?>
				</form>
			</div>
		</div>
	</div>
</body>
</html>
