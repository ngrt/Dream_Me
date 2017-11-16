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
	<!-- css Materialize -->
<!-- 	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css"> -->

	<!-- css Materialize - icons -->
	<!-- <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> -->

	<title>Search - Dream.me</title>
</head>
<body>
<div class="nav-wrapper">
	<div class="search">
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
				<input type="radio" name="price_sort" value="high" <?php echo (isset($_POST["price_sort"]) && $_POST["price_sort"] == "high") ? "checked" : ""; ?>>High to low
				<input type="radio" name="price_sort" value="low" <?php echo (isset($_POST["price_sort"]) && $_POST["price_sort"] == "low") ? "checked" : ""; ?>>Low to high
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
			<input type="submit" value="Search">
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
</body>
</html>