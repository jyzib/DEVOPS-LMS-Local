<?php
require_once('../../config.php');
require_once($CFG->libdir.'/tablelib.php');
require_once(__DIR__.'/invoice_paid_table.php');

$sort = optional_param('sort', 'ip.course', PARAM_ALPHA);
$dir = optional_param('dir', 'ASC', PARAM_ALPHA);
$coursefilter = optional_param('coursefilter', '', PARAM_RAW);
$perpage = optional_param('perpage', 10, PARAM_INT);
$download = optional_param('download', '', PARAM_ALPHA);

$PAGE->set_pagelayout('standard');

$table = new test_table('uniqueid');
$table->is_downloading($download, 'view_invoice_paid', 'testing123');

if (!$table->is_downloading()) {
    echo '<link rel="stylesheet" href="'.$CFG->wwwroot.'/local/runningbatch/css/runningbatch.css">';
    $PAGE->set_title('Invoice Paid');
    $PAGE->set_heading('Invoice Paid');
    echo $OUTPUT->header();
 

    echo '<div class="card p-3"><form method="get" id="table-invoice-paid" class="mb-5">
            <b><label for="coursefilter">Course filter:</label></b>
            <div class="d-flex align-items-center justify-content-start gap-2 w-30">
            <input type="text" name="coursefilter" id="coursefilter" class="form-control" placeholder="Filter" value="' . $coursefilter . '">
            <input type="submit" value="Filter" Class="submit btn ">
            <a href="'.$CFG->wwwroot.'/local/runningbatch/view_invoice_paid.php"><input type="button" value="Reset" Class="reset btn "></a>
    </div>


</form>';
}

$params = [
    'coursefilter' => "%{$coursefilter}%"
];

$fields = 'ip.date, ip.batch_name, ip.course, ip.invoice, ip.paid_amount, ip.advance, ip.file, ip.pay_list';

$from = '{invoice_paid} ip';

$where = "ip.course LIKE :coursefilter";

$table->set_sql($fields, $from, $where, $params);

$table->define_baseurl("$CFG->wwwroot/local/runningbatch/view_invoice_paid.php?coursefilter={$coursefilter}&perpage={$perpage}");

$table->pagesize($perpage, [10, 20, 50, 100]); // Set the available page size options

$table->out($perpage, true);

    echo '<div class="mt-4 pull-right d-flex align-items-center justify-content-center gap-2 rows-custom">';
    echo '<b class="mb-0"><label for="perpage"  class="mb-0">Records per page:</label></b>';
    echo '<select name="perpage" class="form-control" id="perpage">';
    echo '<option value="10"' . ($perpage == 10 ? ' selected' : '') . '>10</option>';
    echo '<option value="20"' . ($perpage == 20 ? ' selected' : '') . '>20</option>';
    echo '<option value="50"' . ($perpage == 50 ? ' selected' : '') . '>50</option>';
    echo '<option value="100"' . ($perpage == 100 ? ' selected' : '') . '>100</option>';
    echo '</select></div></div>';

if (!$table->is_downloading()) {
    echo $OUTPUT->footer();
}

?>

<script>
    function backButton(){
         const x = document.querySelector('#page-header .d-flex.align-items-center');
         let anchortag = document.createElement('a');
         anchortag.href = 'https://yislms.com/croma/moodle/my/';
         anchortag.textContent = 'Back';
         x.append(anchortag);
         anchortag.classList.add('btn-dark','btn')
       
    }
    backButton();
</script>

