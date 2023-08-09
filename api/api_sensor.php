<meta http-equiv="content-type" content="text/html" charset="utf-8">
<?php
include "../connection.php";

$sensorid = $_REQUEST['sensorid'];
$temp = $_REQUEST['temp'];
$hum = $_REQUEST['hum'];
$createdat = $_REQUEST['createdat'];

if ($sensorid!='' && $createdat!=''){
	$sql= "insert into temphum (sensor_id, create_date_time, save_date_time, temp,hum)
						value('$sensorid','$createdat', now(),$temp, $hum)";
	echo "$sql";
	$result = mysqli_query($conn, $sql);		

	mysqli_close($conn);
} else {
	echo "error";
}

?>
