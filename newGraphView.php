<?php
include "connection.php";
$user_id=$_SESSION['id'];
$pt_id = $_GET['pt_id'];
mysqli_query($conn,"set names 'utf8'");
$sql = "SELECT * FROM glucose_tbl WHERE pt_id=$pt_id ORDER BY rec_id DESC limit 2000";
// $sql = array("SELECT * FROM glucose_tbl WHERE pt_id=$pt_id ORDER BY rec_id DESC limit 2000");
// echo "$sql";
$result = mysqli_query($conn, $sql);
$total_data = mysqli_num_rows($result);

$entry_bg="";
$entry_bg2="";
$final_row="";
while($row = mysqli_fetch_assoc($result)) {
  $entry_bg .= "['".$row["date_time"]."',".$row["bg_level"].",10,390,190],";
  $entry_bg2 .= "['".$row["date_time"]."',".$row["bg_level"]."],";
  $final_row = $row;
}
//echo "$entry_temp";

?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">Blood Glucose Level Patient ID : <?php echo"$pt_id"; ?></h1>
          <p class="mb-4">Information of Blood Glucose Level</p>

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Line Charts</h6>
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

  <!-- <form class="form-horizontal" action="exportIoTCsv.php?sensor_id=<?php echo $sensor_id;?>" method="post" name="upload_excel"   
                      enctype="multipart/form-data"> -->
    <!-- <button type="submit" class="btn btn-primary" name="export">Download as CSV</button> -->

            </div>
            </div>
        <!-- /.container-fluid -->




          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Histogram</h6>
            </div>
            <div class="card-body">

              <?php 
              if ($entry_bg2!=''){
              ?>
                
                <div id="chart_bg2" style="width: 100%; height: 400px;"></div>
                <script type="text/javascript" src="https://www.google.com/jsapi"></script>
                <script type="text/javascript">
                  google.charts.load("current", {packages:["corechart"]});
                  google.charts.setOnLoadCallback(drawChart);

                    function drawChart() {
                        var data2 = google.visualization.arrayToDataTable([
                        ['Blood Glucose', 'Count'],
                        <?php echo $entry_bg2 ?>
                    ]);
                    var options = {
                      title: 'Blood Glucose Level Histogram',
                      legend: { position: 'none' },
                      vAxes: { 0: {title: 'Frequency'}}
                      
                    };
                       
                var chart = new google.visualization.Histogram(document.getElementById('chart_bg2'));
                chart.draw(data2, options);
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



