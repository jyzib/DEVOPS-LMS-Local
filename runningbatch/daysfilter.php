<?php

require_once('../../config.php');
require_once('../../lib/enrollib.php');
require_once('../../cohort/lib.php');
global $DB,$CFG,$OUTPUT;
$groupid = $_POST['groupid'];
$courseid = $_POST['courseid'];
$studentid = $DB->get_field('role', 'id', array('shortname' => 'student'));
$if_student = user_has_role_assignment($USER->id, $studentid);


if($groupid && $courseid==''){
  
$course_enrols =  enrol_get_all_users_courses($USER->id);
$cohort_detail=array();
foreach($course_enrols as $courseid){
$cohortsssss = $DB->get_records_sql("SELECT * from {groups} where courseid=$courseid->id and id=$groupid");
foreach($cohortsssss as $cohort)
{
    $teacherid = $DB->get_field('role', 'id', array('shortname' => 'editingteacher'));
    $studentid = $DB->get_field('role', 'id', array('shortname' => 'student'));
    if(is_siteadmin($USER->id)||(user_has_role_assignment($USER->id, $teacherid)) || $if_student){
     $cohort_detail[]=['cohort'=>$cohort,'course'=>$courseid];
    }
}
}
}
if($groupid=='' && $courseid){
  
    $course_details = $DB->get_record_sql("SELECT * from {course} where id = $courseid");
    $cohortsssss = $DB->get_records_sql("SELECT * from {groups} where courseid=$courseid  ");
   foreach($cohortsssss as $cohort)
   {
    $teacherid = $DB->get_field('role', 'id', array('shortname' => 'editingteacher'));
    $studentid = $DB->get_field('role', 'id', array('shortname' => 'student'));
    if(is_siteadmin($USER->id)||(user_has_role_assignment($USER->id, $teacherid)) || $if_student){
     $cohort_detail[]=['cohort'=>$cohort,'course'=>$course_details];
    }
   }
}

if($groupid && $courseid){
 
    $course_details = $DB->get_record_sql("SELECT * from {course} where id = $courseid");
    $cohortsssss = $DB->get_records_sql("SELECT * from {groups} where courseid=$courseid and id=$groupid");
   foreach($cohortsssss as $cohort)
   {
    $teacherid = $DB->get_field('role', 'id', array('shortname' => 'editingteacher'));
    $studentid = $DB->get_field('role', 'id', array('shortname' => 'student'));
    if(is_siteadmin($USER->id)||(user_has_role_assignment($USER->id, $teacherid)) || $if_student){
     $cohort_detail[]=['cohort'=>$cohort,'course'=>$course_details];
    }
   }
}


for($i=0; $i<count(($cohort_detail)); $i++)
    {
      
      

      $unique_array = (($cohort_detail));    

         //attendance
         $var=$unique_array[$i]['cohort']->courseid;
         $total_attendance=0;
         $attendance=$DB->get_records_sql("SELECT max(a.id),l.* FROM {attendance} as a join {attendance_statuses} as s on a.id=s.attendanceid join {attendance_log} as l on s.id=l.statusid where s.acronym='P' and a.course=$var and l.studentid=$USER->id");
         if($attendance){
         $total_attendance=COUNT($attendance);
         }
           


      if(($unique_array[$i])['cohort']){
      //     $name_of_cohort = $DB->get_record_sql("SELECT * from {cohort} where id=($unique_array[$i])->id");
          $a =(int)(($unique_array[$i])['cohort']->id);
          $total_cohort_members = $DB->get_record_sql("SELECT count(userid) as totalmmbers from {groups_members} where groupid=$a");
      }
      if(($unique_array[$i])['course']){
          $b =(($unique_array[$i])['course']->startdate);
          $dayOfWeek = date('w', strtotime($b));
          if ($dayOfWeek == 0 || $dayOfWeek == 6) {
             $weekname='(Weekend)...';
          } else {
             $weekname='(Weekday)...';
            }
              }
      $html='<section>
          <div class="container">
    <div class="row">
    <div class="col-4">
    <a href="'.$CFG->wwwroot.'/local/runningbatch/batch.php?groupid='.$a.'" >
          <div class="box1">
              <p class="title">'.($unique_array[$i])['course']->shortname.''.date('/M/y/', $b).''.$unique_array[$i]['cohort']->name.'/'.($unique_array[$i])['course']->shortname.''.date('/h:i a', $b).'</p>
              <div class="div">
                  <i class="bi bi-calendar3"></i>
                <p class="date" >Start Date : '.date('d M Y', $b).''.$weekname.'</p>
              </div>
              <div class="div"><img class="icon" src="https://png.pngtree.com/png-vector/20230209/ourmid/pngtree-notebook-icon-png-image_6591697.png">
              <p class="date" >'.($unique_array[$i])['course']->fullname.'</p>
              </div>';
              if($if_student){
                $html=$html."<div class='div'><i class='bi bi-calendar-check'></i>
                    <p class='date' >Attendance $total_attendance</p>
                    </div>";}
                $html=$html.'
             
              <br>
                  <div class="div">';
                  if(!$if_student){
                  
                    $html=$html.' <i class="bi bi-person-circle"></i>
              <p class="date" >'.$total_cohort_members->totalmmbers.' Students</p>';
                  }

                $html=$html.'<div class="batch-btn" >
              <a href="'.$CFG->wwwroot.'/local/runningbatch/batch.php?groupid='.$a.'" class="btn btn-primary " role="button" aria-pressed="true">Batch Details </a>
              </div>
              </div>
              
          </div>
      </div>
    </div></a>
  </div>
  </section>';
   }

$json = array();
$json['success'] = true;
$json['table_data'] = $html;
echo json_encode($json);
