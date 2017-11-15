<?php  
session_start();
require "bdd_pdo.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Home - Dream.me</title>
</head>
<body>
	<?php 
	if (isset($_COOKIE["username"]) || isset($_SESSION["username"]))
	{
		if (!isset($_SESSION["username"]))
		{
			$_SESSION["username"] = $_COOKIE["username"];
		}
	?>
	<form method="post" action="search.php">
		<label>Search</label>
		<input type="text" name="keywords" placeholder="Type the dream name">
		<input type="submit" value="Search">
	</form>
	<?php
		echo "<p><a href='./logout.php'>Log out</a></p>";
		echo "<p><a href='./my_account.php'>My account</a></p>";
		$isadmin = $bdd->prepare("SELECT admin FROM users WHERE username = :username");
		$isadmin->execute(array(
			'username' => $_SESSION["username"]));
		$res = $isadmin->fetch();

		if ($res["admin"] == '1')
		{
			echo "<p><a href='./admin.php'>Settings [Admin mode]</a></p>";
		}

	}
	else 
	{
		echo "<p><a href='./login.php'>Log in - Sign in</a></p>";
	}
// Affichage tableau avec produits avec un maximum de produits par page défini par :
$results_per_page = 3;

if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };
$start_from = ($page-1) * $results_per_page;

$sql = "SELECT name FROM products ORDER BY ID DESC LIMIT $start_from, ".$results_per_page;
$result = $bdd->query($sql); 

while($dream = $result->fetch()) {
?>
<div><a href="product.php?product=<?php echo $dream['name'] ?>"><?php echo $dream['name'] ?></a></div> <?php  
};
// Pagination
$req = "SELECT COUNT(ID) AS totalprod FROM products";
$result = $bdd->query($req);
$count = $result->fetch(PDO::FETCH_ASSOC);
$total_pages = ceil($count["totalprod"] / $results_per_page);

//counter for pages (we only need 3 displayed pagination so I will do $i -1 | $i | $i + 1)
?><ul><?php

for ($i=1; $i<=$total_pages; $i++) { 
    echo "<li><a href='index.php?page=".$i."'>".$i."</a></li>"; 
};
?>
</ul>
</body>
</html>