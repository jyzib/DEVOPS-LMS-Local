<?php

require_once "../../config.php";
global $DB;
global $CFG ;
global $USER ;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $responce = array();
    $data = json_decode(file_get_contents('php://input'), true);
    $promo = $data['promocode'];
    $userid = $USER->id;
    $isavaliable = $DB->get_record_sql("SELECT * from {promocode} WHERE promocodename = '$promo' ");
if($isavaliable){
$responce['status'] = true;

$promored = $DB->get_record_sql("SELECT * from {promo_redeem_records} WHERE promocode = '$promo' and userid = '$userid'");
if(!$promored){
    // $record = new stdClass();
    // $record->promocode = $promo;
    // $record->userid = $userid;
    // $record->usagedate = time();
    // $insert = $DB->insert_record('promo_redeem_records', $record, false);
    if($isavaliable -> remainingusagecount == '0'){
       $responce['msg'] = "Your promo code Limit Expired";
    }else{
        $responce['per'] = $isavaliable->percentage;
        $responce['applied'] = true;
        $responce['msg'] = 'Congratulations! Your promo code has been successfully applied. You are now eligible for a ' . $isavaliable->percentage . '% discount.';
    }
    
}else{
    $responce['msg'] = 'Sorry, you have already used this promo code.';
}

}else{
$responce['msg'] = 'Incorrect promo code entered. Please try again.';
$responce['status'] = false;
}

$responce['data'] = $data;
echo json_encode($responce);
}