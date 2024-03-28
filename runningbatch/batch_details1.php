<?php
require_once('../../config.php');
require_once('../../lib/enrollib.php');
require_once('../../cohort/lib.php');


global $DB, $CFG, $OUTPUT;
$course_enrols =  enrol_get_all_users_courses($USER->id);
$studentid = $DB->get_field('role', 'id', array('shortname' => 'student'));
$if_student = user_has_role_assignment($USER->id, $studentid);
// $courseids=$DB->get_records_sql("SELECT *  from {course} where id!=1 ");
$cohort_detail = array();
foreach ($course_enrols as $courseid) {
   // $contextid = context_course::instance($courseid->id);
   $cohortsssss = $DB->get_records_sql("SELECT * from {groups} where courseid=$courseid->id");
   foreach ($cohortsssss as $cohort) {
      // $total_enrol=0;
      // $userids= $DB->get_records_sql("SELECT userid from {group_members} where groupid=$cohort->id");
      // foreach($userids as $userid){
      // if(is_enrolled($contextid,$userid->userid)){
      //  $total_enrol = $total_enrol+1;
      // }
      // }
      // $count_cohort_members =  $DB->get_record_sql("SELECT count(userid) as userids,cohortid from {cohort_members} where cohortid=$cohort->id");
      $teacherid = $DB->get_field('role', 'id', array('shortname' => 'editingteacher'));
      $studentid = $DB->get_field('role', 'id', array('shortname' => 'student'));

      if (is_siteadmin($USER->id) || (user_has_role_assignment($USER->id, $teacherid)) || (user_has_role_assignment($USER->id, $studentid))) {
         // if((int)($count_cohort_members->userids)==$total_enrol){
         $cohort_detail[] = ['cohort' => $cohort, 'course' => $courseid];
      }
      // }

   }
}
echo $OUTPUT->header();
echo '
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="stylesheet" href="https://yislms.com/croma/moodle/local/runningbatch/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <title></title>
    <style>
      @import url("https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300&display=swap");
    </style>
    
    <style type="text/css">
           .bi-journal-album{
        color: #EA907A;
         font-weight: bold;
        font-size: 26px;

     }
     .bi-file-earmark-pdf{
     color: #19A7CE;
         font-weight: bold;
         font-weight: bold;
        font-size: 26px;

     }
     .bi-currency-rupee{
          color: #146C94   ;
         font-weight: bold;
        
        font-size: 26px;
     }
     .bi-person-circle{
        color: #19A7CE;
     }
     .bi-calendar3{
        color: #205E61;
        background-color: white;
        border-radius: 10px;
        padding: 8px 10px ;
     }
     .bi-calendar-check{
        color: #1363DF ;
     }
     .card-hover{
        list-style: none;
        
     }
     .bx-flip-horizontal{
      font-size: 35px;
      color: gray;
     }
         .navbar-bg{
        width: 107%;
        z-index: -0;
        position: absolute;
        top: -76px;
        left: -68px;
    
          }
        body{
            padding: 20px;

            
        }
        .box1:hover{
        transform: scale(1.04);
     }
     .icon{
        width: 20px;
        height: 20px;

     }
     .button {
  -webkit-user-select: none;
  -moz-user-select: none;
  user-select: none;
  border: none;
  outline: none;
  color: rgb(255 255 255);
  text-transform: uppercase;
  font-weight: 700;
  font-size: .75rem;
  padding: 0.75rem 1.5rem;
  background-color: rgb(33 150 243);
  border-radius: 0.5rem;
  width: 100%;
  display: block;
  text-shadow: 0px 4px 18px #2c3442;
}
     
      .back-btn{
         width: 80px;
         border: 0.5px solid ;
         border-radius: 10px;
         padding: 8px;
         color: #fff;
         background-color: #007bff;
         text-align: center;
      }
     .clock{
        width: 90px;
        height: 50px;
     }
     .div1{
        display: flex;
        flex-direction: column-reverse;
        align-content: center;
        justify-content: center;
        gap: 10px;
        width: 80%;
     }
     /* .box1{ 
        box-shadow: 0 1px 2px rgb(0 0 0 / 0.2);
        padding: 15px;
        border-radius: 10px;
        transition: transform .2s; 
        background-color:#DAF5FF;

     } */
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
        flex-direction: column;
    
     


     }

     .date{
        font-size: 15px;
        color: rgb(255, 255, 255);
        margin: 0;
     }
     .profie{
        width: 25px;
        height: 25px;
        border-radius: 100%;
     }
     .title{
        font-weight: normal;
        font-family: "Roboto Condensed", sans-serif;
        font-size:20px;
        color: white;
        margin-bottom: 63px;
     }
     .section-one{
        margin-bottom: 20px;
     }
     .batch{
        font-weight: bold;
        color:  #505050;
     }
     a.back-btn {
    width: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
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
     .box2{
         box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
        padding: 15px 0px;
      
        transition: 0.2s;
        height: 140px;
        display: flex;
        align-content: center;

background: rgba(255, 255, 255, 0.62);
border-radius: 8px;
box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
backdrop-filter: blur(5.9px);
-webkit-backdrop-filter: blur(5.9px);
border: 1px solid rgba(255, 255, 255, 0.58);
        justify-content: center;
     }
     .box3{
       box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
        padding: 15px 0px;
      
        transition: 0.2s;
        height: 140px;
        display: flex;
        align-content: center;

        border-bottom: 2px solid rgb(46, 126, 255) !important;
background: rgba(255, 255, 255, 0.86);
border-radius: 16px;
box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
backdrop-filter: blur(7.9px);
-webkit-backdrop-filter: blur(7.9px);
border: 1px solid rgba(255, 255, 255, 0.3);
        justify-content: center;

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
     select {
  text-indent: 10px;
}
.text-h{
   font-weight: bold;
   color: #081735;
}
 .icon-background {
      display: flex;
      align-items: center;
      justify-content: center;
    background-color: white;
    width: 58px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.037);
    border-radius: 20px;
    padding: 11px;
}
     
       .form-select{
        width: 300px;
        border: 0.5px solid #C8C8C8;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.62);
/* From https://css.glass */
background: rgba(255, 255, 255, 0.63);
border-radius: 16px;
box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
backdrop-filter: blur(5px);
-webkit-backdrop-filter: blur(5px);
border: 1px solid rgba(255, 255, 255, 0.3);
        font-size: 10px;
        padding: 18px;
        outline: none;
         box-shadow: 0 3px 10px rgb(0 0 0 / 0.2);
     }
  
     .btn-primary{
      margin-left: 30px;
     }
     .bi-person-circle::before {
    content: "\f4d7";
    font-size: 41px;
    color: white;
    margin: 10px;
}
     .box1{
      transition: transform .2s; 
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: space-around;
  width: 355px;
  height: 389px;
  padding: 20px 1px;
  margin: 10px 0;
  text-align: center;
  position: relative;
  cursor: pointer;
  box-shadow: 0 10px 15px -3px rgba(33,150,243,.4),0 4px 6px -4px rgba(33,150,243,.4);
  border-radius: 10px;
  background-color: #6B6ECC;
  background: linear-gradient(45deg, #04051dea 0%, #2b566e 100%);
}
.text-bold{
   font-weight: bold;
   color: rgb(0, 0, 0);
}  
.content {
  padding: 20px;
}
.div5{
   display: flex;
   align-items: center;
   margin: 10px 0;
   gap: 10px;
   color: white;
}
a{
   color: white !important;
}
.main-title{
   font-weight: bold;
   color: #081735;
   text-decoration: underline;
}
.form-select{
        width: 300px;
        border: 0.5px solid #e5e5e5;
        border-radius: 10px;
        font-size: 17px !important;
    padding: 6px;
        outline: none;
         box-shadow: 0 3px 10px rgba(0, 0, 0, 0.063);
     }
     .back-icon{
      font-size: 20px;
      font-weight: bold;
      padding: 0 10px;
     }
a:link { text-decoration: none; }


a:visited { text-decoration: none; }


a:hover { text-decoration: none; }


a:active { text-decoration: none; }
  /*jazib internel css*/
  .bi-journal-bookmark-fill{
   color: #00488c;
        background-color: white;
        border-radius: 10px;
        padding: 8px 10px ;
  }
    </style>
</head>
<body>
    <section class="section-one" >
      <section class="section-one" >
         <img class="navbar-bg" src="' . $CFG->wwwroot . '/local/runningbatch/image/navbarbg.png" alt="">
                      <div class="container">
        <div class="row">
          <div class="col-3">
              <a style="text-decoration:none" href="' . $CFG->wwwroot . '/local/runningbatch/batch_details.php">
              <div class=" box3">
                  <div class="div1 ">
                      <div class="text">
                     <p class="batch mb-0" >Running <span class="text-bold" > Batch</span></p> 
                  </div>
               <div class="icon-background">
                  <i class="bx bx-time-five bx-flip-horizontal" ></i>
               </div>
                  </div>
              </div>
          </a>
          
            
          </div>
              <div class="col-3">
              <div class="box2">
                  <div class="div1 ">
                      <div class="text">
                        <p class="batch mb-0">Finished <span class="text-bold" >Batch</span> </p>
                     </div>
                     <div class="icon-background">
                     <i class="bi bi-journal-album"></i>
                     </div>
                  </div>
                  
                  
              </div>
            
          </div>
              <div class="col-3"><a style="text-decoration:none" href="' . $CFG->wwwroot . '/local/runningbatch/view_invoice_paid.php">
              <div class="box2">
                  <div class="div1 ">
                      <div class="text">
                  <p class="batch mb-0" >Invoice <span class="text-bold">Paid</span></p>
                  </div>
                  <div class="icon-background">
              <i class="bi bi-file-earmark-pdf"></i>
                  </div>
                  </div>
              
              </div></a>
            
          </div>
              <div class="col-3"><a style="text-decoration:none" href="' . $CFG->wwwroot . '/local/runningbatch/view_payment_history.php">
              <div class="box2">
                  <div class="div1 ">
                      <div class="text">
                  <p class="batch mb-0" >Payment <span class="text-bold" >History</span></p>
                  </div>
                  <div class="icon-background">
                  <i class="bi bi-currency-rupee"></i>
                  </div>
                  </div>
              </div></a>
          </div>
        </div>
      </div>
          </section>
      
                <div class="container">
  <div class="row">
       <div class="col-4">
         <h4 class="text-bg">Running <span class="main-title" > Batch</span></h4>
     </div>';
//      if((is_siteadmin($USER->id)||(user_has_role_assignment($USER->id, $teacherid))||!(user_has_role_assignment($USER->id, $studentid)))){

//      // if((int)($count_cohort_members->userids)==$total_enrol){
//        echo '<div class="col-4">
//          <select id="groups_change" class="form-select" aria-label="Default select example">
//          <option selected>Select groups</option>
//          ';
//          for($a=0; $a<count(($cohort_detail)); $a++)
//          {
//   echo'
//   <option value="'.$cohort_detail[$a]['cohort']->id.'">'.$cohort_detail[$a]['cohort']->name.'</option>';
//          }
// echo'</select>
//      </div>';

//    }
if (is_siteadmin($USER->id) || (user_has_role_assignment($USER->id, $teacherid)) || (user_has_role_assignment($USER->id, $studentid))) {

   echo ' <div class="col-4">
            <select id="course_change" class="form-select" aria-label="Default select example">
  <option selected>Select Course</option>';
   for ($j = 0; $j <= (count(($course_enrols))) + 1; $j++) {
      if ($course_enrols[$j]) {
         echo '<option value="' . $course_enrols[$j]->id . '">' . $course_enrols[$j]->fullname . '</option>';
      }
   }
   echo '</select>
     </div>';
   if (!$if_student) {
      echo '<a href="' . $CFG->wwwroot . '/local/runningbatch/dashboard.php" class="back-btn" ><span class="back-icon" >&#8592</span>  Back</a>';
   }
   echo '</div>
</div>
    </section><div id="batch_section">
      <section>
         <div class="container">
   <div class="row">';
}

// for ($i = 0; $i < count(($cohort_detail)); $i++) {

//    $unique_array = (($cohort_detail));


//    //attendance
//    $var = $unique_array[$i]['cohort']->courseid;
//    $total_attendance = 0;
//    $attendance = $DB->get_records_sql("SELECT max(a.id),l.* FROM {attendance} as a join {attendance_statuses} as s on a.id=s.attendanceid join {attendance_log} as l on s.id=l.statusid where s.acronym='P' and a.course=$var and l.studentid=$USER->id");
//    if ($attendance) {
//       $total_attendance = COUNT($attendance);
//    }



//    if (($unique_array[$i])['cohort']) {
//       //     $name_of_cohort = $DB->get_record_sql("SELECT * from {cohort} where id=($unique_array[$i])->id");
//       $a = (int)(($unique_array[$i])['cohort']->id);
//       $total_cohort_members = $DB->get_record_sql("SELECT count(userid) as totalmmbers from {groups_members} where groupid=$a");
//    }
//    if (($unique_array[$i])['course']) {
//       $b = (($unique_array[$i])['course']->startdate);
//       $dayOfWeek = date('w', strtotime($b));
//       if ($dayOfWeek == 0 || $dayOfWeek == 6) {
//          $weekname = '(Weekend)...';
//       } else {
//          $weekname = '(Weekday)...';
//       }
//    }
//    echo '
     
//       <div class="col-4">
//       <a class="remove-anchor" href="' . $CFG->wwwroot . '/local/runningbatch/batch.php?groupid=' . $a . '" >
//          <div class="box1">
//          <div class="content">
//               <p class="title"> ' . ($unique_array[$i])['course']->shortname . '' . date('/M/y/', $b) . '' . $unique_array[$i]['cohort']->name . '/' . ($unique_array[$i])['course']->shortname . '' . date('/h:i
//  a', $b) . '</p>
//               <div class="div5">
//                   <i class="bi bi-calendar3"></i>
//                 <p class="date" >Start Date : ' . date('d M Y', $b) . '' . $weekname . '</p>
//               </div>
//               <div class="div5"><i class="bi bi-journal-bookmark-fill"></i>
//               <p class="date" >' . ($unique_array[$i])['course']->fullname . '</p>
//               </div>';
//    if ($if_student) {
//       echo "<div class='div'><i class='bi bi-calendar-check'></i>
//                   <p class='' >Attendance $total_attendance</p>
//                   </div>";
//    }
//    echo '<br>
//                   <div class="div">';
//    if (!$if_student) {
//       echo '<i class="bi bi-person-circle"></i>
//               <p class="" >' . $total_cohort_members->totalmmbers . ' Students</p>';
//    }

//    echo  '<div class="batch-btn" >
//                <a href="' . $CFG->wwwroot . '/local/runningbatch/batch.php?groupid=' . $a . '" class="button" role="button" aria-pressed="true">Batch Details </a>
//                </div>
//               </div>
              
//           </div>
//          </div></a>
//       </div>
//  ';
// }

// Batches
// $batches = $DB->get_records('batch');
$batches = $DB->get_records_sql("SELECT mb.* FROM {batch} mb JOIN {groups_members} mgm ON mb.groupid = mgm.groupid WHERE mgm.userid=$USER->id");

foreach ($batches as $batch) {
   $course = $DB->get_record('course', ['id' => $batch->courseid]);
   $group = $DB->get_record('groups', ['id' => $batch->groupid]);
   $total_members = count($DB->get_records('groups_members', ['groupid' => $batch->groupid]));
   $total_attendance = count($DB->get_records_sql("SELECT mal.id FROM {attendance} ma JOIN {attendance_statuses} mas ON ma.id=mas.attendanceid JOIN {attendance_log} mal ON mas.id=mal.statusid WHERE ma.course=$batch->courseid AND mas.acronym='P' AND mal.studentid=$USER->id"));

   echo '
     
      <div class="col-4">
         <a class="remove-anchor" href="' . $CFG->wwwroot . '/local/runningbatch/batch.php?groupid=' . $batch->groupid . '" >
            <div class="box1">
               <div class="content">
                  <p class="title"> ' . $group->name . '</p>
                  <div class="div5">
                     <i class="bi bi-calendar3"></i>
                     <p class="date" >Start Date : ' . date('d M Y h:i A', $batch->start_datetime) . '</p>
                  </div>
               <div class="div5"><i class="bi bi-journal-bookmark-fill"></i>
                  <p class="date" >' . $course->fullname . '</p>
               </div>';
   if ($if_student) {
      echo "<div class='div'><i class='bi bi-calendar-check'></i>
                              <p class='' >Attendance $total_attendance</p>
                              </div>";
   }
   echo        '<br>
               <div class="div">';
   if (!$if_student) {
      echo '<i class="bi bi-person-circle"></i>
                  <p class="" >' . $total_members . ' Students</p>';
   }
   echo '<div class="batch-btn" >
                     <a href="' . $CFG->wwwroot . '/local/runningbatch/batch.php?groupid=' . $group->id . '" class="button" role="button" aria-pressed="true">Batch Details </a>
                  </div>
               </div> 
            </div>
         </div>
      </a>
     </div>
';
}


echo ' </div><script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="https://yislms.com/croma/moodle/local/runningbatch/js/boxicons.min.js"></script>
<script>
   
$(document).ready(function() {
                $("#groups_change").on("change", function() {
                   var groupid = this.value;
                   $.ajax({
                      type: "POST",
                      async: false,
                      url: "daysfilter.php",
                      dataType: "json",
                      data: {
                         groupid: groupid,
                      },
                      success: function(json) {
                         if (json.success) {
                           $("#batch_section").html(json.table_data);
                         }
                      }
                   });
                });
                $("#course_change").on("change", function() {
                   var courseid = this.value;
                   $.ajax({
                      type: "POST",
                      async: false,
                      url: "daysfilter.php",
                      dataType: "json",
                      data: {
                         courseid: courseid,
                      },
                      success: function(json) {
                         if (json.success) {
                           $("#batch_section").html(json.table_data);
                         }
                      }
                   });
                });

          
  });
</script>
</div>
</div>
</section>
</body>
</html>
 ';

// echo $OUTPUT->footer();