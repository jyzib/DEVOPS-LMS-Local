<?php 

require_once('../../config.php');
require_once('../../lib/enrollib.php');
require_once('../../cohort/lib.php');


global $DB, $USER, $CFG;
require_login();

$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('batchdetails', 'local_runningbatch'));

$courseid = $_GET['courseid'];

$studentroleid = $DB->get_field('role', 'id', array('shortname' => 'student'));
$is_student = user_has_role_assignment($USER->id, $studentroleid);
$course_condition = "WHERE 1=1";
if($courseid){
   $_SESSION['courseid'] = $courseid;
}

if($_SESSION['courseid'] > 0){
   $course_id = $_SESSION['courseid'];
   $course_condition .= " AND mb.courseid=$course_id";
}

if(is_siteadmin()){
    $batches = $DB->get_records_sql("SELECT * FROM {batch} mb $course_condition");
} else {
    $batches = $DB->get_records_sql("SELECT mb.* FROM {batch} mb JOIN {groups_members} mgm ON mb.groupid = mgm.groupid $course_condition AND mgm.userid=$USER->id");
}

$batch_arr = [];
foreach ($batches as $batch) {
    $course = $DB->get_record('course', ['id' => $batch->courseid]);
    $group = $DB->get_record('groups', ['id' => $batch->groupid]);
    $total_members = count($DB->get_records('groups_members', ['groupid' => $batch->groupid]));
    $total_attendance = count($DB->get_records_sql("SELECT mal.id FROM {attendance} ma JOIN {attendance_statuses} mas ON ma.id=mas.attendanceid JOIN {attendance_log} mal ON mas.id=mal.statusid WHERE ma.course=$batch->courseid AND mas.acronym='P' AND mal.studentid=$USER->id"));

    $obj = new stdClass();
    $obj->batchid = $batch->groupid;
    $obj->batch_name = $group->name;
    $obj->start_datetime = date("d M Y H:i A", $batch->start_datetime);
    $obj->coursename = $course->fullname;

    $teacher = $DB->get_record_sql("SELECT mu.id, CONCAT(mu.firstname,' ', mu.lastname) as name FROM {user} mu JOIN {user_enrolments} mue ON mu.id=mue.userid JOIN {enrol} me ON me.id=mue.enrolid JOIN {role_assignments} mra ON mu.id=mra.userid JOIN {role} mr ON mr.id=mra.roleid WHERE mr.shortname IN ('teacher', 'editingteacher') AND me.courseid=$course->id AND mu.id!=2");
    
    $obj->teacher = $teacher->name;

    // if($is_student){
    //   $icon = "bi bi-calendar-check";
    //   $text = "Attendance $total_attendance";
    // } else {
    //   $icon = "bi bi-person-circle";
    //   $text = "$total_members Students";
    // }
    $obj->total_members = $total_members;

    $obj->icon = $icon;
    $obj->text = $text;

    $batch_arr[] = $obj;
}

$courses = enrol_get_my_courses();
$course_arr = [];
foreach($courses as $course){
   $obj = new stdClass();
   $obj->courseid = $course->id;
   $obj->coursename = $course->fullname;

   $course_arr[] = $obj;
}



$templatecontext=[
    'output' => $OUTPUT,
    'base_url' => $CFG->wwwroot,
    'batch_arr' => $batch_arr,
    'course_arr' => $course_arr,
    'is_student' => $is_student
];

// if(!$is_student){
//   $templatecontext['student']=array('student'=>true);
// }


echo $OUTPUT->header();

echo $OUTPUT->render_from_template('local_runningbatch/batch_details', $templatecontext);

echo $OUTPUT->footer();
