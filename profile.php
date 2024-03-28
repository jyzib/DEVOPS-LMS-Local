<?php
global $CFG,$DB,$USER;
require_once('../config.php');

require_login();

$context = context_system::instance();
// $USER = $USER->username;

$PAGE->set_context($context);
// $PAGE->set_url('/upcomingcourse.php');
$PAGE->set_title('Profile');
// $PAGE->set_heading('Course Video');
$PAGE->set_pagelayout('standard');



$que=$DB->get_records_sql("SELECT mc.fullname,mc.id from {course} mc join {enrol} me on mc.id=me.courseid join {user_enrolments} mue on me.id=mue.enrolid join {user} mu on mu.id=mue.userid where mc.id !=1 and mu.id=$USER->id");

 
   
function col_coursestatus($val)
{   
    global $DB, $USER;
    
    $course = $DB->get_record('course', ['id' => $val]);
    
    $progress = \core_completion\progress::get_course_progress_percentage($course, $USER->id);

    $roundedProgress = round($progress, 2);

    return $roundedProgress;
}
function isiscompleted($val){
    if(col_coursestatus($val) == 100){
        return 'green';
    }else if (col_coursestatus($val) > 0 && col_coursestatus($val) < 100){
        return 'yellow';
    }
    return '';
   

}
function inprogress($val,$count){

    if (col_coursestatus($val) >= 0 && col_coursestatus($val) < 100){
        $count++;
    }
;
   

}

$coursecount = 0;
$completecount = 0;
 
    $course = array();

    foreach ($que as $field) {
      $coursecount += 1;
 
        $courseData = array(
            'fullname' => $field->fullname,
            'coursestatus' => col_coursestatus($field->id), // Assuming col_coursestatus returns some data
            'iscompleted' => isiscompleted($field->id),
            
           

        );
        if(isiscompleted($field->id) == 'green'){
            $completecount += 1;
        }
      
        $course[] = $courseData;
    }

    $data['inprogres'] = $coursecount - $completecount ;
    $data['complected'] =  $completecount ;

    $data['course'] = $course;
   

$data['username'] = $USER->firstname . ' '.$USER->lastname;
$data['email'] = $USER->email ;
$data['mobile'] = $USER->phone1;
$data['id'] = $USER->id;
$data['address'] = $USER->address;
// var_dump($data);
// die;

echo $OUTPUT->header();
echo $OUTPUT->render_from_template('local_dashboard_main/profile', $data);
echo $OUTPUT->footer();
?>
