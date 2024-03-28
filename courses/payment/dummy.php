<?php
require_once "../../../config.php";
require_once($CFG->libdir . '/tcpdf/tcpdf.php');

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
    $filename=uniqid();
    // var_dump($filename);
    // die;
    $file_path = __DIR__ .'/invoices/'.  $filename.'.pdf';
    $pdf->Output($file_path, 'F'); 

    return $file_path;
}

$invoice_data = array(
    'invoice_number' => 'INV123',
    'date' => '2024-03-09',
    'customer_name' => 'John Doe',
    
);

$invoice_file = generate_invoice($invoice_data);

echo "Invoice saved at: $invoice_file";

?>