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
        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];

        $stmt = $pdo->prepare("UPDATE categories SET name=:name,description=:description WHERE id=:id"); // bind param
        $result = $stmt->execute(
            array(':name'=>$name,':description'=>$description,':id'=>$id)
        );
        if($result){
            echo "<script>alert('Category Updated.');window.location.href='category.php';</script>";
        }
    }
  }

  $stmt = $pdo->prepare("SELECT * FROM categories WHERE id=".$_GET['id']);
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
                  <form action="" method="post">
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
