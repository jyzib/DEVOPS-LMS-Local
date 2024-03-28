<?php
class test_table extends table_sql {
    // function __construct($uniqueid) {
    //     parent::__construct($uniqueid);
    // }
     function __construct($uniqueid)
    {
        parent::__construct($uniqueid);
        // Define the list of columns to show.
        $columns = array('date', 'batch_name','course', 'paid_amount',);
        $this->define_columns($columns);

        // Define the titles of columns to show in header.
        $headers = array('Date', 'Batch Name', 'Course', 'Paid Amount',);
        $this->define_headers($headers);
    }
    
    // function col_num($values) {
        
    //     // If the data is being downloaded than we don't want to show HTML.
    //     if ($this->is_downloading()) {
    //         return $values->num;
    //     } else {
    //         return $values->num;
    //     }
    // }
    
    // function col_username($values) {
        
    //     // If the data is being downloaded than we don't want to show HTML.
    //     if ($this->is_downloading()) {
    //         return $values->username;
    //     } else {
    //         return $values->username;
    //     }
    // }
    
    // function col_course_name($values) {
        
    //     // If the data is being downloaded than we don't want to show HTML.
    //     if ($this->is_downloading()) {
    //         return $values->course_name;
    //     } else {
    //         return $values->course_name;
    //     }
    // }
    
    // function col_timestart($values) {
    //     if (!$values->timestart==0) {
    //         // If the data is being downloaded than we don't want to show HTML.
    //         if ($this->is_downloading()) {
    //             return date('d-m-Y',$values->timestart);
    //         } else {
    //             return date('d-m-Y',$values->timestart);
    //         }
    //     }else{
    //         // If the data is being downloaded than we don't want to show HTML.
    //         if ($this->is_downloading()) {
    //             return "-";
    //         } else {
    //             return "-";
    //         }
    //     }
    // }

    // function col_timeend($values) {
        
    //     if (!$values->timestart==0) {
            
    //         // If the data is being downloaded than we don't want to show HTML.
    //         if ($this->is_downloading()) {
    //             if ($values->timeend==0) {
    //                 return "unlimited";
    //             }
    //             return date('d-m-Y',$values->timeend);
    //         }else {
    //             if ($values->timeend==0) {
    //                 return "unlimited";
    //             }
    //             return date('d-m-Y',$values->timeend);
    //         }
    //     }else{
    //         // If the data is being downloaded than we don't want to show HTML.
    //         if ($this->is_downloading()) {
    //             return "-";
    //         } else {
    //             return "-";
    //         }
    //     }
    // }
    
    // function col_module_name($values) {
        
    //     // If the data is being downloaded than we don't want to show HTML.
    //     if ($this->is_downloading()) {
    //         return $values->module_name;
    //     } else {
    //         return $values->module_name;
    //     }
    // }

    // function col_instance_id($values) {
    //    $moname = get_coursemodule_from_instance($values->module_name,$values->instance_id);
        
    //     // If the data is being downloaded than we don't want to show HTML.
    //     if ($this->is_downloading()) {
    //         return $moname->name;
    //     } else {
    //         return $moname->name;
    //     }
    // }
    
    // function col_module_status($values) {
        
    //     if ($values->module_status==0) {
    //         if ($this->is_downloading()) {
    //         return 'Incomplete';
    //     } else {
    //         return 'Incomplete';
    //     }}elseif ($values->module_status==1) {
    //         if ($this->is_downloading()) {
    //         return 'Completed';
    //     } else {
    //         return 'Completed';
    //     }}elseif ($values->module_status==2) {
    //         if ($this->is_downloading()) {
    //         return 'Inprogress';
    //     } else {
    //         return 'Inprogress';
    //     }}else{
    //         return 'Incomplete';
    //     }
    // }
    
}
