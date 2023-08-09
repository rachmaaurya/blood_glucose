<!DOCTYPE html>
<html lang="en">
<html>
<head>
	<title><?=$lang['updateprofile']?></title>
</head>
<body>
<!-- Begin Page Content -->
<div class="container-fluid">

   <!-- Page Heading -->
   <h1 class="h3 mb-2 text-gray-800"><?=$lang['updateprofile']?></h1>
   

      <!-- DataTales Example -->
      <div class="card shadow mb-4">
         <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><?=$lang['dataprofile']?></h6>
         </div>
            <div class="card-body">

<?php

require('connection.php');

$id = $_SESSION['id'];
$name=$_POST['name'];
$email=$_POST['email'];
$phone=$_POST['phone'];
$address=$_POST['address'];
$fullname=$_POST['fullname'];
$company=$_POST['company'];

mysqli_query($conn,"set names 'utf8'");
$checkemail = "SELECT email from users where email='$email' and id<>'$id'";
$checkemailres = mysqli_query($conn, $checkemail);
if (mysqli_num_rows($checkemailres) > 0) {
    echo"<br/><div class='alert alert-danger' role='alert'>";
    echo $lang['updateprofileerror']."<br>";
    echo"</div>";                   
    echo "<p><p><a href='?page=updateProfileView'><button type='button' class='btn btn-primary'>Back</button></a>";
}else{
	mysqli_query($conn,"set names 'utf8'");
	$sql = "update users set fullname='$fullname', name='$name', email='$email', phone='$phone', address='$address', company='$company' where id=$id";
	if (mysqli_query($conn, $sql)) {
		echo"<div class='alert alert-success' role='alert'>";
	    echo $lang['updateprofilesuccess']."<br>";
		echo"</div>";
	} else {
	    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}

mysqli_close($conn);
}
?>



         </div>

   </div>
        <!-- /.container-fluid -->

</div>
</body>
</html>