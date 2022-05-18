<?php 
	// if no session
	// if(session_start() == PHP_SESSION_NONE){
	session_start();
	require 'config/common.php';

	// }
?>

<!DOCTYPE html>
<html lang="zxx" class="no-js">

<head>
	<!-- Mobile Specific Meta -->
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Favicon-->
	<link rel="shortcut icon" href="img/fav.png">
	<!-- Author Meta -->
	<meta name="author" content="CodePixar">
	<!-- Meta Description -->
	<meta name="description" content="">
	<!-- Meta Keyword -->
	<meta name="keywords" content="">
	<!-- meta character set -->
	<meta charset="UTF-8">
	<!-- Site Title -->
	<title>Karma Shop</title>

	<!--
            CSS
            ============================================= -->
	<link rel="stylesheet" href="css/linearicons.css">
	<link rel="stylesheet" href="css/owl.carousel.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/themify-icons.css">
	<link rel="stylesheet" href="css/nice-select.css">
	<link rel="stylesheet" href="css/nouislider.min.css">
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/main.css">
</head>

<body id="category">

	<!-- Start Header Area -->
	<header class="header_area sticky-header">
		<div class="main_menu">
			<nav class="navbar navbar-expand-lg navbar-light main_box">
				<div class="container">
					<!-- Brand and toggle get grouped for better mobile display -->
					<a class="navbar-brand logo_h" href="index.php"><h4>AP Shopping<h4></a>
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
					 aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<?php 
						$link = $_SERVER['PHP_SELF'];
						$link_array = explode('/',$link);
						$page = end($link_array);
						// print($page);
					?>
					<!-- Collect the nav links, forms, and other content for toggling -->
					<?php
						$cart = 0;
						if(isset($_SESSION['cart'])){
							foreach($_SESSION['cart'] as $key => $qty){
								$cart += $qty;
							}
						}
					?>
					<div class="collapse navbar-collapse offset" id="navbarSupportedContent">
						<ul class="nav navbar-nav navbar-right">
							<li class="nav-item"><a href="cart.php" class="cart"><span class="ti-bag"><?php echo $cart; ?></span></a></li>
							<?php if($page != 'product_detail.php'): ?>
							<li class="nav-item">
								<button class="search"><span class="lnr lnr-magnifier" id="search"></span></button>
							</li>
							<?php endif; ?>
						</ul>
					</div>
				</div>
			</nav>
		</div>

		<?php if($page != 'product_detail.php'): ?>
		<div class="search_input" id="search_input_box">
			<div class="container">
				<form class="d-flex justify-content-between" action="index.php" method="post">
            		<input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
					<input type="text" class="form-control" name="search" id="search_input" placeholder="Search Here">
					<button type="submit" class="btn"></button>
					<span class="lnr lnr-cross" id="close_search" title="Close Search"></span>
				</form>
			</div>
		</div>
		<?php endif; ?>
	</header>
	<!-- End Header Area -->

	<!-- Start Banner Area -->
	<section class="banner-area organic-breadcrumb" style="margin-bottom: 0 !important;">
		<div class="container">
			<div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
				<div class="col-first">
					<?php if(!empty($_SESSION['username'])){ ?>
					<h1>Welcome <?php echo escape($_SESSION['username']); ?></h1>
					<a href="logout.php" class="primary-btn" style="line-height:30px;color:black;background:white">Logout</a>
					<?php }else{ ?>
					<!-- <nav class="d-flex align-items-center">
						<a href="index.html">Home<span class="lnr lnr-arrow-right"></span></a>
						<a href="category.html">Login/Register</a>
					</nav> -->
					<h1>Welcome from AP Shopping</h1>
					<h3>
						<a href="login.php" style="color:white">Login/</a>
						<a href="register.php" style="color:white">Register</a>
					</h3>
					<?php } ?>
				</div>
			</div>
		</div>
	</section>
	<!-- End Banner Area -->
	
				
