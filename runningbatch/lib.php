<?php
    function local_runningbatch_extend_navigation(global_navigation $navigation)
    {
        global $CFG, $PAGE,$DB,$USER;
        $teacher = $DB->get_field('role', 'id', ['shortname'=>'teacher']);
        $editingteacher = $DB->get_field('role', 'id', ['shortname'=>'editingteacher']);
        $student = $DB->get_field('role', 'id', ['shortname'=>'student']);
        
        $icon = new pix_icon('navicon', '', 'local_runningbatch', array('class' => 'icon pluginicon'));
        if(is_siteadmin())
        {

            $managelink = $navigation->add('Manage', null, null, null, 'managelink');

            $managelink->add('Manage Courses', new moodle_url('/course/management.php'), navigation_node::TYPE_CUSTOM, null, 'managecourses');
            // if (user_has_role_assignment($USER->id,$teacher) || user_has_role_assignment($USER->id,$editingteacher))
            // {
            //     $navigation->add(
            //         get_string('navname', 'local_runningbatch'),
            //         new moodle_url($CFG->wwwroot . '/local/runningbatch/dashboard.php'),
            //         navigation_node::TYPE_SYSTEM,
            //         null,
            //         'local_runningbatch',
            //         $icon,
            //     )->showinflatnavigation = true;
            // }
            // if (user_has_role_assignment($USER->id,$student))
            // {
            //     $navigation->add(
            //         get_string('navname', 'local_runningbatch'),
            //         new moodle_url($CFG->wwwroot . '/local/runningbatch/student_batch_details.php'),
            //         navigation_node::TYPE_SYSTEM,
            //         null,
            //         'local_runningbatch',
            //         $icon,
            //     )->showinflatnavigation = true;
            // }
            $navigation->add(
                        "Manage Courses",
                        new moodle_url($CFG->wwwroot . '/course/management.php'),
                        navigation_node::TYPE_SYSTEM,
                        null,
                        'local_runningbatch',
                        new pix_icon("i/course", "")
                    )->showinflatnavigation = true;
        }
    }
?>