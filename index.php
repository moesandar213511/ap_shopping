<?php include('header.php') ?>
		<?php 
			require 'config/config.php';

			// search box မှာ ရှာပြီးရင် pagination နှိပ်ရင် all data တွေပဲ ပြတဲ့ error ရှင်း။
			if(!empty($_POST['search'])){
				setcookie('search', $_POST['search'], time() + (86400 * 30), "/"); // 86400 = 1 day

			}else{
				if(empty($_GET['pageno'])){
					unset($_COOKIE['search']);
					setcookie('search', null, -1, '/');
				}
			}

			if(!empty($_GET['pageno'])){
				$pageno = $_GET['pageno'];
			}else{
				$pageno = 1;
			}
			$numOfRecords = 3;
			$offset = ($pageno-1)*$numOfRecords;

			// search ရှာတာမရှိရင် 
			if(empty($_POST['search']) && empty($_COOKIE['search'])){
				if(!empty($_GET['category_id'])){
					$category_id = $_GET['category_id'];

					$stmt = $pdo->prepare("SELECT * FROM products WHERE category_id=$category_id AND quantity > 0 ORDER BY id DESC");
					$stmt->execute();
					$rawResult = $stmt->fetchAll();
					// echo count($rawResult);
					$total_pages = ceil(count($rawResult)/$numOfRecords);

					$stmt = $pdo->prepare("SELECT * FROM products WHERE category_id=$category_id AND quantity > 0 ORDER BY id DESC LIMIT $offset,$numOfRecords");
					$stmt->execute();
					$result = $stmt->fetchAll();
				}else{
					$stmt = $pdo->prepare("SELECT * FROM products WHERE quantity > 0 ORDER BY id DESC");
					$stmt->execute();
					$rawResult = $stmt->fetchAll();
					$total_pages = ceil(count($rawResult)/$numOfRecords);

					$stmt = $pdo->prepare("SELECT * FROM products WHERE quantity > 0 ORDER BY id DESC LIMIT $offset,$numOfRecords");
					$stmt->execute();
					$result = $stmt->fetchAll();
				}
				
			}else{
				$search = (!empty($_POST['search'])) ? $_POST['search'] : $_COOKIE['search'];
				$stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE '%$search%' AND quantity > 0 ORDER BY id DESC");
				$stmt->execute();
				$rawResult = $stmt->fetchAll();
				$total_pages = ceil(count($rawResult)/$numOfRecords);

				$stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE '%$search%' AND quantity > 0 ORDER BY id DESC LIMIT $offset,$numOfRecords");
				$stmt->execute();
				$result = $stmt->fetchAll();
			}
		?>
	<div class="container">
	<div class="row">
		<div class="col-xl-3 col-lg-4 col-md-5">
			<div class="sidebar-categories">
				<div class="head">Browse Categories</div>
				<ul class="main-categories">
					<li class="main-nav-list">
					<?php 
						$catStmt = $pdo->prepare("SELECT * FROM categories");
						$catStmt->execute();
						$catResult = $catStmt->fetchAll();
					?>
					<?php foreach ($catResult as $value) { ?>
						<a href="index.php?category_id=<?php echo $value['id'] ?>">
							<span class="lnr lnr-arrow-right"></span><?php echo escape($value['name']) ?><span class="number">(53)</span>
						</a>
					<?php } ?>
					</li>
				</ul>
			</div>
		</div>
		<div class="col-xl-9 col-lg-8 col-md-7">
			<!-- Start Filter Bar -->
		<div class="filter-bar d-flex flex-wrap align-items-center">
			<div class="pagination">
				<a href="?pageno=1" class="active">First</a>

				<a <?php if($pageno <= 1){ echo 'disabled';} ?> href="<?php if($pageno <= 1){ echo '#';}else{ echo "?pageno=".($pageno-1); } ?>" class="prev-arrow">
					<i class="fa fa-long-arrow-left" aria-hidden="true"></i>
				</a>

				<a href="#" class="active"><?php echo $pageno; ?></a>
				
				<a <?php if($pageno >= $total_pages){ echo 'disabled';} ?> href="<?php if($pageno >= $total_pages){ echo '#';}else{ echo "?pageno=".($pageno+1); } ?>" class="next-arrow">
					<i class="fa fa-long-arrow-right" aria-hidden="true"></i>
				</a>

				<a href="<?php echo "?pageno=".$total_pages ?>" class="active">Last</a>

			</div>
		</div>
				<!-- End Filter Bar -->
				<!-- Start Best Seller -->
				<section class="lattest-product-area pb-40 category-list">
					<div class="row">
						<?php if($result){
							foreach ($result as $value) {
						?>
							<!-- single product -->
						<div class="col-lg-4 col-md-6">
							<div class="single-product">
								<a href="product_detail.php?id=<?php echo $value['id']; ?>">
									<img class="img-fluid" src="admin/images/<?php echo $value['image'] ?>" style="height:200px;" alt="">
								</a>
								
								<div class="product-details">
									<h6><?php echo $value['name'] ?></h6>
									<div class="price">
										<h6>$<?php echo escape($value['price']) ?></h6>
										<!-- <h6 class="l-through">$210.00</h6> -->
									</div>
									<div class="prd-bottom">
										<form action="addtocart.php" method="post">
											<input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">

                      						<input type="hidden" name="id" value="<?php echo escape($value['id']) ?>">

                      						<input type="hidden" name="qty" value="1">

											<div class="social-info">
												<button style="display:contents" class="social-info" type="submit">
													<span class="ti-bag"></span>
													<p style="left:23px" class="hover-text">add to bag</p>
												</button>
											</div>

											<a href="product_detail.php?id=<?php echo $value['id']; ?>" class="social-info">
											<span class="lnr lnr-move"></span>
											<p class="hover-text">view more</p>
											</a>
										</form>
										
									</div>
								</div>
							</div>
						</div>
						<?php
							}
						} 
						?>
						
					</div>
				</section>
				<!-- End Best Seller -->
<?php include('footer.php');?>
