<?php
/* $noNavbar=" "; */
ob_start(); //output buffering start
session_start();


if (isset ($_SESSION['Username'])){
    $pageTitle="Dashboard";
    include 'initial.php';
    $users_number =4; //number of latest users
    $items_number =4; //number of latest items
    $comments_number=5; //number of latest comments

    $latest_users= getLatest('*','users','user_ID',$users_number); //latest users array

    $latest_items= getLatest('*','items','item_ID',$items_number); //latest items array

    ?>

    <div class="container home-stats text-center">
    <h1 class='text-center display-4 font-weight-bold' > Dashboard </h1>;
        <div class="row">
            <div class="col-md-3">
                <div class="start members">
                    <i class="fas fa-users"></i>
                    <br>
                    Total Members
                    <span><a href="members.php"><?php echo CountItems('user_ID','users') ?> </a> </span>
                </div>
            </div>

            <div class="col-md-3">
                <div class="start P-members">
                    <i class="fas fa-user-plus"></i>
                    <br>
                    Pending Members
                    <span><?php echo checkitem ('RegStatus' ,'users',0) ?></span>
                </div>
            </div>

            <div class="col-md-3">
                <div class="start items">
                    <i class="fas fa-tag"></i>
                    <br>
                    Total Items
                    <span><a href="items.php"><?php echo CountItems('item_ID','items') ?> </a></span>
                </div>
            </div>

            <div class="col-md-3">
                <div class="start comments">
                    <i class="fas fa-comments"></i>
                    <br>
                    Total Comments 
                    <span><a href="items.php"><?php echo CountItems('C_ID','comments') ?> </a></span>

                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <div class="card text-dark bg-light mb-3">
                    <div class="card-header">Latest <?php echo '<strong>' . $users_number .'</strong>' ?> Registered Users <i class=" toggle-info fas fa-plus float-right mt-2"></i></div>
                    <div class="card-body">
                        <p class="card-text">
                        <ul class="list-unstyled latest-users">
                            <?php
                            if(!empty($latest_users)){
                            foreach ($latest_users as $user){
                                echo "<li>"
                                . $user['Username'] 
                                ."<a href='members.php?do=Edit&user_ID=" .$user['user_ID'].
                                "' class='btn btn-success float-right'><i class='fas fa-edit'></i>Edit</a>";
                                if($user['RegStatus'] == 0){
                                    echo "<a href='members.php?do=Activate&user_ID=" .$user['user_ID']."'class='btn btn-info float-right mr-1'> Activate </a>";
                                } 
                                echo "</li>";
                                //echo "<span class='btn btn-success float-right'><i class='fas fa-edit'></i>Edit</span>";
                            }
                        }else
                        {
                            echo "No users registered";
                        }
                            ?>
                        </p>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
            <div class="card text-dark bg-light mb-3">
                    <div class="card-header">
                        Latest <?php echo '<strong>' . $items_number .'</strong>' ?> Registered Items <i class="toggle-info fas fa-plus float-right mt-2"></i>
                    </div>
                    <div class="card-body">
                        <p class="card-text">
                        <ul class="list-unstyled latest-users">
                            <?php
                            if(!empty($latest_items)){
                            foreach ($latest_items as $item){
                                echo "<li>"
                                . $item['Name'] 
                                ."<a href='items.php?do=Edit&item_ID=" .$item['item_ID'].
                                "' class='btn btn-success float-right'><i class='fas fa-edit'></i>Edit</a>";
                                if($item['Approve'] == 0){
                                    echo "<a href='items.php?do=Approve&item_ID=" .$item['item_ID']."'class='btn btn-info float-right mr-1'><i class ='fas fa-check'></i> Approve </a>";
                                } 
                                echo "</li>";
                                //echo "<span class='btn btn-success float-right'><i class='fas fa-edit'></i>Edit</span>";
                            }
                        }else{
                            echo "No items found";
                        }
                            ?>
                        </p>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!----------------Comment section--------------->
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-9 col-sm-12">
                <div class="card text-dark bg-light mb-3">
                    <div class="card-header">Latest <?php echo '<strong>' . $comments_number .'</strong>' ?>  Comments <i class=" toggle-info fas fa-plus float-right mt-2"></i></div>
                    <div class="card-body">
<!--                         <p class="card-text"> -->
                            <?php
                                $stmt =$con ->prepare("SELECT 
                                                comments.* ,users.Username AS user_Name
                                                FROM
                                                    comments
                                                Inner Join
                                                    users
                                                on
                                                users.user_ID=comments.User_ID
                                                ORDER BY C_ID DESC
                                                LIMIT $comments_number
                                ");
                                $stmt ->execute();
                                $comments =$stmt ->fetchAll();
                                if(!empty($comments)){
                                    foreach($comments as $comment){
                                        echo '<div class="comment-box">' ;
                                        echo '<span class="member-n">' . $comment['user_Name'] .'</span>'
                                        . '<p class="member-c" >' . $comment['Comment'] .'</p>';
                                        echo '</div>';
                                    }
                                }else{
                                    echo "No Comments Found";
                                }
                            ?>
<!--                         </p> -->
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
        <?php
    
    include $tmpl . "footer.php" ;  /**So jQuery is included and bootstrap JS is included as well */
}
else {
    echo "You are not authorized";
    header('Location:index.php');
    exit();
}
ob_end_flush(); //release the output
?>