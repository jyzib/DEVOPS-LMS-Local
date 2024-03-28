<?php

require_once('../../config.php');
require_login();
global $CFG, $DB, $PAGE, $OUTPUT;

// Set up the page
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Video List');
$PAGE->set_heading('Video List');

// Add CSS and JavaScript for the popup functionality
// $PAGE->requires->css(new moodle_url("$CFG->wwwroot/local/runningbatch/css/style.css"));
// $PAGE->requires->js(new moodle_url("$CFG->wwwroot/local/runningbatch/js/main.js"));

// Get the list of videos from your database or any other source
// $videos = "$CFG->wwwroot/local/runningbatch/videos/abc.mp4";
$courseid =$_GET['courseid'];
$course_section = $DB->get_record_sql("SELECT id FROM {course_sections} WHERE course=$courseid AND name='Recordings'");

$course_modules = $DB->get_records_sql("SELECT id, instance FROM {course_modules} WHERE course=$courseid AND section=$course_section->id");


// Display the video list
echo $OUTPUT->header();
echo $OUTPUT->heading('Video List');
?>
<div>
    <button class="btn btn-sm alert alert-primary my-2" onclick="history.back()">Go Back</button>
</div>
    <div class="d-flex">
       
        <div class="row">
        <?php
            foreach ($course_modules as $course_module) 
            {
                $context = $DB->get_record_sql("SELECT id FROM {context} WHERE instanceid=$course_module->id AND depth=4");

                $files_object = $DB->get_record_sql("SELECT * FROM {files} WHERE contextid=$context->id");
                if ($files_object) 
                {
                    $fs = get_file_storage();
                    $file = $fs->get_file($context->id, $files_object->component, $files_object->filearea, 0, '/', $files_object->filename);

                    if ($file) 
                    {
                        $sourceURL = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), $file->get_itemid(), $file->get_filepath(), $file->get_filename());
                        $characterToReplace = "/0";
                        $replacementCharacter = "";
                        $sourceURL = str_replace($characterToReplace, $replacementCharacter, $sourceURL);

                        $label_name = $DB->get_record_sql("SELECT id,l.name,intro FROM {label} l WHERE id=$course_module->instance");
                        // $title =$label_name->intro;
                        
                        echo '<div class="col-sm-12">
                                <div class="card  mb-3">
                                <div class="card-header" id="labelname'.$label_name->id.'">'.$label_name->name.'</div>
                                <div class="card-body text-primary">
                                    <div>
                                        <video width="300" height="240" controls>
                                        <source src="'.$sourceURL.'" type="video/mp4">
                                        <source src="'.$sourceURL.'" type="video/ogg">
                                        Your browser does not support the video tag.
                                        </video>
                                    </div>
                                </div>
                                </div>
                            </div>';
                        echo "<script>
                            var title_object = '$label_name->intro';
                            var new_html = document.createElement('div');
                            new_html.innerHTML = title_object.trim();
                            var video_title ='Video';
                            if(new_html.firstChild.firstElementChild.title)
                            {
                                video_title =new_html.firstChild.firstElementChild.title;
                            }
                            document.getElementById('labelname$label_name->id').innerText=video_title;
                            </script>";
                    //  echo $sourceURL;
                    } else {
                        echo 'File not found.';
                    }
                }
            }
        ?>
    
            
        </div>
    </div>
</body>
</html>
<?php
echo $OUTPUT->footer();
?>
<!-- <script>
    var title_object = '<?php echo ($title); ?>';
    var new_html = document.createElement('div');
    new_html.innerHTML = title_object.trim();
    console.log(new_html.firstChild.firstElementChild.title);
</script> -->