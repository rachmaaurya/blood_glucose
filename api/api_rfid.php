<meta http-equiv="content-type" content="text/html" charset="utf-8">
<?php
include "../connection.php";

$epc = $_REQUEST['epc'];
$eventTime = $_REQUEST['eventTime'];
$readPoint = $_REQUEST['readPoint'];

if ($epc!='' && $eventTime!='' && $readPoint!=''){
$readPoint2 = $readPoint +1;
$sql= "insert into rfid (eventTime, recordTime, epc, ACTION, readPoint,bizLocation) 
VALUES ('$eventTime',now(),'$epc','observe','$readPoint2','warehouse')";
echo "$sql";
$result = mysqli_query($conn, $sql);
						
						
mysqli_close($conn);
} else {
	echo "error";
}

?>
