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
 * A custom configuration form that extends the block_edit_form and
 * is used by the site policies block.
 *
 * @package sitepolicies
 * @author Greg Gibeau ggibeau@ualberta.ca
 **/
class block_sitepolicies_edit_form extends block_edit_form{

    protected function specific_definition( $mform ) {
        // Section header title according to language file.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block_sitepolicies'));

        $mform->addElement('advcheckbox', 'config_enablecourselinks',
            get_string('enablecourselinks', 'block_sitepolicies'),
            get_string('enablecourselinksdesc', 'block_sitepolicies'));
        $mform->setDefault('config_enablecourselinks', 0);
        $mform->addElement('text', 'config_title', get_string('courseheader', 'block_sitepolicies'));
        $mform->setDefault('config_title', get_string('courselinks', 'block_sitepolicies'));
        $mform->setType('config_title', PARAM_MULTILANG);
        $mform->addElement('editor', 'config_rawhtml', get_string('policieslinksettings', 'block_sitepolicies'), array(
            'subdirs' => 0,
            'maxbytes' => 0,
            'maxfiles' => 0,
            'changeformat' => 0,
            'context' => null,
            'noclean' => 0,
            'trusttext' => 0));
        $mform->setType('fieldname', PARAM_RAW);
    }

}