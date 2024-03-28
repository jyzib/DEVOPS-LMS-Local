<?php
require_once("../../config.php"); 
require_once('lib.php');
// include($CFG'/local/vendor/autoload.php')

global $DB, $USER, $PAGE;

$context = context_system::instance();
$page = optional_param('page', 0, PARAM_INT);
$userid = optional_param('userid', 0, PARAM_INT);
$amount = optional_param('amount', 0, PARAM_INT);
$courseid = optional_param('courseid', 0, PARAM_INT);

$PAGE->set_context($context);
$PAGE->set_pagelayout("standard");
$reponse=array();

$total = $DB->get_records_sql("SELECT c.fullname AS coursename, c.id AS courseid
FROM {course} c
WHERE c.id NOT IN (
    SELECT ue.courseid
    FROM {enrol} ue
    JOIN {user_enrolments} us ON ue.id = us.enrolid
    WHERE us.userid = $USER->id ) and c.id !=1");

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

//   var_dump(getcourseimg(4));
//   die;
foreach($total as $data){
    $a = new stdClass();
    $a->coursename = $data->coursename;
    $a->courseid = $data->courseid;
    $a->userid = $USER->id;
    $a->courseimage=getcourseimg($data->courseid);

    // $image=get_course_image($data->courseid);
    // $url=$CFG->wwwroot.'/'.$image->get('path').'/'.$image->get('slashargument');
    // $a->data2 = 'value2';

    // var_dump($image->get('path'));
    // die;
$coursearr[] = $a;

}
// var_dump($coursearr);
// die;

$response['data']=$coursearr;



echo $OUTPUT->header();
echo $OUTPUT->render_from_template('local_courses/coursedetail', $response);
echo $OUTPUT->footer();
?>