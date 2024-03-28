<?php
require_once("../../config.php"); 

global $DB, $USER, $PAGE;


$courses = $DB->get_records_sql("SELECT c.fullname as coursename, c.summary as summary,c.id as courseid,cd.value as amount , ci.id  as cartid from {course} c join {cart} ci on c.id = ci.courseid join {customfield_data} cd on cd.instanceid = c.id where ci.userid = $USER->id and cd.value > 3 ");
$cartData = array();

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
 

foreach ($courses as $record) {
    $cartObject = new stdClass();

    $cartObject->courseid = $record->courseid;
    $cartObject->coursename = $record->coursename;
    $cartObject->amount = $record->amount;
    $cartObject->img =get_course_image($record->courseid);
    $cartObject->id =$record->cartid;
    $cartObject->summary =$record->summary;


    $cartData[] = $cartObject;
}



// var_dump($cartData);
// die;




echo json_encode($cartData);
?>
