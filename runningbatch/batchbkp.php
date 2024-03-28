<?php 
require_once('../../config.php');
require_login();
global $DB,$CFG,$OUTPUT;
echo $OUTPUT->header();

#Code by Raju
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

$course_attendanceid = $DB->get_record_sql("SELECT max(id) as id FROM {course_modules} WHERE course=$course->id AND module=$module_attendanceid");

$course_labels = $DB->get_records_sql("SELECT id, instance FROM {course_modules} WHERE course=$course->id AND module=$module_labelid");

$zoom_meeting = $DB->get_record_sql("SELECT max(id), join_url FROM {zoom} WHERE course=$course->id");

$course_sections = $DB->get_record_sql("SELECT section FROM {course_sections} WHERE course=$course->id AND name='Recordings'");

foreach ($course_labels as $label) {
   $zoom_meeting = $DB->get_record_sql("SELECT intro FROM {label} WHERE id= $label->instance AND course=$course->id");
   // echo "<div>$zoom_meeting->intro</div>";
}
// var_dump($zoom_meeting->join_url);
// die("hii");

#End Code

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
        gap: 15px;
     


     }
        .div1{
        display: flex;
        flex-direction: column;
        align-content: center;
        justify-content: center;
        gap: 10px;
     }
     .date{
        font-size: 13px;
        color: grey;
     }
     .profie{
        width: 25px;
        height: 25px;
        border-radius: 100%;
     }
     .title{
        font-weight: bold;
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
        font-size: 10px;
        padding: 8px;
        outline: none;
         box-shadow: 0 3px 10px rgb(0 0 0 / 0.2);
     }
     .box{
        box-shadow: 0 1px 2px rgb(0 0 0 / 0.2);
        padding: 15px;
        border-radius: 10px;
        background-color:#DAF5FF;

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
        font-size: 10px;
        color: grey;
     }
     .btns{
        padding:7px;
        border-radius:5px;
        border: none;
      background: linear-gradient(90deg, rgba(255,65,65,1) 0%, rgba(255,84,84,1) 100%, rgba(110,9,121,0.13209033613445376) 100%, rgba(221,221,221,1) 100%);
      color: white;
      font-weight: bold;
     }
     .row{
        row-gap: 10px;
     }
  
    </style>
</head>
<body>

    <section>
        <div class="container">
             <p class="batch" >Batch Details</p> 
  <div class="row">

    <div class="col-7">
        <div class="box">
         <p class="title">WD/MAY/23/LMS/WD/6:00am</p>
        <div class="box1">

          <div class="div">
                <i class="bi bi-calendar3"></i>
              <p class="date" >Batch Start Date <br> <span class="dep">'.$course_stat_date.'(weekdey)...</span> </p>
            </div>
             <div class="div">
             <i class="bi bi-list-ul"></i>
              <p class="date" >Student Count <br> <span class="dep">'.$group_members_count.' Student</span>  </p>
            </div>
              <div class="div">
               <i class="bi bi-calendar-check"></i>
              <p class="date" ><a href="'.$CFG->wwwroot.'/mod/attendance/view.php?id='.$course_attendanceid->id.'">Attendance</a></p>
            </div>
              <div class="div">
              <i class="bi bi-journal-bookmark-fill"></i>
              <p class="date" >Course <br> <span class="dep">'.$course->fullname.'</span>  </p>
            </div>
                <div class="div">
               <i class="bi bi-arrow-repeat"></i>
              <p class="date" >Course Status <br>
                <span class="dep"> '.$completion.' % Complete</span>
              </p>
            </div>
                <div class="div">
               <i class="bi bi-wifi"></i>
              <p class="date" ><a href="'.$CFG->wwwroot.'/course/modedit.php?add=label&type=&course='.$course->id.'&section='.$course_sections->section.'&return=0&sr=0">Upload Video</a> <br>
               <i class="bi bi-youtube"></i>
              </p>
            </div>
                
                <div class="div">
               <i class="bi bi-star"></i>
              <p class="date" >Rating <br> 
                <span class="dep"> 0 out of 1 based</span>
              </p>
            </div>
            <div class="div">
               <i class="bi bi-wifi"></i>
              <p class="date" ><a href="'.$CFG->wwwroot.'/local/runningbatch/video_list.php?courseid='.$course->id.'">View Videos</a> <br>
               <i class="bi bi-youtube"></i>
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
            <p class="line-dep" >Now you can go live for your students & batch them any time anywhere! </p>
            <a href="'.$zoom_meeting->join_url.'" class="btns btn" >Go Live Now</a>
            </div>
            
            
        </div>
             </div>
             <div class="col-md-12 ">
                <div class="box2">
           <div class="div1">
            <p class="line" >Course Curriculum</p>
            <p class="line-dep" >Make the course curriculum and start teaching! </p>
            <a href="'.$CFG->wwwroot.'/local/runningbatch/course_curriculum.php?groupid='.$groupid.'" class="btns btn text-white" >Mark Curriculm</a>
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

// echo $OUTPUT->footer();


