<?php

function local_courses_extend_navigation(global_navigation $nav3) {
    if (!is_siteadmin()){
    global $CFG, $PAGE, $DB, $USER;

    $nav3->add(
    'Courses',
    new moodle_url($CFG->wwwroot . '/local/courses/course_list.php'),
    navigation_node::TYPE_SYSTEM,
    null,
    'local_courses',
    null
    )->showinflatnavigation = true;
    }
}
