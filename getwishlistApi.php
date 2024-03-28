<?php
require_once "../config.php";
global $CFG, $DB ,$USER;
$response = array();
$time=time();



$courses = $DB->get_records_sql("SELECT c.fullname,c.summary,c.startdate,c.id from {course} c join {order_item} oi on c.id = oi.courseid where startdate > $time and 1=1 and oi.userid=$USER->id");
$newcoursearr = array();


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
 

foreach ($courses as $cours) {
    $upcomingcoursedetails = new stdClass();
    $upcomingcoursedetails->fullname = $cours->fullname;
    $upcomingcoursedetails->summary = $cours->summary;
    $upcomingcoursedetails->startdate = $cours->startdate;
    $upcomingcoursedetails->id = $cours->id;
    $upcomingcoursedetails->img = get_course_image($cours->id);

  
    $newcoursearr[] = $upcomingcoursedetails;
}

$response['responce'] = $newcoursearr;
// var_dump($newcoursearr);
// die;







// var_dump($isadded);
// die;
echo json_encode($response);
?>