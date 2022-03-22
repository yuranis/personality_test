<?php
require_once(dirname(__FILE__) . '/lib.php');

if( !isloggedin() ){
            return;
}

$courseid = optional_param('cid', 0, PARAM_INT);
$error  = optional_param('error', 0, PARAM_INT);

if ($courseid == SITEID && !$courseid) {
    redirect($CFG->wwwroot);
}

/*if (!isset($SESSION->honorcodetext)) {
    redirect(new moodle_url('/course/view.php', array('id' => $courseid)));
}*/

$course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
$PAGE->set_course($course);
$context = $PAGE->context;
  
$PAGE->set_url('/blocks/personality_test/view.php', array('cid'=>$courseid));

$title = get_string('pluginname', 'block_personality_test');

$PAGE->set_pagelayout('print');
$PAGE->set_title($title." : ".$course->fullname);
$PAGE->set_heading($title." : ".$course->fullname);

echo $OUTPUT->header();
echo $OUTPUT->box_start('generalbox');
echo "<h1 class='title_personality_test'>".$title ."</h1>";
echo "<div class='content_personality_test'>".$SESSION->honorcodetext."</div>";
$action_form = new moodle_url('/blocks/personality_test/save.php');
?>

<form method="POST" action="<?php echo $action_form ?>" >
    <div class="content-accept <?php echo ($error)?"error":"" ?>">
        <?php if($error): ?>
            <p class="error"><?php echo get_string('required_message', 'block_personality_test') ?></p>
        <?php endif; ?>

        <ol class="personality_test_q">
        <?php for ($i=1;$i<=72;$i++){ ?>
        

        <li class="personality_test_item"><?php echo get_string("personality_test:q".$i, 'block_personality_test') ?>
        <select name="personality_test:q<?php echo $i; ?>" required>
            <option value="" disabled selected hidden>Selecciona</option>
            <option value="1"><?php echo get_string('personality_test:q'.$i.'_a', 'block_personality_test') ?></option>
            <option value="0"><?php echo get_string('personality_test:q'.$i.'_b', 'block_personality_test') ?></option>
        </select>
        </li>
        <?php } ?>
        </ol>
        <div class="clearfix"></div>
        <input class="btn" type="submit" value="<?php echo get_string('submit_text', 'block_personality_test') ?>" >
    
    </div>
    
    <input type="hidden" name="cid" value="<?php echo $courseid ?>">
    <div class="clearfix"></div>
    
</form>

<?php

echo $OUTPUT->box_end();
echo $OUTPUT->footer();