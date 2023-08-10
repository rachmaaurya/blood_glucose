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
$mean = mysqli_query($conn, "SELECT AVG(bg_level) AS average FROM new_glucose WHERE pt_id=$pt_id");
$row_mean = mysqli_fetch_assoc($mean);
$average = $row_mean["average"];

//IQR
$query_data_urut = "SELECT * FROM glucose_tbl WHERE pt_id=$pt_id ORDER BY bg_level";
$hasil_query = mysqli_query($conn, $query_data_urut);
$data_urut = mysqli_fetch_all($hasil_query, MYSQLI_ASSOC);
$total_data_urut = count($data_urut);

// echo $data_habis ?"data habis" : "data tidak habis";
// echo $data_genap ?"data genap" : "data ganjil";
// echo strval($data_genap );
// echo '<br>';
// echo $total_data_urut;

function cari_quartile($q, $data){
  $banyak_data = count($data);
  $data_genap = $banyak_data % 2 == 0;
  $data_habis = ($banyak_data + 1) % 4 == 0;

  if ($data_genap){
    if ($data_habis){
      if ($q == 1){
        return ($data[(($banyak_data - 1) / 4) - 1]['bg_level'] + $data[(($banyak_data + 3) / 4) - 1]['bg_level']) / 2;
      } elseif ($q == 3) {
        return ($data[((3 * $banyak_data + 1) / 4) - 1]['bg_level'] + $data[((3 * $banyak_data + 5) / 4) - 1]['bg_level']) / 2;
      }
    } else{
      if ($q == 1){
        return $data[(($banyak_data + 2) / 4) - 1]['bg_level'];
      } elseif ($q == 3) {
        return $data[((3 * $banyak_data + 2) / 4) - 1]['bg_level'];
      }
    }
  } else{
    if ($data_habis){
      if ($q == 1){
        return $data[(($banyak_data + 1) / 4) - 1]['bg_level'];
      } elseif ($q == 3) {
        return $data[((3 * ($banyak_data + 1)) / 4) - 1]['bg_level'];
      }
    } else{
      if ($q == 1){
        return ($data[(($banyak_data - 1) / 4) - 1]['bg_level'] + $data[(($banyak_data + 3) / 4) - 1]['bg_level']) / 2;
      } elseif ($q == 3) {
        return ($data[((3 * $banyak_data + 1) / 4) - 1]['bg_level'] + $data[((3 * $banyak_data + 5) / 4) - 1]['bg_level']) / 2;
      }
    }
  }
}

// var_dump($data_urut);
// echo cari_quartile(1, $data_urut);
// echo '<br>';
// echo cari_quartile(3, $data_urut);

$q1 = cari_quartile(1, $data_urut);
$q3 = cari_quartile(3, $data_urut);
$iqr = $q3 - $q1;

//menghitung IQR
$upper_limit = $average + $iqr;
$lower_limit = $average - $iqr;

$original_data = mysqli_fetch_all($result, MYSQLI_ASSOC);

$graph_data = array_map(function ($data) use ($upper_limit, $lower_limit) {
    $copy = array_merge(array(), $data);
  
    if ($copy['bg_level'] > $upper_limit) {
      $copy['bg_level'] = $upper_limit;
    }
  
    elseif ($copy['bg_level'] < $lower_limit) {
      $copy['bg_level'] = $lower_limit;
    }
  
    return $copy;
  }, $original_data);
  
  
  //
  $before_data = "";
  
  foreach ($original_data as $row) {
    $before_data .= "['" . $row["date_time"] . "'," . $row["bg_level"] . "," . $lower_limit . "," . $upper_limit . "," . $average . ",],";
  }
  
  //
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
      <h6 class="m-0 font-weight-bold text-primary">Outlier Detection using IQR</h6>
    </div>
    <div class="card-body">

      <?php
      if ($before_data && $after_data) {
      ?>

        <div class="mb-5">
          <h2 class="h4 mb-2 text-gray-800">Before copy</h2>
          <div id="chart_before" style="width: 100%; height: 400px;"></div>
        </div>
        <div>
          <h2 class="h4 mb-2 text-gray-800">After copy</h2>
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