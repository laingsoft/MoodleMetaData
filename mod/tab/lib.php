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
 * Library of functions and constants for module tab
 *
 * @author : Patrick Thibaudeau
 * @version $Id: lib.php,v 2.0 2010/07/13 18:17:00
 * @package tab
 **/

defined('MOODLE_INTERNAL') || die;


/**
 * List of features supported in Tab display
 * @uses FEATURE_IDNUMBER
 * @uses FEATURE_GROUPS
 * @uses FEATURE_GROUPINGS
 * @uses FEATURE_GROUPMEMBERSONLY
 * @uses FEATURE_MOD_INTRO
 * @uses FEATURE_COMPLETION_TRACKS_VIEWS
 * @uses FEATURE_GRADE_HAS_GRADE
 * @uses FEATURE_GRADE_OUTCOMES
 * @param string $feature FEATURE_xx constant for requested feature
 * @return bool|null True if module supports feature, false if not, null if doesn't know
 */
function tab_supports($feature) {
    switch($feature) {
        case FEATURE_IDNUMBER:
            return false;
        case FEATURE_GROUPS:
            return false;
        case FEATURE_GROUPINGS:
            return false;
        case FEATURE_GROUPMEMBERSONLY:
            return false;
        case FEATURE_MOD_INTRO:
            return false;
        case FEATURE_COMPLETION_TRACKS_VIEWS:
            return true;
        case FEATURE_GRADE_HAS_GRADE:
            return false;
        case FEATURE_GRADE_OUTCOMES:
            return false;
        case FEATURE_MOD_ARCHETYPE:
            return MOD_ARCHETYPE_RESOURCE;
        case FEATURE_BACKUP_MOODLE2:
            return true;

        default:
            return null;
    }
}
/**
 * Returns all other caps used in module
 * @return array
 */
function tab_get_extra_capabilities() {
    return array('moodle/site:accessallgroups');
}
/**
 * This function is used by the reset_course_userdata function in moodlelib.
 * @param $data the data submitted from the reset course.
 * @return array status array
 */
function tab_reset_userdata($tab) {
    return array();
}

/**
 * List of view style log actions
 * @return array
 */
function tab_get_view_actions() {
    return array('view', 'view all');
}

/**
 * List of update style log actions
 * @return array
 */
function tab_get_post_actions() {
    return array('update', 'add');
}

/**
 * Add tab display instance.
 * @param object $data
 * @param object $mform
 * @return int new page instance id
 */
function tab_add_instance($tab) {
    global $CFG, $DB;

    require_once("$CFG->libdir/resourcelib.php");

    $cmid = $tab->coursemodule;
    $tab->timemodified = time();

    // Insert tabs and content.
    if ($tab->id = $DB->insert_record("tab", $tab)) {

        // We need to use context now, so we need to make sure all needed info is already in db.
        $DB->set_field('course_modules', 'instance', $tab->id, array('id' => $cmid));
        $context = context_module::instance($cmid);
        $editoroptions = array('subdirs' => 1, 'maxbytes' => $CFG->maxbytes, 'maxfiles' => -1, 'changeformat' => 1,
            'context' => $context, 'noclean' => 1, 'trusttext' => true);

        tab_name_unnamed_tabs_having_content($tab);

        foreach ($tab->tabname as $key => $value) {
            $value = trim($value);
            if (isset($value) && $value <> '') {
                $option = new object();
                $option->tabname = $value;
                $option->tabid = $tab->id;

                if (isset($tab->content[$key]['format'])) {
                    $option->contentformat = $tab->content[$key]['format'];
                }

                if (isset($tab->tabcontentorder[$key])) {
                    $option->tabcontentorder = $tab->tabcontentorder[$key];
                }
                $option->timemodified = time();
                // Must get id number from inserted record to update the editor field (tabcontent).
                $newtabcontentid = $DB->insert_record("tab_content", $option);

                // Tab content is now an array due to the new editor
                // in order to enter file information from the editor
                // we must now update the record once it has been created.

                if (isset($tab->content[$key]['text'])) {
                    $draftitemid = $tab->content[$key]['itemid'];
                    if ($draftitemid) {
                        $tabcontentupdate = new object();
                        $tabcontentupdate->id = $newtabcontentid;
                        $tabcontentupdate->tabcontent = file_save_draft_area_files($draftitemid, $context->id, 'mod_tab',
                            'content', $newtabcontentid, $editoroptions, $tab->content[$key]['text']);
                        $DB->update_record('tab_content', $tabcontentupdate);

                    }
                }
            }
        }
    }
    return $tab->id;

}

/**
 * Given an object containing all the necessary data,
 * (defined by the form in mod.html) this function
 * will name tabs given content but no name.
 *
 * @param object $instance An object from the form in mod.html
 **/
function tab_name_unnamed_tabs_having_content($tab) {
    global $USER;
    foreach ($tab->tabname as $key => $value) {
        if ($value == '') {
            // Tab was not given a name.

            if (($tab->content[$key]['text'] != '')) {
                $tab->tabname[$key] = '...';
                $offendingtabnumber = $key + 1;
                // Maybe should warn user "Tab #".$offendingtabnumber." has no name,
                // but contains text or a URL. Renaming to '".$tab->tabname[$key]."'.");.
            }
        }
    }
}

/**
 * Given an object containing all the necessary data,
 * (defined by the form in mod.html) this function
 * will update an existing instance with new data.
 *
 * @param object $instance An object from the form in mod.html
 * @return boolean Success/Fail
 **/
function tab_update_instance($tab) {
    global $CFG, $DB;

    require_once("$CFG->libdir/resourcelib.php");

    $cmid = $tab->coursemodule;

    $tab->timemodified = time();
    $tab->id = $tab->instance;

    tab_name_unnamed_tabs_having_content($tab);

    foreach ($tab->tabname as $key => $value) {

        // We need to use context now, so we need to make sure all needed info is already in db.
        $DB->set_field('course_modules', 'instance', $tab->id, array('id' => $cmid));
        $context = context_module::instance($cmid);
        $editoroptions = array('subdirs' => 1, 'maxbytes' => $CFG->maxbytes, 'maxfiles' => -1, 'changeformat' => 1,
            'context' => $context, 'noclean' => 1, 'trusttext' => true);

        $value = trim($value);
        $option = new object();
        $option->tabname = $value;
        $option->tabcontentorder = $tab->tabcontentorder[$key];
        // Tab content is now an array due to the new editor.
        $draftitemid = $tab->content[$key]['itemid'];

        if ($draftitemid) {
            $option->tabcontent = file_save_draft_area_files($draftitemid, $context->id, 'mod_tab', 'content',
            $tab->optionid[$key], $editoroptions, $tab->content[$key]['text']);
        }
        $option->contentformat = $tab->content[$key]['format'];
        $option->tabid = $tab->id;
        $option->timemodified = time();

        if (isset($tab->optionid[$key]) && !empty($tab->optionid[$key])) { // Existing tab record.
            $option->id = $tab->optionid[$key];
            if (isset($value) && $value <> '') {
                $DB->update_record("tab_content", $option);
            } else { // Empty old option - needs to be deleted.
                $DB->delete_records("tab_content", array("id" => $option->id));
            }
        } else {
            if (isset($value) && $value <> '') {
                $newtabcontentid = $DB->insert_record("tab_content", $option);
                 // Tab content is now an array due to the new editor
                // In order to enter file information from the editor
                // We must now update the record once it has been created.

                if (isset($tab->content[$key]['text'])) {
                    $draftitemid = $tab->content[$key]['itemid'];
                    if ($draftitemid) {
                        $tabcontentupdate = new object();
                        $tabcontentupdate->id = $newtabcontentid;
                        $tabcontentupdate->tabcontent = file_save_draft_area_files($draftitemid, $context->id, 'mod_tab',
                            'content', $newtabcontentid, $editoroptions, $tab->content[$key]['text']);
                        $DB->update_record('tab_content', $tabcontentupdate);

                    }
                }
            }
        }

    }
    return $DB->update_record("tab", $tab);
}

/**
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @param int $id Id of the module instance
 * @return boolean Success/Failure
 **/
function tab_delete_instance($id) {
    global $DB;

    if (! $tab = $DB->get_record("tab", array("id" => "$id"))) {
        return false;
    }

    $result = true;

    // Delete any dependent records here.

    if (! $DB->delete_records("tab", array("id" => "$tab->id"))) {
        $result = false;
    }
    if (! $DB->delete_records("tab_content", array("tabid" => "$tab->id"))) {
        $result = false;
    }

    return $result;
}

/**
 * Serves the tab images or files. Implements needed access control ;-)
 *
 * @param object $course
 * @param object $cm
 * @param object $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @return bool false if file not found, does not return if found - justsend the file
 */
function tab_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload) {
    global $CFG, $DB;

    // The following code is for security.
    require_course_login($course, true, $cm);

    if ($context->contextlevel != CONTEXT_MODULE) {
        return false;
    }

    $fileareas = array('mod_tab', 'content', 'fileattachment');  // DR: why is 'mod_tab' a filearea listed here?
                                                                 // Is it not the 'component'?
    if (!in_array($filearea, $fileareas)) {
        return false;
    }
    // ID of the content row.
    $tabcontentid = (int)array_shift($args);  // DR: corresponds to itemid column in mdl_files db table.

    if ($filearea != 'fileattachment') {
        // Security - Check if exists.
        if (!$tabcontent = $DB->get_record('tab_content', array('id' => $tabcontentid))) {
            return false;
        }
    } else {
        if (!$tabcontent = $DB->get_record('tab_content', array('id' => $tabcontentid))) {
            trigger_error('DR: File attachment appears not to exist='.var_export($tabcontent, true));
            return false;
        }
    }

    if (!$tab = $DB->get_record('tab', array('id' => $cm->instance))) {
        return false;
    }

    // Now gather file information.
    $fs = get_file_storage();
    $relativepath = implode('/', $args);
    $fullpath = "/$context->id/mod_tab/$filearea/$tabcontentid/$relativepath";

    if (!$file = $fs->get_file_by_hash(sha1($fullpath)) or $file->is_directory()) {
        return false;
    }

    // Finally send the file.
    send_stored_file($file, 0, 0, false);
}


/**
 * Return a small object with summary information about what a
 * user has done with a given particular instance of this module
 * Used for user activity reports.
 * $return->time = the time they did it
 * $return->info = a short text description
 *
 * @return null
 * @todo Finish documenting this function
 **/
function tab_user_outline($course, $user, $mod, $tab) {
    global $DB;

    if ($logs = $DB->get_records('log', array('userid' => $user->id, 'module' => 'tab',
                                              'action' => 'view', 'info' => $tab->id), 'time ASC')) {

        $numviews = count($logs);
        $lastlog = array_pop($logs);

        $result = new stdClass();
        $result->info = get_string('numviews', '', $numviews);
        $result->time = $lastlog->time;

        return $result;
    }
    return null;
}

/**
 * Print a detailed representation of what a user has done with
 * a given particular instance of this module, for user activity reports.
 *
 * @return boolean
 * @todo Finish documenting this function
 **/
function tab_user_complete($course, $user, $mod, $tab) {
     global $CFG, $DB;

    if ($logs = $DB->get_records('log', array('userid' => $user->id, 'module' => 'tab',
                                              'action' => 'view', 'info' => $tab->id), 'time ASC')) {
        $numviews = count($logs);
        $lastlog = array_pop($logs);

        $strmostrecently = get_string('mostrecently');
        $strnumviews = get_string('numviews', '', $numviews);

        echo "$strnumviews - $strmostrecently ".userdate($lastlog->time);

    } else {
        print_string('neverseen', 'tab');
    }
}

/**
 * Given a course and a time, this module should find recent activity
 * that has occurred in tab activities and print it out.
 * Return true if there was output, or false is there was none.
 *
 * @uses $CFG
 * @return boolean
 * @todo Finish documenting this function
 **/
function tab_print_recent_activity($course, $viewfullnames, $timestart) {
    global $CFG;

    return false;  // True if anything was printed, otherwise false.
}
/**
 * Given a course_module object, this function returns any
 * "extra" information that may be needed when printing
 * this activity in a course listing.
 *
 * See {@link get_array_of_activities()} in course/lib.php
 *
 * @param object $coursemodule
 * @return object info
 */
function tab_get_coursemodule_info($coursemodule) {
    global $CFG, $DB;
    require_once("$CFG->libdir/resourcelib.php");

    if (!$tab = $DB->get_record('tab', array('id' => $coursemodule->instance), 'id, name')) {
        return null;
    }

    $info = new stdClass();
    $info->name = $tab->name;

    return $info;
}
