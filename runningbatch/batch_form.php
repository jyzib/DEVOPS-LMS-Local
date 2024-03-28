<?php

require_once("../../config.php");
require_once("$CFG->libdir/formslib.php");
echo"
<style>
    #page-content {
    display: flex;
    flex-direction: row-reverse;
}
</style>
";


$courseid = $_GET['id'];
if ($courseid) {
    $_SESSION['batch_form_id'] = $_GET['id'];
} else {
    $courseid = $_SESSION['batch_form_id'];
}
// $submit = optional_param('submit', '', PARAM_TEXT);
// $groupid = optional_param('groupid', 0, PARAM_INT);
// $courseid = optional_param('courseid', 0, PARAM_INT);



// session_start();
// if ($submit && $groupid && $courseid) {
//     $_SESSION['submit'] = $submit;
//     $_SESSION['groupid'] = $groupid;
//     $_SESSION['courseid'] = $courseid;
// }


$PAGE->set_title(get_string('batch_create', 'local_runningbatch'));
$PAGE->set_heading(get_string('batch_create', 'local_runningbatch'));

function get_weekday_options()
{
    return [
        1 => get_string('sunday', 'calendar'),
        2 => get_string('monday', 'calendar'),
        3 => get_string('tuesday', 'calendar'),
        4 => get_string('wednesday', 'calendar'),
        5 => get_string('thursday', 'calendar'),
        6 => get_string('friday', 'calendar'),
        7 => get_string('saturday', 'calendar'),
    ];
}
class batchform extends moodleform
{
    protected $course_name;
    public $submit;
    public $courseid;
    public $groupid;
    public static $submitted = false;

    public function __construct($course_name, $courseid)
    {
        $this->course_name = $course_name;
        // $this->submit = $submit;
        $this->courseid = $courseid;
        // $this->groupid = $groupid;
        parent::__construct();
    }
    public function definition()
    {
        global $DB;
        $mform = $this->_form;

        $mform->addElement('static', 'course_name', 'Course Name:', $this->course_name);

        // Group/Batch name
        $mform->addElement('text', 'group_name', 'Batch Name:');
        $mform->setType('group_name', PARAM_TEXT);
        $mform->addRule('group_name', get_string('required'), 'required');

        // Group/Batch description
        $mform->addElement('textarea', 'description', 'Group Description:');
        $mform->setType('description', PARAM_TEXT);

        // Batch Start Date Time
        $starttimeoptions = [
            'step' => 5,
            'defaulttime' => time() + 3600,
        ];
        $mform->addElement('date_time_selector', 'start_time', get_string('start_time', 'local_runningbatch'), $starttimeoptions);

        // Add duration.
        $mform->addElement('duration', 'duration', get_string('duration', 'local_runningbatch'));
        $mform->setDefault('duration', ['number' => 1, 'timeunit' => 3600]);

        // Add recurring widget.
        $mform->addElement(
            'advcheckbox',
            'recurring',
            get_string('recurringbatch', 'local_runningbatch'),
            get_string('recurringbatchthisis', 'local_runningbatch')
        );
        // $mform->setDefault('recurring', $config->defaultrecurring);
        $mform->addHelpButton('recurring', 'recurringbatch', 'local_runningbatch');

        // Add options for recurring batch
        $recurrencetype = [
            RECURRINGTYPE_DAILY => get_string('recurrence_option_daily', 'local_runningbatch'),
            RECURRINGTYPE_WEEKLY => get_string('recurrence_option_weekly', 'local_runningbatch'),
            RECURRINGTYPE_MONTHLY => get_string('recurrence_option_monthly', 'local_runningbatch'),
        ];
        $mform->addElement('select', 'recurrence_type', get_string('recurrencetype', 'local_runningbatch'), $recurrencetype);
        $mform->hideif('recurrence_type', 'recurring', 'notchecked');

        // Weekly options.
        $weekdayoptions = get_weekday_options();
        $group = [];
        foreach ($weekdayoptions as $key => $weekday) {
            $weekdayid = 'weekly_days_' . $key;
            $group[] = $mform->createElement(
                'advcheckbox',
                $weekdayid,
                '',
                $weekday,
                null,
                [0, $key]
            );
        }

        $mform->addGroup($group, 'weekly_days_group', get_string('occurson', 'local_runningbatch'), ' ', false);
        $mform->hideif('weekly_days_group', 'recurrence_type', 'noteq', RECURRINGTYPE_WEEKLY);
        $mform->hideif('weekly_days_group', 'recurring', 'notchecked');

        // Monthly options.
        $monthoptions = [];
        for ($i = 1; $i <= 31; $i++) {
            $monthoptions[$i] = $i;
        }

        $group = [];
        $group[] = $mform->createElement(
            'radio',
            'monthly_repeat_option',
            '',
            get_string('day', 'calendar'),
            MONTHLY_REPEAT_OPTION_DAY
        );
        $group[] = $mform->createElement('select', 'monthly_day', '', $monthoptions);
        $group[] = $mform->createElement('static', 'month_day_text', '', get_string('month_day_text', 'local_runningbatch'));
        $mform->addGroup($group, 'monthly_day_group', get_string('occurson', 'local_runningbatch'), null, false);
        $mform->hideif('monthly_day_group', 'recurrence_type', 'noteq', RECURRINGTYPE_MONTHLY);
        $mform->hideif('monthly_day_group', 'recurring', 'notchecked');
        $mform->setDefault('monthly_repeat_option', MONTHLY_REPEAT_OPTION_DAY);

        // End date option
        $mform->addElement('date_selector', 'end_date', 'End Date');
        $mform->hideif('end_date', 'recurring', 'notchecked');

        $context = context_course::instance($this->courseid);
        $role = $DB->get_record_sql("SELECT * from {role} where shortname='editingteacher'");

        $teachers1 = $DB->get_records_sql("SELECT u.id,CONCAT(u.firstname,' ', u.lastname) as name from {role_assignments} as ra join {user} as u on ra.userid=u.id where ra.roleid=$role->id and ra.contextid=$context->id");



        $mform->addElement('select', 'teacher', get_string('availableteachers', 'local_runningbatch'), $teachers);
        $mform->setType('teacher', PARAM_TEXT);
        foreach ($teachers1 as $teacher) {
            $mform->getElement('teacher')->addOption($teacher->name, $teacher->id);
        }
        $mform->addRule('teacher', "Required", 'required');



        // Additional Teachers field
        // if ($_SESSION['submit'] && $_SESSION['groupid'] && $_SESSION['courseid']) {
        //     $courseid = $_SESSION['courseid'];
        //     $groupid = $_SESSION['groupid'];
        //     $teachers = $DB->get_records_sql("SELECT mu.id, CONCAT(mu.firstname,' ', mu.lastname) as name FROM {user} mu JOIN {user_enrolments} mue ON mue.userid=mu.id JOIN {enrol} me ON me.id=mue.enrolid JOIN {role_assignments} mra ON mra.userid=mu.id JOIN {role} mr ON mra.roleid=mr.id WHERE mr.shortname IN ('teacher', 'editingteacher') AND me.courseid=$courseid AND mu.id NOT IN (SELECT GROUP_CONCAT(mgm.userid) FROM {groups_members} mgm JOIN {groups} mg ON mgm.groupid=mg.id AND mg.id!=$groupid)");
        //     $available_teachers[0] = "Teachers";
        //     foreach ($teachers as $teach) {
        //         $available_teachers[$teach->id] = $teach->name;
        //     }

        //     $mform->addElement('select', 'ankit', get_string('availableteachers', 'local_runningbatch'), $available_teachers);
        //     $mform->setType('ankit', PARAM_TEXT);
        // }

        $this->add_action_buttons();
    }

    // // Form validation
    public function validation($data, $files)
    {
        global $DB;
        $errors = array();

        if (empty($data['group_name'])) {
            $errors['group_name'] = get_string('required');
        } else if (!empty($data['group_name'])) {
            if ($data['teacher'] != 0) {
                $group = $DB->get_record('groups', ['name' => $data['group_name']]);
                if ($group) {
                    $errors['group_name'] = get_string('group_name_exists', 'local_runningbatch');
                }
            }
        }

        return $errors;
    }
}


$course_name = $DB->get_field('course', 'fullname', ['id' => $courseid]);

// Create an instance of the form with the course name
$form2 = new batchform($course_name, $courseid);
// $form2 = new batchform($course_name, $submit, $courseid, $groupid);

echo $OUTPUT->header();

if ($form2->is_cancelled()) {
    unset($_SESSION['submit']);
    unset($_SESSION['courseid']);
    unset($_SESSION['groupid']);
    redirect($url, "Form Cancelled");
} else if ($form2->is_submitted() && $form2->is_validated()) {

    global $data;
    // Get the form data
    $data = $form2->get_data();

    // var_dump($data);
    // die();

    if ($data->teacher) {

        // code to perform teacher task



    }



    // Create a new group
    $group = new stdClass();
    $group->courseid = $courseid;
    $group->name = $data->group_name;
    $group->description = $data->description;
    $group->timecreated = time();
    $id = groups_create_group($group);

    // Create a new batch
    $batch = new stdClass();
    $batch->groupid = $id;
    $batch->courseid = $courseid;
    $batch->start_datetime = $data->start_time;
    $batch->duration = $data->duration;
    $batch->recurring = $data->recurring;
    // Setting the recurrence fields
    if ($data->recurring == 1) {
        if ($data->recurrence_type == "RECURRINGTYPE_MONTHLY") {

            $recur_type = 'monthly';
            $batch->recurrence_type = $recur_type;
            $batch->monthly_day = $data->monthly_day;

        } else if ($data->recurrence_type == "RECURRINGTYPE_WEEKLY") {

            $recur_type = 'weekly';
            $batch->recurrence_type = $recur_type;
            $days = [];
            if ($data->weekly_days_1 != 0) {
                $days[] = $data->weekly_days_1;
            }
            if ($data->weekly_days_2 != 0) {
                $days[] = $data->weekly_days_2;
            }
            if ($data->weekly_days_3 != 0) {
                $days[] = $data->weekly_days_3;
            }
            if ($data->weekly_days_4 != 0) {
                $days[] = $data->weekly_days_4;
            }
            if ($data->weekly_days_5 != 0) {
                $days[] = $data->weekly_days_5;
            }
            if ($data->weekly_days_6 != 0) {
                $days[] = $data->weekly_days_6;
            }
            if ($data->weekly_days_7 != 0) {
                $days[] = $data->weekly_days_7;
            }
            $weekly_days = implode(",", $days);
            $batch->weekly_days = $weekly_days;

        } else {

            $recur_type = 'daily';
            $batch->recurrence_type = $recur_type;

        }

        $batch->end_date = $data->end_date;
    }

    $batch_id = $DB->insert_record('batch', $batch);


    groups_add_member($id, $data->teacher);
    // redirect("$url?id=$courseid", "Group Created");
    // $params = ['courseid' => $courseid, 'groupid' => $id, 'group_name' => $data->group_name, 'description' => $data->description, 'start_time' => $data->start_time, 'duration' => $data->duration, 'recurring' => $data->recurring, 'recurrence_type' => $data->recurrence_type, 'weekly_days_1' => $data->weekly_days_1, 'weekly_days_2' => $data->weekly_days_2, 'weekly_days_3' => $data->weekly_days_3, 'weekly_days_4' => $data->weekly_days_4, 'weekly_days_5' => $data->weekly_days_5, 'weekly_days_6' => $data->weekly_days_6, 'weekly_days_7' => $data->weekly_days_7, 'end_date' => $data->end_date];
    // $url .= "?" . http_build_query($params);

    redirect("$CFG->wwwroot/group/members.php?group=$id", "Group created, Now you can add student");
} else {
    $groupid = optional_param('groupid', 0, PARAM_INT);
    $group_name = optional_param('group_name', '', PARAM_TEXT);
    $description = optional_param('description', '', PARAM_TEXT);
    $start_time = optional_param('start_time', '', PARAM_TEXT);
    $duration = optional_param('duration', '', PARAM_TEXT);
    $recurring = optional_param('recurring', '', PARAM_TEXT);
    $recurrence_type = optional_param('recurrence_type', '', PARAM_TEXT);
    $weekly_days_1 = optional_param('weekly_days_1', '', PARAM_TEXT);
    $weekly_days_2 = optional_param('weekly_days_2', '', PARAM_TEXT);
    $weekly_days_3 = optional_param('weekly_days_3', '', PARAM_TEXT);
    $weekly_days_4 = optional_param('weekly_days_4', '', PARAM_TEXT);
    $weekly_days_5 = optional_param('weekly_days_5', '', PARAM_TEXT);
    $weekly_days_6 = optional_param('weekly_days_6', '', PARAM_TEXT);
    $weekly_days_7 = optional_param('weekly_days_7', '', PARAM_TEXT);
    $end_date = optional_param('end_date', '', PARAM_TEXT);

    $defaults = [
        'group_name' => $group_name,
        'description' => $description,
        'start_time' => $start_time,
        'duration' => $duration,
        'recurring' => $recurring,
        'recurrence_type' => $recurrence_type,
        'weekly_days_1' => $weekly_days_1,
        'weekly_days_2' => $weekly_days_2,
        'weekly_days_3' => $weekly_days_3,
        'weekly_days_4' => $weekly_days_4,
        'weekly_days_5' => $weekly_days_5,
        'weekly_days_6' => $weekly_days_6,
        'weekly_days_7' => $weekly_days_7,
        'end_date' => $end_date,
    ];

    unset($_SESSION['submit']);
    unset($_SESSION['courseid']);
    unset($_SESSION['groupid']);
    // Set default fields
    $form2->set_data($defaults);

    // Display the form
    $form2->display();
}

echo $OUTPUT->footer();
