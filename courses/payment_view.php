<?php
/**
 * Simple file payment_view.php to drop into root of Moodle installation.
 * This is an example of using a sql_table class to format data.
 */
global $CFG,$DB;
require_once "../../config.php";
require "$CFG->libdir/tablelib.php";
require "classes/table/payment_report.php";
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/payment_view.php');
$PAGE->set_pagelayout("standard");

$download = optional_param('download', '', PARAM_ALPHA);
$search = optional_param('search', '', PARAM_TEXT);
$startdate = optional_param('startdate', '', PARAM_TEXT);
$enddate = optional_param('enddate', '', PARAM_TEXT);


$table = new payment_report('uniqueid');
$table->is_downloading($download, 'test', 'testing123');

if (!$table->is_downloading()) {
    
    $PAGE->set_title('Payment Report');
    $PAGE->set_heading('Payment Reports');
    $PAGE->navbar->add('Complete Payment Reports', new moodle_url('/payment_view.php'));
    echo $OUTPUT->header();
}
// echo "<p>Start Date <input type='date' value='$startdate' class='border'></p>
// <p>End Date <input type='date' value='$enddate' class='border'></p>
// <button type='button'>SELECT</button>";

echo "
    <div>
        <form method='post' class='d-flex float-right' '>
            <input type='text' class='me-2 form-control rounded' name='search' placeholder='Search' value='$search'>
            <input type='date' name='startdate' class='ml-2 form-control rounded' value='$startdate'>
            <input type='date' name='enddate' class='ml-2 form-control rounded' value='$enddate'>
            <input type='submit' class='btn btn-primary ml-2' name='submit' value='Search'>
            <a href='$CFG->wwwroot/local/courses/payment_view.php'>
            <input type='button' class='btn btn-secondary ml-2' name='cancel' value='Cancel'></a>
        </form>
    </div>
    ";

// echo "<form class='search-input-form expanded float-right' method='POST' action='$CFG->wwwroot/local/courses/payment_view.php' >
// <div class='input-group '>
//   <input type='search' class='form-control' placeholder='Search' name='search' value='$search'>";


$fields = "(@row_number := @row_number + 1) as sno,pr.amount,c.fullname as coursename,u.username as username,pr.paymenttime as paymenttime";
$from = "{payment_razorpay}  pr
JOIN {course} c on c.id = pr.courseid
JOIN {user} u on u.id=pr.userid";
$where = "1=1";

if($startdate or $enddate){
        $start = strtotime($startdate);
        if(empty($enddate)){
            $end = time();
        } else {
            $end = strtotime($enddate);
        }     
        $where .= " and (pr.paymenttime BETWEEN '$start' AND '$end')";
    }
if($search){
        $where .= " and (u.username LIKE '%$search%')";
    }
$perpage = 10;
$page = optional_param('page', '0', PARAM_INT);
$DB->execute('SET @row_number = ' . ($perpage * $page));

$table->set_sql($fields, $from, $where);


$table->define_baseurl("$CFG->wwwroot/local/courses/payment_view.php?search=$search&startdate=$startdate&enddate=$enddate");
$table->out($perpage, true);


if (!$table->is_downloading()) {
    echo $OUTPUT->footer();
}