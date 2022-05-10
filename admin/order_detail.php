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
                  $stmt = $pdo->prepare("SELECT * FROM sale_order_detail WHERE sale_order_id=".$_GET['id']);
                  $stmt->execute();
                  $result = $stmt->fetchAll();
                    // print'<pre>';
                    // print_r($result);
                    // exit();  
              ?>

              <!-- /.card-header -->
              <div class="card-body">
                <div>
                  <a href="order_list.php" type="button" class="btn btn-default">Back</a>
                </div>
                <br>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Product</th>
                      <th>Quantity</th>
                      <th>Order Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $i = 1;
                      if($result){
                            // display category name
                          $productStmt = $pdo->prepare("SELECT * FROM products WHERE id=".$result[0]['product_id']);
                          $productStmt->execute();
                          $pResult = $productStmt->fetch(PDO::FETCH_ASSOC);
                        
                    ?>
                    <tr>
                      <td><?php echo $i; ?></td>
                      <td><?php echo escape($pResult['name']); ?></td>
                      <td><?php echo escape($result[0]['quantity']); ?></td>
                      <td><?php echo escape(date('Y-m-d',strtotime($result[0]['order_date']))); ?></td>
                    </tr>
                    <?php 
                      $i++;
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