<?php

include "initial.php";
include  $tmpl . "Header.php" ;

if(isset($_GET['pagename'])){ 
    $page_title=$_GET['pagename'];
    ?>
     <div class="container">
        <h3 class='text-center display-4'> <?php echo  $page_title ?> </h3>
        <div class="row">
        <?php
            foreach(getItems($_GET['page_ID']) as $item){
                //echo $item['Name']; ?>
                <div class="col-md-3 col-sm-6 mt-4">
                    <div class="my-card">
                        <div class="text-center">
                            <img src="dummy.jpg" class="img-fluid" alt="dummy">
                        </div>
                        <hr>
                        <h4><?php echo $item['Name'] ?></h4>
                        <p> <?php echo $item['Description'] ?></p>
                        <div class="text-center">Price is <?php echo $item['Price'] ?> $</div>
                    </div>
                </div>
                <?php
            }
        ?>
        </div>
     </div>;
    <?php
}

include $tmpl . "footer.php" ;

?>