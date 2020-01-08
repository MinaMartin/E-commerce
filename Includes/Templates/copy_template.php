<?php
/******************************* */
////////******************      page */
/******************************* */
ob_start(); //output buffering start
session_start();
$pageTitle="";
if (isset ($_SESSION['Username'])){
    
    include 'initial.php';
    include $tmpl . "footer.php" ;  /**So jQuery is included and bootstrap JS is included as well */

    $do = isset ($_GET['do']) ? $_GET['do'] : 'Manage';

    if ($do == 'Manage'){

    }
    elseif ($do == 'Add'){

    }
    elseif ($do == 'Insert'){

    }
    elseif ($do == 'Edit'){ }

    elseif ($do == "Update"){}

    elseif ($do == 'Delete'){}

    elseif ($do == 'Activate'){}
}
else {
    echo "You are not authorized";
    header('Location:index.php');
    exit();
}

ob_end_flush(); //release the output
?>
