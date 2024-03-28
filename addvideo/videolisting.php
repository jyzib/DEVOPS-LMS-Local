<?php
/**
 * Simple file test_custom.php to drop into root of Moodle installation.
 * This is an example of using a sql_table class to format data.
 */
global $CFG,$DB;
require_once "../../config.php";
require_once "$CFG->libdir/tablelib.php";
require_once "classes/table/video_list.php";


$context = context_system::instance();
$PAGE->set_context($context);

// $PAGE->set_url('classes/table/video_list.php');
$cvdetailid = optional_param('cvid', '', PARAM_INT);
// var_dump($cvdetailid);
// die;
if($cvdetailid>0){
    $delete=$DB-> delete_records(
        'course_video_detail',
       ['id'=>$cvdetailid]
    );
}

$table = new video_list('uniqueid');
$table->is_downloading($download, 'test', 'testing123');

if (!$table->is_downloading()) {
    // Only print headers if not asked to download data.
    // Print the page header.
    $PAGE->set_title('Testing');
    $PAGE->set_heading('Testing table class');
    $PAGE->navbar->add('Testing table class', new moodle_url('videolisting.php'));
    $PAGE->set_pagelayout('standard');
    echo $OUTPUT->header();
    // echo "<button class='btn btn-secondary ml-3 rounded'><a href = '$url'>Clear</a></button></div>";
}


$fields="(@row_number := @row_number + 1) as serialno,c.id as courseid,cvd.id as cvdid ,cvd.title as titl ,cvd.description as description,cvd.videopath as videopath,cvd.timecreated as timecreated ";
$from="{course} c 
JOIN {course_video_detail} cvd ON c.id=cvd.courseid  ";
$where='1=1';



$perpage = 10;
$page = optional_param('page', 0, PARAM_INT);
$DB->execute('SET @row_number = ' . (($perpage * $page)));
// Work out the sql for the table.
$table->set_sql($fields,$from ,$where );


// var_dump($table);die;
// Work out the sql for the table.
// $table->set_sql('*', "{course_video_detail}", '1=1');

$table->define_baseurl("$CFG->wwwroot/local/addvideo/videolisting.php");

$table->out(10, true);

if (!$table->is_downloading()) {
    echo $OUTPUT->footer();
}