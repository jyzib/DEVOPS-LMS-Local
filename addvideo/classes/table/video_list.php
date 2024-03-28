<?php

defined('MOODLE_INTERNAL') || die();


// require_once '../../../../config.php';
// require_once "../../../../lib/tablelib.php";

class video_list extends table_sql {

    
  
    function __construct($uniqueid) {
        parent::__construct($uniqueid);
        // Define the list of columns to show.
        $columns = array('titl', 'description', 'courseid','videopath','timecreated','actionbuttons');
        $this->define_columns($columns);

        // Define the titles of columns to show in header.
        $headers = array('Title', 'Description', 'Course','Video Path','Upload Time','Action Buttons');
        $this->define_headers($headers);
    }

    function col_timecreated($values) {
       
            $times=date("d-m-Y", $values->timecreated);
            return $times;  
        
    }

    function col_actionbuttons($values) {

        

        
   
        // $url=('https://yislms.com/devops/yatharthriti/local/addvideo/addvideo.php');
        // // $times=date("d-m-Y", $values->timecreated);
        // // return $times;  
        // // html_writer::div(<button></button>, class="", attributes="");
        // $edit="<button class='btn btn-primary ml-3 rounded'><a href = '$url'>Edit</a></button></div>";
        // $copy="<button class='btn btn-primary ml-3 rounded'>Copy</a></button></div>";
        // $delete="<button class='btn btn-primary ml-3 rounded'>Delete</a></button></div>";
        // $arr=
        GLOBAL $CFG;
        return html_writer::tag("div", "<a href = '$CFG->wwwroot/local/addvideo/addvideo.php?titl=$values->titl&description=$values->description&courseid=$values->courseid' title='Edit'><i class='fa fa-pencil'></i></a>
                    <a href='$CFG->wwwroot/local/addvideo/videolisting.php?cvid=$values->cvdid' title='Delete'><i class='fa fa-trash'></i></a>
                    <i data-videopath='$values->videopath' class='custom-copy fa fa-copy' ></i>", null);
    // return 
    // return 
}
function col_courseid($values) {
    // var_dump($values);
    // die;   
    global $DB;
    $cname=$DB->get_record_sql("SELECT fullname FROM {course} WHERE id=$values->courseid");
    // foreach($cname as $name){
  
        return $cname->fullname;
    // }
    
}
    function other_cols($colname, $value) {
        // For security reasons we don't want to show the password hash.
        // if ($colname == 'password') {
        //     return "****";
        // }
    }
}

echo"


<script src='https://yislms.com/devops/yatharthriti/local/addvideo/classes/table/videoscript.js'></script>

";

