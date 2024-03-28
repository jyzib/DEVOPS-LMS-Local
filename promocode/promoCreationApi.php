<?php

require_once "../../config.php";
global $DB;
global $CFG;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400); // Bad Request
        die('Invalid JSON data provided.');
    }
    
    $response = array(
        'status' => 'success', // Or 'error' if processing fails
        'message' => 'Data received and processed successfully.' // Customize message
    );
    
    header('Content-Type: application/json; charset=UTF-8');
    
    $record = new stdClass();
    
    // Assign values from $data array to $record object properties
    $record->promocodename = $data["promo"];
    $record->percentage = $data["discount"];
    $record->expireon = strtotime($data["expirationDate"]); // Convert expiration date to timestamp
    $record->usagecount = $data["usageLimit"];
    $record->createdtime = time();
    $record->remainingusagecount = $data["usageLimit"];


// Insert the record into the 'promocode' table
$insert = $DB->insert_record('promocode', $record, false);

if($insert){
    echo "Inserted";
}
// Encode and return the response as JSON
echo json_encode($data);

}