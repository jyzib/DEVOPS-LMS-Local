<?php
require_once("../../config.php"); 
// require_once('lib.php');
// include($CFG'/local/vendor/autoload.php')

global $DB, $USER, $PAGE;
if(is_siteadmin()){

    $context = context_system::instance();
    
    
    $PAGE->set_context($context);
    $PAGE->set_pagelayout("standard");
    $reponse=array();
    
    
    $response['data']="jj";
    
    
    echo $OUTPUT->header();
    echo $OUTPUT->render_from_template('local_promocode/promocode', $response);
    echo $OUTPUT->footer();
}else{
    redirect('/devops/yatharthriti/my/');
}
?>