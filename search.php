<?php
require "bdd_pdo.php";

$sql = "SELECT categories.name AS cat_name FROM categories";
$categories = $bdd->query($sql);


if (isset($_POST["keywords"]))
{
	$keywords = $_POST["keywords"];

	if ($keywords == "")
	{
		if (isset($_POST["alphabetical"]))
		{
			if ($_POST["alphabetical"] == 'yes')
			{
			$sql = "SELECT products.name, price, categories.name AS cat_name FROM products JOIN categories ON category_id = categories.id ORDER BY products.name";
			}
			else
			{
			$sql = "SELECT products.name, price, categories.name AS cat_name FROM products JOIN categories ON category_id = categories.id ORDER BY products.name DESC";
			}
		}else
		{
			$sql = "SELECT products.name, price, categories.name AS cat_name FROM products JOIN categories ON category_id = categories.id";
		}
	}else
	{
		if (isset($_POST["alphabetical"]))
		{
			if ($_POST["alphabetical"] == 'yes')
			{
				$sql = "SELECT products.name, price, categories.name AS cat_name FROM products JOIN categories ON category_id = categories.id WHERE products.name LIKE '%$keywords%' ORDER BY products.name";
			}
			else
			{
				$sql = "SELECT products.name, price, categories.name AS cat_name FROM products JOIN categories ON category_id = categories.id WHERE products.name LIKE '%$keywords%' ORDER BY products.name DESC";
			}
		}else
		{
			$sql = "SELECT products.name, price, categories.name AS cat_name FROM products JOIN categories ON category_id = categories.id WHERE products.name LIKE '%$keywords%'";
		}
	}
	
	$result_name = $bdd->query($sql);

	try {
		$count = $result_name->rowCount();
	} catch (Exception $e) {
		$count = 0;
	}

	function product_name_with_id($id)
	{
		$sql = "SELECT name FROM products WHERE id = " . $id;
		$product = $bdd->query($sql);

		$name = $product->fetch()["name"];

		$product->closeCursor();

		return $name;
	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Search - Dream.me</title>
</head>
<body>

	<div class="search">
		<form method="post" action="search.php">
			<label>Search</label>
			<input type="text" name="keywords" placeholder="Type the dream name" value=<?php echo isset($_POST["keywords"]) ? $_POST["keywords"] : null; ?>>
			
			<p>
				<label>Sort by name</label>
				<input type="radio" name="alphabetical" value="yes" <?php echo ($_POST["alphabetical"] == "yes") ? "checked" : ""; ?>>Alphabetical order
				<input type="radio" name="alphabetical" value="no" <?php echo ($_POST["alphabetical"] == "no") ? "checked" : ""; ?>>Reverse alphabetical order
			</p>
			<input type="submit" value="Search">
		</form>
	</div>
	<div class="result">
		<p>Found <?php echo $count; ?> result(s)</p>
		<?php
			while ($data = $result_name->fetch())
			{
		?>
				<h2><?php echo $data["name"] ;?></h2>
				<p>Price : <?php echo $data["price"] ;?>$</p>
				<p>Category : <?php echo $data["cat_name"] ;?></p>
		<?php
			}
		?>

	</div>
</body>
</html>