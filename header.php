<header>

		<div class="navbar-fixed">
		<nav>

		<div class="nav-wrapper">
			<a href="index.php" class="brand-logo">Dream.me</a>
	<?php 

		function arrayToRange($arr)
		{
			$str = "(";

			for ($i=0; $i < count($arr) ; $i++) { 
				$str .= $arr[$i];

				if ($i == count($arr) - 1)
				{
					$str .= ")";
				}
				else
				{
					$str .= ",";
				}
			}
			return $str;
		}

		if (isset($_SESSION["cart"]) && count($_SESSION["cart"]) != 0)
		{

			$sql = "SELECT id, name, price FROM products WHERE id IN " . arrayToRange($_SESSION["cart"]);
			$itemsInCard = $bdd->query($sql);
	?>

	<ul id="dropdown2" class="dropdown-content" style="min-width: 250px; margin-top: 5%; padding: 2%">
	<?php
		$sum = 0;
		while ($item = $itemsInCard->fetch()) 
		{
	?>
			<li><?php echo $item['name'] . " " . $item['price'] . "$"; ?><a href=<?php echo "delete-cart.php?id=" . $item['id']; ?>><i class="material-icons">delete</i></a></li>
	<?php
		$sum += $item['price'];
		}
	
	 ?>
	  <li class="divider"></li>
	  <li><?php echo "Total " . $sum . "$";?></li>
	  <li><a class="waves-effect waves-light btn" href="#">Order now</a></li>
	</ul>

	<?php 
		} 
	?>
<!-- MENU NORMAL-->
		<ul class="right hide-on-med-and-down">
	<!-- ICONE CLIQUABLE SEARCH NAVBAR-->
		<li><a href="search.php"><i class="material-icons">search</i></a></li>
	<!-- ICONE CLIQUABLE PANIER-->
		<li><a class="dropdown-button" href="#!" data-activates="dropdown2"><i class="material-icons">shopping_cart</i></a></li>

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

	?>
		
	</li>
</ul>
<!-- UTILISATION DE L'ID mobile-demo POUR ACTIVER LE MENU (en dessous du menu normal)-->
	<a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
<!-- DEBUT MENU HAMBURGER MOBILE-->
		<ul class="side-nav" id="mobile-demo">
	<!-- ICONE CLIQUABLE SEARCH NAVBAR-->
		<li><a href="search.php"><i class="material-icons">search</i>Search your dream</a></li>
	<!-- ICONE CLIQUABLE PANIER-->

	<?php
	if (isset($_SESSION["cart"]))
		{

			$sql = "SELECT id, name, price FROM products WHERE id IN " . arrayToRange($_SESSION["cart"]);
			//echo $sql;
			$itemsInCard = $bdd->query($sql);
	?>

	<ul id="dropdown3" class="dropdown-content" style="min-width: 250px; margin-top: 5%; padding: 2%">
	<?php
		$sum = 0;
		while ($item = $itemsInCard->fetch()) 
		{
	?>
			<li><?php echo $item['name'] . " " . $item['price'] . "$"; ?><a href=<?php echo "delete-cart.php?id=" . $item['id']; ?>><i class="material-icons">delete</i></a></li>
	<?php
		$sum += $item['price'];
		}
	
	 ?>
	  <li class="divider"></li>
	  <li><?php echo "Total " . $sum . "$";?></li>
	  <li><a class="waves-effect waves-light btn" href="#">Order now</a></li>
	</ul>

	<?php 
		} 
	?>


		<li><a class="dropdown-button" data-activates="dropdown3" href="#"><i class="material-icons">shopping_cart</i>Your shopping cart</a></li>

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
	</li>
</ul>
<!-- FIN MENU HAMBURGER MOBILE-->
	</div></nav></div>
	</header>