<?php 
  session_start();
  require 'config/config.php';
  require 'config/common.php';

  // print_r($_SESSION);
  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location:login.php');
  }
  if($_SESSION['role'] != 1){
    header('Location: login.php');
  }

  if(!empty($_POST)){
    //  //csrf protection (look config/common.php)
    // if (!hash_equals($_SESSION['_token'], $_POST['_token'])) die();

    // backend validation than front validation is most secure.
    if(empty($_POST['name']) || empty($_POST['description']) || empty($_POST['category']) 
    || empty($_POST['quantity']) || empty($_POST['price'])){
        echo "test";
        if(empty($_POST['name'])){
            $nameError = "Name is Empty";
        }
        if(empty($_POST['description'])){
            $desError = "Description is Empty";
        }
        if(empty($_POST['category'])){
            $catError = "Category is Empty";
        }
        if(empty($_POST['quantity'])){
            $qtyError = "Quantity is Empty";
        }elseif(is_numeric($_POST['quantity']) != 1){ // check is int or not
            $qtyError = "Quantity must be integer value.";
        }

        if(empty($_POST['price'])){
            $priceError = "Price is Empty";
        }elseif(is_numeric($_POST['price']) != 1){ // check is int or not
            $priceError = "Price must be integer value.";
        }        
      }else{ // validation success
        if(is_numeric($_POST['quantity']) != 1){ // check is int or not
          $qtyError = "Quantity must be integer value.";
        }
        if(is_numeric($_POST['price']) != 1){ // check is int or not
          $priceError = "Price must be integer value.";
        }

        if($qtyError == '' && $priceError == ''){
          $id = $_POST['id'];
          $name = $_POST['name'];
          $description = $_POST['description'];
          $category = $_POST['category'];
          $quantity = $_POST['quantity'];
          $price = $_POST['price'];
          // image has
          if($_FILES['image']['name'] != null){
              $file = "images/".$_FILES['image']['name'];
              $imageType = pathinfo($file,PATHINFO_EXTENSION);

              if($imageType != 'jpg' && $imageType != 'jpeg' && $imageType != 'png'){
                  echo "<script>alert('Image must be jpg,jpeg,png.');</script>";
              }else{
                  $image = $_FILES['image']['name'];
                  move_uploaded_file($_FILES['image']['tmp_name'],$file);

                  $stmt = $pdo->prepare("UPDATE products SET name=:name,description=:description,category_id=:category_id,quantity=:quantity,price=:price,image=:image WHERE id=:id");
                  $result = $stmt->execute(
                      array(':name' => $name,':description' => $description,':category_id' => $category,':quantity' => $quantity,':price' => $price,':image' => $image,':id'=>$id)
                  );
                  if($result){
                      echo "<script>alert('Product is Updated.');window.location.href='index.php';</script>";
                  }
              }
          }else{// image hasn't
              $stmt = $pdo->prepare("UPDATE products SET name=:name,description=:description,category_id=:category_id,quantity=:quantity,price=:price WHERE id=:id");
              $result = $stmt->execute(
                  array(':name' => $name,':description' => $description,':category_id' => $category,':quantity' => $quantity,':price' => $price,':id'=>$id)
              );
              if($result){
                  echo "<script>alert('Product is Updated.');window.location.href='index.php';</script>";
              }
          }
        }
    }
  }

  $stmt = $pdo->prepare("SELECT * FROM products WHERE id=".$_GET['id']);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

?>

  <?php include 'header.php'; ?>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                  <form class="" action="" method="post" enctype="multipart/form-data">
                      <!-- config/common.php -->
                      <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">

                      <input type="hidden" name="id" value="<?php echo $result['id'] ?>">

                      <div class="form-group">
                          <label for="">Name</label><br>
                          <p style="color: red;"><?php echo empty($nameError) ? '' : "*".$nameError; ?></p>
                          <input type="text"  class="form-control" name="name" value="<?php echo escape($result['name']) ?>">
                      </div>

                      <div class="form-group">
                          <label for="">Description</label><br>
                          <p style="color: red;"><?php echo empty($desError) ? '' : "*".$desError; ?></p>
                          <textarea class="form-control" name="description" rows="8" cols="80"><?php echo escape($result['description']) ?></textarea>
                      </div>
                      <?php
                        $stmt = $pdo->prepare("SELECT * FROM categories");
                        $stmt->execute();
                        $catResult = $stmt->fetchAll();
                      
                      ?>
                      <div class="form-group">
                          <label for="">Category</label><br>
                          <p style="color: red;"><?php echo empty($catError) ? '' : "*".$catError; ?></p>
                          <select name="category" class="form-control">
                            <option value="">Select Category</option>
                            <?php foreach ($catResult as $value) { ?>
                                <?php if($value['id'] == $result['category_id']): ?>
                                    <option value="<?php echo $value['id'] ?>" selected><?php echo $value['name'] ?></option>
                                <?php else: ?>
                                    <option value="<?php echo $value['id'] ?>"><?php echo $value['name'] ?></option>
                                <?php endif ?>
                            <?php }  ?>
                          </select>
                      </div>
                      
                      <div class="form-group">
                          <label for="">Quantity</label><br>
                          <p style="color: red;"><?php echo empty($qtyError) ? '' : "*".$qtyError; ?></p>
                          <input type="number"  class="form-control" name="quantity" value="<?php echo escape($result['quantity']) ?>">
                      </div>
                      
                      <div class="form-group">
                          <label for="">Price</label><br>
                          <p style="color: red;"><?php echo empty($priceError) ? '' : "*".$priceError; ?></p>
                          <input type="number"  class="form-control" name="price" value="<?php echo escape($result['price']) ?>">
                      </div>
                      
                      <div class="form-group">
                          <label for="">Image</label><br>
                          <p style="color: red;"><?php echo empty($imageError) ? '' : "*".$imageError; ?></p>
                          <img src="images/<?php echo escape($result['image']) ?>" alt="" width="94px" height="100px"><br>
                          <input type="file" name="image" value="">
                      </div><br>

                      <div class="form-group">
                          <input type="submit"  class="btn btn-success
                          " value="SUBMIT">
                          <a href="index.php" class="btn btn-secondary">Back</a>
                      </div>
                  </form>
              </div>
            </div>
  
          </div>
          <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    <div class="p-3">
      <h5>Title</h5>
      <p>Sidebar content</p>
    </div>
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <?php include 'footer.php'; ?>
