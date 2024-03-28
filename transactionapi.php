<?php
require_once "../config.php";
global $CFG, $DB ,$USER;
$response = array();



$transaction = $DB->get_records_sql("SELECT pr.id, pr.amount as price , c.fullname as coursename , u.username as username , pr.paymenttime as paymenttime
                                    FROM {payment_razorpay} pr 
                                    JOIN {course} c ON c.id = pr.courseid 
                                    JOIN {user} u ON u.id = pr.userid 
                                    ORDER BY pr.id DESC LIMIT 3") ;
$transactionObjects = array();


foreach ($transaction as $data) {
    // Creating an object for each transaction
    $transactionObject = new stdClass();
    $transactionObject->id = $data->id;
    $transactionObject->amount = $data->price;
    $transactionObject->coursename = $data->coursename;
    $transactionObject->username = $data->username;
    $transactionObject->paymenttime = date("d-m-Y H:i", $data->paymenttime);

    $transactionObjects[] = $transactionObject;
}

$response['response']  = $transactionObjects;

$response['lastchange'] = $transactionObjects[0]->id;





echo json_encode($response);
?>