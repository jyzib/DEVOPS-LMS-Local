<?php
// Retrieve data sent via POST
require_once "../config.php";
global $CFG, $DB;
$data = json_decode(file_get_contents('php://input'), true);

// Initialize response object
$response = array();

// Check if data is received
if ($data) {
    $timeStamp = $data['timeStamp'];
    $videoId = $data['videoId'];



$sql = "UPDATE {course_video} SET durationwatchinseconds = :duration WHERE id = :id";
    $params = array('duration' => $timeStamp, 'id' => $videoId);

    $affected_rows = $DB->execute($sql, $params);
    $query = $DB->get_records_sql("SELECT durationwatchinseconds FROM {course_video} WHERE id='$videoId' ");

    foreach ($query as $que) {
    
        $response['durationwatchinseconds'] = $que->durationwatchinseconds;
    }
    $response['error'] = "No data received.";



    }
$response['videoId'] = $videoId;
$response['timeStamp'] = $timeStamp;

// Convert the response array to JSON and echo it
echo json_encode($response);
?>
