<?php

require_once "../../config.php";
global $CFG, $DB;
$data = json_decode(file_get_contents('php://input'), true);
$response = array();
$newArr = array();
$time = time();

if($data){
    $cartid = $data['id'];

    $isdeleted = $DB->delete_records('cart', ['id' =>$cartid ]);
   
    $response['data'] = $data;
    $response['isdeleted'] = $isdeleted;
}



echo json_encode($response);
?>
 