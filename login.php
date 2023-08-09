<!DOCTYPE html>
<html lang="en">
<?php
  require('connection.php');
  session_start();

  if (isset($_GET['la'])){
    $_SESSION['la'] = $_GET['la'];
    header('Location:'.$_SERVER['PHP_SELF']);
    exit();
  }

  switch(isset($_SESSION['la'])){
    default:
      require('lang/eng.php');
  }
?>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title><?=$lang['title']?> - <?=$lang['login']?></title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.css" rel="stylesheet">
</head>

<body class="bg-gradient-primary">
  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">
      <div class="col-xl-8 col-lg-12 col-md-9">
        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-3"></div>
              <div class="col-lg-6">
              	<?php
        					if (isset($_POST['email'])){
        						$email = stripslashes($_REQUEST['email']);
        						$email = mysqli_real_escape_string($conn,$email);
        						$password = stripslashes($_REQUEST['password']);
        						$password = mysqli_real_escape_string($conn,$password);
        						mysqli_query($conn,"set names 'utf8'");
        					    $query = "SELECT * FROM `users` WHERE email='$email' and password='".md5($password)."'";
        						$result = mysqli_query($conn,$query) or die(mysql_error());
        						//$rows = mysqli_num_rows($result);
        					        if($row = mysqli_fetch_array($result)){
        					        	$_SESSION['id'] = $row['id'];
        					        	$_SESSION['name'] = $row['name'];
        						    	  $_SESSION['email'] = $row['email'];
        						    	  $_SESSION['status'] = $row['status'];
        						    	//echo $_SESSION['name'];
        						    	header("Location: index.php?page=welcome");
        					        }else{
        					        	echo"<div class='p-5'>";
        								echo "<br/><div class='alert alert-danger' role='alert'>
        								<h3>Username/password is incorrect.</h3>
        								<br/>Click <a href='login.php'>here</a> to login.</div><br>";
        								echo"</div>";
        							}
        					    }else{
        				?>
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4"><?=$lang['login']?></h1>
                  </div>
                  <br>
                  <form class="user" action="" method="post">
                    <div class="form-group">
                      <input type="email" class="form-control" name="email" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="<?=$lang['email']?>" required>
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control" name="password" id="exampleInputPassword" placeholder="<?=$lang['password']?>" required>
                    </div>
                    <input type="submit" class="btn btn-primary btn-user btn-block" name="submit" value="<?=$lang['login']?>">
                  </form>
                  <hr>
                  <div class="text-center">
                    <div class="small"> <?=$lang['notaccount']?> <a href="register.php">(<?=$lang['register']?>)</a></div>
                  </div>
                </div>
                <?php
	              }
	            ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

</body>
</html>
