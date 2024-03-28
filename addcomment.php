<?php
// Retrieve data sent via POST
require_once "../config.php";
global $CFG, $DB;
$data = json_decode(file_get_contents('php://input'), true);

// Initialize response object
$response = array();
$newArr = array();

// Check if data is received
if ($data) {
    $comment = $data['comment'];
    $commentTime = $data['commentTime'];
    $timeStamp = $data['timeStamp'];
    $videoId = $data['videoId'];
    $response['comment'] = $comment;
    $response['commentTime'] = $commentTime;
    $response['timeStamp'] = $timeStamp;
    $response['videoId'] = $videoId;

    $insert = $DB->insert_record("course_video_comment", array(
        "cvideoid" => $videoId,
        "comment" => $comment,
        "timecreated" => $commentTime,
        "commenttime" => $timeStamp,
    ));

    if ($insert) {
        $query = $DB->get_records_sql("SELECT * FROM {course_video_comment} WHERE cvideoid='$videoId' ");
        
        // Initialize an array to store comment objects
        $commentArray = array();

        foreach ($query as $comment) {
            $commentObject = new stdClass();
            $commentObject->commenttime = $comment->timecreated; // Replace with the actual column name
            $commentObject->commentText = $comment->comment; // Replace with the actual column name
            $commentObject->commentstamp = $comment->commenttime; // Replace with the actual column name
            // Add more properties as needed
            
            // Add the comment object to the array
            $commentArray[] = $commentObject;
        }

        // Add the comment array to the response
        $response['data'] = $commentArray;
    }
}

// Convert the response array to JSON and echo it
echo json_encode($response);
?>
