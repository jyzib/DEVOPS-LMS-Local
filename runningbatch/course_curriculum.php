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
$module_labelid = $DB->get_field('modules', 'id', ['name'=>'label']);
$course_labels = $DB->get_records_sql("SELECT id, instance FROM {course_modules} WHERE course=$course->id AND module=$module_labelid");

$zoom_meeting = $DB->get_record_sql("SELECT max(id), join_url, start_time FROM {zoom} WHERE course=$course->id");
// $start_time = date('H:i:s d:M:Y', $zoom_meeting->start_time);
$current_time = date('H:i:s d:M:Y', date());
$course_sections = $DB->get_record_sql("SELECT section FROM {course_sections} WHERE course=$course->id AND name='Recordings'");
$if_student = $DB->get_record_sql("SELECT * from {role_assignments} as ra join {role} as r on ra.roleid=r.id where r.shortname='student' and ra.userid=$USER->id");
$checkbox = $DB->get_record_sql("SELECT * FROM {course_curriculum} WHERE cid = '$course->id' ");
$meeting=$DB->get_record_sql("SELECT id, instance  from {course_modules} where course=$course->id and module=3 and deletioninprogress=0");
if($meeting){
   $url11="../../mod/bigbluebuttonbn/bbb_view.php?action=join&id=$meeting->id&bn=$meeting->instance";
}
$timeing=$DB->get_record_sql("SELECT openingtime from {bigbluebuttonbn} where id=$meeting->instance");
$start_time = date('H:i:s d:M:Y', $timeing->openingtime);
$course_sections = $DB->get_records_sql("SELECT * FROM {course_sections} WHERE course=$course->id");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$course_exits = $DB->get_records_sql("SELECT data FROM {course_curriculum} WHERE cid = $course->id");
// Serialize the $_POST array
$postData = serialize($_POST);
// Prepare the SQL query
if ($course_exits) {
    $sql = "UPDATE {course_curriculum} SET data = ? WHERE cid = ?";
} else {
    $sql = "INSERT INTO {course_curriculum} (data , cid) VALUES (?, ?)";
}
// Execute the query with parameters
$result = $DB->execute($sql, array($postData, $course->id));
   if ($result) {
      $message = 'Successfully Submit...';
      $redirectUrl = "course_curriculum.php?groupid=$groupid&courseid=$course->id";
      redirect($redirectUrl, $message, null, NOTIFY_SUCCESS);
   } else {
      $message = 'Something went wrong!';
      $redirectUrl = "course_curriculum.php?groupid=$groupid&courseid=$course->id";
      redirect($redirectUrl, $message, null, NOTIFY_ERROR);
   }
}
$sql1 = "SELECT data FROM {course_curriculum} WHERE cid = :cid";
$params = array('cid' => $course->id);
$data = $DB->get_record_sql($sql1, $params);

if ($data !== false) {
    $serializedData = $data->data; // Replace 'fieldname' with the actual field name in your database
    $postData = unserialize($serializedData);
}
 echo'
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
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
        border-radius: 6px;
        background: rgba(255, 255, 255, 0.62);
border-radius: 8px;
box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
backdrop-filter: blur(5.9px);
-webkit-backdrop-filter: blur(5.9px);
border: 1px solid rgba(255, 255, 255, 0.58);
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
        border-radius: 10px;
        padding: 15px;
        border: 1px solid #dbdbdb;

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
     .btnss{
      /* padding:2px; */
      padding: 4px 10px;
        border-radius:5px;
        border: none;
      
      color: white;
      /* font-weight: bold; */
      font-size: 14px;

     }
     .btnsss{
         padding-right: 122px;
         padding-left: 122px;
         padding-top: 8px;
         padding-bottom: 8px;
        border-radius:5px;
        border: none;
        background: linear-gradient(40deg, #053f74, #0e2437);
      color: white;
     
      font-weight: bold;
     }
     .btnsss1{
      width: 100%;

      display: flex;
      justify-content: center;
      align-items: center;
      padding-top: 8px;
      padding-bottom: 8px;
     border-radius:5px;
     border: none;
     background-image: url("'.$CFG->wwwroot.'//local/runningbatch/image/navbarbg.png");
   color: white;
  
   font-weight: bold;
  }
  .btnsss1:hover{

   color: white !important;
  }
     .btns{
        padding:7px;
        border-radius:5px;
        border: none;
        background-image: url("'.$CFG->wwwroot.'//local/runningbatch/image/navbarbg.png");
      color: white;
      font-weight: bold;
     }
     .row{
        row-gap: 10px;
     }
  /*punit internel css*/
  #page-header{
      display:none;
  }

  .course-curriculum {
   padding: 0 24px;
   font-size: 20px;
   color: white;
   font-weight: 600;
   letter-spacing: 0.5px;
   margin-bottom: 22px;
   display: flex;
   justify-content: space-between;
   
   height: 50px;
   align-items: center;
   margin: 10px;
   /* padding: 15px; */
   /* border-left: 4px solid #093358; */
}
a:hover { text-decoration: none; color: rgb(255, 255, 255); }
h4.submit-curriculum {
   font-size: 17px;
   font-weight: 600;
   padding: 15px 15px;
 
   color: #000;
   border-bottom: 1px solid #0b2b47;
}

.bi-arrow-right{
   font-size: 13px;


}
.padding-15{
   padding: 12px 15px;
}
.accordian-heading h5 {
   font-size: 17px;
   letter-spacing: 0.5px;
   color:#000;
}
.accordian-heading {
   background: #b4d8ff;
   border: none;
   padding: 10px;
}
.back{
   color: #053f74;
}

element.style {
}
a:visited {
    text-decoration: none;
}
a.back-btn {
    width: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.back {
   font-size: 17px;
   width: 80px;
   /* border: 0.5px solid; */
   border-radius: 10px;
   padding: 7px;
   display: flex;
   justify-content: center;
   background-color: #007bff;
   text-align: center;
}
.accordian-content{
   border: 1px solid #d9d9d9;
   padding: 10px;
}
.accordian-data{
   border: 1px solid #dbdbdb;
}
input[type="checkbox"]:focus{
   box-shadow:none;
}
.icon{
   float:right;
}
.body-margin{
   margin-top: 60px;
}
form {
    margin-top: 23px;
    z-index: 999;
    position: relative;
}
.meeting{
   display: none;
}
.navbar-bg{
        width: 107%;
        z-index: -0;
        position: absolute;
        top: -76px;
        left: -68px;
    
          }
          .cource-tittle{
          font-weight: bold;
          }
  /*jazib internel css end*/
    </style>
</head>
<body>

<section>
   <img class="navbar-bg" src="'.$CFG->wwwroot.'//local/runningbatch/image/navbarbg.png" alt="">
    <form method="post" action="">
        <div class="container-fluid mx-0 px-0">
         <div class="course-curriculum">
            <h2 class="">Course <span class="cource-tittle" >Curriculum </span></h2>
              
               <a href="'.$CFG->wwwroot.'/local/runningbatch/batch.php?groupid='.$groupid.'" class="back"><span class="btnss" > Back</span></a>
         </div>
           
   <div class="row body-margin">
      <div class="col-md-8">
        <div class="box">
         <p class="title">Content</p> ';
         // $course_sections = $DB->get_records_sql("SELECT name from {course_sections} where course=$course->id");
         // foreach($course_sections as $course_section){
         //    if($course_section->name != null){
         //       echo'<div class="accordian-data">
         //             <div>
         //                <div class="accordian">
         //                   <div class="accordian-heading">
         //                      <h5 class="mb-0"><div class="checkbox d-inline mr-2"><input type="checkbox" name="realnumber" value="Real numbers" ';if($checkbox->data){ echo'Checked';} else{ echo'unchecked';} if($if_student){ 
         //                         echo' style="pointer-events: none;"';} 
         //                         echo'></div> '.$course_section->name.'<br>
         //                      </h5>
         //                   </div>
         //                </div>
         //          </div>
         //       </div>';
         //    }
         // }
         foreach ($course_sections as $course_section) {
            if ($course_section->name != null) {
               echo '<div class="accordian-data">
                        <div>
                           <div class="accordian">
                              <div class="accordian-heading">
                                 <h5 class="mb-0">
                                    <div class="checkbox d-inline mr-2">
                                       <input type="checkbox" name="'.$course_section->id.'" value="'.$course_section->name.'" ';
                                       if (array_key_exists($course_section->id, $postData)) {
                                          echo 'checked';
                                       } else {
                                          echo 'unchecked';
                                       }
                                       if ($if_student) {
                                          echo ' style="pointer-events: none;"';
                                       }
                                       echo '>
                                    </div>
                                    '.$course_section->name.'<br>
                                 </h5>
                              </div>
                           </div>
                        </div>
                     </div>';
            }
         }
      echo'</div>
   </div>
       <div class="col-md-4">
         <div class="row ">';
         if(!$if_student){
            echo '<div class="col-md-12">
               <div class="box2">
                  <h4 class="submit-curriculum mb-0">Submit Curriculum</h4>
                     <div class="padding-15">
                        <p class="line-dep" >The course curriculum provides a structured outline of the topics, lessons, and activities covered in a course. It serves as a roadmap for students, guiding them through the learning journey and ensuring they gain the necessary knowledge and skills. </p>
                        <button type="submit" class="btns w-100">Submit</button>
                     </div>
               </div>
            </div>';
         }
         echo'<div class="col-md-12">
               <div class="box2">
                  <h4 class="submit-curriculum mb-0">Take live class</h4>
                     <div class="padding-15">
                        <p class="line-dep" >Join our live class for an interactive learning experience. Do not miss out on valuable content and real-time engagement. Join now! </p>
                           <span class="meeting" style="color:red; font-size: 14px;">Your meeting will be start on '.$start_time.'</span><br><br>'; if($start_time == $current_time){ echo'<a href="'.$url11.'" class="" target="_blank"><span class="btnsss "> ';if($if_student){ echo'Start Class';} else{ echo'Go Live Now';} echo'</span> </a>';} else { echo'
                              <a href="'.$url11.'"><span class="btnsss1 "> ';if($if_student){ echo'Start Class';} else{ echo'Go Live Now';} echo'</span> </a> ';} echo'
                     </div>
               </div>
            </div>
         </div>

        </div>
      
    </div>

  </div>
</div>
</form>
    </section>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>
// $(document).ready(function(){
//    $(".checkbox").click(function (evt) {
//       evt.stopPropagation();
//   });
//    $(".accordian-content").hide();
//    $(".accordian-heading").click(function(){
//         $(this).parents(".accordian").find(".accordian-content").slideToggle();
//    });
   
// });
</script>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>
 ';

echo $OUTPUT->footer();

