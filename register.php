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

  <title><?=$lang['title']?> - <?=$lang['register']?></title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

  <div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5">
      <div class="card-body p-0">
        <!-- Nested Row within Card Body -->
        <div class="row">
        <div class="col-lg-3"></div>
          <div class="col-lg-6">
            <?php
              if (isset($_REQUEST['name'])){
                $name = stripslashes($_REQUEST['name']);
                $name = mysqli_real_escape_string($conn,$name); 
                $email = stripslashes($_REQUEST['email']);
                $email = mysqli_real_escape_string($conn,$email);
                $password = stripslashes($_REQUEST['password']);
                $password = mysqli_real_escape_string($conn,$password);
                $status = 1;
                mysqli_query($conn,"set names 'utf8'");
                // check the email already exist or not
                $checkemail = "SELECT email from users where email='$email'";
                $checkemailres = mysqli_query($conn, $checkemail);
                if (mysqli_num_rows($checkemailres) > 0) {
                    echo"<div class='p-5'>";
                    echo"<br/><div class='alert alert-danger' role='alert'>";
                    echo $lang['erroremailregister']."<br>";
                    echo"</div>";                   
                    echo "<p><p><a href='register.php'><button type='button' class='btn btn-primary'>".$lang['register']."</button></a>"; 
                    echo"</div>";
                }else{  
                  $created_on = date("Y-m-d H:i:s");
                  mysqli_query($conn,"set names 'utf8'");
                  $query = "INSERT into `users` (name, password, email, created_on, status) VALUES ('$name', '".md5($password)."', '$email', '$created_on', '$status')";
                  $result = mysqli_query($conn,$query);
                  if($result){
                    echo"<div class='p-5'>";
                    echo "<div class='alert alert-success' role='alert'><h3>".$lang['successregister']."</h3> <br/><a href='login.php'>(".$lang['loginhere'].")</a></div><br>";
                    echo"</div>";
                  }
                }
              }else{
            ?>
            <div class="p-5">
              <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4"><?=$lang['register']?></h1>
              </div>
              <form class="user" action="" method="post">
                <div class="form-group">
                    <input type="text" class="form-control" name="name" id="name" placeholder="<?=$lang['name']?>" required>
                </div>
                <div class="form-group">
                  <input type="email" class="form-control" name="email" id="email" placeholder="<?=$lang['email']?>" required>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" id="password" placeholder="<?=$lang['password']?>" required>
                </div>
                <input type="submit" class="btn btn-primary btn-user btn-block" name="submit" value="<?=$lang['register']?>">
              </form>
              <hr>
              <div class="text-center">
                <div class="small"><?=$lang['haveaccount']?> <a href="login.php">(<?=$lang['loginhere']?>)</a></div>
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

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

</body>
</html>