<?php
include "connection.php";
$user_id=$_SESSION['id'];
$pt_id = $_GET['pt_id'];
mysqli_query($conn,"set names 'utf8'");
$sql = "SELECT * FROM glucose_tbl WHERE pt_id=$pt_id ORDER BY pt_id DESC";
//echo "$sql";
$result = mysqli_query($conn, $sql);
$total_data = mysqli_num_rows($result);

?>
        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">Blood Glucose Level of Patient ID : <?php echo"$pt_id";?></h1>

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Total Data : <?php echo $total_data; ?></h6>
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
                      while($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row["pt_id"]. " </td>"; 
                        echo "<td>" . $row["date_time"]. " </td>";
                        echo "<td>" . $row["bg_level"]. " </td>"; 
                    
                        echo"</tr>";
                    } 

                    if ($total_data == 0){
                        echo "<tr><td colspan='11'><center>".$lang['nodata']."</center></td></tr>";
                    }
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


