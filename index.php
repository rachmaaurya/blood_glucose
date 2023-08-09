<!DOCTYPE html>
<html lang="en">
<?php  
    session_start();//session starts here  
    if (!isset($_SESSION['id'])){
        header("Location: login.php");
    }

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
<?php include 'header.php';?>

        <?php
        
        if(isset($_GET['page'])) {
            $page=$_GET['page'];
            if (!file_exists("$page.php")){
                include "welcome.php";
            } else {
                include "$page.php";
            }
        }else{
            include "welcome.php";
        }
        ?>

<?php include 'footer.php';?>

</body>

</html>
