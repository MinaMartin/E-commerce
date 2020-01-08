<?php
include "Admin/Conf.php";

/** Directories */
$tmpl="Includes/Templates/";
$Lang="Includes/languages/";
$css = "layout/css/";
$JS ="layout/JS/" ;
$func ="Includes/Functions/";
/*** *****************************************/

include $func . 'function.php';
include  $Lang . "eng.php";    /**the language files must be put here before other files as other files will take words from it  */
include  $Lang . "Arabic.php";
include  $tmpl . "Header.php" ;
include  $tmpl . "navbar.php" ;
?>