<?php

require_once "../../config.php";
global $CFG, $DB;
$data = json_decode(file_get_contents('php://input'), true);
$response = array();
$newArr = array();
$time = time();

if($data){
    $userid = $data['userid'];
    $courseid = $data['courseid'];
    $courseamount = $data['amount'];

        $insert = $DB->insert_record("cart", array(
            "courseid" => $courseid,
            "userid" => $userid,
            "cart" => 1,    
            "timecreated" => $time ,
            "amount" =>  $courseamount,
        ));
        $response['insert'] = $insert;

 
    $response['data'] = $data;
}



echo json_encode($response);
?>
 
