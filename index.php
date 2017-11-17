<?php  
session_start();
require "bdd_pdo.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<!-- CDN Materialize -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">

	<!--Import Google Icon Font + google font (polices)-->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	
	<link href="https://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Roboto:300" rel="stylesheet">
	<!-- css local -->
	<link rel="stylesheet" href="css/index_style.css">

	<!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

	<meta charset="UTF-8">
	<title>Home - Dream.me</title>
</head>
<body>
	<?php include_once("header.php"); ?>
 
<div class="parallax-container">
    <div class="parallax"><img src="img/nebula-img-dream.jpg">
    </div>
</div>
<h2 id="title">Your life sucks, buy yourself a dream</h2>

	<div class="row container"><?php

// Affichage tableau avec produits avec un maximum de produits par page défini par :
	$results_per_page = 3;

	if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };
		$start_from = ($page-1) * $results_per_page;

	$sql = "SELECT * FROM products ORDER BY ID DESC LIMIT $start_from, ".$results_per_page;
	$result = $bdd->query($sql); 
	$i = 0;
		while($dream = $result->fetch()) {
			$i++;
	?>
<!-- FAUT FAIRE : REQUETES SUR LES IMAGES STOCKEES DANS IMG SRC-->
	<div class="row col s12 m6 l4" id="products">
		<a class="modal-trigger" href="#modal<?php echo $i ?>">
		<div class="card z-depth-3">
            <div class="card-image products_card-image">
              	<img src="<?php echo $dream['imgurl']; ?>"/>
              	<span class="shadow"></span>
              	<span class="card-title" style="float: left;"><?php echo $dream['name'] ?><p style="position: absolute;right: 15px;bottom: 0;"><?php echo $dream['price'] ?> $</p> </span>

          	</div>
		</a>
          	<div id="modal<?php echo $i ?>" class="modal">
          		<div class="modal-content">
          			<a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat" style="float: right;">Close</a>
					<a href=<?php echo "cart.php?id=" . $dream["id"]; ?> class="modal-action modal-close waves-effect waves-green btn" style="float: right;">Add to shopping cart</a>

					<h4><?php echo $dream['price'] ?> $</h4>
				</div>
				<div class="modal-content">
					<h4><?php echo $dream['name'] ?></h4>
					<p><?php echo $dream['description'] ?></p>
				</div>
				<div class="modal-content">
					<div class="card-image">  
	              	<img src="<?php echo $dream['imgurl'] ?>"/>
	              </div>
	          </div>
				
          	</div>
        </div>
    </div>
	<?php  
	}; ?> </div>

	
		<?php
		// Pagination
		$req = "SELECT COUNT(ID) AS totalprod FROM products";
		$result = $bdd->query($req);
		$count = $result->fetch(PDO::FETCH_ASSOC);
		$total_pages = ceil($count["totalprod"] / $results_per_page);

		?>
		<div class="row">
		<ul class="pagination"><?php

		for ($i=1; $i<=$total_pages; $i++) {
			//if ($i == $page) 
		    	echo "<li><a href='index.php?page=".$i."'>".$i."</a></li>"; 
		};
		?>
		</ul>
	</div>
	<!-- <style>
		. {
			height: 274px;
			background-color: blue;
		}
	</style> -->
<?php include_once("footer.php"); ?>