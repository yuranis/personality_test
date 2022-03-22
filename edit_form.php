<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Form for editing HTML block instances.
 *
 * @package   block_html
 * @copyright 2009 Tim Hunt
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Form for editing HTML block instances.
 *
 * @copyright 2009 Tim Hunt
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_personality_test_edit_form extends block_edit_form {
    protected function specific_definition($mform) {
        $mform->addElement('header', 'general', get_string('general', 'form'));
        $mform->addElement('editor', 'config_personality_test_content', get_string('personality_test_content', 'block_personality_test'), null,null );
        $mform->addRule('config_personality_test_content', null, 'required', null, 'client');
        $mform->setType('config_personality_test_content', PARAM_RAW); // XSS is prevented when printing the block contents and serving files
    }

    function set_data($defaults) {
        if (!empty($this->block->config) && is_object($this->block->config)) {
            if( empty( $this->block->config->personality_test_content ) ){
                $defaults->config_personality_test_content["text"] =  get_string('code_honor_text', 'block_personality_test');
            }
        }else{
            $defaults->config_personality_test_content["text"] =  get_string('code_honor_text', 'block_personality_test');
        }
        parent::set_data($defaults);        
    }
}
