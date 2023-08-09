<meta http-equiv="content-type" content="text/html" charset="utf-8">
<?php
include "../connection.php";

$epc = $_REQUEST['epc'];
$eventTime = $_REQUEST['eventTime'];


if ($epc!='' && $eventTime!=''){

$sql= "insert into tag_registration (eventTime, recordTime, epc) 
VALUES ('$eventTime',now(),'$epc')";
echo "$sql";
$result = mysqli_query($conn, $sql);
						
						
mysqli_close($conn);
} else {
	echo "error";
}

?>
