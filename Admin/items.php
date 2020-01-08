<?php
/******************************* */
////////****************** Items page */
/******************************* */
ob_start(); //output buffering start
session_start();
$pageTitle="";
if (isset ($_SESSION['Username'])){
    
    include 'initial.php';
    include $tmpl . "footer.php" ;  /**So jQuery is included and bootstrap JS is included as well */

    $do = isset ($_GET['do']) ? $_GET['do'] : 'Manage';

    if ($do == 'Manage'){
        
        $query='';

        $stmt =$con ->prepare("
                    SELECT 
                        items.* , categories.Name as Cat_Name , users.Username as Users_name 
                    FROM 
                        items
                    INNER JOIN
                        categories 
                    ON 
                        categories.ID = items.Cat_ID
                    INNER JOIN 
                        users 
                    ON  
                    users.user_ID = items.Member_ID
        ");
        $stmt ->execute();
        $items =$stmt ->fetchAll();
        if(!empty($items)){
            echo "<h1 class='text-center display-4 font-weight-bold' > Items members </h1>";
        ?>
    <div class="container">
        <div class="table-responsive mt-5">
            <table class="main-table table table-hover table-bordered ">
                <tr  class="table-primary">
                    <th> #id</th>
                    <th>Name</th>
                    <th>Description </th>
                    <th>Price </th>
                    <th>Adding date </th>
                    <th>Category </th>
                    <th>Added By </th>
                    <th>Control </th>
                </tr>
                <?php
                foreach ($items as $row){
                    echo "<tr>";
                    echo "<td>" .  $row['item_ID']. "</td>";
                    echo "<td>" .  $row['Name']. "</td>";
                    echo "<td>" .  $row['Description']. "</td>";
                    echo "<td>" .  $row['Price']. "</td>";
                    echo "<td>" .  $row['Add_Date']. "</td>";
                    echo "<td>" .  $row['Cat_Name']. "</td>";
                    echo "<td>" .  $row['Users_name']. "</td>";


                    echo "<td>" . "<a href='items.php?do=Edit&item_ID=".
                     $row['item_ID'] .
                     "'class='btn btn-warning'> <i class ='fas fa-edit'></i>Edit</a>
                    
                    <a href='items.php?do=Delete&item_ID=".
                     $row['item_ID'] .
                     "'class='btn btn-danger confirm'> <i class ='fas fa-times'></i> Delete </a>";
                    if($row['Approve'] == 0){
                        echo "<a href='items.php?do=Approve&item_ID=" .
                        $row['item_ID'].
                        "'class='btn btn-info ml-1'> <i class ='fas fa-check'></i> Approve</a>";
                    }
                    echo "</tr>";
                }
                ?>
            </table>
            <a href="items.php?do=Add"class="btn btn-primary text-light"> <i class="fas fa-plus"> </i> Add new item</a>
        </div>
    </div>
    <?php }else{
        echo '<div class="container">';
        echo '<div class="alert alert-info">No Items Found </div>' ;
        echo '<a href="items.php?do=Add"class="btn btn-primary text-light mt-3"> <i class="fas fa-plus"> </i> Add new item</a>';
        echo '</div>';
    } ?>
        <?php
    }
    elseif ($do == 'Add'){
        ?>

        <h1 class="text-center display-4 font-weight-bold"> Add new Item </h1>
        <div class="container">
            <form  action="?do=Insert" method="POST">

                <div class="form-group row">
                    <label for="new_item_name" 
                    class="col-md-2 font-weight-bold col-form-label">item Name</label>
                    <div class="col-md-6">
                        <input type ="text" class="form-control  mb-3" 
                        id="new_item_name" 
                        name="Name" autocomplete="off" placeholder="Item Name"  
                        required='required'>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="new_item_description" 
                    class="col-md-2 font-weight-bold col-form-label">Description</label>
                    <div class="col-md-6">
                        <input type ="text" class="form-control  mb-3" 
                        id="new_item_description" 
                        name="description" autocomplete="off" placeholder="Description of Item "  
                        required='required'>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="new_item_price" 
                    class="col-md-2 font-weight-bold col-form-label">Price</label>
                    <div class="col-md-6">
                        <input type ="text" class="form-control  mb-3" 
                        id="new_item_price" 
                        name="price" autocomplete="off" placeholder="item price"  
                        required='required'>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="new_item_country" 
                    class="col-md-2 font-weight-bold col-form-label">Made in</label>
                    <div class="col-md-6">
                        <input type ="text" class="form-control  mb-3" 
                        id="new_item_country" 
                        name="country" autocomplete="off" placeholder="Where the item is made ?"  
                        >
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="new_item_username" 
                    class="col-md-2 font-weight-bold col-form-label">Stauts</label>
                    <div class="col-md-6 col-sm-10">
                        <select name="status" class="form-control">
                        <option value="0">...</option>
                        <option value="1">Available </option>
                        <option value="2">New </option>
                        <option value="3">Used </option>
                        <option value="4">Out of stock </option>
                        </select>   
                    </div>
                </div>

                <div class="form-group row">
                    <label for="new_item_username" 
                    class="col-md-2 font-weight-bold col-form-label">Member</label>
                    <div class="col-md-6  col-sm-10">
                        <select name="member" class="form-control">
                            <option value="">...</option>
                            <?php 
                                $stmt = $con ->prepare('SELECT * from users');
                                $stmt ->execute();
                                $users = $stmt-> fetchAll();

                                foreach($users as $user){
                                    echo "<option value='";
                                    echo $user["user_ID"];
                                    echo "'> ";
                                    echo $user["Username"] ; 
                                    echo "</option> ";
                                }
                            ?>          
                        </select>   
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="new_item_username" 
                    class="col-md-2 font-weight-bold col-form-label">Category</label>
                    <div class="col-md-6  col-sm-10">
                        <select name="category" class="form-control">
                            <option value="">...</option>
                            <?php 
                                $stmt = $con ->prepare('SELECT * from categories');
                                $stmt ->execute();
                                $categories = $stmt-> fetchAll();

                                foreach($categories as $category){
                                    echo "<option value='";
                                    echo $category["ID"];
                                    echo "'> ";
                                    echo $category["Name"] ; 
                                    echo "</option> ";
                                }
                            ?>          
                        </select>   
                    </div>
                </div>

                <div class=" mx-auto text-center" >
                    <input type="submit" class="btn btn-primary btn-lg" value="Add new Item " style="width: 200px;">
                </div>

            </form>
        </div>

        <?php
    }
    elseif ($do == 'Insert'){
        echo "<h1 class='text-center display-4 font-weight-bold' > Insert item </h1>";
        if ($_SERVER['REQUEST_METHOD'] =='POST'){

            $name       =$_POST ['Name'];
            $desc       =$_POST ['description'];
            $price      =$_POST ['price'];
            $country    =$_POST['country'];
            $status     = $_POST['status'];
            $member     = $_POST['member'];
            $category     = $_POST['category'];

            //Validate before DB
            $errorArray = array();
            echo " <div class='container' >";
            if(empty($name)){
                $errorArray[]="Name cannot be <strong>Empty</strong> ";
            }
            if(empty($desc )){
                $errorArray[]=" Description cannot be <strong>Empty</strong>  ";
            }
            if(empty($price)){
                $errorArray[]=" Price cannot be <strong>Empty</strong>  ";
            }
            if(empty($country)){
                $errorArray[]=" Country cannot be <strong>Empty</strong> ";
            }
            if($status == 0){
                $errorArray[]=" You must choose the <strong>Status</strong> ";
            }
            if($member == 0){
                $errorArray[]=" You must choose the <strong>Member</strong> ";
            }
            if($category == 0){
                $errorArray[]=" You must choose the <strong>Category</strong> ";
            }
            foreach ($errorArray as $error ){
                echo "<div class ='alert alert-danger'>" . $error . "</div>" . "<br />";
            }
            echo " </div>";

            //Update the DB
            if( count($errorArray) ==0 ){
                $stmt=$con ->prepare('INSERT INTO 
                                        items(Name,Description,Price,Country_made,Status,Add_Date,Cat_ID,Member_ID) 
                                        VALUES(:zname,:zdesc,:zprice,:zcountry,:zstatus,now(),:zcat,:zmember)');
                $stmt->execute(array(
                "zname"         => $name, 
                "zdesc"         => $desc,
                "zprice"        => $price,
                "zcountry"      => $country,
                "zstatus"       => $status,
                "zcat"          =>$category,
                "zmember"       =>$member         
            ));

            echo "<div class='container'>";
                $msg = "<div class ='alert alert-success'>" . $stmt->rowcount() . " Records inserted </div>";
                redirect ($msg ,'back');   
            echo "</div>";       
              
            }

        }
        else {
            echo "<div class='container'>";
                $Msg = "<div class='alert alert-danger text-center'>Sorry you Cannot browse this page directly </div>";
                redirect ($Msg);
            echo "</div>";
        }
    }
    /*************************************************************************** */
    elseif ($do == 'Edit'){ 
        if (isset ($_GET['item_ID'] ) && is_numeric($_GET['item_ID'])){
            $itemid = intval($_GET['item_ID']);

            $stmt = $con ->prepare ('SELECT * FROM items where item_ID=? LIMIT 1');    
            $stmt -> execute (array($itemid));
            $row = $stmt -> fetch();
            $count = $stmt ->rowCount();

            if($count >0){         
                ?>
        <h1 class="text-center display-4 font-weight-bold"> Edit items </h1>
        <div class="container">
            <form  action="?do=Update" method="POST">
                <input type="hidden" name="itemID" value=<?php echo $itemid ?> >
                <div class="form-group row">
                    <label for="itemname" class="col-md-2 font-weight-bold col-form-label"> Item name</label>
                    <div class="col-md-6">
                        <input type ="text" class="form-control  mb-4" id="itemname" name="Name" value=<?php echo $row['Name']?> autocomplete="off" >
                    </div>
                </div>                
                <div class="form-group row">
                    <label for="desc" class="col-md-2 font-weight-bold"> Description</label>
                    <div class="col-md-6">
                        <input type= "text" class="form-control  mb-4" id="desc" name="description" value=<?php echo $row['Description']?>>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="price" class="col-md-2 font-weight-bold"> Price</label>
                    <div class="col-md-6">
                        <input type= "text" class="form-control  mb-4" id="price" name="price" value= <?php echo $row['Price']?> >
                    </div>
                </div> 

                <div class="form-group row">
                    <label for="country" class="col-md-2 font-weight-bold"> Made In</label>
                    <div class="col-md-6">
                        <input type= "text" class="form-control  mb-4" id="country" name="country" value= <?php echo $row['Country_Made']?> >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="new_item_username" 
                    class="col-md-2 font-weight-bold col-form-label">Stauts</label>
                    <div class="col-md-6 col-sm-10">
                        <select name="status" class="form-control">
                            <option value="1" <?php  if($row['Status'] == 1) echo 'selected'?> >Available </option>
                            <option value="2" <?php  if($row['Status'] == 2) echo 'selected'  ?> >New </option>
                            <option value="3" <?php  if($row['Status'] == 3) echo 'selected'  ?>>Used </option>
                            <option value="4" <?php  if($row['Status'] == 4) echo 'selected'  ?>>Out of stock </option>
                        </select>   
                    </div>
                </div>

                <div class="form-group row">
                    <label for="new_item_username" 
                    class="col-md-2 font-weight-bold col-form-label">Member</label>
                    <div class="col-md-6  col-sm-10">
                        <select name="member" class="form-control">
                            <?php 
                                $stmt = $con ->prepare('SELECT * from users');
                                $stmt ->execute();
                                $users = $stmt-> fetchAll();

                                foreach($users as $user){
                                    echo "<option value='";
                                    echo $user["user_ID"] . "'";
                                    if($row['Member_ID'] == $user["user_ID"]) echo 'selected';
                                    echo ">" .$user["Username"] ; 
                                    echo "</option> ";
                                }
                            ?>          
                        </select>   
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="new_item_username" 
                    class="col-md-2 font-weight-bold col-form-label">Category</label>
                    <div class="col-md-6  col-sm-10">
                        <select name="category" class="form-control">
                            <?php 
                                $stmt = $con ->prepare('SELECT * from categories');
                                $stmt ->execute();
                                $categories = $stmt-> fetchAll();

                                foreach($categories as $category){
                                    echo "<option value='";
                                    echo $category["ID"] . "'";
                                    if($row['Cat_ID'] == $category["ID"]) echo 'selected';
                                    echo '>'. $category["Name"] ; 
                                    echo "</option> ";
                                }
                            ?>          
                        </select>   
                    </div>
                </div>

                <div class=" mx-auto text-center" >
                    <input type="submit" class="btn btn-primary btn-lg" value="Save item" style="width: 200px;">
                </div>
            </form>

            <!---------------------------------------------------------->
            <?php
            
            $stmt =$con ->prepare("SELECT 
                                comments.* ,users.Username AS user_Name
                            FROM
                            comments
                            Inner Join
                                users
                            on
                            users.user_ID=comments.User_ID
                            WHERE Item_ID = ?
                            ");
            $stmt ->execute(array($itemid));
            $rows =$stmt ->fetchAll();

            if(!empty($rows)){
                echo "<h1 class='text-center font-weight-bold' > Manage [ ".$row['Name']." ]comments </h1>";
            ?>
        
            <div class="table-responsive mt-5">
                <table class="main-table table table-hover table-bordered ">
                    <tr  class="table-primary">
                        <th>Comment</th>
                        <th>Added by</th>
                        <th>Added on</th>
                        <th>Control </th>
                    </tr>
                    <?php
                    foreach ($rows as $row){
                        echo "<tr>";
                        echo "<td>" .  $row['Comment']. "</td>";
                        echo "<td>" .  $row['user_Name']. "</td>";
                        echo "<td>" .  $row['Comment_Date']. "</td>";


                        
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
            <?php }else{
                echo "<div class = 'alert alert-info mt-3'>No Comments for this item</div>";
            } ?>
        <!------------------------------------------------------>
    </div>
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
    /*************************************************************************** */
    elseif ($do == "Update"){
        echo "<h1 class='text-center display-4 font-weight-bold' > Update items </h1>";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            //Get variables from the form 
            $id             =$_POST ['itemID'];
            $name           =$_POST ['Name'];
            $desc           =$_POST ['description'];
            $price          =$_POST ['price'];
            $country        =$_POST['country'];
            $status         = $_POST['status'];
            $member         = $_POST['member'];
            $category       = $_POST['category'];
            
            //Validate before DB
            $errorArray = array();
            echo " <div class='container' >";
                if(empty($name)){
                    $errorArray[]="Name cannot be <strong>Empty</strong> ";
                }
                if(empty($desc )){
                    $errorArray[]=" Description cannot be <strong>Empty</strong>  ";
                }
                if(empty($price)){
                    $errorArray[]=" Price cannot be <strong>Empty</strong>  ";
                }
                if(empty($country)){
                    $errorArray[]=" Country cannot be <strong>Empty</strong> ";
                }
                if($status == 0){
                    $errorArray[]=" You must choose the <strong>Status</strong> ";
                }
                if($member == 0){
                    $errorArray[]=" You must choose the <strong>Member</strong> ";
                }
                if($category == 0){
                    $errorArray[]=" You must choose the <strong>Category</strong> ";
                }
                foreach ($errorArray as $error ){
                    echo "<div class ='alert alert-danger'>" . $error . "</div>" . "<br />";
                }
            echo " </div>";

            //Update the DB
            if( count($errorArray) ==0 ){
                $stmt = $con ->prepare ('UPDATE items SET 
                Name = ? , Description = ? ,
                Price=? ,Country_Made=?,
                Status=?,Cat_ID=?,Member_ID=? where item_ID=? ');
                $stmt ->execute(array($name,$desc,$price,$country,$status,$category,$member,$id));
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
    }

    elseif ($do == 'Delete'){
        echo "<h1 class='text-center display-4 font-weight-bold' > Delete item </h1>";
        if (isset ($_GET['item_ID'] ) && is_numeric($_GET['item_ID'])){
            $itemid = intval($_GET['item_ID']);
            //$stmt= $con->prepare('SELECT * FROM users where user_ID=?');
            //$stmt->execute(array($userid));
            //$rows=$stmt->fetch();
            //$count=$stmt->rowCount();

            $check = checkitem ('item_ID' ,'items' ,$itemid);

            if($check >0){
            $stmt= $con->prepare("DELETE FROM items WHERE item_ID=:zid");
            $stmt->execute(array(
                /**$stmt = bindParam("zid",$userid)       another method then we $stmt->execute() */
                "zid" => $itemid));

            echo "<div class='container'>";
                $msg ="<div class ='alert alert-success'>" . $stmt->rowcount() . " Record deleted </div>";
                redirect($msg,"back");
            echo "</div>";
            }else{
                echo "<div class='container'>";
                    $msg = "<div class ='alert alert-danger text-center text-uppercase'>No ID like this</div>";
                    redirect($msg);
                echo "</div>";
            }
        }
        else {
            echo "<div class='container'>";
                $msg = "<div class ='alert alert-danger text-center text-uppercase'>No ID like this</div>";
                redirect($msg,"back");
            echo "</div>";
        }
    }

    elseif ($do == 'Approve'){
        echo "<h1 class='text-center display-4 font-weight-bold' > Approve items </h1>";
        if (isset ($_GET['item_ID'] ) && is_numeric($_GET['item_ID'])){
            $itemid = intval($_GET['item_ID']);
            //$stmt= $con->prepare('SELECT * FROM users where user_ID=?');
            //$stmt->execute(array($userid));
            //$rows=$stmt->fetch();
            //$count=$stmt->rowCount();

            $check = checkitem ('item_ID' ,'items' ,$itemid);

            if($check >0){
            $stmt= $con->prepare("UPDATE items SET Approve = 1 WHERE item_ID=?");
            $stmt->execute(array($itemid));
                /**$stmt = bindParam("zid",$userid)       another method then we $stmt->execute() */

            echo "<div class='container'>";
                $msg ="<div class ='alert alert-success'>" . $stmt->rowcount() . " Record Activated </div>";
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
}
else {
    echo "You are not authorized";
    header('Location:index.php');
    exit();
}

ob_end_flush(); //release the output
?>
