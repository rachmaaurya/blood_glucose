<?php
include "connection.php";
$user_id = $_SESSION['id'];
$pt_id = $_GET['pt_id'];
mysqli_query($conn, "set names 'utf8'");

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

$remove = mysqli_query($conn, "SELECT * FROM glucose_tbl WHERE pt_id=$pt_id ORDER BY rec_id DESC limit 2000");
$row_remove = mysqli_fetch_all($remove, MYSQLI_ASSOC);

$graph_data = array_map(function ($data) use ($upper_limit, $lower_limit) {
  $winsorizing = array_merge(array(), $data);

  if ($winsorizing['bg_level'] > $upper_limit) {
    echo $winsorizing['bg_level'] . " ";
    $winsorizing['bg_level'] = $upper_limit;
  }

  if ($winsorizing['bg_level'] < $lower_limit) {
    $winsorizing['bg_level'] = 300;
  }

  return $winsorizing;
}, $row_remove);



$entry_bg = "";

foreach ($graph_data as $row) {
  $entry_bg .= "['" . $row["date_time"] . "'," . $row["bg_level"] . "," . $lower_limit . "," . $upper_limit . "," . $average . ",],";
}

?>

<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-2 text-gray-800">Blood Glucose Level of Patient ID : <?php echo "$pt_id"; ?></h1>
  <!-- <p class="mb-4">Information of Blood Glucose Level</p> -->

  <!-- DataTales Example -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Outlier Detection with 3 Sigma</h6>
    </div>
    <div class="card-body">

      <?php
      if ($entry_bg != '') {
      ?>

        <div id="chart_bg" style="width: 100%; height: 400px;"></div>
        <script type="text/javascript" src="https://www.google.com/jsapi"></script>
        <script type="text/javascript">
          google.load("visualization", "1", {
            packages: ["corechart"]
          });
          google.setOnLoadCallback(drawChart);

          function drawChart() {
            var data = google.visualization.arrayToDataTable([
              ['Date Time', 'Blood Glucose', 'Lower Limit', 'Uppper Limit', 'Average'],
              <?php echo $entry_bg ?>
            ]);
            var options = {
              title: '',
              legend: {
                position: 'top',
                textStyle: {
                  fontSize: 18
                }
              },
              hAxis: {
                direction: -1,
                slantedText: true,
                slantedTextAngle: 20,
                textStyle: {
                  fontSize: 17
                }
              },
              series: {
                0: {
                  targetAxisIndex: 0
                },
                1: {
                  targetAxisIndex: 0,
                  lineDashStyle: [14, 2, 2, 7]
                },
                2: {
                  targetAxisIndex: 0,
                  lineDashStyle: [3, 3]
                }
              },
              colors: ['#0000FF', '#FF0000', '#008000', '#000000'],
              vAxes: {
                0: {
                  title: 'Blood Glucose (mg/dL)',
                  titleTextStyle: {
                    fontSize: 20
                  }
                },
                1: {
                  title: 'Lower Limit',
                  titleTextStyle: {
                    fontSize: 20
                  }
                },
                2: {
                  title: 'Upper Limit',
                  titleTextStyle: {
                    fontSize: 20
                  }
                },
                3: {
                  title: 'Average',
                  titleTextStyle: {
                    fontSize: 20
                  }
                }
              }
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