<?php
include "connection.php";
$user_id=$_SESSION['id'];

mysqli_query($conn,"set names 'utf8'");
$sql = "SELECT DISTINCT(pt_id) FROM glucose_tbl ORDER BY pt_id";
$result1 = mysqli_query($conn, $sql);
$result2 = mysqli_query($conn, $sql);
$result3 = mysqli_query($conn, $sql);
$result4 = mysqli_query($conn, $sql);
$result5 = mysqli_query($conn, $sql);
$total_data = mysqli_num_rows($result1);


?>
<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title><?=$lang['title']?> - <?=$lang['dashboard']?></title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
  <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  
  <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
        <!-- <div class="sidebar-brand-icon rotate-n-15">
          <i class="fas fa-laugh-wink"></i>
        </div> -->
        <div class="sidebar-brand-text mx-3"><?=$lang['title']?></div>
      </a>
             <!-- Divider -->
      <hr class="sidebar-divider">
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseRFID" aria-expanded="true" aria-controls="collapseRFID">
          <i class="fas fa-list-alt"></i>
          <span>Blood Glucose Data</span>
        </a>
          <div id="collapseRFID" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">

            <?php
            while($row2 = mysqli_fetch_assoc($result2)) {

              echo"<a class='collapse-item' href='?page=bgView&pt_id=".$row2["pt_id"]."'>Patient ".$row2["pt_id"]. "</a>";
              }
            ?>
            
          </div>
        </div>
      </li>
       <!-- Divider -->
       <!-- Divider -->
      <hr class="sidebar-divider">
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTemphum" aria-expanded="true" aria-controls="collapseTemphum">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Visualization 3-Sigma</span>
        </a>
          <div id="collapseTemphum" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">

            <?php
            while($row1 = mysqli_fetch_assoc($result1)) {

              echo"<a class='collapse-item' href='?page=graphView&pt_id=".$row1["pt_id"]."'>Patient ".$row1["pt_id"]. "</a>";
              }
            ?>
            
          </div>
        </div>
      </li>
       <!-- Divider -->
       <!-- Divider -->
      <hr class="sidebar-divider">
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseExample" aria-expanded="true" aria-controls="collapseExample">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Visualization IQR</span>
        </a>
          <div id="collapseExample" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">

            <?php
            while($row3 = mysqli_fetch_assoc($result3)) {

              echo"<a class='collapse-item' href='?page=graphView2&pt_id=".$row3["pt_id"]."'>Patient ".$row3["pt_id"]. "</a>";
              }
            ?>
            
          </div>
        </div>
      </li>
       <!-- Divider -->
       <!-- Divider -->
      <hr class="sidebar-divider">
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          <i class="fas fa-list-alt"></i>
          <span>Data Outlier 3-Sigma</span>
        </a>
          <div id="collapseOne" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">

            <?php
            while($row4 = mysqli_fetch_assoc($result4)) {

              echo"<a class='collapse-item' href='?page=dataOutlier&pt_id=".$row4["pt_id"]."'>Patient ".$row4["pt_id"]. "</a>";
              }
            ?>
            
          </div>
        </div>
      </li>
       <!-- Divider -->
       <!-- Divider -->
       <hr class="sidebar-divider">
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-list-alt"></i>
          <span>Data Outlier IQR</span>
        </a>
          <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">

            <?php
            while($row5 = mysqli_fetch_assoc($result5)) {

              echo"<a class='collapse-item' href='?page=dataOutlier2&pt_id=".$row5["pt_id"]."'>Patient ".$row5["pt_id"]. "</a>";
              }
            ?>
            
          </div>
        </div>
      </li>


      <hr class="sidebar-divider">

    </ul>
    <!-- End of Sidebar -->
        <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
    	      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>


          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
            <li class="nav-item dropdown no-arrow d-sm-none">
              <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
              </a>
              <!-- Dropdown - Messages -->
              <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                  <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="button">
                        <i class="fas fa-search fa-sm"></i>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </li>

            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo"$_SESSION[name]";?></span>
                <img class="img-profile rounded-circle" src="img/genderfree.png">
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="?page=updateProfileView">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  <?=$lang['profile']?>
                </a>
                <!-- <a class="dropdown-item" href="?page=updateProfileView">
                  <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                  Settings
                </a> -->
                
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="logout.php" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  <?=$lang['logout']?>
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->



  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"><?=$lang['logoutready']?></h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body"><?=$lang['logoutmsg']?></div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal"><?=$lang['cancel']?></button>
          <a class="btn btn-primary" href="logout.php"><?=$lang['logout']?></a>
        </div>
      </div>
    </div>
  </div>
