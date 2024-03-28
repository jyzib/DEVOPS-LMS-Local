<?php

require_once("../../config.php");
global $CFG,$DB;
require_once "classes/forms/addvideo.php";
global $OUTPUT;
require_login();


$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_pagelayout('standard');
// $PAGE->set_title('Add Video');
$PAGE->set_heading('Add New Video to Course');
$PAGE->set_url("$CFG->wwwroot/local/addvideo/addvideo.php");

$mform = new addvideo();
echo $OUTPUT->header();

if ($mform->is_cancelled()) {
    $urltogo= "$CFG->wwwroot/local/addvideo/addvideo.php";
redirect($urltogo);
    
} else if ($fromform = $mform->get_data()) {
    
    $new_name = $mform->get_new_filename('video');
    $filename = uniqid();
    $path= 'video/'.$filename.'.mp4';
    $fullpath = '/local/addvideo/video/'. $filename.'.mp4';
    $success = $mform->save_file('video', $path, true);
    var_dump($fromform);
    // die;
   
    $record = new stdClass();       
    $record->id='';
    $record->courseid= $fromform->course;
    $record->videopath= $fullpath;
    $record->title= $fromform->name;
    $record->description= $fromform->description;
    $record->timecreated= time();
    // var_dump($fullpath);
    // var_dump($success);
    // die;
    $insert=$DB->insert_record('course_video_detail', $record, false);
    $url=("$CFG->wwwroot/local/addvideo/addvideo.php");
    redirect($url);
        
}else{


    // $mform->set_data($toform);

    $mform->display();  
    echo $OUTPUT->footer();
}

    
// 
