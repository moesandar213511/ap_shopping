<?php 
    include('header.php');
	  require 'config/config.php';

    if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location:login.php');
  }

    // select product data by id
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id=".$_GET['id']);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $catStmt = $pdo->prepare("SELECT * FROM categories WHERE id=".$result['category_id']);
    $catStmt->execute();
    $catResult = $catStmt->fetch(PDO::FETCH_ASSOC);

    // unset($_SESSION['cart']);
    
    // if submit add to cart
    // if($_POST){
    //   $id = $_POST['id'];
    //   $qty = $_POST['qty'];

    //   if(isset($_SESSION['cart']['id'.$id])){

    //       $_SESSION['cart']['id'.$id] += $qty;

    //   }else{
    //       $_SESSION['cart']['id'.$id] = $qty;

    //   }
      
    //   if($_SESSION['cart']['id'.$id] > $result['quantity']){
    //     // echo "hello";
    //     if($result['quantity'] == 0){
    //       $_SESSION['cart']['id'.$id] = 0;

    //     }else{
    //       $_SESSION['cart']['id'.$id] = $_SESSION['cart']['id'.$id] - $qty; 

    //     }
          
    //     echo '<h2 style="background-color:lightgrey;color:red;text-align:center;">No enough quantity.</h2>';

    //   }
      
    // }

    
      // print'<pre>';
      // print_r($_SESSION['cart']);
      
  ?>
  
<!--================Single Product Area =================-->

<div class="product_image_area" style="padding-top:100px">
  <div class="container">
    <div class="row s_product_inner">
      <div class="col-lg-6">
        <!-- <div class="s_Product_carousel"> -->
          <!-- <div class="single-prd-item">
            <img class="img-fluid" src="admin/images/<?php echo $result['image'] ?>" alt="">
          </div> -->
          <div class="single-prd-item">
            <img class="img-fluid" src="admin/images/<?php echo $result['image'] ?>" alt="">
          </div>
          <!-- <div class="single-prd-item">
            <img class="img-fluid" src="admin/images/<?php echo $result['image'] ?>" alt="">
          </div> -->
        <!-- </div> -->
      </div>
      
      <div class="col-lg-5 offset-lg-1" style="margin-top: -40px;">
        <div class="s_product_text">
          <h3><?php echo escape($result['name']) ?></h3>
          <h2><?php echo escape($result['price']) ?></h2>

          <ul class="list">
            <li><a class="active" href="#"><span>Category</span> : <?php echo escape($catResult['name']) ?></a></li>
            <li><a href="#"><span>Availibility</span> : <?php echo ($result['quantity'] != 0) ? "In Stock" : "No Stock"; ?></a></li>
            <li><a href="#"><span>Quantity </span> : <?php echo $result['quantity']; ?></a></li>
          </ul>

          <p><?php echo escape($result['description']) ?></p>

          <form action="addtocart.php" method="post">
            <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
            <input type="hidden" name="id" value="<?php echo escape($result['id']); ?>">
            <div class="product_count">
              <label for="qty">Quantity:</label>
              <input type="text" name="qty" id="sst" maxlength="12" value="0" title="Quantity:" class="input-text qty">

              <button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;"
              class="increase items-count" type="button"><i class="lnr lnr-chevron-up"></i></button>
              
              <button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst ) &amp;&amp; sst > 0 ) result.value--;return false;"
              class="reduced items-count" type="button"><i class="lnr lnr-chevron-down"></i></button>
            </div>

            <div class="card_area d-flex align-items-center">
              <input type="submit" name="" class="primary-btn" value="Add to Cart" style="border: 1px;">
              <a class="primary-btn" href="index.php">Back</a>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</div><br>
<!--================End Single Product Area =================-->

<!--================End Product Description Area =================-->
<?php include('footer.php');?>
