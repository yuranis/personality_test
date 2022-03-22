<?php 

require_once(dirname(__FILE__) . '/../../config.php');

function save_personality_test($course,$act_ref,$sen_int,$vis_vrb,$seq_glo) {
    GLOBAL $DB, $USER, $CFG;
    if (!$entry = $DB->get_record('personality_test', array('user' => $USER->id, 'course' => $course))) {
        $entry = new stdClass();
        $entry->user = $USER->id;
        $entry->course = $course;
        $entry->state = "1";
        $entry->created_at = time();
        $entry->updated_at = time();
        $entry->id = $DB->insert_record('personality_test', $entry);
        return true;
    }else{
        return false;
    }
}

