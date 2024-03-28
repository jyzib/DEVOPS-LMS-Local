<?php
require_once("../../config.php"); 

global $DB, $USER, $PAGE;

$reponse=array();
$time = time(); 
$total = $DB->get_records_sql("SELECT c.fullname AS coursename, c.id AS courseid, cd.value AS price
FROM {course} c
LEFT JOIN {customfield_data} cd ON c.id = cd.instanceid
WHERE c.id NOT IN (
    SELECT ue.courseid
    FROM {enrol} ue
    JOIN {user_enrolments} us ON ue.id = us.enrolid
    WHERE us.userid = $USER->id
) AND c.id != 1 AND c.startdate <= $time AND cd.value > 2 ");


$coursearr=array();

function getcourseimg($val){
    $course = get_course($val);
    $imageUrl = \core_course\external\course_summary_exporter::get_course_image($course);
    if($imageUrl){
      return $imageUrl ;
  
    }else{
      return 'https://cdni.iconscout.com/illustration/premium/thumb/no-data-found-9887654-8019228.png?f=webp' ;
    }
  }

function iscartadded($courseid){
  global $DB, $USER;

  $courses = $DB->get_record_sql("SELECT * from {cart}  where courseid = $courseid and userid = $USER->id");
if($courses){
  return true; 
}else{

  return false;
}
}


foreach($total as $data){
    $a = new stdClass();
    $a->coursename = $data->coursename;
    $a->courseid = $data->courseid;
    $a->userid = $USER->id;
    $a->courseimage=getcourseimg($data->courseid);
    $a->price=$data->price;
    $a->isAdded=iscartadded($data->courseid);


$coursearr[] = $a;

}
// var_dump($coursearr);
// die;



echo json_encode($coursearr)


?>