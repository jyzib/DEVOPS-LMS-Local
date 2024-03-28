<?php 
require_once('../../config.php');
require_once('../../lib/enrollib.php');
require_once('../../cohort/lib.php');

require_login();
global $DB,$CFG,$OUTPUT;
$courseids=$DB->get_records_sql("SELECT *  from {course} where id!=1 ");
$cohort_detail=array();
foreach($courseids as $courseid){
// $contextid = context_course::instance($courseid->id);
$cohortsssss = $DB->get_records_sql("SELECT * from {groups} where courseid=$courseid->id");
foreach($cohortsssss as $cohort)
{
    // $total_enrol=0;
    // $userids= $DB->get_records_sql("SELECT userid from {group_members} where groupid=$cohort->id");
    // foreach($userids as $userid){
    // if(is_enrolled($contextid,$userid->userid)){
    //  $total_enrol = $total_enrol+1;
    // }
    // }
    // $count_cohort_members =  $DB->get_record_sql("SELECT count(userid) as userids,cohortid from {cohort_members} where cohortid=$cohort->id");
    $studentid = $DB->get_field('role', 'id', array('shortname' => 'student'));

    if(is_siteadmin($USER->id)||(user_has_role_assignment($USER->id, $studentid)))
    // if((int)($count_cohort_members->userids)==$total_enrol){
     $cohort_detail[]=['cohort'=>$cohort,'course'=>$courseid];
    // }

}
}
 echo $OUTPUT->header();
 echo'
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <title></title>
    <style type="text/css">
        body{
            padding: 20px;

            
        }
     .icon{
        width: 20px;
        height: 20px;

     }
     .clock{
        width: 90px;
        height: 50px;
     }
     .box1{ 
        box-shadow: 0 1px 2px rgb(0 0 0 / 0.2);
        padding: 15px;
        border-radius: 10px;
        background-color:#DAF5FF;

     }
        .box2{
        box-shadow: 0 1px 2px rgb(0 0 0 / 0.2);
        padding: 6px;
        border-radius: 6px;
        display: flex;
        align-content: center;
        background-color: #DAF5FF ;
        justify-content: center;
     }
     .div{
        display: flex;
        flex-direction: row;
        gap: 6px;
     


     }
        .div1{
        display: flex;
        flex-direction: row;
        align-content: center;
        justify-content: center;
        gap: 10px;
        
     


     }
     .date{
        font-size: 12px;
        color: grey;
     }
     .profie{
        width: 25px;
        height: 25px;
        border-radius: 100%;
     }
     .title{
        font-weight: bold;
        font-size:18px;
     }
     .section-one{
        margin-bottom: 20px;
     }
     .batch{
        font-weight: bold;
        color:  #505050;
     }
     .text{
        display: flex;
        flex-direction: column;

     }
     .count{
        font-size: 13px;
        font-weight: bold;
        margin-top: -10px;
        color: grey;

     }
     .bi-clock-fill{
        font-weight: bold;
        font-size: 30px;
/*        padding: 10px;*/
        color: #083D56;
     }
     .bi-journal-album{
        color: #EA907A;
         font-weight: bold;
        font-size: 30px;

     }
     .bi-file-earmark-pdf{
     color: #19A7CE;
         font-weight: bold;
         font-weight: bold;
        font-size: 30px;

     }
     .bi-currency-rupee{
          color: #146C94   ;
         font-weight: bold;
        
        font-size: 34px;
     }
     .bi-person-circle{
        color: #19A7CE;
     }
     .bi-calendar3{
        color: #205E61;
     }
     .bi-calendar-check{
        color: #1363DF ;
     }
       .form-select{
        width: 300px;
        border: 0.5px solid #C8C8C8;
        border-radius: 10px;
        font-size: 10px;
        padding: 8px;
        outline: none;
         box-shadow: 0 3px 10px rgb(0 0 0 / 0.2);
     }
  
    </style>
</head>
<body>
    <section class="section-one" >
                <div class="container">
  <div class="row">
       <div class="col-4">
         <h4>Running Batch</h4>
     </div>
       <div class="col-4">
         <select class="form-select" aria-label="Default select example">
  <option selected>Select Slot</option>
  <option value="1">One</option>
  <option value="2">Two</option>
  <option value="3">Three</option>
</select>
     </div>
       <div class="col-4">
            <select class="form-select" aria-label="Default select example">
  <option selected>Select Course</option>
  <option value="1">One</option>
  <option value="2">Two</option>
  <option value="3">Three</option>
</select>
     </div>
  </div>
</div>
    </section>';
    
    for($i=0; $i<count(($cohort_detail)); $i++)
    {

      $unique_array = (($cohort_detail));    
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
      echo'<section>
          <div class="container">
    <div class="row">
      <div class="col-4">
          <div class="box1">
              <p class="title">'.($unique_array[$i])['course']->shortname.''.date('/M/y/', $b).''.$unique_array[$i]['cohort']->name.'/'.($unique_array[$i])['course']->shortname.''.date('/h:i a', $b).'</p>
              <div class="div">
                  <i class="bi bi-calendar3"></i>
                <p class="date" >Start Date : '.date('d M Y', $b).''.$weekname.'</p>
              </div>
              <div class="div"><img class="icon" src="https://png.pngtree.com/png-vector/20230209/ourmid/pngtree-notebook-icon-png-image_6591697.png">
              <p class="date" >'.($unique_array[$i])['course']->fullname.'</p>
              </div>
              <div class="div"><i class="bi bi-calendar-check"></i>
              <p class="date" >Attendance 0</p>
              </div>
              <br>
                  <div class="div"><i class="bi bi-person-circle"></i>
              <p class="date" >'.$total_cohort_members->totalmmbers.' Students</p>
               <div>
               <a href="'.$CFG->wwwroot.'/local/runningbatch/student_batch.php?groupid='.$a.'" class="btn btn-secondary " role="button" aria-pressed="true">Batch Details </a>
               </div>
              </div>
              
          </div>
      </div>
    </div>
  </div>
  </section>';
   }
   echo' <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>
 ';

// echo $OUTPUT->footer();

