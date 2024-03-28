<?php 
require_once('../../config.php');
global $DB,$CFG,$OUTPUT,$USER;
require_login();
$PAGE->set_pagelayout('standard');

echo $OUTPUT->header();
$groupid = $_GET['groupid'];
$groupid_details = $DB->get_record('groups', ['id'=> $groupid]);
$group_members_count =  count($DB->get_records_sql("SELECT * FROM {groups_members} WHERE groupid=$groupid"));
$course =  $DB->get_record('course', ['id'=> $groupid_details->courseid]);
$course_stat_date = date('d M Y', $course->startdate);
$completion=0;
$context = context_course::instance($course->id);
$enrolled_users = get_enrolled_users($context, $options = array());
foreach ($enrolled_users as $user_one) {
   $completion = $completion + core_completion\progress::get_course_progress_percentage($course,  $user_one->id);
}
$module_attendanceid = $DB->get_field('modules', 'id', ['name'=>'attendance']);
$module_labelid = $DB->get_field('modules', 'id', ['name'=>'label']);
$course_labels = $DB->get_records_sql("SELECT id, instance FROM {course_modules} WHERE course=$course->id AND module=$module_labelid");
$zoom_meeting = $DB->get_record_sql("SELECT max(id), join_url, start_time FROM {zoom} WHERE course=$course->id");

$current_time = date('H:i:s d:M:Y', date());
$course_sections = $DB->get_record_sql("SELECT section FROM {course_sections} WHERE course=$course->id AND name='Recordings'");
$studentid = $DB->get_field('role', 'id', array('shortname' => 'student'));
$if_student = user_has_role_assignment($USER->id, $studentid);
// var_dump($if_student);die;
$course_attendanceid = $DB->get_record_sql("SELECT max(id) as id FROM {course_modules} WHERE course=$course->id AND module=$module_attendanceid");

$batch = $DB->get_record('batch', ['groupid' => $groupid]);

$meeting=$DB->get_record_sql("SELECT id, instance  from {course_modules} where course=$course->id and module=3 and deletioninprogress=0");
if($meeting){
   $url11="../../mod/bigbluebuttonbn/bbb_view.php?action=join&id=$meeting->id&bn=$meeting->instance";
}
$timeing=$DB->get_record_sql("SELECT openingtime from {bigbluebuttonbn} where id=$meeting->instance");
$start_time = date('H:i:s d:M:Y', $timeing->openingtime);
// var_dump($completion);die;
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
     
        gap: 20px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        
     }

     .div{
        display: flex;
        height: 87px;
        gap: 15px;
    border: 1px solid rgba(21, 189, 255, 0);
        
        border-radius: 10px;
        padding: 20px;
        box-shadow: rgba(0, 0, 0, 0.12) 0px 1px 3px, rgba(0, 0, 0, 0.24) 0px 1px 2px;
        transition: ease-in-out  0.4s;
     }
     .div:hover{
    border: 1px solid rgb(21, 189, 255);
    transition: ease-in-out  0.4s;
   

     

     }
        .div1{
        display: flex;
        flex-direction: column;
        align-content: center;
        justify-content: center;
        gap: 10px;
     }

     .date{
      
        color: grey;
     }
     .profie{
        width: 25px;
        height: 25px;
        border-radius: 100%;
     }
     .title{
        font-weight: bold;
        font-size: 16px;
     }
     .section-one{
        margin-bottom: 20px;
     }
     .batch{
        font-weight: bold;
        font-size: 20px;
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
     .table-form{
        margin-top:20px;
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
        border: 0.5px solid #BEBEBE;
        border-radius: 10px;
        font-size: 17px !important;
    padding: 6px;
        outline: none;
         box-shadow: 0 3px 10px rgb(0 0 0 / 0.2);
     }
     .box{
        box-shadow: 0 1px 2px rgb(0 0 0 / 0.2);
        padding: 15px;
        border-radius: 10px;
    /* From https://css.glass */
/* From https://css.glass */
background: rgba(255, 255, 255, 0.51);
border-radius: 16px;
box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
backdrop-filter: blur(10.7px);
-webkit-backdrop-filter: blur(10.7px);
border: 1px solid rgba(255, 255, 255, 0.3);

     }

     .bi-youtube{
       color: red;
     }
     .dep{
        color: black;
     }
     .line{
         border-bottom: 1px solid grey;
         padding:6px;
      
     }
     .cross{
        font-weight: bold;
        color: red;
     }
     .line-dep{
        font-size: 14px;
        color: grey;
     }
     .btns{
        padding:7px;
        border-radius:5px;
        border: none;
      background-image: url("'.$CFG->wwwroot.'//local/runningbatch/image/navbarbg.png");
      font-weight: bold;
      color: white;
     }
     .btns1{
      padding:7px;
      border-radius:5px;
      border: none;
      background-image: url("'.$CFG->wwwroot.'//local/runningbatch/image/navbarbg.png");
    color: white;
    font-weight: bold;
   }
   a:link { text-decoration: none; }


a:visited { text-decoration: none; }


a:hover { text-decoration: none; color: rgb(255, 255, 255); }


a:active { text-decoration: none; }

     .row{
        row-gap: 10px;
     }

   .back-btn {
      top: -18px;
      /* left: -595px; */
      right: 35px;
      font-size: 17px;
      border-radius: 10px;
      padding: 8px 19px;
      position: absolute;
      color: #fff;
      background-color: #007bff;
      text-align: center;
  }
   .navbar-bg{
        width: 107%;
        z-index: -0;
        position: absolute;
        top: -127px;
        left: -68px;
    
          }
          .box2 {
    box-shadow: 0 1px 2px rgb(0 0 0 / 0.2);
    padding: 6px;
    border-radius: 6px;
    display: flex;
    align-content: center;
    height: 244px;
    background: rgba(255, 255, 255, 0.51);
border-radius: 16px;
box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
backdrop-filter: blur(10.7px);
-webkit-backdrop-filter: blur(10.7px);
border: 1px solid rgba(255, 255, 255, 0.3);
    justify-content: center;
}
.bi{
   background-image: linear-gradient(to right top, #3874a9, #2d6394, #22527f, #16426b, #093257);
   color: white;
  display: block;
   padding: 10px;
   border-radius: 5px;
}
.container{
   z-index: 999;
   margin-top: 80px;
}
.btns:hover{
   color: white !important;
}
.main-title{
   font-weight: bold;
   color: #081735;
   text-decoration: underline;
}
.meeting{
   display: none;
}
    </style>
</head>
<body>

    <section>
        <div class="container">
         <img class="navbar-bg" src="'.$CFG->wwwroot.'//local/runningbatch/image/navbarbg.png" alt="">
             <p class="batch" >Batch <span class="main-title" >Details</span>  
             <a href="'.$CFG->wwwroot.'/local/runningbatch/batch_details.php" class="back-btn" style="margin-left: 600px;"> Back </a></p> 
  <div class="row">

    <div class="col-7">
        <div class="box">
         <p class="title">'.$groupid_details->name.'</p>
        <div class="box1">

          <div class="div">

                <i class="bi bi-calendar3"></i>
              <p class="date" >Batch Start Date <br> <span class="dep">'.date("d M Y h:i A", $batch->start_datetime).'</span> </p>
            </div>
             <div class="div">
             <i class="bi bi-list-ul"></i>
              <p class="date" >Student Count <br> <span class="dep">'.$group_members_count.' Student</span>  </p>
            </div>
              <div class="div">
               <i class="bi bi-calendar-check"></i>
               <p class="date" >Attendance<br>
               <a href="'.$CFG->wwwroot.'/mod/attendance/view.php?id='.$course_attendanceid->id.'" class="dep">View Attendance</a></p>
            </div>
              <div class="div">
              <i class="bi bi-journal-bookmark-fill"></i>
              <p class="date" >Course <br> <a href="../../course/view.php?id='.$course->id.'"><span class="dep">'.$course->fullname.'</span></a>  </p>
            </div>
                <div class="div">
               <i class="bi bi-arrow-repeat"></i>
              <p class="date" >Course Status <br>
                <span class="dep"> '.$completion.' % Complete</span>
              </p>
            </div>';
            if(!$if_student){
            echo'<div class="div">
               <i class="bi bi-wifi"></i>
              <p class="date" >Upload Video <br>
              <a class="dep" href="'.$CFG->wwwroot.'/course/modedit.php?add=label&type=&course='.$course->id.'&section='.$course_sections->section.'&return=0&sr=0">Upload Video</a>
              </p>
            </div>';
       } echo'<div class="div">
               <i class="bi bi-wifi"></i>
               <p class="date" >Videos <br>
               <a class="dep" href="'.$CFG->wwwroot.'/local/runningbatch/video_list.php?courseid='.$course->id.'">View Videos</a>
              </p>
            </div>
                <div class="div">
               <i class="bi bi-star"></i>
              <p class="date" >Rating <br> 
                <span class="dep"> 0 out of 1 based</span>
              </p>
            </div>
        </div>
      
    </div>
    </div>
       <div class="col-5">
         <div class="row ">
             <div class="col-md-12">
        <div class="box2">
            <div class="div1">
            <p class="line" >Take live class</p>
            <p class="line-dep" >Join our live class for an interactive learning experience. Do not miss out on valuable content and real-time engagement. Join now! </p>
            <span class="meeting" style="color:red; font-size: 14px;">Your meeting will be start on '.$start_time.'</span>'; if($start_time == $current_time){ echo'<a href="'.$url11.'" class="btns btn" target="_blank" >'; if($if_student){echo'Start  Class';} else{echo'Go Live Now';} echo'</a>';} else { echo'
            <a href="" class="btns1 btns btn"  >'; if($if_student){echo'Start  Class';} else{echo'Go Live Now';} echo'</a>';} echo'
            </div>
             
        </div>
             </div>
             <div class="col-md-12 ">
                <div class="box2">
           <div class="div1">
            <p class="line" >Course Curriculum</p>
            <p class="line-dep" >The course curriculum provides a structured outline of the topics, lessons, and activities covered in a course. It serves as a roadmap for students, guiding them through the learning journey and ensuring they gain the necessary knowledge and skills. </p>
            <a href="'.$CFG->wwwroot.'/local/runningbatch/course_curriculum.php?groupid='.$groupid.'&courseid='.$course->id.'" class="btns btn">'; if($if_student){echo'Show Curriculm';} else{echo'Mark Curriculm';} echo'</a>
            </div>
            
            
        </div>
             </div>
         </div>

        </div>
      
    </div>
  
  </div>
</div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>
 ';

echo $OUTPUT->footer();

