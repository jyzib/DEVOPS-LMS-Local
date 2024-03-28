<?php
// global $CFG,$DB;
require_once('../config.php');

require_login();
$studentdashboard = $USER->id;
$context = context_system::instance();
$userid = $USER->id;

$PAGE->set_context($context);
// $PAGE->set_url('/upcomingcourse.php');
$PAGE->set_title('Course VIdeo');
// $PAGE->set_heading('Course Video');
$PAGE->set_pagelayout('standard');

$name = isset($_GET['id']) ? $_GET['id'] : '';

$namee = $DB->get_record_sql("SELECT username FROM {user} WHERE id='$studentdashboard'");
$titleofvideo = $DB->get_record_sql("SELECT * FROM {course_video_detail} WHERE videopath='$name'");



$data = array('id' => $name,'userId'=>$userid);
$data["title"] = $titleofvideo->title  ;
$data["description"] = $titleofvideo->description;

$data['username'] = $namee->username;
echo $OUTPUT->header();
echo $OUTPUT->render_from_template('local_dashboard_main/coursedetails', $data);
echo $OUTPUT->footer();
?>
