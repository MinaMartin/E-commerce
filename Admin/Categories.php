<?php
/******************************* */
////////****************** Categories page */
/******************************* */
ob_start(); //output buffering start
session_start();
$pageTitle="Categories";
if (isset ($_SESSION['Username'])){
    
    include 'initial.php';
    include $tmpl . "footer.php" ;  /**So jQuery is included and bootstrap JS is included as well */

    $do = isset ($_GET['do']) ? $_GET['do'] : 'Manage';

/******************************************************** */
    if ($do == 'Manage'){
        echo "<h1 class='text-center display-4 font-weight-bold' > Manage Categories </h1>";
        $query='';

        if(isset($_GET['page']) && $_GET['page']=='Pending'){
            $query='AND RegStatus=0';
        }

        $sort="ASC";
        $sort_array=array('ASC','DESC');

        if(isset($_GET['sort']) && in_array($_GET['sort'],$sort_array) ){
            $sort=$_GET['sort'];
        }
        $stmt =$con ->prepare("SELECT * FROM categories ORDER BY Ordering $sort");
        $stmt ->execute();
        $catgs =$stmt ->fetchAll();
        ?>
        <div class="container">

            <div class='ordering'>
                <i class="fas fa-sort"></i> <span style="position:relative; bottom:3px">Order By:</span>
                <a href="categories.php?sort=Asc" class="<?php if($sort=="ASC") echo "active"; ?> asc"> ASC </a>
                <a href="categories.php?sort=DESC" class="<?php if($sort=="DESC") echo "active"; ?>"> DESC</a>
            </div>
            <?php 
                foreach($catgs as $cat){
                echo '<div class="card text-dark bg-light catg mb-1">';

                    echo   '<div class="card-header">';
                    echo        $cat["Name"];
                    echo        '</div>';

                    echo   '<div class="card-body">';

                        echo   '<div class="hidden-buttons">';
                        echo   "<a href='categories.php?do=Edit&ID="
                        . $cat['ID'] 
                        ."'class='btn btn-warning float-right'> <i class ='fas fa-edit'></i>Edit</a>";
                        echo "<a href='categories.php?do=Delete&ID="
                        . $cat['ID']
                        ."'class='btn btn-danger float-right confirm'> <i class ='fas fa-times'></i> Delete </a>";
                        echo        '</div>';

                        echo   $cat["Description"] . "</br>";
                        if($cat["Visibility"] ==1) echo "<span class='Hidden '><i class='fas fa-eye'> </i>Hidden</span>";
                        if($cat["Allow_Comment"] ==1) echo "<span class='Commenting  '><i class='fas fa-times'></i>Comment disabled</span>";
                        if($cat["Allow_Ads"] ==1) echo "<span class='Ads '><i class='fas fa-times'></i>Ads disabled</span>";
                        
                    echo '</div>';

                echo '</div>';
                    
                
                }
                
            ?>
        <a href="Categories.php?do=Add"class="btn btn-info text-light mt-4 mb-4"> <i class="fas fa-plus"> </i> Add new category</a>
        </div>

        <?php
    }
    
/******************************************************** */
    elseif ($do == 'Add'){
        ?>

        <h1 class="text-center display-4 font-weight-bold"> Add new Category </h1>
        <div class="container">
            <form  action="?do=Insert" method="POST">
                <div class="form-group row">
                    <label for="new_member_username" class="col-md-2 font-weight-bold col-form-label">Category Name</label>
                    <div class="col-md-6">
                        <input type ="text" class="form-control  mb-4" id="new_member_username" 
                        name="Name" autocomplete="off" placeholder="Category Name"  required='required'>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="new_member_pass" class="col-md-2 font-weight-bold"> Description</label>
                    <div class="col-md-6">
                        <input type= "text" class="form-control description  mb-4" id="new_member_pass" 
                        name="Description" placeholder="description of the category">
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="new_member_mail" class="col-md-2 font-weight-bold">Ordering</label>
                    <div class="col-md-6">
                        <input type= "text" class="form-control  mb-4" id="new_member_mail" 
                        name="number" placeholder="order of the category">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="new_member_Fname" class="col-md-2 font-weight-bold"> Visibility</label>
                    <div class="col-md-6">
                        <div>
                            <input type= "radio"  id="visible" value="0" name="visibility" checked>
                            <label for="visible">Yes </label>
                        </div>
                        <div>
                            <input type= "radio"  id="not-visible" value="1" name="visibility">
                            <label for="not-visible">No </label>
                        </div>
                    </div>
                </div> 

                <div class="form-group row">
                    <label for="new_member_Fname" class="col-md-2 font-weight-bold">Allow Comments</label>
                    <div class="col-md-6">
                        <div>
                            <input type= "radio"  id="commented" value="0" name="commenting" checked>
                            <label for="commented">Yes </label>
                        </div>
                        <div>
                            <input type= "radio"  id="not-commented" value="1" name="commenting">
                            <label for="not-commented">No </label>
                        </div>
                    </div>
                </div> 

                <div class="form-group row">
                    <label for="new_member_Fname" class="col-md-2 font-weight-bold">Allow Ads</label>
                    <div class="col-md-6">
                        <div>
                            <input type= "radio"  id="Ads" value="0" name="Ads" checked>
                            <label for="Ads">Yes </label>
                        </div>
                        <div>
                            <input type= "radio"  id="no-Ads" value="1" name="Ads">
                            <label for="no-Ads">No </label>
                        </div>
                    </div>
                </div>      
                <div class=" mx-auto text-center" >
                    <input type="submit" class="btn btn-primary btn-lg" value="Add new category " style="width: 200px;">
                </div>
            </form>
        </div>

        <?php
    }
/******************************************************** */
    elseif ($do == 'Insert'){

        echo "<h1 class='text-center display-4 font-weight-bold' > Insert Category </h1>";
        if ($_SERVER['REQUEST_METHOD'] =='POST'){
            $name       =$_POST ['Name'];
            $desc       =$_POST ['Description'];
            $order      =$_POST ['number'];
            $visible    =$_POST['visibility'];
            $comment    =$_POST['commenting'];
            $ad         =$_POST['Ads'];
            

            //Validate before DB
            $errorArray = array();
            echo " <div class='container' >";
            if(empty($name)){
                $errorArray[]="Category cannot be <strong>Empty</strong> ";
            }
            foreach ($errorArray as $error ){
                echo "<div class ='alert alert-danger'>" . $error . "</div>" . "<br />";
            }
            echo " </div>";

            //Update the DB
            if( count($errorArray) ==0 ){

                //checks if the category exists in the DB
                $check = checkitem ('Name' ,'categories' ,$name);
                if($check > 0){
                    $MSG="<div class ='alert alert-danger text-center'>" . "Sorry, this category exists" . "  </div>";
                    redirect($MSG,'back');
                }
                else{
                $stmt=$con ->prepare("INSERT INTO 
                                        categories(Name,Description,Ordering,Visibility,Allow_Comment,Allow_Ads) 
                                        VALUES(:zname,:zdisc,:zorder,:zvisibe,:zcomment,:zad)");
                $stmt->execute(array(
                "zname"     => $name, 
                "zdisc"     => $desc,
                "zorder"    => $order,
                "zvisibe"   => $visible,
                "zcomment"  => $comment,
                "zad"       => $ad
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
/************************************************************************************ */
    elseif ($do == 'Edit'){ 
        if (isset ($_GET['ID'] ) && is_numeric($_GET['ID'])){
            $catgid = intval($_GET['ID']);

            $stmt = $con ->prepare ('SELECT * FROM categories where ID=? LIMIT 1');
            //username =? means any username
        
            $stmt -> execute (array($catgid));
            $catg = $stmt -> fetch();
            $count = $stmt ->rowCount();

            if($count >0){         
                ?>
                    <h1 class="text-center display-4 font-weight-bold"> Edit Categories </h1>
                    <div class="container">
            <form  action="?do=Update" method="POST">
                <input type="hidden" name="catid" value="<?php  echo $catgid; ?>" />
                <div class="form-group row">
                    <label for="new_member_username" class="col-md-2 font-weight-bold col-form-label">Category Name</label>
                    <div class="col-md-6">
                        <input type ="text" class="form-control  mb-4" id="new_member_username" 
                        name="Name" autocomplete="off" value=" <?php  echo $catg['Name']; ?>"  />
                    </div>
                </div>

                <div class="form-group row">
                    <label for="new_member_pass" class="col-md-2 font-weight-bold"> Description</label>
                    <div class="col-md-6">
                        <input type= "text" class="form-control description  mb-4" id="new_member_pass" 
                        name="Description" placeholder="Description of the category" value=" <?php  echo $catg['Description']; ?>">
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="new_member_mail" class="col-md-2 font-weight-bold">Ordering</label>
                    <div class="col-md-6">
                        <input type= "text" class="form-control  mb-4" id="new_member_mail" 
                        name="number" placeholder="order of the category" value=" <?php  echo $catg['Ordering']; ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="new_member_Fname" class="col-md-2 font-weight-bold"> Visibility</label>
                    <div class="col-md-6">
                        <div>
                            <input type= "radio"  id="visible" value="0" name="visibility" <?php if(!$catg['Visibility']) echo "checked"; ?> >
                            <label for="visible">Yes </label>
                        </div>
                        <div>
                            <input type= "radio"  id="not-visible" value="1" name="visibility" <?php if($catg['Visibility']) echo "checked"; ?>>
                            <label for="not-visible">No </label>
                        </div>
                    </div>
                </div> 

                <div class="form-group row">
                    <label for="new_member_Fname" class="col-md-2 font-weight-bold">Allow Comments</label>
                    <div class="col-md-6">
                        <div>
                            <input type= "radio"  id="commented" value="0" name="commenting" <?php if(!$catg['Allow_Comment']) echo "checked"; ?>>
                            <label for="commented">Yes </label>
                        </div>
                        <div>
                            <input type= "radio"  id="not-commented" value="1" name="commenting" <?php if($catg['Allow_Comment']) echo "checked"; ?>>
                            <label for="not-commented">No </label>
                        </div>
                    </div>
                </div> 

                <div class="form-group row">
                    <label for="new_member_Fname" class="col-md-2 font-weight-bold">Allow Ads</label>
                    <div class="col-md-6">
                        <div>
                            <input type= "radio"  id="Ads" value="0" name="Ads" <?php if(!$catg['Allow_Ads']) echo "checked"; ?>>
                            <label for="Ads">Yes </label>
                        </div>
                        <div>
                            <input type= "radio"  id="no-Ads" value="1" name="Ads" <?php if($catg['Allow_Ads']) echo "checked"; ?>>
                            <label for="no-Ads">No </label>
                        </div>
                    </div>
                </div>      
                <div class=" mx-auto text-center" >
                    <input type="submit" class="btn btn-primary btn-lg" value="Edit the category" style="width: 200px;">
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
/************************************************************************************ */
    elseif ($do == "Update"){
        echo "<h1 class='text-center display-4 font-weight-bold' > Update Category </h1>";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            //Get variables from the form 
            $catid          = $_POST ['catid'];
            $name           =$_POST ['Name'];
            $desc           =$_POST ['Description'];
            $order          =$_POST ['number'];
            $visibility     =$_POST ['visibility'];
            $commenting     =$_POST['commenting'];;
            $Ads            =$_POST['Ads'];


            //Update the DB
            //if( count($errorArray) ==0 ){
                $stmt = $con ->prepare ('UPDATE categories SET Name = ? , Description = ?,
                                        Ordering=? ,Visibility=?,Allow_Comment=?,Allow_Ads=? where ID=? ');
                $stmt ->execute(array($name,$desc,$order,$visibility,
                                    $commenting,$Ads,$catid));
            //}
            
            echo "<div class='container'>";
                $msg = "<div class ='alert alert-success'>" . $stmt->rowcount() . " Category updated </div>";
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
/************************************************************************************ */
    elseif ($do == 'Delete'){
        echo "<h1 class='text-center display-4 font-weight-bold' > Delete Category </h1>";
        if (isset ($_GET['ID'] ) && is_numeric($_GET['ID'])){
            $catgid = intval($_GET['ID']);
            //$stmt= $con->prepare('SELECT * FROM users where user_ID=?');
            //$stmt->execute(array($userid));
            //$rows=$stmt->fetch();
            //$count=$stmt->rowCount();

            $check = checkitem ('ID' ,'categories' ,$catgid);

            if($check >0){
            $stmt= $con->prepare("DELETE FROM categories WHERE ID=:zid");
            $stmt->execute(array(
                /**$stmt = bindParam("zid",$userid)       another method then we $stmt->execute() */
                "zid" => $catgid));

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
}
else {
    echo "You are not authorized";
    header('Location:index.php');
    exit();
}

ob_end_flush(); //release the output
?>
