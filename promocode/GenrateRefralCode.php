<?php

require_once "../../config.php";
global $DB;
global $CFG;
global $USER;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

   
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400); // Bad Request
        die('Invalid JSON data provided.');
    }

    $response['data'] = $data['courseid'];
    $response['id'] = $USER -> id;
    $courseid = intval($data['courseid']);
    $userid = intval($USER->id);

    $isAvailable=$DB->get_record_sql("SELECT * from {course_referrals} WHERE user_id = $userid and course_id = $courseid");
    $response['refral'] = $isAvailable ->referral_code ;
    function generateRandomString($length = 6) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $max)];
        }
        return $randomString;
    }


    // var_dump($isAvailable);
    // die;
    if(!$isAvailable){
    $record = new stdClass();
    $randomcode = generateRandomString();
    $response['refral'] = $randomcode;
    $record->user_id  = $USER -> id;
    $record->course_id  =$courseid; // Convert expiration date to timestamp
    $record->referral_code  = $randomcode;
    // $record->expiration_date   = $data["usageLimit"];
    // $record->createdtime = time();
    // $record->usage_count  = $data["usageLimit"];
    // $record->referral_date   = $data["usageLimit"];


// Insert the record into the 'promocode' table
$insert = $DB->insert_record('course_referrals', $record, false);
var_dump($insert);


    }

   
// Encode and return the response as JSON
echo json_encode($response);

}