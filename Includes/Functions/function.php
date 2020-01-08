<?php
/*****Front End */


function getCat(){
    global $con ;
    $getCat = $con->prepare("SELECT * FROM categories ORDER BY ID ASC");
    $getCat->execute();

    return $getCat->fetchAll();
}

function getItems($category){
    global $con ;
    $getItems = $con->prepare("SELECT * FROM items WHERE Cat_ID= $category ORDER BY item_ID DESC ");
    $getItems->execute();

    return $getItems->fetchAll();
}

/*************************************** */


/** title function  */
function get_title (){
    global $pageTitle;
    if(isset($pageTitle)){
        echo $pageTitle;
    }
    else {
        echo 'Default';
    }
}

/**************** */

/** Home Redirect function (error,second before redirect)   V2 */

function redirect ($error_msg  ,$url=null, $seconds =3){
    $link ="";
    if ($url === null ){
        $url ="index.php";
        $link ="Homepage";
    }
    else{
        if(isset($_SERVER["HTTP_REFERER"]) && $_SERVER["HTTP_REFERER"] !==""){
            $url =$_SERVER["HTTP_REFERER"];
            $link ="Previous page";
        }
        else {
            $url ="index.php";
            $link ="Homepage";
        }
        
    }
    echo $error_msg ;
    
    echo "<div class='alert alert-primary text-center'>" . "You will directed after : $seconds seconds to $link </div>";

    header("refresh:$seconds ; url=$url");
    exit();
}
/**************** */

/**  function to check item in DB ($select items , $) */
function checkitem ($select ,$from ,$value){
    global $con ;
    $statement = $con -> prepare ("SELECT $select FROM $from WHERE $select =?");
    $statement ->execute(array($value));
    $count = $statement ->rowCount();
    return $count;
}
/************************************* */

/***Count Number of Items funtion V.1 ********************************** */
function CountItems($item,$table){
    global $con;
    $stmt2 = $con-> prepare("SELECT COUNT($item) FROM $table");
    $stmt2->execute();
    return $stmt2->fetchColumn();
}
/********************************** */

/***Get latest items funtion V.1 ********************************** */
function getLatest($select,$table,$order,$limit=4){
    global $con ;
    $stmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit ");
    $stmt->execute();

    return $stmt->fetchAll();
}
/********************************** */
