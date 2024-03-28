<?php 
require_once('../../config.php');
require_once('../../lib/enrollib.php');
require_once('../../cohort/lib.php');

require_login();

$PAGE->set_pagelayout('standard');

global $DB,$CFG,$OUTPUT;
$course_enrols =  enrol_get_all_users_courses($USER->id);
$cohort_detail=array();
foreach($course_enrols as $courseid)
{
// $contextid = context_course::instance($courseid->id);
$cohortsssss = $DB->get_records_sql("SELECT * from {groups} where courseid=$courseid->id");
foreach($cohortsssss as $cohort){
    // $total_enrol=0;
    // $userids= $DB->get_records_sql("SELECT userid from {group_members} where groupid=$cohort->id");
    // foreach($userids as $userid){
    // if(is_enrolled($contextid,$userid->userid)){
    //  $total_enrol = $total_enrol+1;
    // }
    // }
    // $count_cohort_members =  $DB->get_record_sql("SELECT count(userid) as userids,cohortid from {cohort_members} where cohortid=$cohort->id");
    $teacherid = $DB->get_field('role', 'id', array('shortname' => 'editingteacher'));

    if(is_siteadmin($USER->id)||(user_has_role_assignment($USER->id, $teacherid)))
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title></title>
    <style type="text/css">
      .section-one{
         /* background-image: url("'.$CFG->wwwroot.'//local/runningbatch/image/navbarbg.png"); */
         
      }
      .navbar-bg{
        width: 107%;
    position: absolute;
    top: -76px;
    left: -68px;

      }
  

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
        transition: transform .2s; 
     

     }
    
     li.my-class {
      color: white;
      list-style: none;
    margin-left: 23px;
}
     .box1:hover{
        transform: scale(1.1);
     }
        .box2{
         box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
        padding: 15px 0px;
      
        transition: 0.2s;
        height: 140px;
        display: flex;
        align-content: center;
/* From https://css.glass */
background: rgba(255, 255, 255, 0.62);
border-radius: 8px;
box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
backdrop-filter: blur(5.9px);
-webkit-backdrop-filter: blur(5.9px);
border: 1px solid rgba(255, 255, 255, 0.58);
        justify-content: center;
     }
     /* .box2:hover{
        border-radius: 20px;
        background-color: #cbf1ff ;
     } */
     .div{
        display: flex;
        flex-direction: row;
        gap: 6px;
     


     }
        .div1{
        display: flex;
        flex-direction: column-reverse;
        align-content: center;
        justify-content: center;
        gap: 10px;
        width: 80%;
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
        font-size:17px;
     }
     .section-one{
        margin-bottom: 55px;
     }
     .main{
      background-color: #b8b7ff;
     }
     .batch{
        /* font-weight: ; */
        font-size: large;
        color:  #3c3c3c;
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
     }
     .bi-calendar-check{
        color: #1363DF ;
     }
     .card-hover{
        list-style: none;
        
     }
     a:link,
a:visited,
a:hover,
a:active {
  text-decoration: none;
}
     .bx-flip-horizontal{
      font-size: 35px;
      color: gray;
     }
     #myChart{
       margin-left: -94px;  
     }
     .contarea{
      position: absolute;
      right: 0;
     
    position: absolute;
    right: 32px;
    top: 99px;

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
/* li{
   text-decoration: none;
   margin: 10px;
   padding: 10px 25px;
   border-radius: 10px; 
   margin: 10px;
   padding: 10px 25px;
 
} */
ul{
   list-style: none;
}
.piechart-display{
   display: flex;
   justify-content: center;
   align-items: center;
   
   height:  87%;
   border-radius: 5px;
   box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
   position: relative;
   display: flex;
  
}
.card-back{
   box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
   border: none;
}
.piechart-css{
   border: 1;
}

.text-bold{
   font-weight: bold;
   color: rgb(0, 0, 0);
}
.complet{
   text-decoration: none;
   margin: 10px;
   padding: 10px 35px;
   border-radius: 10px; 
   margin: 10px;
   padding: 10px 25px;
   background-color: #ECD4FA !important;
}
.incomplet{
   text-decoration: none;
   margin: 10px;
   padding: 10px 35px;
   border-radius: 10px; 
   margin: 10px;
   padding: 10px 25px;
   background-color: #b8b7ff !important;
}

.text-h{
   font-weight: bold;
   color: #081735;
}
.progre{
   text-decoration: none;
   margin: 10px;
   padding: 10px 35px;
   border-radius: 10px; 
   margin: 10px;
   padding: 10px 25px;
   background-color: #dfecab !important;
}
* {margin:0; padding:0;}
.conainer {width: 600px; margin:100px auto; font-family: "Arial"}
.circle_percent {font-size:200px; width:1em; height:1em; position: relative; background: #eee; border-radius:50%; overflow:hidden; display:inline-block; margin:20px;}
.circle_inner {position: absolute; left: 0; top: 0; width: 1em; height: 1em; clip:rect(0 1em 1em .5em);}
.round_per {position: absolute; left: 0; top: 0; width: 1em; height: 1em; background: #e4a6d2; clip:rect(0 1em 1em .5em); transform:rotate(180deg); transition:1.05s;}
.percent_more .circle_inner {clip:rect(0 .5em 1em 0em);}
.percent_more:after {position: absolute; left: .5em; top:0em; right: 0; bottom: 0; background: #e4a6d2; content:"";}
.circle_inbox {position: absolute; top: 10px; left: 10px; right: 10px; bottom: 10px; background: #fff; z-index:3; border-radius: 50%;}
.percent_text {position: absolute; font-size: 36px; left: 50%; top: 50%; transform: translate(-50%,-50%); z-index: 3;}
     
#page-local-runningbatch-dashboard #page-footer{
   display : none ! important;
}
    </style>
</head>
<body>
    <section class="section-one" >
   <img class="navbar-bg" src="'.$CFG->wwwroot.'//local/runningbatch/image/navbarbg.png" alt="">
                <div class="container">
  <div class="row">
    <div class="col-3">
        <a style="text-decoration:none" href="'.$CFG->wwwroot.'/local/runningbatch/batch_details.php">
        <div class="box2">
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
        <div class="col-3"><a style="text-decoration:none" href="'.$CFG->wwwroot.'/local/runningbatch/view_invoice_paid.php">
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
        <div class="col-3"><a style="text-decoration:none" href="'.$CFG->wwwroot.'/local/runningbatch/view_payment_history.php">
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
    <section>
        <div class="container">
         <div class="row">
         <div class="col-lg-6"> 
            <h4 class="mb-3 text-h">Week Activity</h4><div class="col card p-4 card-back">
          
        <canvas id="barChart"></canvas>
        </div>
      </div>
        <div class="col-lg-6">
         <h4 class="mb-3 text-h">Course..</h4>
         <div class="piechart-display">
            
            <canvas id="myChart"></canvas>
            <div class="contarea">
              <ul>
                <li class="complet" >Completed</li>
                <li class="incomplet"  >Incomplete</li>
                <li class="progre"  >Inprogress</li>
              </ul>
            </div>
          </div>
      </div>
         </div>
        </div>
        
        
       <!-- <div class="row">
         ';
 for($i=0; $i<count(($cohort_detail)); $i++){

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
    echo'
    <div class="col-4">
     <div class="box1">
    
        <a class="card-hover" style="text-decoration:none" href="'.$CFG->wwwroot.'/local/runningbatch/batch.php?groupid='.$a.'">
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
            </div>
        </a>
        </div>
    </div>
  ';

 }
    echo'<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</div> -->
</div>';?>

</section>
<script src="https://yislms.com/croma/moodle/local/runningbatch/js/boxicons.min.js">

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://code.angularjs.org/1.2.21/angular.js"></script>
<script src="https://code.highcharts.com/highcharts.src.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
<script>
   var ctx = document.getElementById("myChart").getContext('2d');
var myChart = new Chart(ctx, {
  type: 'pie',
  data: {
    labels: [],
    datasets: [{
      backgroundColor: [
        "rgba(158, 198, 0, 0.33)",
        "rgba(223, 181, 246, 0.58)",
        "rgba(184, 183, 255, 1)",
      ],
      data: [12, 19, 3]
    }]
  }
});



   $(".circle_percent").each(function() {
    var $this = $(this),
		$dataV = $this.data("percent"),
		$dataDeg = $dataV * 3.6,
		$round = $this.find(".round_per");
	$round.css("transform", "rotate(" + parseInt($dataDeg + 180) + "deg)");	
	$this.append('<div class="circle_inbox"><span class="percent_text"></span></div>');
	$this.prop('Counter', 0).animate({Counter: $dataV},
	{
		duration: 2000, 
		easing: 'swing', 
		step: function (now) {
            $this.find(".percent_text").text(Math.ceil(now)+"%");
        }
    });
	if($dataV >= 51){
		$round.css("transform", "rotate(" + 360 + "deg)");
		setTimeout(function(){
			$this.addClass("percent_more");
		},1000);
		setTimeout(function(){
			$round.css("transform", "rotate(" + parseInt($dataDeg + 180) + "deg)");
		},1000);
	} 
});
   var ctx = document.getElementById("barChart").getContext("2d");
   var barChart = new Chart(ctx, {
     type: "bar",
     data: {
         labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sst", "Sun"],
       datasets: [{
         label: "Active",
         data: [12, 19, 3, 17, 28, 24, 7],
         backgroundColor: "#A4D4EF"
       }, {
         label: "InActive",
         data: [30, 29, 5, 5, 20, 3, 10],
         backgroundColor: "#0665F3"
       }]
     }
   });
//    const y = document.querySelector('.list-group')
   
//   console.log(y)
// const li = document.createElement('li');
// const a = document.createElement('a');


// a.innerHTML = 'Courses';


// a.classList.add('my-class');

// const ul = document.getElementById('myUl');


// y.appendChild(li);
   </script>

</html>

<?php 
echo $OUTPUT->footer();
?>
