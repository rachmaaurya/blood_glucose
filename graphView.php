<?php
include "connection.php";
$user_id=$_SESSION['id'];
$pt_id = $_GET['pt_id'];
mysqli_query($conn,"set names 'utf8'");

$sql = "SELECT * FROM glucose_tbl WHERE pt_id=$pt_id ORDER BY rec_id DESC limit 2000";
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

// echo "std = " . $deviation;
// echo "mean = " . $average;
// echo "upper_limit = " . $upper_limit;
// echo "lower_limit = " . $lower_limit;

$remove = mysqli_query($conn, "SELECT bg_level FROM glucose_tbl WHERE pt_id=$pt_id");
$row_remove = mysqli_fetch_array($remove);
$winsorizing = $row_remove['bg_level'];

// while ($row_remove = mysqli_fetch_array($remove)){
//   if ($winsorizing > $upper_limit) {
//     $winsorizing = $upper_limit;
//   } 
//   elseif ($winsorizing < $lower_limit) {
//     $winsorizing = $lower_limit;
//   }
//   // echo '' .$winsorizing. ',';
// }

$entry_bg="";
$final_row="";
while($row = mysqli_fetch_assoc($result)) {
  $entry_bg .= "['".$row["date_time"]."',".$row["bg_level"].",".$lower_limit.",".$upper_limit.",".$average.",],";
  $final_row = $row;
}


?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">Blood Glucose Level of Patient ID : <?php echo"$pt_id"; ?></h1>
          <!-- <p class="mb-4">Information of Blood Glucose Level</p> -->

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Outlier Detection with 3 Sigma</h6>
            </div>
            <div class="card-body">

              <?php 
              if ($entry_bg!=''){
              ?>
                
                <div id="chart_bg" style="width: 100%; height: 400px;"></div>
                <script type="text/javascript" src="https://www.google.com/jsapi"></script>
                <script type="text/javascript">
                    google.load("visualization", "1", {packages:["corechart"]});
                    google.setOnLoadCallback(drawChart);

                    function drawChart() {
                        var data = google.visualization.arrayToDataTable([
                        ['Date Time', 'Blood Glucose', 'Lower Limit', 'Uppper Limit', 'Average'],
                        <?php echo $entry_bg ?>
                    ]);
                        var options = {
                            title: '',
                            legend: { position: 'top' , textStyle: {
						      fontSize: 18 }},
                            hAxis: { direction:-1, slantedText:true, slantedTextAngle:20, textStyle: {fontSize: 17} },
                            series: {0: {targetAxisIndex: 0},1: {targetAxisIndex: 0, lineDashStyle: [14, 2, 2, 7]},2: {targetAxisIndex: 0, lineDashStyle: [3, 3]}},
                            colors: ['#0000FF', '#FF0000', '#008000', '#000000'],
                            vAxes: { 0: {title: 'Blood Glucose (mg/dL)', titleTextStyle: {fontSize: 20}}, 1: {title: 'Lower Limit', titleTextStyle: {fontSize: 20}}, 2: {title: 'Upper Limit', titleTextStyle: {fontSize: 20}},
                            3: {title: 'Average', titleTextStyle: {fontSize: 20}}}
                        };
                       
                        var chart = new google.visualization.LineChart(document.getElementById('chart_bg'));
                        chart.draw(data, options);

                    setInterval(drawChart, 5000);
                    }
                </script>

                <?php
              }
              ?>

            </div>
            </div>
        <!-- /.container-fluid -->

        </div>
      <!-- End of Main Content -->



