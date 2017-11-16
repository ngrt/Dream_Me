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
		<div class="navbar">
		<nav>
		<div class="nav-wrapper">
		<!-- 	<form method="post" action="search.php">
				<label>Search</label>
				<input type="text" name="keywords" placeholder="Type the dream name">
				<input type="submit" value="Search">
			</div>
		</form> -->
		<ul id="nav-mobile" class="right hide-on-med-and-down">
<!-- ICONE CLIQUABLE SEARCH NAVBAR-->
		<!-- <li><a href="search.php"><i class="material-icons">search</i></a></li> -->
<!-- ESSAI SEARCH BAR IN NAV BAR -->
<!-- 		<li><div class="input-field form-inline">
			<form method="post" action="search.php">
        	<input id="search" type="search" placeholder="Search for a dream" required>
     		<i class="material-icons">close</i>
     		<input type="submit" value="Search">
     		</form>
     	</div></li> -->
		<li>
		<?php
		if (isset($_COOKIE["username"]) || isset($_SESSION["username"]))
		{
			if (!isset($_SESSION["username"]))
			{
				$_SESSION["username"] = $_COOKIE["username"];
			}
			echo "<a href='./logout.php'>Log out</a>";
		?></li><li><?php
			echo "<a href='./my_account.php'>My account</a>";
			$isadmin = $bdd->prepare("SELECT admin FROM users WHERE username = :username");
			$isadmin->execute(array(
				'username' => $_SESSION["username"]));
			$res = $isadmin->fetch();

		?></li><li><?php
			if ($res["admin"] == '1')
			{
				echo "<a href='./admin.php'>Settings [Admin mode]</a>";
			}
		}
		else 
		{
			echo "<a href='./login.php'>Log in - Sign in</a>";
		}

	?></li></ul></div></nav></div>
<div class="container">
	<div class="element-center">
		<a href="#" class="brand-logo">DREAM.me</a>
	</div>
		<ul id="nav-mobile" class="right hide-on-med-and-down">
<!-- ICONE CLIQUABLE -->
		<li><a href="search.php"><i class="material-icons element-right">search</i></a></li>
		</ul>
</div>
	</header>

<!-- 	CAROUSEL -->
	<div class="carousel">
	    <a class="carousel-item" href="#one!"><img src="img/slide1.jpg"></a>
	    <a class="carousel-item" href="#two!"><img src="img/slide2.jpg"></a>
	    <a class="carousel-item" href="#three!"><img src="img/slide3.jpg"></a>
	</div>

<!-- 	SLIDER -->
		<!-- <div class="slider">
			<ul class="slides">
				<li>
					<img src="img/slide1.jpg">
					<div class="caption center-align">
        				<h3>TROLOLO</h3>
      		   			<h5 class="light grey-text text-lighten-3">Super rêve ici testez le tmtc.</h5>
        			</div>
      			</li>
      			<li>
					<img src="img/slide2.jpg">
					<div class="caption center-align">
        				<h3>TITRE COOL ICI</h3>
      		   			<h5 class="light grey-text text-lighten-3">Super rêve ici testez le tmtc.</h5>
        			</div>
      			</li>
			</ul>
		</div> -->


<div class="container"><?php

// Affichage tableau avec produits avec un maximum de produits par page défini par :
	$results_per_page = 3;

	if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };
		$start_from = ($page-1) * $results_per_page;

	$sql = "SELECT name FROM products ORDER BY ID DESC LIMIT $start_from, ".$results_per_page;
	$result = $bdd->query($sql); 

	while($dream = $result->fetch()) {
	?>
<!-- REQUETES SUR LES IMAGES STOCKEES DANS IMG SRC-->
	<div class="dream"><a href="product.php?product=<?php echo $dream['name'] ?>"><img src="img/slide1.jpg"><?php echo $dream['name'] ?></a></div>
	<?php  
	}; ?>

	<footer class="footer">
		<?php
		// Pagination
		$req = "SELECT COUNT(ID) AS totalprod FROM products";
		$result = $bdd->query($req);
		$count = $result->fetch(PDO::FETCH_ASSOC);
		$total_pages = ceil($count["totalprod"] / $results_per_page);

		?><ul class="pagination"><?php

		for ($i=1; $i<=$total_pages; $i++) {
			//if ($i == $page) 
		    	echo "<li><a href='index.php?page=".$i."'>".$i."</a></li>"; 
		};
		?>
		</ul>
	</footer>
	</div>
	<!--Import jQuery before materialize.js-->
        <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>

        <!-- Compiled and minified JavaScript -->
 		<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>
          
        <!--Materializecss Slider-->
       <!--  <script>
            $(document).ready(function () {
                $('.slider').slider({full_width: true});
            });
        </script> -->
        
     	<script>
     		$(document).ready(function(){
      $('.carousel').carousel();
    });
            $('.carousel.carousel-slider').carousel({fullWidth: true});
        </script>
    
</body>
</html>




