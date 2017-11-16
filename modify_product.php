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
				?>
				<label>Category id</label>
				<select name="category_id"> 
					<option value="all">All</option>
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
</body>
</html>
