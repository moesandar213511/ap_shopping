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

    // backend validation is most secure.
    if(empty($_POST['name']) || empty($_POST['description'])){
        if(empty($_POST['name'])){
            $nameError = "Name is Empty";
        }
        if(empty($_POST['description'])){
            $desError = "Description is Empty";
        }
    }else{
        $name = $_POST['name'];
        $description = $_POST['description'];
        $stmt = $pdo->prepare("INSERT INTO categories(name,description) VALUES(:name,:description)"); // bind param
        $result = $stmt->execute(
            array(':name'=>$name,':description'=>$description)
        );
        if($result){
            echo "<script>alert('Category Added.');window.location.href='category.php';</script>";
        }
    }
  }

?>

  <?php include 'header.php'; ?>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                  <form class="" action="category_add.php" method="post">
                      <!-- config/common.php -->
                      <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">

                      <div class="form-group">
                          <label for="">Name</label><br>
                          <p style="color: red;"><?php echo empty($nameError) ? '' : "*".$nameError; ?></p>
                          <input type="text"  class="form-control" name="name" value="">
                      </div>
                      <div class="form-group">
                          <label for="">Description</label><br>
                          <p style="color: red;"><?php echo empty($desError) ? '' : "*".$desError; ?></p>
                          <textarea class="form-control" name="description" rows="8" cols="80"></textarea>
                      </div>

                      <div class="form-group">
                          <input type="submit"  class="btn btn-success
                          " value="SUBMIT">
                          <a href="category.php" class="btn btn-secondary">Back</a>
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
