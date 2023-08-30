<?php
include "connection.php";
$user_id = $_SESSION['id'];
$pt_id = $_GET['pt_id'];
mysqli_query($conn, "set names 'utf8'");

//mengambil jumlah keseluruhan data di database
$sql = "SELECT * FROM glucose_tbl WHERE pt_id=$pt_id ORDER BY rec_id DESC";
$result = mysqli_query($conn, $sql);
$total_data = mysqli_num_rows($result);

//menghitung rata-rata
$mean = mysqli_query($conn, "SELECT AVG(bg_level) AS average FROM glucose_tbl WHERE pt_id=$pt_id and (bg_level!=0)");
$row_mean = mysqli_fetch_assoc($mean);
$average = $row_mean["average"];

//menghitung standar deviasi
$std = mysqli_query($conn, "SELECT STDDEV(bg_level) AS deviation FROM glucose_tbl WHERE pt_id=$pt_id and (bg_level!=0)");
$row_std = mysqli_fetch_assoc($std);
$deviation = $row_std["deviation"];

//menghitung 3-sigma
$z = 3;
$upper_limit = $average + ($z * $deviation);
$lower_limit = $average - ($z * $deviation);

//winsorizing
//mengambil data agar berbentuk asosiatif array
$original_data = mysqli_fetch_all($result, MYSQLI_ASSOC);

//proses satu array agar bisa dipakai arraynya ke array baru
//data yang berbentuk asosiatif array diwakili oleh variabel data,
$graph_data = array_map(function ($data) use ($upper_limit, $lower_limit) {
  $winsorizing = array_merge(array(), $data); //mematenkan array

  if ($winsorizing['bg_level'] > $upper_limit) {
    $winsorizing['bg_level'] = $upper_limit;
  }

  elseif ($winsorizing['bg_level'] < $lower_limit) {
    $winsorizing['bg_level'] = $lower_limit;
  }

  return $winsorizing;
}, $original_data);


//data yang dipanggil untuk graph sebelum winsorizing
$before_data = "";

foreach ($original_data as $row) {
  $before_data .= "['" . $row["date_time"] . "'," . $row["bg_level"] . "," . $lower_limit . "," . $upper_limit . "," . $average . ",],";
}

//data yang dipanggil untuk graph setelah winsorizing
$after_data = "";

foreach ($graph_data as $row) {
  $after_data .= "['" . $row["date_time"] . "'," . $row["bg_level"] . "," . $lower_limit . "," . $upper_limit . "," . $average . ",],";
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
      <h6 class="m-0 font-weight-bold text-primary">Outlier Detection using 3-Sigma</h6>
    </div>
    <div class="card-body">

      <?php
      if ($before_data && $after_data) {
      ?>

        <div class="mb-5">
          <h2 class="h4 mb-2 text-gray-800">Original Data</h2>
          <div id="chart_before" style="width: 100%; height: 400px;"></div>
        </div>
        <div>
          <h2 class="h4 mb-2 text-gray-800">After Corrected</h2>
          <div id="chart_after" style="width: 100%; height: 400px;"></div>
        </div>
        <script type="text/javascript" src="https://www.google.com/jsapi"></script>
        <script type="text/javascript">
          google.load("visualization", "1", {
            packages: ["corechart"]
          });
          google.setOnLoadCallback(drawChart);

          function drawChart() {
            var dataBefore = google.visualization.arrayToDataTable([
              ['Date Time', 'Blood Glucose', 'Lower Limit', 'Uppper Limit', 'Average'],
              <?php echo $before_data ?>
            ]);
            var dataAfter = google.visualization.arrayToDataTable([
              ['Date Time', 'Blood Glucose', 'Lower Limit', 'Uppper Limit', 'Average'],
              <?php echo $after_data ?>
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

            var chartBefore = new google.visualization.LineChart(document.getElementById('chart_before'));
            var chartAfter = new google.visualization.LineChart(document.getElementById('chart_after'));
            chartBefore.draw(dataBefore, options);
            chartAfter.draw(dataAfter, options);
          }

          drawChart()
        </script>

      <?php
      }
      ?>

    </div>



  </div>
  <!-- /.container-fluid -->

</div>
<!-- End of Main Content -->