<?php
require_once "../config.php";
global $CFG, $DB ,$USER;
$response = array();



  $topusers=$DB->get_records_sql("SELECT cv.userid as id, SUM(cv.durationwatchinseconds) AS total_duration,u.username as username 
  FROM {course_video} cv join {user} u on cv.userid=u.id GROUP BY userid ORDER BY total_duration DESC LIMIT 10 ");

$topuserObjects = array();

// Looping through each transaction record and creating objects
foreach ($topusers as  $data) {
    // Creating an object for each transaction
    $topuserObject = new stdClass();
    $topuserObject->id = $data->id;
    $topuserObject->totalduration = $data->total_duration;
    $topuserObject->username = $data->username;
   
    // Adding the object to the array
    $topuserObjects[] = $topuserObject;
  }
  // var_dump($topuserObjects);
  // die;

$response['response']  = $topuserObjects;

// $response['lastchange'] = $transactionObjects[0]->id;





echo json_encode($response);
?>