<!DOCTYPE html>
<html lang="en">
<html>
<head>
	<title>Update Profile</title>
</head>
<body>

<?php
require('connection.php');

$id = $_SESSION['id'];
$sql = "select * from users where id=$id";
mysqli_query($conn,"set names 'utf8'");
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
?>  


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
               <form method = "post" action = "?page=updateProfileDo">
                <input type = "hidden" name="id" value="<?php echo"$row[id]";?>">
                 <div class="form-group">
                   <label for="exampleFormControlInput1"><?=$lang['name']?></label>
                   <input type="text" class="form-control" name="name" placeholder="" value="<?php echo"$row[name]";?>">
                 </div>
                 <div class="form-group">
                   <label for="exampleFormControlInput1"><?=$lang['fullname']?></label>
                   <input type="text" class="form-control" name="fullname" placeholder="" value="<?php echo"$row[fullname]";?>">
                 </div>
                 <div class="form-group">
                   <label for="exampleFormControlInput1"><?=$lang['email']?></label>
                   <input type="text" class="form-control" name="email" placeholder="" value="<?php echo"$row[email]";?>">
                 </div>
                 <div class="form-group">
                   <label for="exampleFormControlInput1"><?=$lang['phone']?></label>
                   <input type="text" class="form-control" name="phone" placeholder="" value="<?php echo"$row[phone]";?>">
                 </div>
                 <div class="form-group">
                   <label for="exampleFormControlInput1"><?=$lang['address']?></label>
                   <input type="text" class="form-control" name="address" placeholder="" value="<?php echo"$row[address]";?>">
                 </div>
                 <div class="form-group">
                   <label for="exampleFormControlInput1"><?=$lang['company']?></label>
                   <input type="text" class="form-control" name="company" placeholder="" value="<?php echo"$row[company]";?>">
                 </div>
                 <div class="form-group">
                   <button type="submit" class="btn btn-primary"><?=$lang['submit']?></button>
                 </div>
                 
               </form>

         </div>

   </div>
        <!-- /.container-fluid -->

</div>
</body>
</html>