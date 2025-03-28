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
 * Unit tests for the lib/upgradelib.php library.
 *
 * @package   core
 * @category  phpunit
 * @copyright 2013 onwards Eloy Lafuente (stronk7) {@link http://stronk7.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir.'/upgradelib.php');


/**
 * Tests various classes and functions in upgradelib.php library.
 */
class core_upgradelib_testcase extends advanced_testcase {

    /**
     * Test the {@link upgrade_stale_php_files_present() function
     */
    public function test_upgrade_stale_php_files_present() {
        // Just call the function, must return bool false always
        // if there aren't any old files in the codebase.
        $this->assertFalse(upgrade_stale_php_files_present());
    }

    /**
     * Test the {@link upgrade_grade_item_fix_sortorder() function with
     * faked duplicate sortorder data.
     */
    public function test_upgrade_grade_item_fix_sortorder() {
        global $DB;

        $this->resetAfterTest(true);

        // The purpose of this test is to make sure that after upgrade script
        // there is no duplicates in the field grade_items.sortorder (for each course)
        // and the result of query "SELECT id FROM grade_items WHERE courseid=? ORDER BY sortorder, id" does not change.
        $sequencesql = 'SELECT id FROM {grade_items} WHERE courseid=? ORDER BY sortorder, id';

        // Each set is used for filling the db with fake data and will be representing the result of query:
        // "SELECT sortorder from {grade_items} WHERE courseid=? ORDER BY id".
        $testsets = array(
            // Items that need no action.
            array(1,2,3),
            array(5,6,7),
            array(7,6,1,3,2,5),
            // Items with sortorder duplicates
            array(1,2,2,3,3,4,5),
            // Only one sortorder duplicate.
            array(1,1),
            array(3,3),
            // Non-sequential sortorders with one or multiple duplicates.
            array(3,3,7,5,6,6,9,10,8,3),
            array(7,7,3),
            array(3,4,5,3,5,4,7,1)
        );
        $origsequences = array();

        // Generate the data and remember the initial sequence or items.
        foreach ($testsets as $testset) {
            $course = $this->getDataGenerator()->create_course();
            foreach ($testset as $sortorder) {
                $this->insert_fake_grade_item_sortorder($course->id, $sortorder);
            }
            $DB->get_records('grade_items');
            $origsequences[$course->id] = $DB->get_fieldset_sql($sequencesql, array($course->id));
        }

        $duplicatedetectionsql = "SELECT courseid, sortorder
                                    FROM {grade_items}
                                GROUP BY courseid, sortorder
                                  HAVING COUNT(id) > 1";

        // Verify there are duplicates before we start the fix.
        $dupes = $DB->record_exists_sql($duplicatedetectionsql);
        $this->assertTrue($dupes);

        // Do the work.
        upgrade_grade_item_fix_sortorder();

        // Verify that no duplicates are left in the database.
        $dupes = $DB->record_exists_sql($duplicatedetectionsql);
        $this->assertFalse($dupes);

        // Verify that sequences are exactly the same as they were before upgrade script.
        $idx = 0;
        foreach ($origsequences as $courseid => $origsequence) {
            if (count(($testsets[$idx])) == count(array_unique($testsets[$idx]))) {
                // If there were no duplicates for this course verify that sortorders are not modified.
                $newsortorders = $DB->get_fieldset_sql("SELECT sortorder from {grade_items} WHERE courseid=? ORDER BY id", array($courseid));
                $this->assertEquals($testsets[$idx], $newsortorders);
            }
            $newsequence = $DB->get_fieldset_sql($sequencesql, array($courseid));
            $this->assertEquals($origsequence, $newsequence,
                    "Sequences do not match for test set $idx : ".join(',', $testsets[$idx]));
            $idx++;
        }
    }

    /**
     * Populate some fake grade items into the database with specified
     * sortorder and course id.
     *
     * NOTE: This function doesn't make much attempt to respect the
     * gradebook internals, its simply used to fake some data for
     * testing the upgradelib function. Please don't use it for other
     * purposes.
     *
     * @param int $courseid id of course
     * @param int $sortorder numeric sorting order of item
     * @return stdClass grade item object from the database.
     */
    private function insert_fake_grade_item_sortorder($courseid, $sortorder) {
        global $DB, $CFG;
        require_once($CFG->libdir.'/gradelib.php');

        $item = new stdClass();
        $item->courseid = $courseid;
        $item->sortorder = $sortorder;
        $item->gradetype = GRADE_TYPE_VALUE;
        $item->grademin = 30;
        $item->grademax = 110;
        $item->itemnumber = 1;
        $item->iteminfo = '';
        $item->timecreated = time();
        $item->timemodified = time();

        $item->id = $DB->insert_record('grade_items', $item);

        return $DB->get_record('grade_items', array('id' => $item->id));
    }

    public function test_upgrade_fix_missing_root_folders() {
        global $DB, $SITE;

        $this->resetAfterTest(true);

        // Setup some broken data...
        // Create two resources (and associated file areas).
        $this->setAdminUser();
        $resource1 = $this->getDataGenerator()->get_plugin_generator('mod_resource')
            ->create_instance(array('course' => $SITE->id));
        $resource2 = $this->getDataGenerator()->get_plugin_generator('mod_resource')
            ->create_instance(array('course' => $SITE->id));

        // Delete the folder record of resource1 to simulate broken data.
        $context = context_module::instance($resource1->cmid);
        $selectargs = array('contextid' => $context->id,
                            'component' => 'mod_resource',
                            'filearea' => 'content',
                            'itemid' => 0);

        // Verify file records exist.
        $areafilecount = $DB->count_records('files', $selectargs);
        $this->assertNotEmpty($areafilecount);

        // Delete the folder record.
        $folderrecord = $selectargs;
        $folderrecord['filepath'] = '/';
        $folderrecord['filename'] = '.';

        // Get previous folder record.
        $oldrecord = $DB->get_record('files', $folderrecord);
        $DB->delete_records('files', $folderrecord);

        // Verify the folder record has been removed.
        $newareafilecount = $DB->count_records('files', $selectargs);
        $this->assertSame($newareafilecount, $areafilecount - 1);

        $this->assertFalse($DB->record_exists('files', $folderrecord));

        // Run the upgrade step!
        upgrade_fix_missing_root_folders();

        // Verify the folder record has been restored.
        $newareafilecount = $DB->count_records('files', $selectargs);
        $this->assertSame($newareafilecount, $areafilecount);

        $newrecord = $DB->get_record('files', $folderrecord, '*', MUST_EXIST);
        // Verify the hash is correctly created.
        $this->assertSame($oldrecord->pathnamehash, $newrecord->pathnamehash);
    }

    public function test_upgrade_fix_missing_root_folders_draft() {
        global $DB, $SITE;

        $this->resetAfterTest(true);

        $user = $this->getDataGenerator()->create_user();
        $usercontext = context_user::instance($user->id);
        $this->setUser($user);
        $resource1 = $this->getDataGenerator()->get_plugin_generator('mod_resource')
            ->create_instance(array('course' => $SITE->id));
        $context = context_module::instance($resource1->cmid);
        $draftitemid = 0;
        file_prepare_draft_area($draftitemid, $context->id, 'mod_resource', 'content', 0);

        $queryparams = array(
            'component' => 'user',
            'contextid' => $usercontext->id,
            'filearea' => 'draft',
            'itemid' => $draftitemid,
        );

        // Make sure there are two records in files for the draft file area and one of them has filename '.'.
        $records = $DB->get_records_menu('files', $queryparams, '', 'id, filename');
        $this->assertEquals(2, count($records));
        $this->assertTrue(in_array('.', $records));
        $originalhash = $DB->get_field('files', 'pathnamehash', $queryparams + array('filename' => '.'));

        // Delete record with filename '.' and make sure it does not exist any more.
        $DB->delete_records('files', $queryparams + array('filename' => '.'));

        $records = $DB->get_records_menu('files', $queryparams, '', 'id, filename');
        $this->assertEquals(1, count($records));
        $this->assertFalse(in_array('.', $records));

        // Run upgrade script and make sure the record is restored.
        upgrade_fix_missing_root_folders_draft();

        $records = $DB->get_records_menu('files', $queryparams, '', 'id, filename');
        $this->assertEquals(2, count($records));
        $this->assertTrue(in_array('.', $records));
        $newhash = $DB->get_field('files', 'pathnamehash', $queryparams + array('filename' => '.'));
        $this->assertEquals($originalhash, $newhash);
    }

    /**
     * Tests the upgrade of an individual course-module or section from the
     * old to new availability system. (This test does not use the database
     * so it can run any time.)
     */
    public function test_upgrade_availability_item() {
        global $CFG;
        $this->resetAfterTest();

        // This function is in the other upgradelib.
        require_once($CFG->libdir . '/db/upgradelib.php');

        // Groupmembersonly (or nothing). Show option on but ignored.
        // Note: This $CFG option doesn't exist any more but we are testing the
        // upgrade function so it did exist then...
        $CFG->enablegroupmembersonly = 0;
        $this->assertNull(
                upgrade_availability_item(1, 0, 0, 0, 1, array(), array()));
        $CFG->enablegroupmembersonly = 1;
        $this->assertNull(
                upgrade_availability_item(0, 0, 0, 0, 1, array(), array()));
        $this->assertEquals(
                '{"op":"&","showc":[false],"c":[{"type":"group"}]}',
                upgrade_availability_item(1, 0, 0, 0, 1, array(), array()));
        $this->assertEquals(
                '{"op":"&","showc":[false],"c":[{"type":"grouping","id":4}]}',
                upgrade_availability_item(1, 4, 0, 0, 1, array(), array()));

        // Dates (with show/hide options - until date always hides).
        $this->assertEquals(
                '{"op":"&","showc":[true],"c":[{"type":"date","d":">=","t":996}]}',
                upgrade_availability_item(0, 0, 996, 0, 1, array(), array()));
        $this->assertEquals(
                '{"op":"&","showc":[false],"c":[{"type":"date","d":">=","t":997}]}',
                upgrade_availability_item(0, 0, 997, 0, 0, array(), array()));
        $this->assertEquals(
                '{"op":"&","showc":[false],"c":[{"type":"date","d":"<","t":998}]}',
                upgrade_availability_item(0, 0, 0, 998, 1, array(), array()));
        $this->assertEquals(
                '{"op":"&","showc":[true,false],"c":[' .
                '{"type":"date","d":">=","t":995},{"type":"date","d":"<","t":999}]}',
                upgrade_availability_item(0, 0, 995, 999, 1, array(), array()));

        // Grade (show option works as normal).
        $availrec = (object)array(
                'sourcecmid' => null, 'requiredcompletion' => null,
                'gradeitemid' => 13, 'grademin' => null, 'grademax' => null);
        $this->assertEquals(
                '{"op":"&","showc":[true],"c":[{"type":"grade","id":13}]}',
                upgrade_availability_item(0, 0, 0, 0, 1, array($availrec), array()));
        $availrec->grademin = 4.1;
        $this->assertEquals(
                '{"op":"&","showc":[false],"c":[{"type":"grade","id":13,"min":4.10000}]}',
                upgrade_availability_item(0, 0, 0, 0, 0, array($availrec), array()));
        $availrec->grademax = 9.9;
        $this->assertEquals(
                '{"op":"&","showc":[true],"c":[{"type":"grade","id":13,"min":4.10000,"max":9.90000}]}',
                upgrade_availability_item(0, 0, 0, 0, 1, array($availrec), array()));
        $availrec->grademin = null;
        $this->assertEquals(
                '{"op":"&","showc":[true],"c":[{"type":"grade","id":13,"max":9.90000}]}',
                upgrade_availability_item(0, 0, 0, 0, 1, array($availrec), array()));

        // Completion (show option normal).
        $availrec->grademax = null;
        $availrec->gradeitemid = null;
        $availrec->sourcecmid = 666;
        $availrec->requiredcompletion = 1;
        $this->assertEquals(
                '{"op":"&","showc":[true],"c":[{"type":"completion","cm":666,"e":1}]}',
                upgrade_availability_item(0, 0, 0, 0, 1, array($availrec), array()));
        $this->assertEquals(
                '{"op":"&","showc":[false],"c":[{"type":"completion","cm":666,"e":1}]}',
                upgrade_availability_item(0, 0, 0, 0, 0, array($availrec), array()));

        // Profile conditions (custom/standard field, values/not, show option normal).
        $fieldrec = (object)array('userfield' => 'email', 'operator' => 'isempty',
                'value' => '', 'shortname' => null);
        $this->assertEquals(
                '{"op":"&","showc":[true],"c":[{"type":"profile","op":"isempty","sf":"email"}]}',
                upgrade_availability_item(0, 0, 0, 0, 1, array(), array($fieldrec)));
        $fieldrec->value = '@';
        $fieldrec->operator = 'contains';
        $this->assertEquals(
                '{"op":"&","showc":[true],"c":[{"type":"profile","op":"contains","sf":"email","v":"@"}]}',
                upgrade_availability_item(0, 0, 0, 0, 1, array(), array($fieldrec)));
        $fieldrec->operator = 'isnotempty';
        $fieldrec->userfield = null;
        $fieldrec->shortname = 'frogtype';
        $this->assertEquals(
                '{"op":"&","showc":[false],"c":[{"type":"profile","op":"isnotempty","cf":"frogtype"}]}',
                upgrade_availability_item(0, 0, 0, 0, 0, array(), array($fieldrec)));

        // Everything at once.
        $this->assertEquals('{"op":"&","showc":[false,true,false,true,true,true],' .
                '"c":[{"type":"grouping","id":13},' .
                '{"type":"date","d":">=","t":990},' .
                '{"type":"date","d":"<","t":991},' .
                '{"type":"grade","id":665,"min":70.00000},' .
                '{"type":"completion","cm":42,"e":2},' .
                '{"type":"profile","op":"isempty","sf":"email"}]}',
                upgrade_availability_item(1, 13, 990, 991, 1, array(
                    (object)array('sourcecmid' => null, 'gradeitemid' => 665, 'grademin' => 70),
                    (object)array('sourcecmid' => 42, 'gradeitemid' => null, 'requiredcompletion' => 2)
                ), array(
                    (object)array('userfield' => 'email', 'shortname' => null, 'operator' => 'isempty'),
                )));
    }

    /**
     * Test upgrade minmaxgrade step.
     */
    public function test_upgrade_minmaxgrade() {
        global $CFG, $DB;
        require_once($CFG->libdir . '/gradelib.php');
        $initialminmax = $CFG->grade_minmaxtouse;
        $this->resetAfterTest();

        $c1 = $this->getDataGenerator()->create_course();
        $c2 = $this->getDataGenerator()->create_course();
        $c3 = $this->getDataGenerator()->create_course();
        $u1 = $this->getDataGenerator()->create_user();
        $a1 = $this->getDataGenerator()->create_module('assign', array('course' => $c1, 'grade' => 100));
        $a2 = $this->getDataGenerator()->create_module('assign', array('course' => $c2, 'grade' => 100));
        $a3 = $this->getDataGenerator()->create_module('assign', array('course' => $c3, 'grade' => 100));

        $cm1 = get_coursemodule_from_instance('assign', $a1->id);
        $ctx1 = context_module::instance($cm1->id);
        $assign1 = new assign($ctx1, $cm1, $c1);

        $cm2 = get_coursemodule_from_instance('assign', $a2->id);
        $ctx2 = context_module::instance($cm2->id);
        $assign2 = new assign($ctx2, $cm2, $c2);

        $cm3 = get_coursemodule_from_instance('assign', $a3->id);
        $ctx3 = context_module::instance($cm3->id);
        $assign3 = new assign($ctx3, $cm3, $c3);

        // Give a grade to the student.
        $ug = $assign1->get_user_grade($u1->id, true);
        $ug->grade = 10;
        $assign1->update_grade($ug);

        $ug = $assign2->get_user_grade($u1->id, true);
        $ug->grade = 20;
        $assign2->update_grade($ug);

        $ug = $assign3->get_user_grade($u1->id, true);
        $ug->grade = 30;
        $assign3->update_grade($ug);


        // Run the upgrade.
        upgrade_minmaxgrade();

        // Nothing has happened.
        $this->assertFalse($DB->record_exists('config', array('name' => 'noshow_min_max_grades_changed_' . $c1->id)));
        $this->assertSame(false, grade_get_setting($c1->id, 'minmaxtouse', false, true));
        $this->assertFalse($DB->record_exists('grade_items', array('needsupdate' => 1, 'courseid' => $c1->id)));
        $this->assertFalse($DB->record_exists('config', array('name' => 'noshow_min_max_grades_changed_' . $c2->id)));
        $this->assertSame(false, grade_get_setting($c2->id, 'minmaxtouse', false, true));
        $this->assertFalse($DB->record_exists('grade_items', array('needsupdate' => 1, 'courseid' => $c2->id)));
        $this->assertFalse($DB->record_exists('config', array('name' => 'noshow_min_max_grades_changed_' . $c3->id)));
        $this->assertSame(false, grade_get_setting($c3->id, 'minmaxtouse', false, true));
        $this->assertFalse($DB->record_exists('grade_items', array('needsupdate' => 1, 'courseid' => $c3->id)));

        // Create inconsistency in c1 and c2.
        $giparams = array('itemtype' => 'mod', 'itemmodule' => 'assign', 'iteminstance' => $a1->id,
                'courseid' => $c1->id, 'itemnumber' => 0);
        $gi = grade_item::fetch($giparams);
        $gi->grademin = 5;
        $gi->update();

        $giparams = array('itemtype' => 'mod', 'itemmodule' => 'assign', 'iteminstance' => $a2->id,
                'courseid' => $c2->id, 'itemnumber' => 0);
        $gi = grade_item::fetch($giparams);
        $gi->grademax = 50;
        $gi->update();


        // C1 and C2 should be updated, but the course setting should not be set.
        $CFG->grade_minmaxtouse = GRADE_MIN_MAX_FROM_GRADE_GRADE;

        // Run the upgrade.
        upgrade_minmaxgrade();

        // C1 and C2 were partially updated.
        $this->assertTrue($DB->record_exists('config', array('name' => 'noshow_min_max_grades_changed_' . $c1->id)));
        $this->assertSame(false, grade_get_setting($c1->id, 'minmaxtouse', false, true));
        $this->assertTrue($DB->record_exists('grade_items', array('needsupdate' => 1, 'courseid' => $c1->id)));
        $this->assertTrue($DB->record_exists('config', array('name' => 'noshow_min_max_grades_changed_' . $c2->id)));
        $this->assertSame(false, grade_get_setting($c2->id, 'minmaxtouse', false, true));
        $this->assertTrue($DB->record_exists('grade_items', array('needsupdate' => 1, 'courseid' => $c2->id)));

        // Nothing has happened for C3.
        $this->assertFalse($DB->record_exists('config', array('name' => 'noshow_min_max_grades_changed_' . $c3->id)));
        $this->assertSame(false, grade_get_setting($c3->id, 'minmaxtouse', false, true));
        $this->assertFalse($DB->record_exists('grade_items', array('needsupdate' => 1, 'courseid' => $c3->id)));


        // Course setting should not be set on a course that has the setting already.
        $CFG->grade_minmaxtouse = GRADE_MIN_MAX_FROM_GRADE_ITEM;
        grade_set_setting($c1->id, 'minmaxtouse', -1); // Sets different value than constant to check that it remained the same.

        // Run the upgrade.
        upgrade_minmaxgrade();

        // C2 was updated.
        $this->assertSame((string) GRADE_MIN_MAX_FROM_GRADE_GRADE, grade_get_setting($c2->id, 'minmaxtouse', false, true));

        // Nothing has happened for C1.
        $this->assertSame('-1', grade_get_setting($c1->id, 'minmaxtouse', false, true));

        // Nothing has happened for C3.
        $this->assertFalse($DB->record_exists('config', array('name' => 'noshow_min_max_grades_changed_' . $c3->id)));
        $this->assertSame(false, grade_get_setting($c3->id, 'minmaxtouse', false, true));
        $this->assertFalse($DB->record_exists('grade_items', array('needsupdate' => 1, 'courseid' => $c3->id)));


        // Final check, this time we'll unset the default config.
        unset($CFG->grade_minmaxtouse);
        grade_set_setting($c1->id, 'minmaxtouse', null);

        // Run the upgrade.
        upgrade_minmaxgrade();

        // C1 was updated.
        $this->assertSame((string) GRADE_MIN_MAX_FROM_GRADE_GRADE, grade_get_setting($c1->id, 'minmaxtouse', false, true));

        // Nothing has happened for C3.
        $this->assertFalse($DB->record_exists('config', array('name' => 'noshow_min_max_grades_changed_' . $c3->id)));
        $this->assertSame(false, grade_get_setting($c3->id, 'minmaxtouse', false, true));
        $this->assertFalse($DB->record_exists('grade_items', array('needsupdate' => 1, 'courseid' => $c3->id)));

        // Restore value.
        $CFG->grade_minmaxtouse = $initialminmax;
    }

    /**
     * Test upgrade minmaxgrade step.
     */
    public function test_upgrade_mark_grading_configuration() {
        global $CFG, $DB;
        require_once($CFG->libdir . '/gradelib.php');
        $initialminmax = $CFG->grade_minmaxtouse;
        $this->resetAfterTest();

        $c1 = $this->getDataGenerator()->create_course();
        $c2 = $this->getDataGenerator()->create_course();
        $c3 = $this->getDataGenerator()->create_course();
        $u1 = $this->getDataGenerator()->create_user();
        $a1 = $this->getDataGenerator()->create_module('assign', array('course' => $c1, 'grade' => 100));
        $a2 = $this->getDataGenerator()->create_module('assign', array('course' => $c2, 'grade' => 100));
        $a3 = $this->getDataGenerator()->create_module('assign', array('course' => $c3, 'grade' => 100));

        $cm1 = get_coursemodule_from_instance('assign', $a1->id);
        $ctx1 = context_module::instance($cm1->id);
        $assign1 = new assign($ctx1, $cm1, $c1);

        $cm2 = get_coursemodule_from_instance('assign', $a2->id);
        $ctx2 = context_module::instance($cm2->id);
        $assign2 = new assign($ctx2, $cm2, $c2);

        $cm3 = get_coursemodule_from_instance('assign', $a3->id);
        $ctx3 = context_module::instance($cm3->id);
        $assign3 = new assign($ctx3, $cm3, $c3);

        // Give a grade to the student.
        $ug = $assign1->get_user_grade($u1->id, true);
        $ug->grade = 10;
        $assign1->update_grade($ug);

        $ug = $assign2->get_user_grade($u1->id, true);
        $ug->grade = 20;
        $assign2->update_grade($ug);

        $ug = $assign3->get_user_grade($u1->id, true);
        $ug->grade = 30;
        $assign3->update_grade($ug);

        // Create inconsistency in c1 and c2.
        $giparams = array('itemtype' => 'mod', 'itemmodule' => 'assign', 'iteminstance' => $a1->id,
            'courseid' => $c1->id, 'itemnumber' => 0);
        $gi = grade_item::fetch($giparams);
        $gi->grademin = 5;
        $gi->update();

        $giparams = array('itemtype' => 'mod', 'itemmodule' => 'assign', 'iteminstance' => $a2->id,
            'courseid' => $c2->id, 'itemnumber' => 0);
        $gi = grade_item::fetch($giparams);
        $gi->grademax = 50;
        $gi->update();

        // Run the upgrade.
        upgrade_mark_grading_configuration();

        // Nothing has happened.
        $this->assertEquals(GRADE_MIN_MAX_FROM_GRADE_ITEM, grade_get_setting($c1->id, 'minmaxtouse', false, true));
        $this->assertEquals(GRADE_MIN_MAX_FROM_GRADE_ITEM, grade_get_setting($c2->id, 'minmaxtouse', false, true));
        $this->assertEquals(false, grade_get_setting($c3->id, 'minmaxtouse', false, true));


        // Restore value.
        $CFG->grade_minmaxtouse = $initialminmax;
    }
}
