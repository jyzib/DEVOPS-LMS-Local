<?php
global $CFG;
require_once("$CFG->libdir/formslib.php");

class addvideo extends moodleform
{
    
    public function definition()
    {global $DB;

        $title = optional_param('titl', '', PARAM_ALPHA);
    $desc = optional_param('description', '', PARAM_ALPHA);
    $cid = optional_param('courseid', '', PARAM_INT);
  

        $mform = $this->_form;

        $mform->addElement('text', 'name', get_string('name', 'local_addvideo'));
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->setdefault('name',$title);

        
        $mform->addElement('filepicker', 'video', get_string('video', 'local_addvideo'));
        if($cid!=0){
         

        }else{

            $mform->addRule('video', null, 'required', null, 'client');
        }
        
        $mform->addElement('text', 'description', get_string('description'), 'maxlength="1000" size="25" ');
        $mform->setType('description', PARAM_TEXT);
        $mform->addRule('description', null, 'required', null, 'client');
        $mform->setdefault('description',$desc);

        $courses=$DB->get_records_sql('SELECT id,fullname from {course} where id !=1');
        $courseOptions = [];
        foreach($courses as $course){
            $courseOptions[$course->id] = $course->fullname;
        }
        
        $mform->addElement('select', 'course', get_string('course', 'local_addvideo'),$courseOptions);
        $mform->addRule('course', null, 'required', null, 'client');
        $mform->setdefault('course',$cid);
        

        $this->add_action_buttons('button', 'Upload');
        
    }
    

    function validation($data, $files) {
        return [];
    }
}
