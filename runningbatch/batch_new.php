<?php

require_once('../../config.php');
require_once('../../lib/enrollib.php');
require_once('../../cohort/lib.php');

require_login();

global $DB, $USER, $CFG;

$PAGE->set_pagelayout('standard');

$groupid = $_GET['groupid'];

$group = $DB->get_record('groups', ['id' => $groupid]);

$PAGE->set_title("$group->name");

$batch = $DB->get_record('batch', ['groupid' => $groupid]);
$total_members =  count($DB->get_records_sql("SELECT id FROM {groups_members} WHERE groupid=$groupid"));
$course =  $DB->get_record('course', ['id' => $group->courseid]);
$completion = 0;
$context = context_course::instance($course->id);
$enrolled_users = get_enrolled_users($context, $options = array());

$student_roleid = $DB->get_field('role', 'id', ['shortname' => 'student']);


if (user_has_role_assignment($USER->id, $student_roleid)) {
    // If a student, calculate the course percentage
    $progress = core_completion\progress::get_course_progress_percentage($course);
    $progress = round($progress, 2);
} else {
    // If not, calculate the percentage of 'students' who have completed this course
    $students = get_role_users($student_roleid, $context, false, 'u.id, u.username, u.firstname, u.lastname, u.email', null, false);
    $completion_count = 0;
    foreach ($students as $student) {
        if (core_completion\progress::get_course_progress_percentage($course,  $student->id) == 100) {
            $completion_count++;
        }
    }
    $progress = count($students) > 0 ? round(($completion_count / count($students)) * 100, 2) . "%" : "0%";
}


foreach ($enrolled_users as $euser) {
    $completion = $completion + core_completion\progress::get_course_progress_percentage($course,  $euser->id);
}

$module_attendanceid = $DB->get_field('modules', 'id', ['name' => 'attendance']);
$module_labelid = $DB->get_field('modules', 'id', ['name' => 'label']);
$course_labels = $DB->get_records_sql("SELECT id, instance FROM {course_modules} WHERE course=$course->id AND module=$module_labelid");
$zoom_meeting = $DB->get_record_sql("SELECT max(id), join_url, start_time FROM {zoom} WHERE course=$course->id");

$course_section = $DB->get_record_sql("SELECT section FROM {course_sections} WHERE course=$course->id AND name='Recordings'");

$course_attendanceid = $DB->get_record_sql("SELECT max(id) as id FROM {course_modules} WHERE course=$course->id AND module=$module_attendanceid");

$batch = $DB->get_record('batch', ['groupid' => $groupid]);

$meeting=$DB->get_record_sql("SELECT id, instance  from {course_modules} where course=$course->id and module=3 and deletioninprogress=0");
if($meeting){
   $meeting_url="$CFG->wwwroot/mod/bigbluebuttonbn/bbb_view.php?action=join&id=$meeting->id&bn=$meeting->instance";
}
$openingtime=$DB->get_record_sql("SELECT openingtime from {bigbluebuttonbn} where id=$meeting->instance");
$start_time = date('H:i:s d:M:Y', $openingtime->openingtime);

$course_curriculum_url = "$CFG->wwwroot/local/runningbatch/course_curriculum.php?groupid=$groupid&courseid=$course->id";

$studentid = $DB->get_field('role', 'id', array('shortname' => 'student'));
$if_student = user_has_role_assignment($USER->id, $studentid);

$data = [
    'batch_name' => $group->name,
    'start_datetime' => date("d M Y h:i A", $batch->start_datetime),
    'total_members' => $total_members,
    'courseid' => $course->id,
    'coursename' => $course->fullname,
    'section' => $course_section->section,
    'progress' => $progress
];


$templatecontext = [
    'output' => $OUTPUT,
    'base_url' => $CFG->wwwroot,
    'data' => $data,
    'if_student' => $if_student,
    'not_student' => !$if_student,
    'meeting_url' => $meeting_url,
    'course_curriculum_url' => $course_curriculum_url
];


echo $OUTPUT->header();

echo $OUTPUT->render_from_template('local_runningbatch/batch_new', $templatecontext);

echo $OUTPUT->footer();
