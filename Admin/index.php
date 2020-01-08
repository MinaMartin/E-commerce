<?php

session_start();
$noNavbar=" ";
$pageTitle="Login";

if (isset ($_SESSION['Username'])){
    header('Location:dashboard.php ');
}
include "initial.php";
include  $tmpl . "Header.php" ;



if ($_SERVER['REQUEST_METHOD'] == "POST"){
    $username =$_POST['user'];
    $password =$_POST['pass'];
    $hashthepass=sha1($password);    /**hashing */

    //check if the user in the DB
    $stmt = $con ->prepare ('SELECT user_ID,Username,
                            Password FROM users 
                            where Username= ? AND Password=? AND Group_ID =1 ');
    $stmt -> execute (array($username,$hashthepass));
    $row = $stmt -> fetch();
    $count = $stmt ->rowCount();
    
    //if $count > 0 this mean the DB contains record about the user 

    if ($count > 0){
        $_SESSION['Username']= $username; //register session name 
        $_SESSION['ID']= $row['user_ID']; //register session ID
        header('Location:dashboard.php'); //redirect to specific page
        exit();
    }
}
?>
    <h2 class="text-center"> Login Form</h2>
    <form class="login" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
    <input class="form-control" type="text" name="user" placeholder="Username" autocomplete="off">
    <input class="form-control" type="password" name="pass" placeholder="Password" autocomplete="new password">
    <input class="btn btn-primary btn-block" type="submit" name="login">
    </form>

<?php
include $tmpl . "footer.php" ;
?>