<?php

class block_personality_test extends block_base
{
    function my_slider($name, $min, $max, $value, $izq_val, $der_val)
    {
        $slider = '';
        $slider .= '<div class="slider-container" style="text-align:center">';
        $slider .= $izq_val  . " ↹ " .  $der_val . "<br>";
        $slider .= '<input type="range" class="alpy" name="' . $name . '" min="' . $min . '" max="' . $max . '" value="' . $value . '" disabled>';
        $slider .= '</div>';
        return $slider;
    }
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

                $scores = array(
                    "extraversion" => $entry->extraversion,
                    "introversion" => $entry->introversion,
                    "sensing" => $entry->sensing,
                    "intuition" => $entry->intuition,
                    "thinking" => $entry->thinking,
                    "feeling" => $entry->feeling,
                    "judging" => $entry->judging,
                    "perceptive" => $entry->perceptive
                );
                
                // Define the possible combinations of the MBTI types
                $mbti_types = array("ISTJ", "ISFJ", "INFJ", "INTJ", "ISTP", "ISFP", "INFP", "INTP", "ESTP", "ESFP", "ENFP", "ENTP", "ESTJ", "ESFJ", "ENFJ", "ENTJ");
                
                // Define the MBTI type explanations
                /*$mbti_explanations = array(
                    "ISTJ" => "Practical and fact-minded individuals, whose reliability cannot be doubted.",
                    "ISFJ" => "Very dedicated and warm protectors, always ready to defend their loved ones.",
                    "INFJ" => "Quiet and mystical, yet very inspiring and tireless idealists.",
                    "INTJ" => "Visionaries, strategic thinkers, and logical problem-solvers.",
                    "ISTP" => "Bold and practical experimenters, masters of all kinds of tools.",
                    "ISFP" => "Flexible and charming artists, always ready to explore and experience something new.",
                    "INFP" => "Poetic, kind and altruistic people, always eager to help a good cause.",
                    "INTP" => "Innovative inventors with an unquenchable thirst for knowledge.",
                    "ESTP" => "Smart, energetic, and very perceptive people, who truly enjoy living on the edge.",
                    "ESFP" => "Spontaneous, energetic, and enthusiastic entertainers.",
                    "ENFP" => "Enthusiastic, creative, and sociable free spirits, who can always find a reason to smile.",
                    "ENTP" => "Smart and curious thinkers who cannot resist an intellectual challenge.",
                    "ESTJ" => "Practical and fact-minded individuals, whose reliability cannot be doubted.",
                    "ESFJ" => "Extraordinarily caring, social and popular people, always eager to help.",
                    "ENFJ" => "Charismatic and inspiring leaders, able to mesmerize their listeners.",
                    "ENTJ" => "Bold, imaginative and strong-willed leaders, always finding a way – or making one."
                );*/
                $mbti_explanations = array(
                    "ISTJ" => "práctica y centrada en los hechos, cuya fiabilidad no puede ser cuestionada.",
                    "ISFJ" => "protectora muy dedicada y cálida, siempre lista para defender a sus seres queridos.",
                    "INFJ" => "tranquila y mística, pero muy inspiradora e incansable idealista.",
                    "INTJ" => "visionaria, pensadora estratégica y resolvente de problemas lógicos.",
                    "ISTP" => "experimentadora audaz y práctica, maestra de todo tipo de herramientas.",
                    "ISFP" => "artistica flexible y encantadora, siempre dispuesta a explorar y experimentar algo nuevo.",
                    "INFP" => "poética, amable y altruista, siempre dispuesta por ayudar a una buena causa.",
                    "INTP" => "creativa e innovadora con una sed insaciable de conocimiento.",
                    "ESTP" => "inteligente, enérgica y muy perceptiva, que realmente disfruta viviendo al límite.",
                    "ESFP" => "espontánea, enérgica y entusiasta.",
                    "ENFP" => "de espíritu libre, entusiasta, creativa y sociable, que siempre pueden encontrar una razón para sonreír.",
                    "ENTP" => "pensadora, inteligente y curiosa, que no puede resistirse a un desafío intelectual.",
                    "ESTJ" => "práctica y centrada en los hechos, cuya fiabilidad no puede ser cuestionada.",
                    "ESFJ" => "extraordinariamente cariñosa, sociable y popular, siempre dispuesta a ayudar.",
                    "ENFJ" => "líder, carismática e inspiradora, capaz de cautivar a su audiencia.",
                    "ENTJ" => "líder, audaz, imaginativa y de voluntad fuerte, siempre encontrando una forma, o creándola."
                    );
                
                $mbti_score = "";
                if ($scores["extraversion"] >= $scores["introversion"]) {
                    $mbti_score .= "E";
                } else {
                    $mbti_score .= "I";
                }
                
                if ($scores["sensing"] > $scores["intuition"]) {
                    $mbti_score .= "S";
                } else {
                    $mbti_score .= "N";
                }
                
                if ($scores["thinking"] >= $scores["feeling"]) {
                    $mbti_score .= "T";
                } else {
                    $mbti_score .= "F";
                }
                
                if ($scores["judging"] > $scores["perceptive"]) {
                    $mbti_score .= "J";
                } else {
                    $mbti_score .= "P";
                }
                //$mbti_score
                $this->content->text .=  "<p class=''>De acuerdo con el modelo de Myers Briggs todos tendemos a inclinarnos por cuatro facetas de personalidades predominantes.<br>";
                $this->content->text .=  "En tu caso podemos concluir que eres una persona <strong>" . $mbti_explanations[$mbti_score] . "</strong></p>";
                



                //$this->content->text .=  "<p class='alpyintro'>Según el test de personalidad basado en el modelo de Myers Briggs que realizaste, las tendencias que muestras en tu personalidad, son las cuatro siguientes:";


                //$this->content->text .= "<ul class='alpyintro'>";

                //foreach ($interpretations as $interpretation) {
                //    $this->content->text .=  "<li>$interpretation</li>";
                //}
                //$this->content->text .= "</ul></p>";

                //$this->content->text .= "<p class='alpyintro'>Estas personalidades indican: La primera, la forma en que te relacionas con los demas. La segunda, como procesas la información. La tercera, como tomas las desiciones. Y por ultimo, la cuarta, como organiza tu vida.</p>";          
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
