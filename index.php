<?php  
session_start();
require "bdd_pdo.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<!-- CDN Materialize -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">

	<!--Import Google Icon Font + google font (police for logo)-->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	
	<link href="https://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet">
	<!-- css local -->
	<link rel="stylesheet" href="css/index_style.css">

	<!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

	<meta charset="UTF-8">
	<title>Home - Dream.me</title>
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
 
<div class="parallax-container">
    <div class="parallax"><img src="img/nebula-img-dream.jpg">
    </div>
</div>
<!-- <h2 id="title">Dream whatever you want</h2> -->

	<div class="row container"><?php

// Affichage tableau avec produits avec un maximum de produits par page défini par :
	$results_per_page = 3;

	if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };
		$start_from = ($page-1) * $results_per_page;

	$sql = "SELECT name FROM products ORDER BY ID DESC LIMIT $start_from, ".$results_per_page;
	$result = $bdd->query($sql); 

	while($dream = $result->fetch()) {
	?>
<!-- REQUETES SUR LES IMAGES STOCKEES DANS IMG SRC-->
	<div class="row col s12 m6 l4">
		<div class="card z-depth-3">
            <div class="card-image">
              	<a href="product.php?product=<?php echo $dream['name'] ?>"><img src="img/slide1.jpg"><span class="card-title"><?php echo $dream['name'] ?></span>
              </a>
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
<footer>
</footer>
	</div>
</div>
	<!--Import jQuery before materialize.js-->
        <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>

        <!-- Compiled and minified JavaScript -->
 		<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>
          
        <!--Materializecss Parallax JS script-->
     	<script>
     		 $(document).ready(function(){
      $('.parallax').parallax();
    });
        </script>

		<!--Materializecss mobile sidemenu JS script-->
		<script>
     		$(".button-collapse").sideNav();
        </script>

        <style>
        	#sidenav-overlay
        	{
        		z-index: -1;
        	}

        </style>
        
    
</body>
</html>




