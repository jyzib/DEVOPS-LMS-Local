<?php
// Retrieve data sent via POST
require_once "../config.php";
global $CFG, $DB;
$data = json_decode(file_get_contents('php://input'), true);
$response = array();
$newArr = array();
$time = time();
if($data){
    $userid = $data['userid'];
    $courseid = $data['courseid'];
    
    $isadded=$DB->get_record_sql("SELECT wishlist,id from {order_item} where courseid = '$courseid' and  userid= '$userid'");
    if($isadded){
        $isdeleted = $DB->delete_records('order_item', ['id' => $isadded->id]);
        $response['res'] ="is deleted";

    }else{

        $insert = $DB->insert_record("order_item", array(
            "courseid" => $courseid,
            "userid" => $userid,
            "wishlist" => 1,    
            "timecreated" => $time
        ));
        $response['insert'] = $insert;
    }

 
    $response['data'] = $data;
}



echo json_encode($response);
?>