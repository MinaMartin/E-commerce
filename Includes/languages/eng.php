<?php

function lang_eng ($phrase){
    static $lang = array(
        /**Navbar words */
        "e-comerce" => "E-comerce",
        "home" => "Home",
        "CATOGORIES" =>"Catogories",
        "Admin"     =>"Admin",
        "profile" =>"Edit profile",
        "settings" =>"Settings",
        "VISIT THE PANEL" =>"Visit The Panel",
        "logout"   => "Log out",
        "ITEMS"  => "Items",
        "MEMBERS" =>"Members",
        "COMMENTS" =>"Comments"
        /********************************************** */

    );
    return $lang[$phrase];
};
?>