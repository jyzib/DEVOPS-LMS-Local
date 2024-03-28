<?php

function local_addvideo_extend_navigation(global_navigation $nav3) {
    if (is_siteadmin()){
    global $CFG, $PAGE, $DB, $USER;

    $nav3->add(
    'ADD Video',
    new moodle_url($CFG->wwwroot . '/local/addvideo/addvideo.php'),
    navigation_node::TYPE_SYSTEM,
    null,
    'local_addvideo',
    null
    )->showinflatnavigation = true;
    }
}
