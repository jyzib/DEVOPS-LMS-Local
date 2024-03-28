<?php
require_once("../../config.php");

global $DB;
$insert = $DB->insert_record("cart", array(
            "courseid" => 2,
            "userid" => 3,
            "cart" => 1,    
            
        ));
if($insert){
    echo "hi";
}