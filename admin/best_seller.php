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
                <h3 class="card-title">Weekly Reports</h3>
              </div>

              <?php 
                    $currentDate = date('Y-m-d');
                    // https://stackoverflow.com/questions/1394791/adding-one-day-to-a-date
                    $fromDate = date('Y-m-d', strtotime($currentDate . '+1 day'));
                    $toDate = date('Y-m-d', strtotime($currentDate . '-7 day'));

                    // echo $fromDate;exit();

                    $stmt = $pdo->prepare("SELECT * FROM sale_orders WHERE order_date<:fromdate AND order_date>=:todate ORDER BY id DESC");
                    $stmt->execute(
                        array(':fromdate'=>$fromDate,':todate'=>$toDate)
                    );
                    $result = $stmt->fetchAll();
                    // print'<pre>';
                    // print_r($result);
                    // exit();
              ?>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered" id="data-table">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>UserID</th>
                      <th>Total Amount</th>
                      <th>Order date</th>
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
                      <td><?php echo escape(date('Y-m-d'),strtotime($value['order_date'])); ?></td>
                
                    </tr>
                    <?php 
                      $i++;
                      }
                    }else{
                    ?>
                        <tr>
                          <td colspan="4" style="text-align:center;">No Data</td>
                        </tr>
                    <?php
                    }
                    ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              
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
    <script>
        $(document).ready(function () {
            $('#data-table').DataTable();
        });
    </script>

  <!-- 
  $numOfRecords = 10;
  page no 1 => 1 to 10
          2 => 11 to 20
          3 => 21 to 30
  $offset = ($pageno-1)*$numOfRecords;
   
  -->