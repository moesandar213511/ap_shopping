<?php 
  session_start();
  require 'config/config.php';
  require 'config/common.php';
  // print_r($_SESSION);
  // die();

  // logout လုပ်ပြီးလဲ admin pages link တွေကို ထည့်ရှာလို့ မရအောင်တား။
  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location:login.php');
  }

  // user acc နဲ့ admin login form ကို ၀◌င်လို့မရအောင် တား။
  if($_SESSION['role'] != 1){
    header('Location: login.php');
  }

  // search box မှာ ရှာပြီးရင် pagination နှိပ်ရင် all data တွေပဲ ပြတဲ့ error ရှင်း။
  if(!empty($_POST['search'])){
      setcookie('search', $_POST['search'], time() + (86400 * 30), "/"); // 86400 = 1 day

  }else{
      if(empty($_GET['pageno'])){
          unset($_COOKIE['search']);
          setcookie('search', null, -1, '/');
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
              <div class="card-header">
                <h3 class="card-title">Order Listings</h3>
              </div>

              <?php 
                  if(!empty($_GET['pageno'])){
                    $pageno = $_GET['pageno'];
                  }else{
                    $pageno = 1;
                  }
                  $numOfRecords = 10;
                  $offset = ($pageno-1)*$numOfRecords;

                  $stmt = $pdo->prepare("SELECT * FROM sale_orders ORDER BY id DESC");
                  $stmt->execute();
                  $rawResult = $stmt->fetchAll();
                  $total_pages = ceil(count($rawResult)/$numOfRecords);

                  $stmt = $pdo->prepare("SELECT * FROM sale_orders ORDER BY id DESC LIMIT $offset,$numOfRecords");
                  $stmt->execute();
                  $result = $stmt->fetchAll();
              ?>

              <!-- /.card-header -->
              <div class="card-body">
                <!-- <div>
                  <a href="category_add.php" type="button" class="btn btn-success">Create Category</a>
                </div> -->
                <br>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Customer</th>
                      <th>Total Price</th>
                      <th>Order Date</th>
                      <th style="width: 40px">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $i = 1;
                      if($result){
                        foreach ($result as $value){
                            // display category name
                          $userStmt = $pdo->prepare("SELECT * FROM users WHERE id=".$value['user_id']);
                          $userStmt->execute();
                          $userResult = $userStmt->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <tr>
                      <td><?php echo $i; ?></td>
                      <td><?php echo escape($userResult['name']); ?></td>
                      <td><?php echo escape($value['total_price']); ?></td>
                      <td><?php echo escape(date('Y-m-d',strtotime($value['order_date']))); ?></td>
                      <td style="width:10%;">
                        <!-- <div class="row">
                          <a href="#" type="button" class="btn btn-warning"><i class="fas fa-edit"></i><a>&nbsp;&nbsp;
                          <a href="#" type="button" class="btn btn-danger"><i class="fas fa-trash-alt"></i><a>
                        </div> -->
                        <div class="btn-group">
                          <div class="container">
                            <a href="order_detail.php?id=<?php echo $value['id'] ?>" type="button" class="btn btn-warning"><i class="fas fa-eye"></i><a>
                          </div>
                          
                        </div>
                      </td>
                    </tr>
                    <?php 
                      $i++;
                      }
                    }else{
                    ?>
                        <tr>
                          <td rowspan="4" style="text-align:center;">No Data</td>
                        </tr>
                    <?php
                    }
                    ?>
                    
                  
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <!-- <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                  <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                  <li class="page-item"><a class="page-link" href="#">1</a></li>
                  <li class="page-item"><a class="page-link" href="#">2</a></li>
                  <li class="page-item"><a class="page-link" href="#">3</a></li>
                  <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                </ul>
              </div> -->
              <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                  <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
                  <li class="page-item <?php if($pageno <= 1){ echo 'disabled';} ?>">
                    <a class="page-link" href="<?php if($pageno <= 1){ echo '#';}else{ echo "?pageno=".($pageno-1); } ?>">Previous</a>
                  </li>
                  <li class="page-item"><a class="page-link" href="#"><?php echo $pageno; ?></a></li>
                  <li class="page-item <?php if($pageno >= $total_pages){ echo 'disabled';} ?>">
                    <a class="page-link" href="<?php if($pageno >= $total_pages){ echo '#';}else{ echo "?pageno=".($pageno+1); } ?>">Next</a>
                  </li>
                  <li class="page-item"><a class="page-link" href="<?php echo "?pageno=".$total_pages ?>">Last</a></li>
                </ul>
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


  <!-- 
  $numOfRecords = 10;
  page no 1 => 1 to 10
          2 => 11 to 20
          3 => 21 to 30
  $offset = ($pageno-1)*$numOfRecords;
   
  -->