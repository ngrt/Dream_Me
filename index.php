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
	<div class="row col s12 m6 l4 align-them">
		<a class="modal-trigger" href="#modal<?php echo $i ?>">
		<div class="card small z-depth-3">
            <div class="card-image">
              	<img src="<?php echo $dream['imgurl']; ?>"/>
              	<span class="card-title"><?php echo $dream['name'] ?></span> 
          	</div>
		</a>
          	<div id="modal<?php echo $i ?>" class="modal">
          		<div class="modal-content">
          			<a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat" style="float: right;">Close</a>
					<a href="cart.php" class="modal-action modal-close waves-effect waves-green btn" style="float: right;">Add to shopping cart</a>

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
<footer class="page-footer">
	<div class="container">
		<div class="row">
			<div class="col l6 s12">
                <h5 class="white-text">Footer Content</h5>
                <p class="grey-text text-lighten-4">You can use rows and columns here to organize your footer content.</p>
            </div>
            <div class="col l4 offset-l2 s12">
                <h5 class="white-text">Links</h5>
                <p class="grey-text text-lighten-4">You can use rows and columns here to organize your footer content.</p>
		</div>
	</div>
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
        
		<!-- Materializecss pop-up cards -->
		<script>
     		$(document).ready(function(){
		    // the "href" attribute of the modal trigger must specify the modal ID that wants to be triggered
		    $('.modal').modal();
		    $('.trigger-modal').modal();
		  });
        </script>
        

        
    
</body>
</html>