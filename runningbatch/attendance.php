<?php
require_once('../../config.php');
require_once($CFG->libdir.'/tablelib.php');
require_once(__DIR__.'/attendance_table.php');

$courseid = $_GET['courseid'];
$download = optional_param('download', '', PARAM_ALPHA);

$table = new test_table('uniqueid');
$table->is_downloading($download, 'attandence', 'testing123');

if (!$table->is_downloading()) {
    echo '<link rel="stylesheet" href="'.$CFG->wwwroot.'/local/runningbatch/css/runningbatch.css">';
    $PAGE->set_title(get_string('attendance', 'local_runningbatch'));
    $PAGE->set_heading(get_string('attendance', 'local_runningbatch'));
    $PAGE->set_pagelayout('standard');
    echo $OUTPUT->header();
}

$params = [];

$fields = '*,(@row_number:=@row_number + 1) as num';

$from = "{bigbluebuttonbn_logs} bbl";

$where = "bbl.courseid = $courseid AND log='join'";
$DB->execute('SET @row_number = 0', array());
$table->set_sql($fields, $from, $where, $params);
$table->define_baseurl("$CFG->wwwroot/local/runningbatch/attendance.php?courseid=$courseid");
$table->out(10, true);
    
if (!$table->is_downloading()) {
    echo $OUTPUT->footer();
}
?>

<script>
    function backButton(){
         const x = document.querySelector('#page-header .d-flex.align-items-center');
         let anchortag = document.createElement('a');
         anchortag.href = "<?php echo "$CFG->wwwroot/my/" ?>";
         anchortag.textContent = 'Back';
         x.append(anchortag);
         anchortag.classList.add('btn-dark','btn')
       
    }
    backButton();
</script>

