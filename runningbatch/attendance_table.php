<?php
class test_table extends table_sql {
    // function __construct($uniqueid) {
    //     parent::__construct($uniqueid);
    // }
     function __construct($uniqueid)
    {
        parent::__construct($uniqueid);
        // Define the list of columns to show.
        $columns = array('num', 'id', 'userid','timecreated', 'log');
        $this->define_columns($columns);

        // Define the titles of columns to show in header.
        $headers = array('S.No.', 'Class Name', 'Student Name', 'Date', 'Status');
        $this->define_headers($headers);
    }
    function col_userid($values) {
        global $DB;
        $student_name = $DB->get_record_sql("Select firstname from {user} where id=$values->userid");
        // If the data is being downloaded than we don't want to show HTML.
        if ($this->is_downloading()) {
            return $student_name->firstname;
        } else {
            return $student_name->firstname;
        }
    }
    function col_id($values) {
        global $DB;
        $class_name = $DB->get_record_sql("Select name from {bigbluebuttonbn} where id = $values->bigbluebuttonbnid ");
        // If the data is being downloaded than we don't want to show HTML.
        if ($this->is_downloading()) {
            return $class_name->name;
        } else {
            return $class_name->name;
        }
    }
    function col_timecreated($values) {
        $time= date('d M Y', $values->timecreated);
        if ($this->is_downloading()) {
            return $time;
        } else {
            return $time;
        }
    }
    function col_log($values) {
        if ($this->is_downloading()) {
            return "joined";
        } else {
            return "joined";
        }
    }
    
}
