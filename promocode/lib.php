<?php

function local_promocode_extend_navigation(global_navigation $nav3) {
    if (is_siteadmin()){
    global $CFG, $PAGE, $DB, $USER;

    $nav3->add(
    'Promo Code',
    new moodle_url($CFG->wwwroot . '/local/promocode/index.php'),
    navigation_node::TYPE_SYSTEM,
    null,
    'local_promocode',
    null
    )->showinflatnavigation = true;
    }
}
