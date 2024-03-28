<?php
require_once "../config.php";
global $CFG, $DB, $USER;
$response = array();

$transaction = $DB->get_records_sql("SELECT pr.id, pr.amount as price , c.fullname as coursename , u.username as username , pr.paymenttime as paymenttime
                                    FROM {payment_razorpay} pr 
                                    JOIN {course} c ON c.id = pr.courseid 
                                    JOIN {user} u ON u.id = pr.userid ");

$monthlyData = array_fill(1, 12, 0); // Initialize an array with 12 months, each initialized with 0

// Iterate through each transaction
foreach ($transaction as $transactionItem) {
    // Extract payment time and convert it to a date object
    $paymentTime = new DateTime('@' . $transactionItem->paymenttime);
    
    // Extract month from payment time
    $month = (int)$paymentTime->format('n'); // Format 'n' gives month without leading zeros

    // Add transaction price to the corresponding month index
    $monthlyData[$month] += $transactionItem->price;
}



$response['responsee'] = $monthlyData;

echo json_encode($response);
?>
