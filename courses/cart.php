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

$response['data']='ok';
$response['userid']=$USER->id;



echo $OUTPUT->header();
echo $OUTPUT->render_from_template('local_courses/cart', $response);
echo $OUTPUT->footer();
?>