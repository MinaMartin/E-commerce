<?php
    $pageTitle="Comments";

/**Through here we can edit the members */
session_start();
if (isset ($_SESSION['Username'])){
    include 'initial.php';
    $do = isset ($_GET['do']) ? $_GET['do'] : 'Manage';

    /**Manage page */
    if ($do == 'Manage'){
        $stmt =$con ->prepare("SELECT 
                                comments.*,items.Name AS item_Name ,users.Username AS user_Name
                            FROM
                            comments
                            Inner Join
                                items
                            on
                            items.item_ID=comments.item_ID
                            Inner Join
                                users
                            on
                            users.user_ID=comments.User_ID
                            ");
        $stmt ->execute();
        $rows =$stmt ->fetchAll();
        if(!empty($rows)){
            echo "<h1 class='text-center display-4 font-weight-bold' > Manage comments </h1>";
        
        ?>
    <div class="container">
        <div class="table-responsive mt-5">
            <table class="main-table table table-hover table-bordered ">
                <tr  class="table-primary">
                    <th>Comment</th>
                    <th>Added by</th>
                    <th>Added on</th>
                    <th>Added on Item</th>
                    <th>Control </th>
                </tr>
                <?php
                foreach ($rows as $row){
                    echo "<tr>";
                    echo "<td>" .  $row['Comment']. "</td>";
                    echo "<td>" .  $row['user_Name']. "</td>";
                    echo "<td>" .  $row['Comment_Date']. "</td>";
                    echo "<td>" .  $row['item_Name']. "</td>";

                    
                    echo "<td>" . "<a href='comments.php?do=Edit&C_ID=". $row['C_ID'] ."'class='btn btn-warning'> <i class ='fas fa-edit'></i>Edit</a>
                    
                    <a href='comments.php?do=Delete&C_ID=". $row['C_ID'] ."'class='btn btn-danger confirm'> <i class ='fas fa-times'></i> Delete </a>";
                    if($row['Status'] == 0){
                        echo "<a href='comments.php?do=Approve&C_ID=" .$row['C_ID']."'class='btn btn-info ml-1'> <i class ='fas fa-check'></i>Approve </a>";
                    }
                    echo "</tr>";
                }
                ?>
            </table>
        </div>
    </div>
    <?php }else{
        echo '<div class="container">';
        echo '<div class="alert alert-info mt-3">No Comments Found </div>';
        echo '</div>';
    } ?>
        <?php
    }
    /**Edit page************************************************************* */
    elseif ($do == 'Edit'){ 
        if (isset ($_GET['C_ID'] ) && is_numeric($_GET['C_ID'])){
            $Commentid = intval($_GET['C_ID']);

            $stmt = $con ->prepare ('SELECT * FROM comments where C_ID=? LIMIT 1');
            //username =? means any username
        
            $stmt -> execute (array($Commentid));
            $row = $stmt -> fetch();
            $count = $stmt ->rowCount();
            if($count >0){         
                ?>
        <h1 class="text-center display-4 font-weight-bold"> Edit Comment </h1>
        <div class="container">
            <form  action="?do=Update" method="POST">
                <input type="hidden" name="CommentID" value=<?php echo $Commentid ?> >
                <div class="form-group row">
                    <label for="Comment" class="col-md-2 font-weight-bold col-form-label"> Comment</label>
                    <div class="col-md-6">
                        <textarea name="comment" id="Comment" cols="5" rows="5" class="form-control" required><?php echo $row['Comment']  ?></textarea>
                    </div>
                </div>

                <div class=" mx-auto text-center" >
                    <input type="submit" class="btn btn-primary btn-lg mt-5" value="Save" style="width: 200px;">
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
        echo "<h1 class='text-center display-4 font-weight-bold' > Update Comment </h1>";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            //Get variables from the form 
            $id         =$_POST ['CommentID'];
            $comment    =$_POST ['comment'];
            
            //Validate before DB
            $errorArray = array();
            echo " <div class='container' >";
            if(empty($comment)){
                $errorArray[]="Comment cannot be <strong>Empty</strong> ";
            }
            foreach ($errorArray as $error ){
                echo "<div class ='alert alert-danger'>" . $error . "</div>" . "<br />";
            }
            echo " </div>";
            //Update the DB
            if( count($errorArray) ==0 ){
                $stmt = $con ->prepare ('UPDATE comments SET Comment = ?  where C_ID=? ');
                $stmt ->execute(array($comment,$id));
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
        echo "<h1 class='text-center display-4 font-weight-bold' > Delete Comment </h1>";
        if (isset ($_GET['C_ID'] ) && is_numeric($_GET['C_ID'])){
            $commentid = intval($_GET['C_ID']);
            //$stmt= $con->prepare('SELECT * FROM users where C_ID=?');
            //$stmt->execute(array($userid));
            //$rows=$stmt->fetch();
            //$count=$stmt->rowCount();

            $check = checkitem ('C_ID' ,'comments' ,$commentid);

            if($check >0){
            $stmt= $con->prepare("DELETE FROM comments WHERE C_ID=:zid");
            $stmt->execute(array(
                /**$stmt = bindParam("zid",$userid)       another method then we $stmt->execute() */
                "zid" => $commentid));

            echo "<div class='container'>";
                $msg ="<div class ='alert alert-success'>" . $stmt->rowcount() . " Record deleted </div>";
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
    elseif ($do == 'Approve'){
        echo "<h1 class='text-center display-4 font-weight-bold' > Activate Members </h1>";
        if (isset ($_GET['C_ID'] ) && is_numeric($_GET['C_ID'])){
            $commentid = intval($_GET['C_ID']);
            //$stmt= $con->prepare('SELECT * FROM users where C_ID=?');
            //$stmt->execute(array($userid));
            //$rows=$stmt->fetch();
            //$count=$stmt->rowCount();

            $check = checkitem ('C_ID' ,'comments' ,$commentid);

            if($check >0){
            $stmt= $con->prepare("UPDATE comments SET Status = 1 WHERE C_ID=?");
            $stmt->execute(array($commentid));
                /**$stmt = bindParam("zid",$userid)       another method then we $stmt->execute() */

            echo "<div class='container'>";
                $msg ="<div class ='alert alert-success'>" . $stmt->rowcount() . " Record Approved </div>";
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