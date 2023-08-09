<?php
include "connection.php";
$user_id = $_SESSION['id'];
$pt_id = $_GET['pt_id'];
mysqli_query($conn, "set names 'utf8'");

$sql = "SELECT * FROM glucose_tbl WHERE pt_id=$pt_id ORDER BY pt_id";
$result = mysqli_query($conn, $sql);
$total_data = mysqli_num_rows($result);

$mean = mysqli_query($conn, "SELECT AVG(bg_level) AS average FROM new_glucose WHERE pt_id=$pt_id");
$row_mean = mysqli_fetch_assoc($mean);
$average = $row_mean["average"];


$std = mysqli_query($conn, "SELECT STDDEV(bg_level) AS deviation FROM new_glucose WHERE pt_id=$pt_id");
$row_std = mysqli_fetch_assoc($std);
$deviation = $row_std["deviation"];


$z = 3;
$upper_limit = $average + ($z * $deviation);
$lower_limit = $average - ($z * $deviation);

$data1 = "SELECT * FROM glucose_tbl WHERE pt_id=$pt_id and (bg_level<$lower_limit OR bg_level>$upper_limit)";
$result2 = mysqli_query($conn, $data1);


// $query = "SELECT bg_level FROM glucose_tbl WHERE bg_level<$lower_limit OR bg_level>$upper_limit";
// $data1 = mysqli_prepare($conn, $query);
// mysqli_stmt_execute($data1);
// $result2 = mysqli_stmt_get_result($data1);
// echo $result2;
// while ($row = mysqli_fetch_assoc($result2)){
//   echo "<td>" . $row['bg_level'] . "</td>";
// }

$data2 = mysqli_query($conn, "SELECT bg_level FROM glucose_tbl WHERE pt_id=$pt_id and (bg_level<$lower_limit OR bg_level>$upper_limit)");
$total_data2 = mysqli_num_rows($data2);
?>
<!-- Begin Page Content -->
<div class="container-fluid">


  <!-- Page Heading -->
  <h1 class="h3 mb-2 text-gray-800">Data Outlier Blood Glucose Level of Patient ID : <?php echo "$pt_id"; ?></h1>
  
  <!-- DataTales Example -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Total Data : <?php echo $total_data; ?></h6>
      <h6 class="m-0 font-weight-bold text-primary">Total Data Outlier : <?php echo $total_data2; ?></h6>
    </div>
    <div class="card-body">


      <div style="height:500px;overflow:auto;">
        <div class="table-responsive">
          <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr class="table-success">
                <th>Patient ID</th>
                <th>Date Time</th>
                <th>Blood Glucose Level (mg/dL)</th>
              </tr>
            </thead>
            <tbody>

              <?php
              while ($row = mysqli_fetch_assoc($result2)) {
                echo "<tr>";
                echo "<td>" . $row["pt_id"] . " </td>";
                echo "<td>" . $row["date_time"] . " </td>";
                echo "<td>" . $row['bg_level'] . " </td>";
                echo "<tr>";
              }

              // if ($total_data == 0){
              //     echo "<tr><td colspan='11'><center>".$lang['nodata']."</center></td></tr>";
              // }
              // elseif ($total_data2 == 0){
              //   echo "<tr><td colspan='11'><center>".$lang['nodata']."</center></td></tr>";
              // }
              ?>

            </tbody>
          </table>


          <?php

          mysqli_close($conn);
          ?>
        </div>
      </div>

    </div>
    <!-- /.container-fluid -->

  </div>
  <!-- End of Main Content -->