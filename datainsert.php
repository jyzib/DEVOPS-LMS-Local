<?php
// Retrieve data sent via POST
require_once "../config.php";
global $CFG, $DB;
$data = json_decode(file_get_contents('php://input'), true);

// Initialize response object
$response = array();

// Check if data is received
if ($data) {
    $videopath = $data['videopath'];
    $userid = $data['userid'];
    $query = $DB->get_records_sql("SELECT durationwatchinseconds,id FROM {course_video} WHERE userid='$userid' AND videopath='$videopath'");
    $isAlreadyExist = false;

    // Check if the video already exists for the user
   
    foreach ($query as $que) {
        $isAlreadyExist = true;
        $response['durationwatchinseconds'] = $que->durationwatchinseconds;
        $response['videoId'] = $que->id;
      
       
    }
$cvideoid = $response['videoId'];
    // If the video already exists, return appropriate message
    if ($isAlreadyExist) {
        $response['message'] = "Video already exists for this user.";
        $response['status'] = "true";
             $query = $DB->get_records_sql("SELECT * FROM {course_video_comment} WHERE cvideoid='$cvideoid' ");
        
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
        $response['data'] = $commentArray;
    } else {
        // Insert the video if it doesn't already exist
  
        $insert = $DB->insert_record("course_video", array(
            "userid" => $userid,
            "videopath" => $videopath
        ));

        $query = $DB->get_records_sql("SELECT durationwatchinseconds,id FROM {course_video} WHERE userid='$userid' AND videopath='$videopath'");
        foreach ($query as $que) {
          
            $response['durationwatchinseconds'] = $que->durationwatchinseconds;
            $response['videoId'] = $que->id;
          
           
        }
      
        $response['message'] = "Video inserted successfully.";
        $response['status'] = "false";
      
    }
} else {
    // No data received
    $response['error'] = "No data received.";
}

// Convert the response array to JSON and echo it
echo json_encode($response);
?>
