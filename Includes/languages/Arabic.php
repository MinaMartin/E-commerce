<?php

function lang_ar ($phrase){
    static $lang = array(
        "MESSAGE" => "مرحبا",
        "ADMIN" => "يا أدمن"
    );
    return $lang[$phrase];
};
?>