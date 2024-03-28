<?php

global $CFG,$DB;
require_once "../../config.php";
require "$CFG->libdir/tablelib.php";
require "classes/table/users_log.php";
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url($CFG->wwwroot.'/users_log_view.php');
$PAGE->set_pagelayout("standard");

// $download = optional_param('download', '', PARAM_ALPHA);
$search = optional_param('search', '', PARAM_TEXT);


$table = new users_log('uniqueid');
$table->is_downloading($download, 'test', 'testing123');

if (!$table->is_downloading()) {
    
    $PAGE->set_title('Video Watched Report');
    $PAGE->set_heading('Video Watched Report');
    $PAGE->navbar->add('Video Watched Report', new moodle_url('/users_log_view.php'));
    echo $OUTPUT->header();
}


// echo "
//     <div>
//         <form method='post' class='d-flex float-right' '>
//             <input type='text' class='me-2 form-control rounded' name='search' placeholder='Search' value='$search'>

//             <input type='submit' class='btn btn-primary ml-2' name='submit' value='Search'>
//             <a href='$CFG->wwwroot/local/courses/users_log_view.php'>
//             <input type='button' class='btn btn-secondary ml-2' name='cancel' value='Cancel'></a>
//         </form>
//     </div>
//     ";


$fields = "(@row_number := @row_number + 1) AS serialno, u.username AS user,SUM(cv.durationwatchinseconds) AS totalduration ";
$from = "{course_video} cv JOIN {user} u ON cv.userid = u.id";
$where = "cv.userid!=1 and cv.userid!=2 group by cv.userid";



// if($startdate or $enddate){
    //         $start = strtotime($startdate);
    //         if(empty($enddate)){
        //             $end = time();
        //         } else {
            //             $end = strtotime($enddate);
            //         }     
            //         $where .= " and (pr.paymenttime BETWEEN '$start' AND '$end')";
            //     }
            if($search){
                        $where .= " and (u.username LIKE '%$search%')";
                    }
                $perpage = 10;
                $page = optional_param('page', '0', PARAM_INT);
                $DB->execute('SET @row_number = ' . ($perpage * $page));
                
                $table->set_sql($fields, $from, $where);
                
                // var_dump($table);
                // die;
                
                $table->define_baseurl("$CFG->wwwroot/local/courses/users_log_view.php");
                $table->out($perpage, true);
                
                
                if (!$table->is_downloading()) {
    echo $OUTPUT->footer();
}
