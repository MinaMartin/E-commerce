<?php
    $pageTitle="Members";

/**Through here we can edit the members */
session_start();
if (isset ($_SESSION['Username'])){
    include 'initial.php';
    $do = isset ($_GET['do']) ? $_GET['do'] : 'Manage';

    /**Manage page */
    if ($do == 'Manage'){
        
        $query='';

        if(isset($_GET['page']) && $_GET['page']=='Pending'){
            $query='AND RegStatus=0';
        }

        $stmt =$con ->prepare("SELECT * FROM users WHERE Group_ID != 1 $query");
        $stmt ->execute();
        $rows =$stmt ->fetchAll();

        if(!empty($rows)){
                echo "<h1 class='text-center display-4 font-weight-bold' > Manage members </h1>";
        ?>
        
    <div class="container">
        <div class="table-responsive mt-5">
            <table class="main-table table table-hover table-bordered ">
                <tr  class="table-primary">
                    <th> #id</th>
                    <th>Username</th>
                    <th>E-mail </th>
                    <th>Full Name </th>
                    <th>Registeration date </th>
                    <th>Control </th>
                </tr>
                <?php
                foreach ($rows as $row){
                    echo "<tr>";
                    echo "<td>" .  $row['user_ID']. "</td>";
                    echo "<td>" .  $row['Username']. "</td>";
                    echo "<td>" .  $row['Email']. "</td>";
                    echo "<td>" .  $row['Full_name']. "</td>";
                    echo "<td>" .  $row['Date']. "</td>";
                    
                    echo "<td>" . "<a href='members.php?do=Edit&user_ID=". $row['user_ID'] ."'class='btn btn-warning'> <i class ='fas fa-edit'></i>Edit</a>
                    
                    <a href='members.php?do=Delete&user_ID=". $row['user_ID'] ."'class='btn btn-danger confirm'> <i class ='fas fa-times'></i> Delete </a>";
                    if($row['RegStatus'] == 0){
                        echo "<a href='members.php?do=Activate&user_ID=" .$row['user_ID']."'class='btn btn-info ml-1'> Activate </a>";
                    }

                    //echo "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
            <a href="members.php?do=Add"class="btn btn-primary text-light"> <i class="fas fa-plus"> </i> Add new memeber</a>
        </div>
    </div>
    <?php }else{
        echo '<div class="container">';
        echo '<div class="alert alert-info">No Users Found </div>';
        echo '<a href="members.php?do=Add"class="btn btn-primary text-light mt-3"> <i class="fas fa-plus"> </i> Add new memeber</a>';
        echo '</div>';
    } ?>
        <?php
    }

/************************************************************* */
    elseif ($do == 'Add'){
        ?>
        <h1 class="text-center display-4 font-weight-bold"> Add new Member </h1>
        <div class="container">
            <form  action="?do=Insert" method="POST">
                <div class="form-group row">
                    <label for="new_member_username" class="col-md-2 font-weight-bold col-form-label"> Username</label>
                    <div class="col-md-6">
                        <input type ="text" class="form-control  mb-4" id="new_member_username" name="username" autocomplete="off" placeholder="Username"  required='required'>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="new_member_pass" class="col-md-2 font-weight-bold"> Password</label>
                    <div class="col-md-6">
                        <input type= "password" class="form-control password  mb-4" id="new_member_pass" name="password" placeholder="Password" required='required'  autocomplete="new-password">
                        <i class ="eye-icon col-md-1 fas fa-eye fa-2x"></i>
                    </div>

                </div>
                
                <div class="form-group row">
                    <label for="new_member_mail" class="col-md-2 font-weight-bold"> E-mail</label>
                    <div class="col-md-6">
                        <input type= "email" class="form-control  mb-4" id="new_member_mail" name="email" placeholder="Email"  required='required'>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="new_member_Fname" class="col-md-2 font-weight-bold"> Full Name</label>
                    <div class="col-md-6">
                        <input type= "text" class="form-control  mb-4" id="new_member_Fname" name="FullName" placeholder="Full name"  required='required'>
                    </div>
                </div> 

                <div class=" mx-auto text-center" >
                    <input type="submit" class="btn btn-primary btn-lg" value="Add new member " style="width: 200px;">
                </div>
            </form>
        </div>

        <?php
    }
/************************************************************* */
    elseif ($do == 'Insert'){
        echo "<h1 class='text-center display-4 font-weight-bold' > Insert Members </h1>";
        if ($_SERVER['REQUEST_METHOD'] =='POST'){
            $user_name  =$_POST ['username'];
            $email      =$_POST ['email'];
            $fname      =$_POST ['FullName'];
            $pass       =$_POST['password'];
            $hashpass   =sha1($pass);

            //Validate before DB
            $errorArray = array();
            echo " <div class='container' >";
            if(empty($user_name)){
                $errorArray[]="Username cannot be <strong>Empty</strong> ";
            }
            if(empty($pass )){
                $errorArray[]=" Password cannot be <strong>Empty</strong>  ";
            }
            if(empty($email)){
                $errorArray[]=" Email cannot be <strong>Empty</strong>  ";
            }
            if(empty($fname)){
                $errorArray[]=" Full name cannot be <strong>Empty</strong> ";
            }
            if(strlen($user_name) < 4){
                $errorArray[]=" Username must be <strong> at least 5 characters </strong>  ";
            }
            if(strlen($user_name) > 20){
                $errorArray[]=" Username must not be <strong> more than 20 characters </strong> ";
            }
            foreach ($errorArray as $error ){
                echo "<div class ='alert alert-danger'>" . $error . "</div>" . "<br />";
            }
            echo " </div>";

            //Update the DB
            if( count($errorArray) ==0 ){
                //checks if the user exists in the DB
                $number=checkitem("Username","users",$user_name);

                if($number == 1){
                    $MSG="<div class ='alert alert-danger text-center'>" . "Sorry, this user exists" . "  </div>";
                    redirect($MSG,'back');
                }
                else{
                $stmt=$con ->prepare('INSERT INTO 
                                        users(Username,Password,Email,Full_name,RegStatus,Date) 
                                        VALUES(:zusername,:zpass,:zemail,:zfname,1,now())');
                $stmt->execute(array(
                "zusername" => $user_name, 
                "zpass"     => $hashpass,
                "zemail"    => $email,
                "zfname"    => $fname 
            ));

            echo "<div class='container'>";
                $msg = "<div class ='alert alert-success'>" . $stmt->rowcount() . " Records inserted </div>";
                redirect ($msg ,'back');   
            echo "</div>";       
        }       
            }

        }
        else {
            echo "<div class='container'>";
                $Msg = "<div class='alert alert-danger text-center'>Sorry you Cannot browse this page directly </div>";
                redirect ($Msg);
            echo "</div>";
        }

    }
    /**Edit page************************************************************* */
    elseif ($do == 'Edit'){ 
        if (isset ($_GET['user_ID'] ) && is_numeric($_GET['user_ID'])){
            $userid = intval($_GET['user_ID']);

            $stmt = $con ->prepare ('SELECT * FROM users where user_ID=? LIMIT 1');
            //username =? means any username
        
            $stmt -> execute (array($userid));
            $row = $stmt -> fetch();
            $count = $stmt ->rowCount();
            if($count >0){         
                ?>
        <h1 class="text-center display-4 font-weight-bold"> Edit Members </h1>
        <div class="container">
            <form  action="?do=Update" method="POST">
                <input type="hidden" name="userID" value=<?php echo $userid ?> >
                <div class="form-group row">
                    <label for="username" class="col-md-2 font-weight-bold col-form-label"> Username</label>
                    <div class="col-md-6">
                        <input type ="text" class="form-control  mb-4" id="username" name="username" value=<?php echo $row['Username']?> autocomplete="off"   required='required'>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="pass" class="col-md-2 font-weight-bold"> Password</label>
                    <div class="col-md-6">
                        <input type= "hidden" class="form-control col-md-10 mb-4"  name="oldpassword" value=<?php echo $row['password']?> >
                        <input type= "password" class="form-control password  mb-4" id="pass" name="newpassword"  autocomplete="new-password">
                        <i class ="eye-icon col-md-1 fas fa-eye fa-2x"></i>
                    </div>

                </div>
                
                <div class="form-group row">
                    <label for="mail" class="col-md-2 font-weight-bold"> E-mail</label>
                    <div class="col-md-6">
                        <input type= "email" class="form-control  mb-4" id="mail" value=<?php echo $row['Email']?> name="email"   required='required'>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="Fname" class="col-md-2 font-weight-bold"> Full Name</label>
                    <div class="col-md-6">
                        <input type= "text" class="form-control  mb-4" id="Fname" name="FullName" value= <?php echo $row['Full_name']?>   required='required'>
                    </div>
                </div> 

                <div class=" mx-auto text-center" >
                    <input type="submit" class="btn btn-primary btn-lg" value="Edit" style="width: 200px;">
                </div>
            </form>
        </div>
        
            <?php
            }
            else{
                echo "<div class='container'>";
                    $msg = "<div class ='alert alert-danger text-center text-uppercase'>No ID like this</div>";
                    redirect($msg);
                echo "</div>";
            }
        }
    }
/******************************************************** */
    elseif ($do == "Update"){
        echo "<h1 class='text-center display-4 font-weight-bold' > Update Members </h1>";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            //Get variables from the form 
            $id         =$_POST ['userID'];
            $user_name  =$_POST ['username'];
            $email      =$_POST ['email'];
            $fname      =$_POST ['FullName'];

            //password
            $pass =empty($_POST ['newpassword']) ? $_POST ['oldpassword'] : sha1($_POST ['newpassword']);
            
            //Validate before DB
            $errorArray = array();
            echo " <div class='container' >";
            if(empty($user_name)){
                $errorArray[]="Username cannot be <strong>Empty</strong> ";
            }
            if(empty($email)){
                $errorArray[]=" Email cannot be <strong>Empty</strong>  ";
            }
            if(empty($fname)){
                $errorArray[]=" Full name cannot be <strong>Empty</strong> ";
            }
            if(strlen($user_name) < 4){
                $errorArray[]=" Username must be <strong> at least 5 characters </strong>  ";
            }
            if(strlen($user_name) > 20){
                $errorArray[]=" Username must not be <strong> more than 20 characters </strong> ";
            }
            foreach ($errorArray as $error ){
                echo "<div class ='alert alert-danger'>" . $error . "</div>" . "<br />";
            }
            echo " </div>";
            //Update the DB
            if( count($errorArray) == 0 ){
                $stmt2 = $con ->prepare ('SELECT * FROM users WHERE Username=? AND user_ID !=? ');
                $stmt2 ->execute(array($user_name,$id));
                $count = $stmt2->rowCount();
                if($count == 1){
                    echo "<div class='container'>";
                        $msg = "<div class ='alert alert-danger'>Sorry this user name exists, please choose another name </div>";
                        redirect($msg,"back",5);
                    echo "</div>";
                }else{
                    $stmt = $con ->prepare ('UPDATE users SET Username = ? , Email = ? , Full_Name=? ,Password=? where user_ID=? ');
                    $stmt ->execute(array($user_name,$email,$fname,$pass,$id));
                }
            }
            
            echo "<div class='container'>";
                $msg = "<div class ='alert alert-success'>" . $stmt->rowcount() . " Records updated </div>";
                redirect($msg,"back");
            echo "</div>";
        }
        else{
            echo "<div class='container'>";
                $msg = "<div class ='alert alert-danger'>Sorry you Cannot browse this page directly</div>";
                redirect($msg);
            echo "</div>";
        }

/******************************************************** */
    } elseif ($do == 'Delete'){
        echo "<h1 class='text-center display-4 font-weight-bold' > Delete Member </h1>";
        if (isset ($_GET['user_ID'] ) && is_numeric($_GET['user_ID'])){
            $userid = intval($_GET['user_ID']);
            //$stmt= $con->prepare('SELECT * FROM users where user_ID=?');
            //$stmt->execute(array($userid));
            //$rows=$stmt->fetch();
            //$count=$stmt->rowCount();

            $check = checkitem ('user_ID' ,'users' ,$userid);

            if($check >0){
            $stmt= $con->prepare("DELETE FROM users WHERE user_ID=:zid");
            $stmt->execute(array(
                /**$stmt = bindParam("zid",$userid)       another method then we $stmt->execute() */
                "zid" => $userid));

            echo "<div class='container'>";
                $msg ="<div class ='alert alert-success'>" . $stmt->rowcount() . " Record(s) deleted </div>";
                redirect($msg,"back");
            echo "</div>";
            }
        }
        else {
            echo "<div class='container'>";
                $msg = "<div class ='alert alert-danger text-center text-uppercase'>No ID like this</div>";
                redirect($msg);
            echo "</div>";
        }
    }
/******************************************************** */  
    elseif ($do == 'Activate'){
        echo "<h1 class='text-center display-4 font-weight-bold' > Activate Members </h1>";
        if (isset ($_GET['user_ID'] ) && is_numeric($_GET['user_ID'])){
            $userid = intval($_GET['user_ID']);
            //$stmt= $con->prepare('SELECT * FROM users where user_ID=?');
            //$stmt->execute(array($userid));
            //$rows=$stmt->fetch();
            //$count=$stmt->rowCount();

            $check = checkitem ('user_ID' ,'users' ,$userid);

            if($check >0){
            $stmt= $con->prepare("UPDATE users SET RegStatus = 1 WHERE user_ID=?");
            $stmt->execute(array($userid));
                /**$stmt = bindParam("zid",$userid)       another method then we $stmt->execute() */

            echo "<div class='container'>";
                $msg ="<div class ='alert alert-success'>" . $stmt->rowcount() . " Record(s) Activated </div>";
                redirect($msg,"back");
            echo "</div>";
            }
        }
        else {
            echo "<div class='container'>";
                $msg = "<div class ='alert alert-danger text-center text-uppercase'>No ID like this</div>";
                redirect($msg);
            echo "</div>";
        }
    }
/******************************************************** */

    include $tmpl . "footer.php" ;  /**So jQuery is included and bootstrap JS is included as well */

}
else {
    echo "You are not authorized";
    header('Location:index.php');
    exit();
}

?>