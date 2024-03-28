<?php
require_once "../config.php";
global $CFG, $DB;

$query = $DB->get_records_sql("SELECT * FROM {course}");



$course = array();

function get_course_image($courseid)
{

    $course = get_course($courseid);
    $url = \core_course\external\course_summary_exporter::get_course_image($course);

    if ($url) {
        return $url;
    } else {
        return null;
    }
}



foreach($query as $data){
    $coursedetails = new stdClass();
    $coursedetails->coursename = $data->fullname; 
   $coursedetails->coursesummary = $data->summary; 
     $coursedetails->courseimg = get_course_image($data->id); 
     $course[] = $coursedetails;
}


echo json_encode($course);

?>
