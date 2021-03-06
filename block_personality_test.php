<?php

class block_personality_test extends block_base
{

    function init()
    {
        $this->title = get_string('pluginname', 'block_personality_test');
    }

    /*function has_config() {
        return false;
    }*/

    function instance_allow_multiple()
    {
        return false;
    }

    /*function instance_allow_config() {
        return false;
    }*/

    function get_content()
    {

        global $OUTPUT, $CFG, $DB, $USER, $COURSE, $SESSION;

        if ($COURSE->id == SITEID) {
            return;
        }

        if ($this->content !== NULL) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->text = '';
        $this->content->footer = '';

        if (empty($this->instance)) {
            return $this->content;
        }

        if (!isloggedin()) {
            return;
        }

        /*$redirect = new moodle_url('/blocks/personality_test/view.php', array('cid' => $COURSE->id));
        redirect($redirect);*/


        $COURSE_ROLED_AS_STUDENT = $DB->get_record_sql("  SELECT m.id
                FROM {user} m 
                LEFT JOIN {role_assignments} m2 ON m.id = m2.userid 
                LEFT JOIN {context} m3 ON m2.contextid = m3.id 
                LEFT JOIN {course} m4 ON m3.instanceid = m4.id 
                WHERE (m3.contextlevel = 50 AND m2.roleid IN (5) AND m.id IN ( {$USER->id} )) AND m4.id = {$COURSE->id} ");

        //Check if user is student
        if (isset($COURSE_ROLED_AS_STUDENT->id) && $COURSE_ROLED_AS_STUDENT->id) {
            //check if user already have the learning style
            $entry = $DB->get_record('personality_test', array('user' => $USER->id, 'course' => $COURSE->id));
            if (!$entry) {
                if (isset($this->config->personality_test_content) && isset($this->config->personality_test_content["text"])) {
                    $SESSION->personality_test = $this->config->personality_test_content["text"];
                    $redirect = new moodle_url('/blocks/personality_test/view.php', array('cid' => $COURSE->id));
                    redirect($redirect);
                }
            } else {
                $personality_result = array(
                    "Extraversion" => $entry->extraversion, "Introversion" => $entry->introversion,
                    "Sensing" => $entry->sensing, "Intuition" => $entry->intuition, 
                    "Thinking" => $entry->thinking, "Feeling" => $entry->feeling,
                    "Judging" => $entry->judging, "Perceptive" => $entry->perceptive
                );
                arsort($personality_result);
                $personality_result = array_slice($personality_result, 0, 4);
                $this->content->text = "<br><img src='" . $OUTPUT->pix_url('ok', 'block_personality_test') . "'>" . get_string('accept_message', 'block_personality_test')."<br>";
                foreach ($personality_result as $key => $value){
                    $this->content->text .= "<br>" . $key . " " . $value;
                }
            }
        } else {
            if (isset($this->config->personality_test_content) && isset($this->config->personality_test_content["text"])) {
                $this->content->text = "<img src='" . $OUTPUT->pix_url('ok', 'block_personality_test') . "'>" . get_string('personality_test_actived', 'block_personality_test');
            } else {
                $this->content->text = "<img src='" . $OUTPUT->pix_url('warning', 'block_personality_test') . "'>" . get_string('personality_test_configempty', 'block_personality_test');
            }
        }
        return $this->content;
    }
}
