<?php

global $USER;
require_once("../../../config.php");
require("config.php");
require_once("../../vendor/autoload.php");

use Razorpay\Api\Api;

$api = new Api(API_KEY, API_SECRET);

$course_data = $DB->get_records_sql("SELECT id, amount from {cart} where userid = $USER->id");
$total_amount = 0; // Initialize total amount variable

foreach ($course_data as $data) {
  $total_amount += $data->amount; // Add each amount to the total
}
if(isset($_POST['data'])) {
  $receivedData = $_POST['data'];
  $receivedpromocode = $_POST['promocode'];
  $percentageAmount = 0;
  if($receivedpromocode == '0'){
      $percentageAmount = 0;
  }else{

      $isavaliable = $DB->get_record_sql("SELECT * from {promocode} WHERE promocodename = '$receivedpromocode' ");
      $percentageAmount = intval($isavaliable->percentage) ;
    
  }
  $total_amount =   ($total_amount) - (($percentageAmount / 100) * $total_amount );
} else {
  redirect('/devops/yatharthriti/local/courses/cart.php');
}




$orderData = [
  'amount' => $total_amount * 100, // Amount in paise
  'currency' => 'INR',
  'receipt' => ORDER_RECIEPT_ID,
  'payment_capture' => 1 // auto captur
];
// Amount in paise
// var_dump($orderData);
// die;

// $order = $api->order->create($orderData);

  $order = $api->order->create($orderData);

// var_dump($order->receipt);
// die;
// foreach($order as $ord){

// }
       


$apikey = API_KEY;


$response['userid']=$USER->id;
$response['totalamount']=$total_amount;
$response['firstname']=$USER->firstname;

$response['price']=$orderData['amount'];
$response['courseid']=$orderData['amount'];
$response['username']=$USER->username;
$response['apikey']=$apikey;
$response['orderid']=$order->receipt;
$response['email']=$USER->email;
$response['phone']=$USER->phone1;
$response['percentage']=$receivedpromocode;
$response['logoimageurl'] = COMPANY_LOGO_URL;






echo $OUTPUT->header();
echo $OUTPUT->render_from_template('local_courses/checkout', $response);
echo $OUTPUT->footer();
?>