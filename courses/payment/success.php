<?php

require_once("../../../config.php");
require_once($CFG->libdir . '/tcpdf/tcpdf.php');

global $DB, $USER;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['razorpay_payment_id'])) {
        $course_data = $DB->get_records_sql("SELECT courseid,amount from {cart} where userid = $USER->id");
        $datapre = $data['percentage'];
        $percentageAmount = 0;
        if($datapre == '0'){
            $percentageAmount = 0;
        }else{

            $isavaliable = $DB->get_record_sql("SELECT * from {promocode} WHERE promocodename = '$datapre' ");
            if($isavaliable){
                $remaining = intval($isavaliable -> remainingusagecount) - 1;

                $isavaliable->remainingusagecount = $remaining;

                // Save the changes
                $result = $DB->update_record('promocode', $isavaliable);
            

                // $isavaliab = $DB->update_record_sql("UPDATE {promocode} set remainingusagecount =' $remaining ' WHERE promocodename = '$datapre'  ");
            }
            $percentageAmount = intval($isavaliable->percentage) ;
                $record = new stdClass();
    $record->promocode = $datapre;
    $record->userid = $USER->id;
    $record->usagedate = time();
    $insert = $DB->insert_record('promo_redeem_records', $record, false);
          
        }
        // var_dump($percentageAmount);
        // die;
        foreach ($course_data as $course) {
            $insert = $DB->insert_record("payment_razorpay", array(
                "transactionid" => $data['razorpay_payment_id'],
                "orderid" => $data['orderid'],
                "paymenttime" => time(),
                "amount" => ($course->amount) - (($percentageAmount / 100) * $course->amount ),
                "userid" => $USER->id,
                "status" => 'success',
                "currency" => 'INR',
                "courseid" => $course->courseid,
            ));

            // Sending Invoice to User over Mail
            $user = $DB->get_record('user', array('id' => $USER->id)); // Fetch user object by ID
            $subject = 'Invoice for your purchase';
            $message = 'Dear ' . $user->firstname . ', please find attached your invoice.';

            // Generate PDF invoice content
            $invoice_data = array(
                'invoice_number' => 'INV123',
                'date' => '2024-03-09',
                'customer_name' => $user->firstname . ' ' . $user->lastname,
            );
            $invoice_pdf_content = generate_invoice($invoice_data);

            // Email the invoice with attachment

            email_invoice_to_user($user, $subject, $message, $invoice_pdf_content);
            
            // Delete the course from the cart
            $isdeleted = $DB->delete_records('cart', ['userid' => $USER->id, 'courseid' => $course->courseid]);
        }

        echo json_encode(['status' => 'success', 'message' => 'Payment ID received successfully', 'data' => $data]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Payment ID not provided']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Only POST requests are allowed']);
}

// Function to generate PDF invoice
function generate_invoice($invoice_data) {
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetTitle('Invoice');
    $pdf->SetSubject('Invoice');
    $pdf->SetKeywords('Invoice, Moodle');
    

    $pdf->AddPage();

    $html = '<h1>Invoice</h1>';
    $html .= '<p>Invoice Number: ' . $invoice_data['invoice_number'] . '</p>';
    $html .= '<p>Date: ' . $invoice_data['date'] . '</p>';
    $html .= '<p>Customer: ' . $invoice_data['customer_name'] . '</p>';

    $pdf->writeHTML($html, true, false, true, false, '');

    $file_path = __DIR__ .'/invoices/'. uniqid() .'.pdf';
    $pdf->Output($file_path, 'F'); 

    return $pdf->Output('', 'S'); // Get PDF content as a string
}

// Function to send email with invoice attachment
function email_invoice_to_user($user, $subject, $message, $pdf_content) {
    // Define email parameters
    $to = $user->email;
    $from = 'From: krishan.yatharthriti@gmail.com';
    $subject = $subject;
    $separator = md5(time());

    // Define attachment headers
    $headers = $from . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"$separator\"\r\n";
    $headers .= "Content-Transfer-Encoding: 7bit\r\n";

    // Define email body
    $body = "--$separator\r\n";
    $body .= "Content-Type: text/html; charset=utf-8\r\n";
    $body .= "Content-Transfer-Encoding: 7bit\r\n";
    $body .= "\r\n";
    $body .= $message . "\r\n";
    $body .= "--$separator\r\n";
    $body .= "Content-Type: application/pdf; name=\"invoice.pdf\"\r\n";
    $body .= "Content-Disposition: attachment; filename=\"invoice.pdf\"\r\n";
    $body .= "Content-Transfer-Encoding: base64\r\n";
    $body .= "\r\n";
    $body .= chunk_split(base64_encode($pdf_content)) . "\r\n";
    $body .= "--$separator--";

    // Send the email with attachment
    // email_to_user($)
  mail($to, $subject, $body, $headers);
    
   
}
