<?php
require "bdd_pdo.php";

$sql = "SELECT id, categories.name AS cat_name FROM categories";
$categories = $bdd->query($sql);

function sort_category($POST)
{
	$string = "";

	if (isset($POST["category_id"]))
	{
		if ($POST["category_id"] == "all")
		{
			return $string = "1 = 1";
		}

		if ($POST["category_id"])
		{
			$string = "category_id = " . $POST["category_id"];
		}
	}
	return $string;
}

if (isset($_POST["keywords"]))
{
	$keywords = $_POST["keywords"];

	if ($keywords == "")
	{
		if (isset($_POST["price_sort"]))
		{
			if ($_POST["price_sort"] == 'high')
			{ 
			$sql = "SELECT products.name, price, categories.name AS cat_name FROM products JOIN categories ON category_id = categories.id WHERE " . sort_category($_POST) . " ORDER BY price DESC";
			}
			else
			{
			$sql = "SELECT products.name, price, categories.name AS cat_name FROM products JOIN categories ON category_id = categories.id WHERE " . sort_category($_POST) . " ORDER BY price";
			}
		}else
		{
			$sql = "SELECT products.name, price, categories.name AS cat_name FROM products JOIN categories ON category_id = categories.id WHERE " . sort_category($_POST);
		}
	}else
	{
		if (isset($_POST["price_sort"]))
		{
			if ($_POST["price_sort"] == 'high')
			{
				$sql = "SELECT products.name, price, categories.name AS cat_name FROM products JOIN categories ON category_id = categories.id WHERE " . sort_category($_POST) . " AND products.name LIKE '%$keywords%' ORDER BY price";
			}
			else
			{
				$sql = "SELECT products.name, price, categories.name AS cat_name FROM products JOIN categories ON category_id = categories.id WHERE " . sort_category($_POST) . " AND products.name LIKE '%$keywords%' ORDER BY price DESC";
			}
		}else
		{
			$sql = "SELECT products.name, price, categories.name AS cat_name FROM products JOIN categories ON category_id = categories.id WHERE " . sort_category($_POST) . " AND products.name LIKE '%$keywords%'";
		}
	}


	$result_name = $bdd->query($sql);

	if ($result_name)
	{
		$count = $result_name->rowCount();
	}
	else
	{
		$count = 0;
	}

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

function printTree($tree, $r = 2, $p = null) {

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
	<!-- CDN Materialize -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">
  
	<!--Import Google Icon Font + google font (police for logo)-->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<!-- css local -->
	<link rel="stylesheet" href="css/index_style.css">
	<link rel="stylesheet" href="css/form_style.css">
	
	<link href="https://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet">

	<!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

	<meta charset="UTF-8">
	<title>Search - Dream.me</title>
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
	<div class="search row">
		<form method="post" action="search.php">
			<label>Search</label>
			<input type="text" name="keywords" placeholder="Type the dream name" value=<?php echo isset($_POST["keywords"]) ? $_POST["keywords"] : null; ?>>
		<!--
			<p>
				<label>Sort by name</label>
				<input type="radio" name="alphabetical" value="yes" <?php echo (isset($_POST["alphabetical"]) && $_POST["alphabetical"] == "yes") ? "checked" : ""; ?>>Alphabetical order
				<input type="radio" name="alphabetical" value="no" <?php echo (isset($_POST["alphabetical"]) && $_POST["alphabetical"] == "no") ? "checked" : ""; ?>>Reverse alphabetical order
			</p>
		-->

		
				<p>
				<label>Sort by price</label>
				<input type="radio" name="price_sort" id="radio1" value="high" <?php echo (isset($_POST["price_sort"]) && $_POST["price_sort"] == "high") ? "checked" : ""; ?>><label for="radio1">High to low</label>
				<input type="radio" name="price_sort" id="radio2" value="low" <?php echo (isset($_POST["price_sort"]) && $_POST["price_sort"] == "low") ? "checked" : ""; ?>><label for="radio2">Low to high</label>
				</p>
	

		
				<p>
				<label>Sort by category</label>
				<select name="category_id"> 
					<option value="all">All</option>
			<?php
				printTree($tree);
			?>
			
				</select>
				</p>
			<div class="row">
			    <button class="search-btn btn waves-effect waves-light" type="submit" name="action">Search dream
			        <i class="material-icons right">search</i>
			    </button>
			</div>
		</form>
	</div>
</div>
	<div class="result">
		
		<?php
			if ($_POST)
			{
		?>
				<p>Found <?php echo $count; ?> result(s)</p>
		<?php
				if ($count != 0)
			{
				while ($data = $result_name->fetch(PDO::FETCH_ASSOC))
				{
			?>
					<h2><?php echo $data["name"] ;?></h2>
					<p>Price : <?php echo $data["price"] ;?>$</p>
					<p>Category : <?php echo $data["cat_name"] ;?></p>
			<?php
				}
			}
			}
			
		?>

	</div>
<!--Import jQuery before materialize.js-->
        <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>

        <!-- Compiled and minified JavaScript -->
 		<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>
          
<!-- SCRIPT JS POUR MATERIALIZE SELECT -->
	<script>
         $(document).ready(function() {
            $('select').material_select();
         });
      </script>
</body>
</html>