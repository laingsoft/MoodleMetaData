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
 * Library of useful functions
 *
 * @copyright 1999 Martin Dougiamas  http://dougiamas.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @package core_course
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir.'/completionlib.php');
require_once($CFG->libdir.'/filelib.php');
require_once($CFG->dirroot.'/course/format/lib.php');

define('COURSE_MAX_LOGS_PER_PAGE', 1000);       // Records.
define('COURSE_MAX_RECENT_PERIOD', 172800);     // Two days, in seconds.

/**
 * Number of courses to display when summaries are included.
 * @var int
 * @deprecated since 2.4, use $CFG->courseswithsummarieslimit instead.
 */
define('COURSE_MAX_SUMMARIES_PER_PAGE', 10);

// Max courses in log dropdown before switching to optional.
define('COURSE_MAX_COURSES_PER_DROPDOWN', 1000);
// Max users in log dropdown before switching to optional.
define('COURSE_MAX_USERS_PER_DROPDOWN', 1000);
define('FRONTPAGENEWS', '0');
define('FRONTPAGECATEGORYNAMES', '2');
define('FRONTPAGECATEGORYCOMBO', '4');
define('FRONTPAGEENROLLEDCOURSELIST', '5');
define('FRONTPAGEALLCOURSELIST', '6');
define('FRONTPAGECOURSESEARCH', '7');
// Important! Replaced with $CFG->frontpagecourselimit - maximum number of courses displayed on the frontpage.
define('EXCELROWS', 65535);
define('FIRSTUSEDEXCELROW', 3);

define('MOD_CLASS_ACTIVITY', 0);
define('MOD_CLASS_RESOURCE', 1);

function make_log_url($module, $url) {
    switch ($module) {
        case 'course':
            if (strpos($url, 'report/') === 0) {
                // there is only one report type, course reports are deprecated
                $url = "/$url";
                break;
            }
        case 'file':
        case 'login':
        case 'lib':
        case 'admin':
        case 'category':
        case 'mnet course':
            if (strpos($url, '../') === 0) {
                $url = ltrim($url, '.');
            } else {
                $url = "/course/$url";
            }
            break;
        case 'calendar':
            $url = "/calendar/$url";
            break;
        case 'user':
        case 'blog':
            $url = "/$module/$url";
            break;
        case 'upload':
            $url = $url;
            break;
        case 'coursetags':
            $url = '/'.$url;
            break;
        case 'library':
        case '':
            $url = '/';
            break;
        case 'message':
            $url = "/message/$url";
            break;
        case 'notes':
            $url = "/notes/$url";
            break;
        case 'tag':
            $url = "/tag/$url";
            break;
        case 'role':
            $url = '/'.$url;
            break;
        case 'grade':
            $url = "/grade/$url";
            break;
        default:
            $url = "/mod/$module/$url";
            break;
    }

    //now let's sanitise urls - there might be some ugly nasties:-(
    $parts = explode('?', $url);
    $script = array_shift($parts);
    if (strpos($script, 'http') === 0) {
        $script = clean_param($script, PARAM_URL);
    } else {
        $script = clean_param($script, PARAM_PATH);
    }

    $query = '';
    if ($parts) {
        $query = implode('', $parts);
        $query = str_replace('&amp;', '&', $query); // both & and &amp; are stored in db :-|
        $parts = explode('&', $query);
        $eq = urlencode('=');
        foreach ($parts as $key=>$part) {
            $part = urlencode(urldecode($part));
            $part = str_replace($eq, '=', $part);
            $parts[$key] = $part;
        }
        $query = '?'.implode('&amp;', $parts);
    }

    return $script.$query;
}


function build_mnet_logs_array($hostid, $course, $user=0, $date=0, $order="l.time ASC", $limitfrom='', $limitnum='',
                   $modname="", $modid=0, $modaction="", $groupid=0) {
    global $CFG, $DB;

    // It is assumed that $date is the GMT time of midnight for that day,
    // and so the next 86400 seconds worth of logs are printed.

    /// Setup for group handling.

    // TODO: I don't understand group/context/etc. enough to be able to do
    // something interesting with it here
    // What is the context of a remote course?

    /// If the group mode is separate, and this user does not have editing privileges,
    /// then only the user's group can be viewed.
    //if ($course->groupmode == SEPARATEGROUPS and !has_capability('moodle/course:managegroups', context_course::instance($course->id))) {
    //    $groupid = get_current_group($course->id);
    //}
    /// If this course doesn't have groups, no groupid can be specified.
    //else if (!$course->groupmode) {
    //    $groupid = 0;
    //}

    $groupid = 0;

    $joins = array();
    $where = '';

    $qry = "SELECT l.*, u.firstname, u.lastname, u.picture
              FROM {mnet_log} l
               LEFT JOIN {user} u ON l.userid = u.id
              WHERE ";
    $params = array();

    $where .= "l.hostid = :hostid";
    $params['hostid'] = $hostid;

    // TODO: Is 1 really a magic number referring to the sitename?
    if ($course != SITEID || $modid != 0) {
        $where .= " AND l.course=:courseid";
        $params['courseid'] = $course;
    }

    if ($modname) {
        $where .= " AND l.module = :modname";
        $params['modname'] = $modname;
    }

    if ('site_errors' === $modid) {
        $where .= " AND ( l.action='error' OR l.action='infected' )";
    } else if ($modid) {
        //TODO: This assumes that modids are the same across sites... probably
        //not true
        $where .= " AND l.cmid = :modid";
        $params['modid'] = $modid;
    }

    if ($modaction) {
        $firstletter = substr($modaction, 0, 1);
        if ($firstletter == '-') {
            $where .= " AND ".$DB->sql_like('l.action', ':modaction', false, true, true);
            $params['modaction'] = '%'.substr($modaction, 1).'%';
        } else {
            $where .= " AND ".$DB->sql_like('l.action', ':modaction', false);
            $params['modaction'] = '%'.$modaction.'%';
        }
    }

    if ($user) {
        $where .= " AND l.userid = :user";
        $params['user'] = $user;
    }

    if ($date) {
        $enddate = $date + 86400;
        $where .= " AND l.time > :date AND l.time < :enddate";
        $params['date'] = $date;
        $params['enddate'] = $enddate;
    }

    $result = array();
    $result['totalcount'] = $DB->count_records_sql("SELECT COUNT('x') FROM {mnet_log} l WHERE $where", $params);
    if(!empty($result['totalcount'])) {
        $where .= " ORDER BY $order";
        $result['logs'] = $DB->get_records_sql("$qry $where", $params, $limitfrom, $limitnum);
    } else {
        $result['logs'] = array();
    }
    return $result;
}

function build_logs_array($course, $user=0, $date=0, $order="l.time ASC", $limitfrom='', $limitnum='',
                   $modname="", $modid=0, $modaction="", $groupid=0) {
    global $DB, $SESSION, $USER;
    // It is assumed that $date is the GMT time of midnight for that day,
    // and so the next 86400 seconds worth of logs are printed.

    /// Setup for group handling.

    /// If the group mode is separate, and this user does not have editing privileges,
    /// then only the user's group can be viewed.
    if ($course->groupmode == SEPARATEGROUPS and !has_capability('moodle/course:managegroups', context_course::instance($course->id))) {
        if (isset($SESSION->currentgroup[$course->id])) {
            $groupid =  $SESSION->currentgroup[$course->id];
        } else {
            $groupid = groups_get_all_groups($course->id, $USER->id);
            if (is_array($groupid)) {
                $groupid = array_shift(array_keys($groupid));
                $SESSION->currentgroup[$course->id] = $groupid;
            } else {
                $groupid = 0;
            }
        }
    }
    /// If this course doesn't have groups, no groupid can be specified.
    else if (!$course->groupmode) {
        $groupid = 0;
    }

    $joins = array();
    $params = array();

    if ($course->id != SITEID || $modid != 0) {
        $joins[] = "l.course = :courseid";
        $params['courseid'] = $course->id;
    }

    if ($modname) {
        $joins[] = "l.module = :modname";
        $params['modname'] = $modname;
    }

    if ('site_errors' === $modid) {
        $joins[] = "( l.action='error' OR l.action='infected' )";
    } else if ($modid) {
        $joins[] = "l.cmid = :modid";
        $params['modid'] = $modid;
    }

    if ($modaction) {
        $firstletter = substr($modaction, 0, 1);
        if ($firstletter == '-') {
            $joins[] = $DB->sql_like('l.action', ':modaction', false, true, true);
            $params['modaction'] = '%'.substr($modaction, 1).'%';
        } else {
            $joins[] = $DB->sql_like('l.action', ':modaction', false);
            $params['modaction'] = '%'.$modaction.'%';
        }
    }


    /// Getting all members of a group.
    if ($groupid and !$user) {
        if ($gusers = groups_get_members($groupid)) {
            $gusers = array_keys($gusers);
            $joins[] = 'l.userid IN (' . implode(',', $gusers) . ')';
        } else {
            $joins[] = 'l.userid = 0'; // No users in groups, so we want something that will always be false.
        }
    }
    else if ($user) {
        $joins[] = "l.userid = :userid";
        $params['userid'] = $user;
    }

    if ($date) {
        $enddate = $date + 86400;
        $joins[] = "l.time > :date AND l.time < :enddate";
        $params['date'] = $date;
        $params['enddate'] = $enddate;
    }

    $selector = implode(' AND ', $joins);

    $totalcount = 0;  // Initialise
    $result = array();
    $result['logs'] = get_logs($selector, $params, $order, $limitfrom, $limitnum, $totalcount);
    $result['totalcount'] = $totalcount;
    return $result;
}


function print_log($course, $user=0, $date=0, $order="l.time ASC", $page=0, $perpage=100,
                   $url="", $modname="", $modid=0, $modaction="", $groupid=0) {

    global $CFG, $DB, $OUTPUT;

    if (!$logs = build_logs_array($course, $user, $date, $order, $page*$perpage, $perpage,
                       $modname, $modid, $modaction, $groupid)) {
        echo $OUTPUT->notification("No logs found!");
        echo $OUTPUT->footer();
        exit;
    }

    $courses = array();

    if ($course->id == SITEID) {
        $courses[0] = '';
        if ($ccc = get_courses('all', 'c.id ASC', 'c.id,c.shortname')) {
            foreach ($ccc as $cc) {
                $courses[$cc->id] = $cc->shortname;
            }
        }
    } else {
        $courses[$course->id] = $course->shortname;
    }

    $totalcount = $logs['totalcount'];
    $count=0;
    $ldcache = array();
    $tt = getdate(time());
    $today = mktime (0, 0, 0, $tt["mon"], $tt["mday"], $tt["year"]);

    $strftimedatetime = get_string("strftimedatetime");

    echo "<div class=\"info\">\n";
    print_string("displayingrecords", "", $totalcount);
    echo "</div>\n";

    echo $OUTPUT->paging_bar($totalcount, $page, $perpage, "$url&perpage=$perpage");

    $table = new html_table();
    $table->classes = array('logtable','generaltable');
    $table->align = array('right', 'left', 'left');
    $table->head = array(
        get_string('time'),
        get_string('ip_address'),
        get_string('fullnameuser'),
        get_string('action'),
        get_string('info')
    );
    $table->data = array();

    if ($course->id == SITEID) {
        array_unshift($table->align, 'left');
        array_unshift($table->head, get_string('course'));
    }

    // Make sure that the logs array is an array, even it is empty, to avoid warnings from the foreach.
    if (empty($logs['logs'])) {
        $logs['logs'] = array();
    }

    foreach ($logs['logs'] as $log) {

        if (isset($ldcache[$log->module][$log->action])) {
            $ld = $ldcache[$log->module][$log->action];
        } else {
            $ld = $DB->get_record('log_display', array('module'=>$log->module, 'action'=>$log->action));
            $ldcache[$log->module][$log->action] = $ld;
        }
        if ($ld && is_numeric($log->info)) {
            // ugly hack to make sure fullname is shown correctly
            if ($ld->mtable == 'user' && $ld->field == $DB->sql_concat('firstname', "' '" , 'lastname')) {
                $log->info = fullname($DB->get_record($ld->mtable, array('id'=>$log->info)), true);
            } else {
                $log->info = $DB->get_field($ld->mtable, $ld->field, array('id'=>$log->info));
            }
        }

        //Filter log->info
        $log->info = format_string($log->info);

        // If $log->url has been trimmed short by the db size restriction
        // code in add_to_log, keep a note so we don't add a link to a broken url
        $brokenurl=(core_text::strlen($log->url)==100 && core_text::substr($log->url,97)=='...');

        $row = array();
        if ($course->id == SITEID) {
            if (empty($log->course)) {
                $row[] = get_string('site');
            } else {
                $row[] = "<a href=\"{$CFG->wwwroot}/course/view.php?id={$log->course}\">". format_string($courses[$log->course])."</a>";
            }
        }

        $row[] = userdate($log->time, '%a').' '.userdate($log->time, $strftimedatetime);

        $link = new moodle_url("/iplookup/index.php?ip=$log->ip&user=$log->userid");
        $row[] = $OUTPUT->action_link($link, $log->ip, new popup_action('click', $link, 'iplookup', array('height' => 440, 'width' => 700)));

        $row[] = html_writer::link(new moodle_url("/user/view.php?id={$log->userid}&course={$log->course}"), fullname($log, has_capability('moodle/site:viewfullnames', context_course::instance($course->id))));

        $displayaction="$log->module $log->action";
        if ($brokenurl) {
            $row[] = $displayaction;
        } else {
            $link = make_log_url($log->module,$log->url);
            $row[] = $OUTPUT->action_link($link, $displayaction, new popup_action('click', $link, 'fromloglive'), array('height' => 440, 'width' => 700));
        }
        $row[] = $log->info;
        $table->data[] = $row;
    }

    echo html_writer::table($table);
    echo $OUTPUT->paging_bar($totalcount, $page, $perpage, "$url&perpage=$perpage");
}


function print_mnet_log($hostid, $course, $user=0, $date=0, $order="l.time ASC", $page=0, $perpage=100,
                   $url="", $modname="", $modid=0, $modaction="", $groupid=0) {

    global $CFG, $DB, $OUTPUT;

    if (!$logs = build_mnet_logs_array($hostid, $course, $user, $date, $order, $page*$perpage, $perpage,
                       $modname, $modid, $modaction, $groupid)) {
        echo $OUTPUT->notification("No logs found!");
        echo $OUTPUT->footer();
        exit;
    }

    if ($course->id == SITEID) {
        $courses[0] = '';
        if ($ccc = get_courses('all', 'c.id ASC', 'c.id,c.shortname,c.visible')) {
            foreach ($ccc as $cc) {
                $courses[$cc->id] = $cc->shortname;
            }
        }
    }

    $totalcount = $logs['totalcount'];
    $count=0;
    $ldcache = array();
    $tt = getdate(time());
    $today = mktime (0, 0, 0, $tt["mon"], $tt["mday"], $tt["year"]);

    $strftimedatetime = get_string("strftimedatetime");

    echo "<div class=\"info\">\n";
    print_string("displayingrecords", "", $totalcount);
    echo "</div>\n";

    echo $OUTPUT->paging_bar($totalcount, $page, $perpage, "$url&perpage=$perpage");

    echo "<table class=\"logtable\" cellpadding=\"3\" cellspacing=\"0\">\n";
    echo "<tr>";
    if ($course->id == SITEID) {
        echo "<th class=\"c0 header\">".get_string('course')."</th>\n";
    }
    echo "<th class=\"c1 header\">".get_string('time')."</th>\n";
    echo "<th class=\"c2 header\">".get_string('ip_address')."</th>\n";
    echo "<th class=\"c3 header\">".get_string('fullnameuser')."</th>\n";
    echo "<th class=\"c4 header\">".get_string('action')."</th>\n";
    echo "<th class=\"c5 header\">".get_string('info')."</th>\n";
    echo "</tr>\n";

    if (empty($logs['logs'])) {
        echo "</table>\n";
        return;
    }

    $row = 1;
    foreach ($logs['logs'] as $log) {

        $log->info = $log->coursename;
        $row = ($row + 1) % 2;

        if (isset($ldcache[$log->module][$log->action])) {
            $ld = $ldcache[$log->module][$log->action];
        } else {
            $ld = $DB->get_record('log_display', array('module'=>$log->module, 'action'=>$log->action));
            $ldcache[$log->module][$log->action] = $ld;
        }
        if (0 && $ld && !empty($log->info)) {
            // ugly hack to make sure fullname is shown correctly
            if (($ld->mtable == 'user') and ($ld->field == $DB->sql_concat('firstname', "' '" , 'lastname'))) {
                $log->info = fullname($DB->get_record($ld->mtable, array('id'=>$log->info)), true);
            } else {
                $log->info = $DB->get_field($ld->mtable, $ld->field, array('id'=>$log->info));
            }
        }

        //Filter log->info
        $log->info = format_string($log->info);

        echo '<tr class="r'.$row.'">';
        if ($course->id == SITEID) {
            $courseshortname = format_string($courses[$log->course], true, array('context' => context_course::instance(SITEID)));
            echo "<td class=\"r$row c0\" >\n";
            echo "    <a href=\"{$CFG->wwwroot}/course/view.php?id={$log->course}\">".$courseshortname."</a>\n";
            echo "</td>\n";
        }
        echo "<td class=\"r$row c1\" align=\"right\">".userdate($log->time, '%a').
             ' '.userdate($log->time, $strftimedatetime)."</td>\n";
        echo "<td class=\"r$row c2\" >\n";
        $link = new moodle_url("/iplookup/index.php?ip=$log->ip&user=$log->userid");
        echo $OUTPUT->action_link($link, $log->ip, new popup_action('click', $link, 'iplookup', array('height' => 400, 'width' => 700)));
        echo "</td>\n";
        $fullname = fullname($log, has_capability('moodle/site:viewfullnames', context_course::instance($course->id)));
        echo "<td class=\"r$row c3\" >\n";
        echo "    <a href=\"$CFG->wwwroot/user/view.php?id={$log->userid}\">$fullname</a>\n";
        echo "</td>\n";
        echo "<td class=\"r$row c4\">\n";
        echo $log->action .': '.$log->module;
        echo "</td>\n";
        echo "<td class=\"r$row c5\">{$log->info}</td>\n";
        echo "</tr>\n";
    }
    echo "</table>\n";

    echo $OUTPUT->paging_bar($totalcount, $page, $perpage, "$url&perpage=$perpage");
}


function print_log_csv($course, $user, $date, $order='l.time DESC', $modname,
                        $modid, $modaction, $groupid) {
    global $DB, $CFG;

    require_once($CFG->libdir . '/csvlib.class.php');

    $csvexporter = new csv_export_writer('tab');

    $header = array();
    $header[] = get_string('course');
    $header[] = get_string('time');
    $header[] = get_string('ip_address');
    $header[] = get_string('fullnameuser');
    $header[] = get_string('action');
    $header[] = get_string('info');

    if (!$logs = build_logs_array($course, $user, $date, $order, '', '',
                       $modname, $modid, $modaction, $groupid)) {
        return false;
    }

    $courses = array();

    if ($course->id == SITEID) {
        $courses[0] = '';
        if ($ccc = get_courses('all', 'c.id ASC', 'c.id,c.shortname')) {
            foreach ($ccc as $cc) {
                $courses[$cc->id] = $cc->shortname;
            }
        }
    } else {
        $courses[$course->id] = $course->shortname;
    }

    $count=0;
    $ldcache = array();
    $tt = getdate(time());
    $today = mktime (0, 0, 0, $tt["mon"], $tt["mday"], $tt["year"]);

    $strftimedatetime = get_string("strftimedatetime");

    $csvexporter->set_filename('logs', '.txt');
    $title = array(get_string('savedat').userdate(time(), $strftimedatetime));
    $csvexporter->add_data($title);
    $csvexporter->add_data($header);

    if (empty($logs['logs'])) {
        return true;
    }

    foreach ($logs['logs'] as $log) {
        if (isset($ldcache[$log->module][$log->action])) {
            $ld = $ldcache[$log->module][$log->action];
        } else {
            $ld = $DB->get_record('log_display', array('module'=>$log->module, 'action'=>$log->action));
            $ldcache[$log->module][$log->action] = $ld;
        }
        if ($ld && is_numeric($log->info)) {
            // ugly hack to make sure fullname is shown correctly
            if (($ld->mtable == 'user') and ($ld->field ==  $DB->sql_concat('firstname', "' '" , 'lastname'))) {
                $log->info = fullname($DB->get_record($ld->mtable, array('id'=>$log->info)), true);
            } else {
                $log->info = $DB->get_field($ld->mtable, $ld->field, array('id'=>$log->info));
            }
        }

        //Filter log->info
        $log->info = format_string($log->info);
        $log->info = strip_tags(urldecode($log->info));    // Some XSS protection

        $coursecontext = context_course::instance($course->id);
        $firstField = format_string($courses[$log->course], true, array('context' => $coursecontext));
        $fullname = fullname($log, has_capability('moodle/site:viewfullnames', $coursecontext));
        $actionurl = $CFG->wwwroot. make_log_url($log->module,$log->url);
        $row = array($firstField, userdate($log->time, $strftimedatetime), $log->ip, $fullname, $log->module.' '.$log->action.' ('.$actionurl.')', $log->info);
        $csvexporter->add_data($row);
    }
    $csvexporter->download_file();
    return true;
}


function print_log_xls($course, $user, $date, $order='l.time DESC', $modname,
                        $modid, $modaction, $groupid) {

    global $CFG, $DB;

    require_once("$CFG->libdir/excellib.class.php");

    if (!$logs = build_logs_array($course, $user, $date, $order, '', '',
                       $modname, $modid, $modaction, $groupid)) {
        return false;
    }

    $courses = array();

    if ($course->id == SITEID) {
        $courses[0] = '';
        if ($ccc = get_courses('all', 'c.id ASC', 'c.id,c.shortname')) {
            foreach ($ccc as $cc) {
                $courses[$cc->id] = $cc->shortname;
            }
        }
    } else {
        $courses[$course->id] = $course->shortname;
    }

    $count=0;
    $ldcache = array();
    $tt = getdate(time());
    $today = mktime (0, 0, 0, $tt["mon"], $tt["mday"], $tt["year"]);

    $strftimedatetime = get_string("strftimedatetime");

    $nroPages = ceil(count($logs)/(EXCELROWS-FIRSTUSEDEXCELROW+1));
    $filename = 'logs_'.userdate(time(),get_string('backupnameformat', 'langconfig'),99,false);
    $filename .= '.xls';

    $workbook = new MoodleExcelWorkbook('-');
    $workbook->send($filename);

    $worksheet = array();
    $headers = array(get_string('course'), get_string('time'), get_string('ip_address'),
                        get_string('fullnameuser'),    get_string('action'), get_string('info'));

    // Creating worksheets
    for ($wsnumber = 1; $wsnumber <= $nroPages; $wsnumber++) {
        $sheettitle = get_string('logs').' '.$wsnumber.'-'.$nroPages;
        $worksheet[$wsnumber] = $workbook->add_worksheet($sheettitle);
        $worksheet[$wsnumber]->set_column(1, 1, 30);
        $worksheet[$wsnumber]->write_string(0, 0, get_string('savedat').
                                    userdate(time(), $strftimedatetime));
        $col = 0;
        foreach ($headers as $item) {
            $worksheet[$wsnumber]->write(FIRSTUSEDEXCELROW-1,$col,$item,'');
            $col++;
        }
    }

    if (empty($logs['logs'])) {
        $workbook->close();
        return true;
    }

    $formatDate =& $workbook->add_format();
    $formatDate->set_num_format(get_string('log_excel_date_format'));

    $row = FIRSTUSEDEXCELROW;
    $wsnumber = 1;
    $myxls =& $worksheet[$wsnumber];
    foreach ($logs['logs'] as $log) {
        if (isset($ldcache[$log->module][$log->action])) {
            $ld = $ldcache[$log->module][$log->action];
        } else {
            $ld = $DB->get_record('log_display', array('module'=>$log->module, 'action'=>$log->action));
            $ldcache[$log->module][$log->action] = $ld;
        }
        if ($ld && is_numeric($log->info)) {
            // ugly hack to make sure fullname is shown correctly
            if (($ld->mtable == 'user') and ($ld->field == $DB->sql_concat('firstname', "' '" , 'lastname'))) {
                $log->info = fullname($DB->get_record($ld->mtable, array('id'=>$log->info)), true);
            } else {
                $log->info = $DB->get_field($ld->mtable, $ld->field, array('id'=>$log->info));
            }
        }

        // Filter log->info
        $log->info = format_string($log->info);
        $log->info = strip_tags(urldecode($log->info));  // Some XSS protection

        if ($nroPages>1) {
            if ($row > EXCELROWS) {
                $wsnumber++;
                $myxls =& $worksheet[$wsnumber];
                $row = FIRSTUSEDEXCELROW;
            }
        }

        $coursecontext = context_course::instance($course->id);

        $myxls->write($row, 0, format_string($courses[$log->course], true, array('context' => $coursecontext)), '');
        $myxls->write_date($row, 1, $log->time, $formatDate); // write_date() does conversion/timezone support. MDL-14934
        $myxls->write($row, 2, $log->ip, '');
        $fullname = fullname($log, has_capability('moodle/site:viewfullnames', $coursecontext));
        $myxls->write($row, 3, $fullname, '');
        $actionurl = $CFG->wwwroot. make_log_url($log->module,$log->url);
        $myxls->write($row, 4, $log->module.' '.$log->action.' ('.$actionurl.')', '');
        $myxls->write($row, 5, $log->info, '');

        $row++;
    }

    $workbook->close();
    return true;
}

function print_log_ods($course, $user, $date, $order='l.time DESC', $modname,
                        $modid, $modaction, $groupid) {

    global $CFG, $DB;

    require_once("$CFG->libdir/odslib.class.php");

    if (!$logs = build_logs_array($course, $user, $date, $order, '', '',
                       $modname, $modid, $modaction, $groupid)) {
        return false;
    }

    $courses = array();

    if ($course->id == SITEID) {
        $courses[0] = '';
        if ($ccc = get_courses('all', 'c.id ASC', 'c.id,c.shortname')) {
            foreach ($ccc as $cc) {
                $courses[$cc->id] = $cc->shortname;
            }
        }
    } else {
        $courses[$course->id] = $course->shortname;
    }

    $count=0;
    $ldcache = array();
    $tt = getdate(time());
    $today = mktime (0, 0, 0, $tt["mon"], $tt["mday"], $tt["year"]);

    $strftimedatetime = get_string("strftimedatetime");

    $nroPages = ceil(count($logs)/(EXCELROWS-FIRSTUSEDEXCELROW+1));
    $filename = 'logs_'.userdate(time(),get_string('backupnameformat', 'langconfig'),99,false);
    $filename .= '.ods';

    $workbook = new MoodleODSWorkbook('-');
    $workbook->send($filename);

    $worksheet = array();
    $headers = array(get_string('course'), get_string('time'), get_string('ip_address'),
                        get_string('fullnameuser'),    get_string('action'), get_string('info'));

    // Creating worksheets
    for ($wsnumber = 1; $wsnumber <= $nroPages; $wsnumber++) {
        $sheettitle = get_string('logs').' '.$wsnumber.'-'.$nroPages;
        $worksheet[$wsnumber] = $workbook->add_worksheet($sheettitle);
        $worksheet[$wsnumber]->set_column(1, 1, 30);
        $worksheet[$wsnumber]->write_string(0, 0, get_string('savedat').
                                    userdate(time(), $strftimedatetime));
        $col = 0;
        foreach ($headers as $item) {
            $worksheet[$wsnumber]->write(FIRSTUSEDEXCELROW-1,$col,$item,'');
            $col++;
        }
    }

    if (empty($logs['logs'])) {
        $workbook->close();
        return true;
    }

    $formatDate =& $workbook->add_format();
    $formatDate->set_num_format(get_string('log_excel_date_format'));

    $row = FIRSTUSEDEXCELROW;
    $wsnumber = 1;
    $myxls =& $worksheet[$wsnumber];
    foreach ($logs['logs'] as $log) {
        if (isset($ldcache[$log->module][$log->action])) {
            $ld = $ldcache[$log->module][$log->action];
        } else {
            $ld = $DB->get_record('log_display', array('module'=>$log->module, 'action'=>$log->action));
            $ldcache[$log->module][$log->action] = $ld;
        }
        if ($ld && is_numeric($log->info)) {
            // ugly hack to make sure fullname is shown correctly
            if (($ld->mtable == 'user') and ($ld->field == $DB->sql_concat('firstname', "' '" , 'lastname'))) {
                $log->info = fullname($DB->get_record($ld->mtable, array('id'=>$log->info)), true);
            } else {
                $log->info = $DB->get_field($ld->mtable, $ld->field, array('id'=>$log->info));
            }
        }

        // Filter log->info
        $log->info = format_string($log->info);
        $log->info = strip_tags(urldecode($log->info));  // Some XSS protection

        if ($nroPages>1) {
            if ($row > EXCELROWS) {
                $wsnumber++;
                $myxls =& $worksheet[$wsnumber];
                $row = FIRSTUSEDEXCELROW;
            }
        }

        $coursecontext = context_course::instance($course->id);

        $myxls->write_string($row, 0, format_string($courses[$log->course], true, array('context' => $coursecontext)));
        $myxls->write_date($row, 1, $log->time);
        $myxls->write_string($row, 2, $log->ip);
        $fullname = fullname($log, has_capability('moodle/site:viewfullnames', $coursecontext));
        $myxls->write_string($row, 3, $fullname);
        $actionurl = $CFG->wwwroot. make_log_url($log->module,$log->url);
        $myxls->write_string($row, 4, $log->module.' '.$log->action.' ('.$actionurl.')');
        $myxls->write_string($row, 5, $log->info);

        $row++;
    }

    $workbook->close();
    return true;
}

/**
 * Checks the integrity of the course data.
 *
 * In summary - compares course_sections.sequence and course_modules.section.
 *
 * More detailed, checks that:
 * - course_sections.sequence contains each module id not more than once in the course
 * - for each moduleid from course_sections.sequence the field course_modules.section
 *   refers to the same section id (this means course_sections.sequence is more
 *   important if they are different)
 * - ($fullcheck only) each module in the course is present in one of
 *   course_sections.sequence
 * - ($fullcheck only) removes non-existing course modules from section sequences
 *
 * If there are any mismatches, the changes are made and records are updated in DB.
 *
 * Course cache is NOT rebuilt if there are any errors!
 *
 * This function is used each time when course cache is being rebuilt with $fullcheck = false
 * and in CLI script admin/cli/fix_course_sequence.php with $fullcheck = true
 *
 * @param int $courseid id of the course
 * @param array $rawmods result of funciton {@link get_course_mods()} - containst
 *     the list of enabled course modules in the course. Retrieved from DB if not specified.
 *     Argument ignored in cashe of $fullcheck, the list is retrieved form DB anyway.
 * @param array $sections records from course_sections table for this course.
 *     Retrieved from DB if not specified
 * @param bool $fullcheck Will add orphaned modules to their sections and remove non-existing
 *     course modules from sequences. Only to be used in site maintenance mode when we are
 *     sure that another user is not in the middle of the process of moving/removing a module.
 * @param bool $checkonly Only performs the check without updating DB, outputs all errors as debug messages.
 * @return array array of messages with found problems. Empty output means everything is ok
 */
function course_integrity_check($courseid, $rawmods = null, $sections = null, $fullcheck = false, $checkonly = false) {
    global $DB;
    $messages = array();
    if ($sections === null) {
        $sections = $DB->get_records('course_sections', array('course' => $courseid), 'section', 'id,section,sequence');
    }
    if ($fullcheck) {
        // Retrieve all records from course_modules regardless of module type visibility.
        $rawmods = $DB->get_records('course_modules', array('course' => $courseid), 'id', 'id,section');
    }
    if ($rawmods === null) {
        $rawmods = get_course_mods($courseid);
    }
    if (!$fullcheck && (empty($sections) || empty($rawmods))) {
        // If either of the arrays is empty, no modules are displayed anyway.
        return true;
    }
    $debuggingprefix = 'Failed integrity check for course ['.$courseid.']. ';

    // First make sure that each module id appears in section sequences only once.
    // If it appears in several section sequences the last section wins.
    // If it appears twice in one section sequence, the first occurence wins.
    $modsection = array();
    foreach ($sections as $sectionid => $section) {
        $sections[$sectionid]->newsequence = $section->sequence;
        if (!empty($section->sequence)) {
            $sequence = explode(",", $section->sequence);
            $sequenceunique = array_unique($sequence);
            if (count($sequenceunique) != count($sequence)) {
                // Some course module id appears in this section sequence more than once.
                ksort($sequenceunique); // Preserve initial order of modules.
                $sequence = array_values($sequenceunique);
                $sections[$sectionid]->newsequence = join(',', $sequence);
                $messages[] = $debuggingprefix.'Sequence for course section ['.
                        $sectionid.'] is "'.$sections[$sectionid]->sequence.'", must be "'.$sections[$sectionid]->newsequence.'"';
            }
            foreach ($sequence as $cmid) {
                if (array_key_exists($cmid, $modsection) && isset($rawmods[$cmid])) {
                    // Some course module id appears to be in more than one section's sequences.
                    $wrongsectionid = $modsection[$cmid];
                    $sections[$wrongsectionid]->newsequence = trim(preg_replace("/,$cmid,/", ',', ','.$sections[$wrongsectionid]->newsequence. ','), ',');
                    $messages[] = $debuggingprefix.'Course module ['.$cmid.'] must be removed from sequence of section ['.
                            $wrongsectionid.'] because it is also present in sequence of section ['.$sectionid.']';
                }
                $modsection[$cmid] = $sectionid;
            }
        }
    }

    // Add orphaned modules to their sections if they exist or to section 0 otherwise.
    if ($fullcheck) {
        foreach ($rawmods as $cmid => $mod) {
            if (!isset($modsection[$cmid])) {
                // This is a module that is not mentioned in course_section.sequence at all.
                // Add it to the section $mod->section or to the last available section.
                if ($mod->section && isset($sections[$mod->section])) {
                    $modsection[$cmid] = $mod->section;
                } else {
                    $firstsection = reset($sections);
                    $modsection[$cmid] = $firstsection->id;
                }
                $sections[$modsection[$cmid]]->newsequence = trim($sections[$modsection[$cmid]]->newsequence.','.$cmid, ',');
                $messages[] = $debuggingprefix.'Course module ['.$cmid.'] is missing from sequence of section ['.
                        $modsection[$cmid].']';
            }
        }
        foreach ($modsection as $cmid => $sectionid) {
            if (!isset($rawmods[$cmid])) {
                // Section $sectionid refers to module id that does not exist.
                $sections[$sectionid]->newsequence = trim(preg_replace("/,$cmid,/", ',', ','.$sections[$sectionid]->newsequence.','), ',');
                $messages[] = $debuggingprefix.'Course module ['.$cmid.
                        '] does not exist but is present in the sequence of section ['.$sectionid.']';
            }
        }
    }

    // Update changed sections.
    if (!$checkonly && !empty($messages)) {
        foreach ($sections as $sectionid => $section) {
            if ($section->newsequence !== $section->sequence) {
                $DB->update_record('course_sections', array('id' => $sectionid, 'sequence' => $section->newsequence));
            }
        }
    }

    // Now make sure that all modules point to the correct sections.
    foreach ($rawmods as $cmid => $mod) {
        if (isset($modsection[$cmid]) && $modsection[$cmid] != $mod->section) {
            if (!$checkonly) {
                $DB->update_record('course_modules', array('id' => $cmid, 'section' => $modsection[$cmid]));
            }
            $messages[] = $debuggingprefix.'Course module ['.$cmid.
                    '] points to section ['.$mod->section.'] instead of ['.$modsection[$cmid].']';
        }
    }

    return $messages;
}

/**
 * For a given course, returns an array of course activity objects
 * Each item in the array contains he following properties:
 */
function get_array_of_activities($courseid) {
//  cm - course module id
//  mod - name of the module (eg forum)
//  section - the number of the section (eg week or topic)
//  name - the name of the instance
//  visible - is the instance visible or not
//  groupingid - grouping id
//  extra - contains extra string to include in any link
    global $CFG, $DB;
    if(!empty($CFG->enableavailability)) {
        require_once($CFG->libdir.'/conditionlib.php');
    }

    $course = $DB->get_record('course', array('id'=>$courseid));

    if (empty($course)) {
        throw new moodle_exception('courseidnotfound');
    }

    $mod = array();

    $rawmods = get_course_mods($courseid);
    if (empty($rawmods)) {
        return $mod; // always return array
    }

    if ($sections = $DB->get_records('course_sections', array('course' => $courseid), 'section ASC', 'id,section,sequence')) {
        // First check and correct obvious mismatches between course_sections.sequence and course_modules.section.
        if ($errormessages = course_integrity_check($courseid, $rawmods, $sections)) {
            debugging(join('<br>', $errormessages));
            $rawmods = get_course_mods($courseid);
            $sections = $DB->get_records('course_sections', array('course' => $courseid), 'section ASC', 'id,section,sequence');
        }
        // Build array of activities.
       foreach ($sections as $section) {
           if (!empty($section->sequence)) {
               $sequence = explode(",", $section->sequence);
               foreach ($sequence as $seq) {
                   if (empty($rawmods[$seq])) {
                       continue;
                   }
                   $mod[$seq] = new stdClass();
                   $mod[$seq]->id               = $rawmods[$seq]->instance;
                   $mod[$seq]->cm               = $rawmods[$seq]->id;
                   $mod[$seq]->mod              = $rawmods[$seq]->modname;

                    // Oh dear. Inconsistent names left here for backward compatibility.
                   $mod[$seq]->section          = $section->section;
                   $mod[$seq]->sectionid        = $rawmods[$seq]->section;

                   $mod[$seq]->module           = $rawmods[$seq]->module;
                   $mod[$seq]->added            = $rawmods[$seq]->added;
                   $mod[$seq]->score            = $rawmods[$seq]->score;
                   $mod[$seq]->idnumber         = $rawmods[$seq]->idnumber;
                   $mod[$seq]->visible          = $rawmods[$seq]->visible;
                   $mod[$seq]->visibleold       = $rawmods[$seq]->visibleold;
                   $mod[$seq]->groupmode        = $rawmods[$seq]->groupmode;
                   $mod[$seq]->groupingid       = $rawmods[$seq]->groupingid;
                   $mod[$seq]->indent           = $rawmods[$seq]->indent;
                   $mod[$seq]->completion       = $rawmods[$seq]->completion;
                   $mod[$seq]->extra            = "";
                   $mod[$seq]->completiongradeitemnumber =
                           $rawmods[$seq]->completiongradeitemnumber;
                   $mod[$seq]->completionview   = $rawmods[$seq]->completionview;
                   $mod[$seq]->completionexpected = $rawmods[$seq]->completionexpected;
                   $mod[$seq]->showdescription  = $rawmods[$seq]->showdescription;
                   $mod[$seq]->availability = $rawmods[$seq]->availability;

                   $modname = $mod[$seq]->mod;
                   $functionname = $modname."_get_coursemodule_info";

                   if (!file_exists("$CFG->dirroot/mod/$modname/lib.php")) {
                       continue;
                   }

                   include_once("$CFG->dirroot/mod/$modname/lib.php");

                   if ($hasfunction = function_exists($functionname)) {
                       if ($info = $functionname($rawmods[$seq])) {
                           if (!empty($info->icon)) {
                               $mod[$seq]->icon = $info->icon;
                           }
                           if (!empty($info->iconcomponent)) {
                               $mod[$seq]->iconcomponent = $info->iconcomponent;
                           }
                           if (!empty($info->name)) {
                               $mod[$seq]->name = $info->name;
                           }
                           if ($info instanceof cached_cm_info) {
                               // When using cached_cm_info you can include three new fields
                               // that aren't available for legacy code
                               if (!empty($info->content)) {
                                   $mod[$seq]->content = $info->content;
                               }
                               if (!empty($info->extraclasses)) {
                                   $mod[$seq]->extraclasses = $info->extraclasses;
                               }
                               if (!empty($info->iconurl)) {
                                   // Convert URL to string as it's easier to store. Also serialized object contains \0 byte and can not be written to Postgres DB.
                                   $url = new moodle_url($info->iconurl);
                                   $mod[$seq]->iconurl = $url->out(false);
                               }
                               if (!empty($info->onclick)) {
                                   $mod[$seq]->onclick = $info->onclick;
                               }
                               if (!empty($info->customdata)) {
                                   $mod[$seq]->customdata = $info->customdata;
                               }
                           } else {
                               // When using a stdclass, the (horrible) deprecated ->extra field
                               // is available for BC
                               if (!empty($info->extra)) {
                                   $mod[$seq]->extra = $info->extra;
                               }
                           }
                       }
                   }
                   // When there is no modname_get_coursemodule_info function,
                   // but showdescriptions is enabled, then we use the 'intro'
                   // and 'introformat' fields in the module table
                   if (!$hasfunction && $rawmods[$seq]->showdescription) {
                       if ($modvalues = $DB->get_record($rawmods[$seq]->modname,
                               array('id' => $rawmods[$seq]->instance), 'name, intro, introformat')) {
                           // Set content from intro and introformat. Filters are disabled
                           // because we  filter it with format_text at display time
                           $mod[$seq]->content = format_module_intro($rawmods[$seq]->modname,
                                   $modvalues, $rawmods[$seq]->id, false);

                           // To save making another query just below, put name in here
                           $mod[$seq]->name = $modvalues->name;
                       }
                   }
                   if (!isset($mod[$seq]->name)) {
                       $mod[$seq]->name = $DB->get_field($rawmods[$seq]->modname, "name", array("id"=>$rawmods[$seq]->instance));
                   }

                    // Minimise the database size by unsetting default options when they are
                    // 'empty'. This list corresponds to code in the cm_info constructor.
                    foreach (array('idnumber', 'groupmode', 'groupingid',
                            'indent', 'completion', 'extra', 'extraclasses', 'iconurl', 'onclick', 'content',
                            'icon', 'iconcomponent', 'customdata', 'availability', 'completionview',
                            'completionexpected', 'score', 'showdescription') as $property) {
                       if (property_exists($mod[$seq], $property) &&
                               empty($mod[$seq]->{$property})) {
                           unset($mod[$seq]->{$property});
                       }
                   }
                   // Special case: this value is usually set to null, but may be 0
                   if (property_exists($mod[$seq], 'completiongradeitemnumber') &&
                           is_null($mod[$seq]->completiongradeitemnumber)) {
                       unset($mod[$seq]->completiongradeitemnumber);
                   }
               }
            }
        }
    }
    return $mod;
}

/**
 * Returns the localised human-readable names of all used modules
 *
 * @param bool $plural if true returns the plural forms of the names
 * @return array where key is the module name (component name without 'mod_') and
 *     the value is the human-readable string. Array sorted alphabetically by value
 */
function get_module_types_names($plural = false) {
    static $modnames = null;
    global $DB, $CFG, $COURSE;
    if ($modnames === null) {
        $modnames = array(0 => array(), 1 => array());

        /*********** local_contextadmin Modification ************
        First Author:  Trevor Jones
        Initial Date:  April 8, 2013
        Last Author:   Trevor Jones
        Date Changed:  May 5, 2014

        Extra Comments: These modifications to the core code give an extra layer of administrative usability.
        Category level show/hide for modules are checked here.

         ************/
        if(file_exists($CFG->dirroot . '/local/contextadmin/locallib.php') and isset($COURSE->category)) {
            require_once($CFG->dirroot . '/local/contextadmin/locallib.php');
            $allmods = get_context_modules($COURSE->category);
        }
        else {
            $allmods = $DB->get_records('modules');
        }

        if ($allmods) {
        /*********** End local_contextadmin Modification ********/
            foreach ($allmods as $mod) {
                if (file_exists("$CFG->dirroot/mod/$mod->name/lib.php") && $mod->visible) {
                    $modnames[0][$mod->name] = get_string("modulename", "$mod->name");
                    $modnames[1][$mod->name] = get_string("modulenameplural", "$mod->name");
                }
            }
            core_collator::asort($modnames[0]);
            core_collator::asort($modnames[1]);
        }
    }
    return $modnames[(int)$plural];
}

/**
 * Set highlighted section. Only one section can be highlighted at the time.
 *
 * @param int $courseid course id
 * @param int $marker highlight section with this number, 0 means remove higlightin
 * @return void
 */
function course_set_marker($courseid, $marker) {
    global $DB;
    $DB->set_field("course", "marker", $marker, array('id' => $courseid));
    format_base::reset_course_cache($courseid);
}

/**
 * For a given course section, marks it visible or hidden,
 * and does the same for every activity in that section
 *
 * @param int $courseid course id
 * @param int $sectionnumber The section number to adjust
 * @param int $visibility The new visibility
 * @return array A list of resources which were hidden in the section
 */
function set_section_visible($courseid, $sectionnumber, $visibility) {
    global $DB;

    $resourcestotoggle = array();
    if ($section = $DB->get_record("course_sections", array("course"=>$courseid, "section"=>$sectionnumber))) {
        $DB->set_field("course_sections", "visible", "$visibility", array("id"=>$section->id));

        $event = \core\event\course_section_updated::create(array(
            'context' => context_course::instance($courseid),
            'objectid' => $section->id,
            'other' => array(
                'sectionnum' => $sectionnumber
            )
        ));
        $event->add_record_snapshot('course_sections', $section);
        $event->trigger();

        if (!empty($section->sequence)) {
            $modules = explode(",", $section->sequence);
            foreach ($modules as $moduleid) {
                if ($cm = get_coursemodule_from_id(null, $moduleid, $courseid)) {
                    if ($visibility) {
                        // As we unhide the section, we use the previously saved visibility stored in visibleold.
                        set_coursemodule_visible($moduleid, $cm->visibleold);
                    } else {
                        // We hide the section, so we hide the module but we store the original state in visibleold.
                        set_coursemodule_visible($moduleid, 0);
                        $DB->set_field('course_modules', 'visibleold', $cm->visible, array('id' => $moduleid));
                    }
                    \core\event\course_module_updated::create_from_cm($cm)->trigger();
                }
            }
        }
        rebuild_course_cache($courseid, true);

        // Determine which modules are visible for AJAX update
        if (!empty($modules)) {
            list($insql, $params) = $DB->get_in_or_equal($modules);
            $select = 'id ' . $insql . ' AND visible = ?';
            array_push($params, $visibility);
            if (!$visibility) {
                $select .= ' AND visibleold = 1';
            }
            $resourcestotoggle = $DB->get_fieldset_select('course_modules', 'id', $select, $params);
        }
    }
    return $resourcestotoggle;
}

/**
 * Retrieve all metadata for the requested modules
 *
 * @param object $course The Course
 * @param array $modnames An array containing the list of modules and their
 * names
 * @param int $sectionreturn The section to return to
 * @return array A list of stdClass objects containing metadata about each
 * module
 */
function get_module_metadata($course, $modnames, $sectionreturn = null) {
    global $CFG, $OUTPUT;

    // get_module_metadata will be called once per section on the page and courses may show
    // different modules to one another
    static $modlist = array();
    if (!isset($modlist[$course->id])) {
        $modlist[$course->id] = array();
    }

    $return = array();
    $urlbase = new moodle_url('/course/mod.php', array('id' => $course->id, 'sesskey' => sesskey()));
    if ($sectionreturn !== null) {
        $urlbase->param('sr', $sectionreturn);
    }
    foreach($modnames as $modname => $modnamestr) {
        if (!course_allowed_module($course, $modname)) {
            continue;
        }
        if (isset($modlist[$course->id][$modname])) {
            // This module is already cached
            $return[$modname] = $modlist[$course->id][$modname];
            continue;
        }

        // Include the module lib
        $libfile = "$CFG->dirroot/mod/$modname/lib.php";
        if (!file_exists($libfile)) {
            continue;
        }
        include_once($libfile);

        // NOTE: this is legacy stuff, module subtypes are very strongly discouraged!!
        $gettypesfunc =  $modname.'_get_types';
        $types = MOD_SUBTYPE_NO_CHILDREN;
        if (function_exists($gettypesfunc)) {
            $types = $gettypesfunc();
        }
        if ($types !== MOD_SUBTYPE_NO_CHILDREN) {
            if (is_array($types) && count($types) > 0) {
                $group = new stdClass();
                $group->name = $modname;
                $group->icon = $OUTPUT->pix_icon('icon', '', $modname, array('class' => 'icon'));
                foreach($types as $type) {
                    if ($type->typestr === '--') {
                        continue;
                    }
                    if (strpos($type->typestr, '--') === 0) {
                        $group->title = str_replace('--', '', $type->typestr);
                        continue;
                    }
                    // Set the Sub Type metadata
                    $subtype = new stdClass();
                    $subtype->title = $type->typestr;
                    $subtype->type = str_replace('&amp;', '&', $type->type);
                    $subtype->name = preg_replace('/.*type=/', '', $subtype->type);
                    $subtype->archetype = $type->modclass;

                    // The group archetype should match the subtype archetypes and all subtypes
                    // should have the same archetype
                    $group->archetype = $subtype->archetype;

                    if (!empty($type->help)) {
                        $subtype->help = $type->help;
                    } else if (get_string_manager()->string_exists('help' . $subtype->name, $modname)) {
                        $subtype->help = get_string('help' . $subtype->name, $modname);
                    }
                    $subtype->link = new moodle_url($urlbase, array('add' => $modname, 'type' => $subtype->name));
                    $group->types[] = $subtype;
                }
                $modlist[$course->id][$modname] = $group;
            }
        } else {
            $module = new stdClass();
            $module->title = $modnamestr;
            $module->name = $modname;
            $module->link = new moodle_url($urlbase, array('add' => $modname));
            $module->icon = $OUTPUT->pix_icon('icon', '', $module->name, array('class' => 'icon'));
            $sm = get_string_manager();
            if ($sm->string_exists('modulename_help', $modname)) {
                $module->help = get_string('modulename_help', $modname);
                if ($sm->string_exists('modulename_link', $modname)) {  // Link to further info in Moodle docs
                    $link = get_string('modulename_link', $modname);
                    $linktext = get_string('morehelp');
                    $module->help .= html_writer::tag('div', $OUTPUT->doc_link($link, $linktext, true), array('class' => 'helpdoclink'));
                }
            }
            $module->archetype = plugin_supports('mod', $modname, FEATURE_MOD_ARCHETYPE, MOD_ARCHETYPE_OTHER);
            $modlist[$course->id][$modname] = $module;
        }
        if (isset($modlist[$course->id][$modname])) {
            $return[$modname] = $modlist[$course->id][$modname];
        } else {
            debugging("Invalid module metadata configuration for {$modname}");
        }
    }

    return $return;
}

/**
 * Return the course category context for the category with id $categoryid, except
 * that if $categoryid is 0, return the system context.
 *
 * @param integer $categoryid a category id or 0.
 * @return context the corresponding context
 */
function get_category_or_system_context($categoryid) {
    if ($categoryid) {
        return context_coursecat::instance($categoryid, IGNORE_MISSING);
    } else {
        return context_system::instance();
    }
}

/**
 * Returns full course categories trees to be used in html_writer::select()
 *
 * Calls {@link coursecat::make_categories_list()} to build the tree and
 * adds whitespace to denote nesting
 *
 * @return array array mapping coursecat id to the display name
 */
function make_categories_options() {
    global $CFG;
    require_once($CFG->libdir. '/coursecatlib.php');
    $cats = coursecat::make_categories_list('', 0, ' / ');
    foreach ($cats as $key => $value) {
        // Prefix the value with the number of spaces equal to category depth (number of separators in the value).
        $cats[$key] = str_repeat('&nbsp;', substr_count($value, ' / ')). $value;
    }
    return $cats;
}

/**
 * Print the buttons relating to course requests.
 *
 * @param object $context current page context.
 */
function print_course_request_buttons($context) {
    global $CFG, $DB, $OUTPUT;
    if (empty($CFG->enablecourserequests)) {
        return;
    }
    if (!has_capability('moodle/course:create', $context) && has_capability('moodle/course:request', $context)) {
    /// Print a button to request a new course
        echo $OUTPUT->single_button(new moodle_url('/course/request.php'), get_string('requestcourse'), 'get');
    }
    /// Print a button to manage pending requests
    if ($context->contextlevel == CONTEXT_SYSTEM && has_capability('moodle/site:approvecourse', $context)) {
        $disabled = !$DB->record_exists('course_request', array());
        echo $OUTPUT->single_button(new moodle_url('/course/pending.php'), get_string('coursespending'), 'get', array('disabled' => $disabled));
    }
}

/**
 * Does the user have permission to edit things in this category?
 *
 * @param integer $categoryid The id of the category we are showing, or 0 for system context.
 * @return boolean has_any_capability(array(...), ...); in the appropriate context.
 */
function can_edit_in_category($categoryid = 0) {
    $context = get_category_or_system_context($categoryid);
    return has_any_capability(array('moodle/category:manage', 'moodle/course:create'), $context);
}

/// MODULE FUNCTIONS /////////////////////////////////////////////////////////////////

function add_course_module($mod) {
    global $DB;

    $mod->added = time();
    unset($mod->id);

    $cmid = $DB->insert_record("course_modules", $mod);
    rebuild_course_cache($mod->course, true);
    return $cmid;
}

/**
 * Creates missing course section(s) and rebuilds course cache
 *
 * @param int|stdClass $courseorid course id or course object
 * @param int|array $sections list of relative section numbers to create
 * @return bool if there were any sections created
 */
function course_create_sections_if_missing($courseorid, $sections) {
    global $DB;
    if (!is_array($sections)) {
        $sections = array($sections);
    }
    $existing = array_keys(get_fast_modinfo($courseorid)->get_section_info_all());
    if (is_object($courseorid)) {
        $courseorid = $courseorid->id;
    }
    $coursechanged = false;
    foreach ($sections as $sectionnum) {
        if (!in_array($sectionnum, $existing)) {
            $cw = new stdClass();
            $cw->course   = $courseorid;
            $cw->section  = $sectionnum;
            $cw->summary  = '';
            $cw->summaryformat = FORMAT_HTML;
            $cw->sequence = '';
            $id = $DB->insert_record("course_sections", $cw);
            $coursechanged = true;
        }
    }
    if ($coursechanged) {
        rebuild_course_cache($courseorid, true);
    }
    return $coursechanged;
}

/**
 * Adds an existing module to the section
 *
 * Updates both tables {course_sections} and {course_modules}
 *
 * Note: This function does not use modinfo PROVIDED that the section you are
 * adding the module to already exists. If the section does not exist, it will
 * build modinfo if necessary and create the section.
 *
 * @param int|stdClass $courseorid course id or course object
 * @param int $cmid id of the module already existing in course_modules table
 * @param int $sectionnum relative number of the section (field course_sections.section)
 *     If section does not exist it will be created
 * @param int|stdClass $beforemod id or object with field id corresponding to the module
 *     before which the module needs to be included. Null for inserting in the
 *     end of the section
 * @return int The course_sections ID where the module is inserted
 */
function course_add_cm_to_section($courseorid, $cmid, $sectionnum, $beforemod = null) {
    global $DB, $COURSE;
    if (is_object($beforemod)) {
        $beforemod = $beforemod->id;
    }
    if (is_object($courseorid)) {
        $courseid = $courseorid->id;
    } else {
        $courseid = $courseorid;
    }
    // Do not try to use modinfo here, there is no guarantee it is valid!
    $section = $DB->get_record('course_sections',
            array('course' => $courseid, 'section' => $sectionnum), '*', IGNORE_MISSING);
    if (!$section) {
        // This function call requires modinfo.
        course_create_sections_if_missing($courseorid, $sectionnum);
        $section = $DB->get_record('course_sections',
                array('course' => $courseid, 'section' => $sectionnum), '*', MUST_EXIST);
    }

    $modarray = explode(",", trim($section->sequence));
    if (empty($section->sequence)) {
        $newsequence = "$cmid";
    } else if ($beforemod && ($key = array_keys($modarray, $beforemod))) {
        $insertarray = array($cmid, $beforemod);
        array_splice($modarray, $key[0], 1, $insertarray);
        $newsequence = implode(",", $modarray);
    } else {
        $newsequence = "$section->sequence,$cmid";
    }
    $DB->set_field("course_sections", "sequence", $newsequence, array("id" => $section->id));
    $DB->set_field('course_modules', 'section', $section->id, array('id' => $cmid));
    if (is_object($courseorid)) {
        rebuild_course_cache($courseorid->id, true);
    } else {
        rebuild_course_cache($courseorid, true);
    }
    return $section->id;     // Return course_sections ID that was used.
}

/**
 * Change the group mode of a course module.
 *
 * Note: Do not forget to trigger the event \core\event\course_module_updated as it needs
 * to be triggered manually, refer to {@link \core\event\course_module_updated::create_from_cm()}.
 *
 * @param int $id course module ID.
 * @param int $groupmode the new groupmode value.
 * @return bool True if the $groupmode was updated.
 */
function set_coursemodule_groupmode($id, $groupmode) {
    global $DB;
    $cm = $DB->get_record('course_modules', array('id' => $id), 'id,course,groupmode', MUST_EXIST);
    if ($cm->groupmode != $groupmode) {
        $DB->set_field('course_modules', 'groupmode', $groupmode, array('id' => $cm->id));
        rebuild_course_cache($cm->course, true);
    }
    return ($cm->groupmode != $groupmode);
}

function set_coursemodule_idnumber($id, $idnumber) {
    global $DB;
    $cm = $DB->get_record('course_modules', array('id' => $id), 'id,course,idnumber', MUST_EXIST);
    if ($cm->idnumber != $idnumber) {
        $DB->set_field('course_modules', 'idnumber', $idnumber, array('id' => $cm->id));
        rebuild_course_cache($cm->course, true);
    }
    return ($cm->idnumber != $idnumber);
}

/**
 * Set the visibility of a module and inherent properties.
 *
 * Note: Do not forget to trigger the event \core\event\course_module_updated as it needs
 * to be triggered manually, refer to {@link \core\event\course_module_updated::create_from_cm()}.
 *
 * From 2.4 the parameter $prevstateoverrides has been removed, the logic it triggered
 * has been moved to {@link set_section_visible()} which was the only place from which
 * the parameter was used.
 *
 * @param int $id of the module
 * @param int $visible state of the module
 * @return bool false when the module was not found, true otherwise
 */
function set_coursemodule_visible($id, $visible) {
    global $DB, $CFG;
    require_once($CFG->libdir.'/gradelib.php');
    require_once($CFG->dirroot.'/calendar/lib.php');

    // Trigger developer's attention when using the previously removed argument.
    if (func_num_args() > 2) {
        debugging('Wrong number of arguments passed to set_coursemodule_visible(), $prevstateoverrides
            has been removed.', DEBUG_DEVELOPER);
    }

    if (!$cm = $DB->get_record('course_modules', array('id'=>$id))) {
        return false;
    }

    // Create events and propagate visibility to associated grade items if the value has changed.
    // Only do this if it's changed to avoid accidently overwriting manual showing/hiding of student grades.
    if ($cm->visible == $visible) {
        return true;
    }

    if (!$modulename = $DB->get_field('modules', 'name', array('id'=>$cm->module))) {
        return false;
    }
    if ($events = $DB->get_records('event', array('instance'=>$cm->instance, 'modulename'=>$modulename))) {
        foreach($events as $event) {
            if ($visible) {
                $event = new calendar_event($event);
                $event->toggle_visibility(true);
            } else {
                $event = new calendar_event($event);
                $event->toggle_visibility(false);
            }
        }
    }

    // Updating visible and visibleold to keep them in sync. Only changing a section visibility will
    // affect visibleold to allow for an original visibility restore. See set_section_visible().
    $cminfo = new stdClass();
    $cminfo->id = $id;
    $cminfo->visible = $visible;
    $cminfo->visibleold = $visible;
    $DB->update_record('course_modules', $cminfo);

    // Hide the associated grade items so the teacher doesn't also have to go to the gradebook and hide them there.
    // Note that this must be done after updating the row in course_modules, in case
    // the modules grade_item_update function needs to access $cm->visible.
    if (plugin_supports('mod', $modulename, FEATURE_CONTROLS_GRADE_VISIBILITY) &&
            component_callback_exists('mod_' . $modulename, 'grade_item_update')) {
        $instance = $DB->get_record($modulename, array('id' => $cm->instance), '*', MUST_EXIST);
        component_callback('mod_' . $modulename, 'grade_item_update', array($instance));
    } else {
        $grade_items = grade_item::fetch_all(array('itemtype'=>'mod', 'itemmodule'=>$modulename, 'iteminstance'=>$cm->instance, 'courseid'=>$cm->course));
        if ($grade_items) {
            foreach ($grade_items as $grade_item) {
                $grade_item->set_hidden(!$visible);
            }
        }
    }

    rebuild_course_cache($cm->course, true);
    return true;
}

/**
 * This function will handles the whole deletion process of a module. This includes calling
 * the modules delete_instance function, deleting files, events, grades, conditional data,
 * the data in the course_module and course_sections table and adding a module deletion
 * event to the DB.
 *
 * @param int $cmid the course module id
 * @since Moodle 2.5
 */
function course_delete_module($cmid) {
    global $CFG, $DB;

    require_once($CFG->libdir.'/gradelib.php');
    require_once($CFG->dirroot.'/blog/lib.php');
    require_once($CFG->dirroot.'/calendar/lib.php');
    require_once($CFG->dirroot . '/tag/lib.php');

    // Get the course module.
    if (!$cm = $DB->get_record('course_modules', array('id' => $cmid))) {
        return true;
    }

    // Get the module context.
    $modcontext = context_module::instance($cm->id);

    // Get the course module name.
    $modulename = $DB->get_field('modules', 'name', array('id' => $cm->module), MUST_EXIST);

    // Get the file location of the delete_instance function for this module.
    $modlib = "$CFG->dirroot/mod/$modulename/lib.php";

    // Include the file required to call the delete_instance function for this module.
    if (file_exists($modlib)) {
        require_once($modlib);
    } else {
        throw new moodle_exception('cannotdeletemodulemissinglib', '', '', null,
            "Cannot delete this module as the file mod/$modulename/lib.php is missing.");
    }

    $deleteinstancefunction = $modulename . '_delete_instance';

    // Ensure the delete_instance function exists for this module.
    if (!function_exists($deleteinstancefunction)) {
        throw new moodle_exception('cannotdeletemodulemissingfunc', '', '', null,
            "Cannot delete this module as the function {$modulename}_delete_instance is missing in mod/$modulename/lib.php.");
    }

    // Call the delete_instance function, if it returns false throw an exception.
    if (!$deleteinstancefunction($cm->instance)) {
        throw new moodle_exception('cannotdeletemoduleinstance', '', '', null,
            "Cannot delete the module $modulename (instance).");
    }

    // Remove all module files in case modules forget to do that.
    $fs = get_file_storage();
    $fs->delete_area_files($modcontext->id);

    // Delete events from calendar.
    if ($events = $DB->get_records('event', array('instance' => $cm->instance, 'modulename' => $modulename))) {
        foreach($events as $event) {
            $calendarevent = calendar_event::load($event->id);
            $calendarevent->delete();
        }
    }

    // Delete grade items, outcome items and grades attached to modules.
    if ($grade_items = grade_item::fetch_all(array('itemtype' => 'mod', 'itemmodule' => $modulename,
                                                   'iteminstance' => $cm->instance, 'courseid' => $cm->course))) {
        foreach ($grade_items as $grade_item) {
            $grade_item->delete('moddelete');
        }
    }

    // Delete completion and availability data; it is better to do this even if the
    // features are not turned on, in case they were turned on previously (these will be
    // very quick on an empty table).
    $DB->delete_records('course_modules_completion', array('coursemoduleid' => $cm->id));
    $DB->delete_records('course_completion_criteria', array('moduleinstance' => $cm->id,
                                                            'criteriatype' => COMPLETION_CRITERIA_TYPE_ACTIVITY));

    // Delete all tag instances associated with the instance of this module.
    tag_delete_instances('mod_' . $modulename, $modcontext->id);

    // Delete the context.
    context_helper::delete_instance(CONTEXT_MODULE, $cm->id);

    // Delete the module from the course_modules table.
    $DB->delete_records('course_modules', array('id' => $cm->id));

    // Delete module from that section.
    if (!delete_mod_from_section($cm->id, $cm->section)) {
        throw new moodle_exception('cannotdeletemodulefromsection', '', '', null,
            "Cannot delete the module $modulename (instance) from section.");
    }

    // Trigger event for course module delete action.
    $event = \core\event\course_module_deleted::create(array(
        'courseid' => $cm->course,
        'context'  => $modcontext,
        'objectid' => $cm->id,
        'other'    => array(
            'modulename' => $modulename,
            'instanceid'   => $cm->instance,
        )
    ));
    $event->add_record_snapshot('course_modules', $cm);
    $event->trigger();
    rebuild_course_cache($cm->course, true);
}

function delete_mod_from_section($modid, $sectionid) {
    global $DB;

    if ($section = $DB->get_record("course_sections", array("id"=>$sectionid)) ) {

        $modarray = explode(",", $section->sequence);

        if ($key = array_keys ($modarray, $modid)) {
            array_splice($modarray, $key[0], 1);
            $newsequence = implode(",", $modarray);
            $DB->set_field("course_sections", "sequence", $newsequence, array("id"=>$section->id));
            rebuild_course_cache($section->course, true);
            return true;
        } else {
            return false;
        }

    }
    return false;
}

/**
 * Moves a section within a course, from a position to another.
 * Be very careful: $section and $destination refer to section number,
 * not id!.
 *
 * @param object $course
 * @param int $section Section number (not id!!!)
 * @param int $destination
 * @return boolean Result
 */
function move_section_to($course, $section, $destination) {
/// Moves a whole course section up and down within the course
    global $USER, $DB;

    if (!$destination && $destination != 0) {
        return true;
    }

    // compartibility with course formats using field 'numsections'
    $courseformatoptions = course_get_format($course)->get_format_options();
    if ((array_key_exists('numsections', $courseformatoptions) &&
            ($destination > $courseformatoptions['numsections'])) || ($destination < 1)) {
        return false;
    }

    // Get all sections for this course and re-order them (2 of them should now share the same section number)
    if (!$sections = $DB->get_records_menu('course_sections', array('course' => $course->id),
            'section ASC, id ASC', 'id, section')) {
        return false;
    }

    $movedsections = reorder_sections($sections, $section, $destination);

    // Update all sections. Do this in 2 steps to avoid breaking database
    // uniqueness constraint
    $transaction = $DB->start_delegated_transaction();
    foreach ($movedsections as $id => $position) {
        if ($sections[$id] !== $position) {
            $DB->set_field('course_sections', 'section', -$position, array('id' => $id));
        }
    }
    foreach ($movedsections as $id => $position) {
        if ($sections[$id] !== $position) {
            $DB->set_field('course_sections', 'section', $position, array('id' => $id));
        }
    }

    // If we move the highlighted section itself, then just highlight the destination.
    // Adjust the higlighted section location if we move something over it either direction.
    if ($section == $course->marker) {
        course_set_marker($course->id, $destination);
    } elseif ($section > $course->marker && $course->marker >= $destination) {
        course_set_marker($course->id, $course->marker+1);
    } elseif ($section < $course->marker && $course->marker <= $destination) {
        course_set_marker($course->id, $course->marker-1);
    }

    $transaction->allow_commit();
    rebuild_course_cache($course->id, true);
    return true;
}

/**
 * Reordering algorithm for course sections. Given an array of section->section indexed by section->id,
 * an original position number and a target position number, rebuilds the array so that the
 * move is made without any duplication of section positions.
 * Note: The target_position is the position AFTER WHICH the moved section will be inserted. If you want to
 * insert a section before the first one, you must give 0 as the target (section 0 can never be moved).
 *
 * @param array $sections
 * @param int $origin_position
 * @param int $target_position
 * @return array
 */
function reorder_sections($sections, $origin_position, $target_position) {
    if (!is_array($sections)) {
        return false;
    }

    // We can't move section position 0
    if ($origin_position < 1) {
        echo "We can't move section position 0";
        return false;
    }

    // Locate origin section in sections array
    if (!$origin_key = array_search($origin_position, $sections)) {
        echo "searched position not in sections array";
        return false; // searched position not in sections array
    }

    // Extract origin section
    $origin_section = $sections[$origin_key];
    unset($sections[$origin_key]);

    // Find offset of target position (stupid PHP's array_splice requires offset instead of key index!)
    $found = false;
    $append_array = array();
    foreach ($sections as $id => $position) {
        if ($found) {
            $append_array[$id] = $position;
            unset($sections[$id]);
        }
        if ($position == $target_position) {
            if ($target_position < $origin_position) {
                $append_array[$id] = $position;
                unset($sections[$id]);
            }
            $found = true;
        }
    }

    // Append moved section
    $sections[$origin_key] = $origin_section;

    // Append rest of array (if applicable)
    if (!empty($append_array)) {
        foreach ($append_array as $id => $position) {
            $sections[$id] = $position;
        }
    }

    // Renumber positions
    $position = 0;
    foreach ($sections as $id => $p) {
        $sections[$id] = $position;
        $position++;
    }

    return $sections;

}

/**
 * Move the module object $mod to the specified $section
 * If $beforemod exists then that is the module
 * before which $modid should be inserted
 *
 * @param stdClass|cm_info $mod
 * @param stdClass|section_info $section
 * @param int|stdClass $beforemod id or object with field id corresponding to the module
 *     before which the module needs to be included. Null for inserting in the
 *     end of the section
 * @return int new value for module visibility (0 or 1)
 */
function moveto_module($mod, $section, $beforemod=NULL) {
    global $OUTPUT, $DB;

    // Current module visibility state - return value of this function.
    $modvisible = $mod->visible;

    // Remove original module from original section.
    if (! delete_mod_from_section($mod->id, $mod->section)) {
        echo $OUTPUT->notification("Could not delete module from existing section");
    }

    // If moving to a hidden section then hide module.
    if ($mod->section != $section->id) {
        if (!$section->visible && $mod->visible) {
            // Module was visible but must become hidden after moving to hidden section.
            $modvisible = 0;
            set_coursemodule_visible($mod->id, 0);
            // Set visibleold to 1 so module will be visible when section is made visible.
            $DB->set_field('course_modules', 'visibleold', 1, array('id' => $mod->id));
        }
        if ($section->visible && !$mod->visible) {
            // Hidden module was moved to the visible section, restore the module visibility from visibleold.
            set_coursemodule_visible($mod->id, $mod->visibleold);
            $modvisible = $mod->visibleold;
        }
    }

    // Add the module into the new section.
    course_add_cm_to_section($section->course, $mod->id, $section->section, $beforemod);
    return $modvisible;
}

/**
 * Returns the list of all editing actions that current user can perform on the module
 *
 * @param cm_info $mod The module to produce editing buttons for
 * @param int $indent The current indenting (default -1 means no move left-right actions)
 * @param int $sr The section to link back to (used for creating the links)
 * @return array array of action_link or pix_icon objects
 */
function course_get_cm_edit_actions(cm_info $mod, $indent = -1, $sr = null) {
    global $COURSE, $SITE;

    static $str;

    $coursecontext = context_course::instance($mod->course);
    $modcontext = context_module::instance($mod->id);

    $editcaps = array('moodle/course:manageactivities', 'moodle/course:activityvisibility', 'moodle/role:assign');
    $dupecaps = array('moodle/backup:backuptargetimport', 'moodle/restore:restoretargetimport');

    // No permission to edit anything.
    if (!has_any_capability($editcaps, $modcontext) and !has_all_capabilities($dupecaps, $coursecontext)) {
        return array();
    }

    $hasmanageactivities = has_capability('moodle/course:manageactivities', $modcontext);

    if (!isset($str)) {
        $str = get_strings(array('delete', 'move', 'moveright', 'moveleft',
            'editsettings', 'duplicate', 'hide', 'show'), 'moodle');
        $str->assign         = get_string('assignroles', 'role');
        $str->groupsnone     = get_string('clicktochangeinbrackets', 'moodle', get_string("groupsnone"));
        $str->groupsseparate = get_string('clicktochangeinbrackets', 'moodle', get_string("groupsseparate"));
        $str->groupsvisible  = get_string('clicktochangeinbrackets', 'moodle', get_string("groupsvisible"));
    }

    $baseurl = new moodle_url('/course/mod.php', array('sesskey' => sesskey()));

    if ($sr !== null) {
        $baseurl->param('sr', $sr);
    }
    $actions = array();

    // Update.
    if ($hasmanageactivities) {
        $actions['update'] = new action_menu_link_secondary(
            new moodle_url($baseurl, array('update' => $mod->id)),
            new pix_icon('t/edit', $str->editsettings, 'moodle', array('class' => 'iconsmall', 'title' => '')),
            $str->editsettings,
            array('class' => 'editing_update', 'data-action' => 'update')
        );
    }

    // Indent.
    if ($hasmanageactivities && $indent >= 0) {
        $indentlimits = new stdClass();
        $indentlimits->min = 0;
        $indentlimits->max = 16;
        if (right_to_left()) {   // Exchange arrows on RTL
            $rightarrow = 't/left';
            $leftarrow  = 't/right';
        } else {
            $rightarrow = 't/right';
            $leftarrow  = 't/left';
        }

        if ($indent >= $indentlimits->max) {
            $enabledclass = 'hidden';
        } else {
            $enabledclass = '';
        }
        $actions['moveright'] = new action_menu_link_secondary(
            new moodle_url($baseurl, array('id' => $mod->id, 'indent' => '1')),
            new pix_icon($rightarrow, $str->moveright, 'moodle', array('class' => 'iconsmall', 'title' => '')),
            $str->moveright,
            array('class' => 'editing_moveright ' . $enabledclass, 'data-action' => 'moveright', 'data-keepopen' => true)
        );

        if ($indent <= $indentlimits->min) {
            $enabledclass = 'hidden';
        } else {
            $enabledclass = '';
        }
        $actions['moveleft'] = new action_menu_link_secondary(
            new moodle_url($baseurl, array('id' => $mod->id, 'indent' => '-1')),
            new pix_icon($leftarrow, $str->moveleft, 'moodle', array('class' => 'iconsmall', 'title' => '')),
            $str->moveleft,
            array('class' => 'editing_moveleft ' . $enabledclass, 'data-action' => 'moveleft', 'data-keepopen' => true)
        );

    }

    // Hide/Show.
    if (has_capability('moodle/course:activityvisibility', $modcontext)) {
        if ($mod->visible) {
            $actions['hide'] = new action_menu_link_secondary(
                new moodle_url($baseurl, array('hide' => $mod->id)),
                new pix_icon('t/hide', $str->hide, 'moodle', array('class' => 'iconsmall', 'title' => '')),
                $str->hide,
                array('class' => 'editing_hide', 'data-action' => 'hide')
            );
        } else {
            $actions['show'] = new action_menu_link_secondary(
                new moodle_url($baseurl, array('show' => $mod->id)),
                new pix_icon('t/show', $str->show, 'moodle', array('class' => 'iconsmall', 'title' => '')),
                $str->show,
                array('class' => 'editing_show', 'data-action' => 'show')
            );
        }
    }

    // Duplicate (require both target import caps to be able to duplicate and backup2 support, see modduplicate.php)
    // Note that restoring on front page is never allowed.
    if ($mod->course != SITEID && has_all_capabilities($dupecaps, $coursecontext) &&
            plugin_supports('mod', $mod->modname, FEATURE_BACKUP_MOODLE2)) {
        $actions['duplicate'] = new action_menu_link_secondary(
            new moodle_url($baseurl, array('duplicate' => $mod->id)),
            new pix_icon('t/copy', $str->duplicate, 'moodle', array('class' => 'iconsmall', 'title' => '')),
            $str->duplicate,
            array('class' => 'editing_duplicate', 'data-action' => 'duplicate', 'data-sr' => $sr)
        );
    }

    // Groupmode.
    if ($hasmanageactivities && !$mod->coursegroupmodeforce) {
        if (plugin_supports('mod', $mod->modname, FEATURE_GROUPS, 0)) {
            if ($mod->effectivegroupmode == SEPARATEGROUPS) {
                $nextgroupmode = VISIBLEGROUPS;
                $grouptitle = $str->groupsseparate;
                $actionname = 'groupsseparate';
                $groupimage = 'i/groups';
            } else if ($mod->effectivegroupmode == VISIBLEGROUPS) {
                $nextgroupmode = NOGROUPS;
                $grouptitle = $str->groupsvisible;
                $actionname = 'groupsvisible';
                $groupimage = 'i/groupv';
            } else {
                $nextgroupmode = SEPARATEGROUPS;
                $grouptitle = $str->groupsnone;
                $actionname = 'groupsnone';
                $groupimage = 'i/groupn';
            }

            $actions[$actionname] = new action_menu_link_primary(
                new moodle_url($baseurl, array('id' => $mod->id, 'groupmode' => $nextgroupmode)),
                new pix_icon($groupimage, null, 'moodle', array('class' => 'iconsmall')),
                $grouptitle,
                array('class' => 'editing_'. $actionname, 'data-action' => $actionname, 'data-nextgroupmode' => $nextgroupmode, 'aria-live' => 'assertive')
            );
        } else {
            $actions['nogroupsupport'] = new action_menu_filler();
        }
    }

    // Assign.
    if (has_capability('moodle/role:assign', $modcontext)){
        $actions['assign'] = new action_menu_link_secondary(
            new moodle_url('/admin/roles/assign.php', array('contextid' => $modcontext->id)),
            new pix_icon('t/assignroles', $str->assign, 'moodle', array('class' => 'iconsmall', 'title' => '')),
            $str->assign,
            array('class' => 'editing_assign', 'data-action' => 'assignroles')
        );
    }

    // Delete.
    if ($hasmanageactivities) {
        $actions['delete'] = new action_menu_link_secondary(
            new moodle_url($baseurl, array('delete' => $mod->id)),
            new pix_icon('t/delete', $str->delete, 'moodle', array('class' => 'iconsmall', 'title' => '')),
            $str->delete,
            array('class' => 'editing_delete', 'data-action' => 'delete')
        );
    }

    return $actions;
}

/**
 * Returns the rename action.
 *
 * @param cm_info $mod The module to produce editing buttons for
 * @param int $sr The section to link back to (used for creating the links)
 * @return The markup for the rename action, or an empty string if not available.
 */
function course_get_cm_rename_action(cm_info $mod, $sr = null) {
    global $COURSE, $OUTPUT;

    static $str;
    static $baseurl;

    $modcontext = context_module::instance($mod->id);
    $hasmanageactivities = has_capability('moodle/course:manageactivities', $modcontext);

    if (!isset($str)) {
        $str = get_strings(array('edittitle'));
    }

    if (!isset($baseurl)) {
        $baseurl = new moodle_url('/course/mod.php', array('sesskey' => sesskey()));
    }

    if ($sr !== null) {
        $baseurl->param('sr', $sr);
    }

    // AJAX edit title.
    if ($mod->has_view() && $hasmanageactivities && course_ajax_enabled($COURSE) &&
                (($mod->course == $COURSE->id) || ($mod->course == SITEID))) {
        // we will not display link if we are on some other-course page (where we should not see this module anyway)
        return html_writer::span(
            html_writer::link(
                new moodle_url($baseurl, array('update' => $mod->id)),
                $OUTPUT->pix_icon('t/editstring', '', 'moodle', array('class' => 'iconsmall visibleifjs', 'title' => '')),
                array(
                    'class' => 'editing_title',
                    'data-action' => 'edittitle',
                    'title' => $str->edittitle,
                )
            )
        );
    }
    return '';
}

/**
 * Returns the move action.
 *
 * @param cm_info $mod The module to produce a move button for
 * @param int $sr The section to link back to (used for creating the links)
 * @return The markup for the move action, or an empty string if not available.
 */
function course_get_cm_move(cm_info $mod, $sr = null) {
    global $OUTPUT;

    static $str;
    static $baseurl;

    $modcontext = context_module::instance($mod->id);
    $hasmanageactivities = has_capability('moodle/course:manageactivities', $modcontext);

    if (!isset($str)) {
        $str = get_strings(array('move'));
    }

    if (!isset($baseurl)) {
        $baseurl = new moodle_url('/course/mod.php', array('sesskey' => sesskey()));

        if ($sr !== null) {
            $baseurl->param('sr', $sr);
        }
    }

    if ($hasmanageactivities) {
        $pixicon = 'i/dragdrop';

        if (!course_ajax_enabled($mod->get_course())) {
            // Override for course frontpage until we get drag/drop working there.
            $pixicon = 't/move';
        }

        return html_writer::link(
            new moodle_url($baseurl, array('copy' => $mod->id)),
            $OUTPUT->pix_icon($pixicon, $str->move, 'moodle', array('class' => 'iconsmall', 'title' => '')),
            array('class' => 'editing_move', 'data-action' => 'move')
        );
    }
    return '';
}

/**
 * given a course object with shortname & fullname, this function will
 * truncate the the number of chars allowed and add ... if it was too long
 */
function course_format_name ($course,$max=100) {

    $context = context_course::instance($course->id);
    $shortname = format_string($course->shortname, true, array('context' => $context));
    $fullname = format_string($course->fullname, true, array('context' => context_course::instance($course->id)));
    $str = $shortname.': '. $fullname;
    if (core_text::strlen($str) <= $max) {
        return $str;
    }
    else {
        return core_text::substr($str,0,$max-3).'...';
    }
}

/**
 * Is the user allowed to add this type of module to this course?
 * @param object $course the course settings. Only $course->id is used.
 * @param string $modname the module name. E.g. 'forum' or 'quiz'.
 * @return bool whether the current user is allowed to add this type of module to this course.
 */
function course_allowed_module($course, $modname) {
    if (is_numeric($modname)) {
        throw new coding_exception('Function course_allowed_module no longer
                supports numeric module ids. Please update your code to pass the module name.');
    }

    $capability = 'mod/' . $modname . ':addinstance';
    if (!get_capability_info($capability)) {
        // Debug warning that the capability does not exist, but no more than once per page.
        static $warned = array();
        $archetype = plugin_supports('mod', $modname, FEATURE_MOD_ARCHETYPE, MOD_ARCHETYPE_OTHER);
        if (!isset($warned[$modname]) && $archetype !== MOD_ARCHETYPE_SYSTEM) {
            debugging('The module ' . $modname . ' does not define the standard capability ' .
                    $capability , DEBUG_DEVELOPER);
            $warned[$modname] = 1;
        }

        // If the capability does not exist, the module can always be added.
        return true;
    }

    $coursecontext = context_course::instance($course->id);
    return has_capability($capability, $coursecontext);
}

/**
 * Efficiently moves many courses around while maintaining
 * sortorder in order.
 *
 * @param array $courseids is an array of course ids
 * @param int $categoryid
 * @return bool success
 */
function move_courses($courseids, $categoryid) {
    global $DB;

    if (empty($courseids)) {
        // Nothing to do.
        return false;
    }

    if (!$category = $DB->get_record('course_categories', array('id' => $categoryid))) {
        return false;
    }

    $courseids = array_reverse($courseids);
    $newparent = context_coursecat::instance($category->id);
    $i = 1;

    list($where, $params) = $DB->get_in_or_equal($courseids);
    $dbcourses = $DB->get_records_select('course', 'id ' . $where, $params, '', 'id, category, shortname, fullname');
    foreach ($dbcourses as $dbcourse) {
        $course = new stdClass();
        $course->id = $dbcourse->id;
        $course->category  = $category->id;
        $course->sortorder = $category->sortorder + MAX_COURSES_IN_CATEGORY - $i++;
        if ($category->visible == 0) {
            // Hide the course when moving into hidden category, do not update the visibleold flag - we want to get
            // to previous state if somebody unhides the category.
            $course->visible = 0;
        }

        $DB->update_record('course', $course);

        // Update context, so it can be passed to event.
        $context = context_course::instance($course->id);
        $context->update_moved($newparent);

        // Trigger a course updated event.
        $event = \core\event\course_updated::create(array(
            'objectid' => $course->id,
            'context' => context_course::instance($course->id),
            'other' => array('shortname' => $dbcourse->shortname,
                             'fullname' => $dbcourse->fullname)
        ));
        $event->set_legacy_logdata(array($course->id, 'course', 'move', 'edit.php?id=' . $course->id, $course->id));
        $event->trigger();
    }
    fix_course_sortorder();
    cache_helper::purge_by_event('changesincourse');

    return true;
}

/**
 * Returns the display name of the given section that the course prefers
 *
 * Implementation of this function is provided by course format
 * @see format_base::get_section_name()
 *
 * @param int|stdClass $courseorid The course to get the section name for (object or just course id)
 * @param int|stdClass $section Section object from database or just field course_sections.section
 * @return string Display name that the course format prefers, e.g. "Week 2"
 */
function get_section_name($courseorid, $section) {
    return course_get_format($courseorid)->get_section_name($section);
}

/**
 * Tells if current course format uses sections
 *
 * @param string $format Course format ID e.g. 'weeks' $course->format
 * @return bool
 */
function course_format_uses_sections($format) {
    $course = new stdClass();
    $course->format = $format;
    return course_get_format($course)->uses_sections();
}

/**
 * Returns the information about the ajax support in the given source format
 *
 * The returned object's property (boolean)capable indicates that
 * the course format supports Moodle course ajax features.
 *
 * @param string $format
 * @return stdClass
 */
function course_format_ajax_support($format) {
    $course = new stdClass();
    $course->format = $format;
    return course_get_format($course)->supports_ajax();
}

/**
 * Can the current user delete this course?
 * Course creators have exception,
 * 1 day after the creation they can sill delete the course.
 * @param int $courseid
 * @return boolean
 */
function can_delete_course($courseid) {
    global $USER;

    $context = context_course::instance($courseid);

    if (has_capability('moodle/course:delete', $context)) {
        return true;
    }

    // hack: now try to find out if creator created this course recently (1 day)
    if (!has_capability('moodle/course:create', $context)) {
        return false;
    }

    $since = time() - 60*60*24;
    $course = get_course($courseid);

    if ($course->timecreated < $since) {
        return false; // Return if the course was not created in last 24 hours.
    }

    $logmanger = get_log_manager();
    $readers = $logmanger->get_readers('\core\log\sql_select_reader');
    $reader = reset($readers);

    if (empty($reader)) {
        return false; // No log reader found.
    }

    // A proper reader.
    $select = "userid = :userid AND courseid = :courseid AND eventname = :eventname AND timecreated > :since";
    $params = array('userid' => $USER->id, 'since' => $since, 'courseid' => $course->id, 'eventname' => '\core\event\course_created');

    return (bool)$reader->get_events_select_count($select, $params);
}

/**
 * Save the Your name for 'Some role' strings.
 *
 * @param integer $courseid the id of this course.
 * @param array $data the data that came from the course settings form.
 */
function save_local_role_names($courseid, $data) {
    global $DB;
    $context = context_course::instance($courseid);

    foreach ($data as $fieldname => $value) {
        if (strpos($fieldname, 'role_') !== 0) {
            continue;
        }
        list($ignored, $roleid) = explode('_', $fieldname);

        // make up our mind whether we want to delete, update or insert
        if (!$value) {
            $DB->delete_records('role_names', array('contextid' => $context->id, 'roleid' => $roleid));

        } else if ($rolename = $DB->get_record('role_names', array('contextid' => $context->id, 'roleid' => $roleid))) {
            $rolename->name = $value;
            $DB->update_record('role_names', $rolename);

        } else {
            $rolename = new stdClass;
            $rolename->contextid = $context->id;
            $rolename->roleid = $roleid;
            $rolename->name = $value;
            $DB->insert_record('role_names', $rolename);
        }
    }
}

/**
 * Returns options to use in course overviewfiles filemanager
 *
 * @param null|stdClass|course_in_list|int $course either object that has 'id' property or just the course id;
 *     may be empty if course does not exist yet (course create form)
 * @return array|null array of options such as maxfiles, maxbytes, accepted_types, etc.
 *     or null if overviewfiles are disabled
 */
function course_overviewfiles_options($course) {
    global $CFG;
    if (empty($CFG->courseoverviewfileslimit)) {
        return null;
    }
    $accepted_types = preg_split('/\s*,\s*/', trim($CFG->courseoverviewfilesext), -1, PREG_SPLIT_NO_EMPTY);
    if (in_array('*', $accepted_types) || empty($accepted_types)) {
        $accepted_types = '*';
    } else {
        // Since config for $CFG->courseoverviewfilesext is a text box, human factor must be considered.
        // Make sure extensions are prefixed with dot unless they are valid typegroups
        foreach ($accepted_types as $i => $type) {
            if (substr($type, 0, 1) !== '.') {
                require_once($CFG->libdir. '/filelib.php');
                if (!count(file_get_typegroup('extension', $type))) {
                    // It does not start with dot and is not a valid typegroup, this is most likely extension.
                    $accepted_types[$i] = '.'. $type;
                    $corrected = true;
                }
            }
        }
        if (!empty($corrected)) {
            set_config('courseoverviewfilesext', join(',', $accepted_types));
        }
    }
    $options = array(
        'maxfiles' => $CFG->courseoverviewfileslimit,
        'maxbytes' => $CFG->maxbytes,
        'subdirs' => 0,
        'accepted_types' => $accepted_types
    );
    if (!empty($course->id)) {
        $options['context'] = context_course::instance($course->id);
    } else if (is_int($course) && $course > 0) {
        $options['context'] = context_course::instance($course);
    }
    return $options;
}

/**
 * Create a course and either return a $course object
 *
 * Please note this functions does not verify any access control,
 * the calling code is responsible for all validation (usually it is the form definition).
 *
 * @param array $editoroptions course description editor options
 * @param object $data  - all the data needed for an entry in the 'course' table
 * @return object new course instance
 */
function create_course($data, $editoroptions = NULL) {
    global $DB;

    //check the categoryid - must be given for all new courses
    $category = $DB->get_record('course_categories', array('id'=>$data->category), '*', MUST_EXIST);

    // Check if the shortname already exists.
    if (!empty($data->shortname)) {
        if ($DB->record_exists('course', array('shortname' => $data->shortname))) {
            throw new moodle_exception('shortnametaken', '', '', $data->shortname);
        }
    }

    // Check if the idnumber already exists.
    if (!empty($data->idnumber)) {
        if ($DB->record_exists('course', array('idnumber' => $data->idnumber))) {
            throw new moodle_exception('courseidnumbertaken', '', '', $data->idnumber);
        }
    }

    // Check if timecreated is given.
    $data->timecreated  = !empty($data->timecreated) ? $data->timecreated : time();
    $data->timemodified = $data->timecreated;

    // place at beginning of any category
    $data->sortorder = 0;

    if ($editoroptions) {
        // summary text is updated later, we need context to store the files first
        $data->summary = '';
        $data->summary_format = FORMAT_HTML;
    }

    if (!isset($data->visible)) {
        // data not from form, add missing visibility info
        $data->visible = $category->visible;
    }
    $data->visibleold = $data->visible;

    $newcourseid = $DB->insert_record('course', $data);
    $context = context_course::instance($newcourseid, MUST_EXIST);

    if ($editoroptions) {
        // Save the files used in the summary editor and store
        $data = file_postupdate_standard_editor($data, 'summary', $editoroptions, $context, 'course', 'summary', 0);
        $DB->set_field('course', 'summary', $data->summary, array('id'=>$newcourseid));
        $DB->set_field('course', 'summaryformat', $data->summary_format, array('id'=>$newcourseid));
    }
    if ($overviewfilesoptions = course_overviewfiles_options($newcourseid)) {
        // Save the course overviewfiles
        $data = file_postupdate_standard_filemanager($data, 'overviewfiles', $overviewfilesoptions, $context, 'course', 'overviewfiles', 0);
    }

    // update course format options
    course_get_format($newcourseid)->update_course_format_options($data);

    $course = course_get_format($newcourseid)->get_course();

    // Setup the blocks
    blocks_add_default_course_blocks($course);

    // Create a default section.
    course_create_sections_if_missing($course, 0);

    fix_course_sortorder();
    // purge appropriate caches in case fix_course_sortorder() did not change anything
    cache_helper::purge_by_event('changesincourse');

    // new context created - better mark it as dirty
    $context->mark_dirty();

    // Save any custom role names.
    save_local_role_names($course->id, (array)$data);

    // set up enrolments
    enrol_course_updated(true, $course, $data);

    // Trigger a course created event.
    $event = \core\event\course_created::create(array(
        'objectid' => $course->id,
        'context' => context_course::instance($course->id),
        'other' => array('shortname' => $course->shortname,
                         'fullname' => $course->fullname)
    ));
    $event->trigger();

    return $course;
}

/**
 * Update a course.
 *
 * Please note this functions does not verify any access control,
 * the calling code is responsible for all validation (usually it is the form definition).
 *
 * @param object $data  - all the data needed for an entry in the 'course' table
 * @param array $editoroptions course description editor options
 * @return void
 */
function update_course($data, $editoroptions = NULL) {
    global $DB;

    $data->timemodified = time();

    $oldcourse = course_get_format($data->id)->get_course();
    $context   = context_course::instance($oldcourse->id);

    if ($editoroptions) {
        $data = file_postupdate_standard_editor($data, 'summary', $editoroptions, $context, 'course', 'summary', 0);
    }
    if ($overviewfilesoptions = course_overviewfiles_options($data->id)) {
        $data = file_postupdate_standard_filemanager($data, 'overviewfiles', $overviewfilesoptions, $context, 'course', 'overviewfiles', 0);
    }

    // Check we don't have a duplicate shortname.
    if (!empty($data->shortname) && $oldcourse->shortname != $data->shortname) {
        if ($DB->record_exists_sql('SELECT id from {course} WHERE shortname = ? AND id <> ?', array($data->shortname, $data->id))) {
            throw new moodle_exception('shortnametaken', '', '', $data->shortname);
        }
    }

    // Check we don't have a duplicate idnumber.
    if (!empty($data->idnumber) && $oldcourse->idnumber != $data->idnumber) {
        if ($DB->record_exists_sql('SELECT id from {course} WHERE idnumber = ? AND id <> ?', array($data->idnumber, $data->id))) {
            throw new moodle_exception('courseidnumbertaken', '', '', $data->idnumber);
        }
    }

    if (!isset($data->category) or empty($data->category)) {
        // prevent nulls and 0 in category field
        unset($data->category);
    }
    $changesincoursecat = $movecat = (isset($data->category) and $oldcourse->category != $data->category);

    if (!isset($data->visible)) {
        // data not from form, add missing visibility info
        $data->visible = $oldcourse->visible;
    }

    if ($data->visible != $oldcourse->visible) {
        // reset the visibleold flag when manually hiding/unhiding course
        $data->visibleold = $data->visible;
        $changesincoursecat = true;
    } else {
        if ($movecat) {
            $newcategory = $DB->get_record('course_categories', array('id'=>$data->category));
            if (empty($newcategory->visible)) {
                // make sure when moving into hidden category the course is hidden automatically
                $data->visible = 0;
            }
        }
    }

    // Update with the new data
    $DB->update_record('course', $data);
    // make sure the modinfo cache is reset
    rebuild_course_cache($data->id);

    // update course format options with full course data
    course_get_format($data->id)->update_course_format_options($data, $oldcourse);

    $course = $DB->get_record('course', array('id'=>$data->id));

    if ($movecat) {
        $newparent = context_coursecat::instance($course->category);
        $context->update_moved($newparent);
    }
    $fixcoursesortorder = $movecat || (isset($data->sortorder) && ($oldcourse->sortorder != $data->sortorder));
    if ($fixcoursesortorder) {
        fix_course_sortorder();
    }

    // purge appropriate caches in case fix_course_sortorder() did not change anything
    cache_helper::purge_by_event('changesincourse');
    if ($changesincoursecat) {
        cache_helper::purge_by_event('changesincoursecat');
    }

    // Test for and remove blocks which aren't appropriate anymore
    blocks_remove_inappropriate($course);

    // Save any custom role names.
    save_local_role_names($course->id, $data);

    // update enrol settings
    enrol_course_updated(false, $course, $data);

    // Trigger a course updated event.
    $event = \core\event\course_updated::create(array(
        'objectid' => $course->id,
        'context' => context_course::instance($course->id),
        'other' => array('shortname' => $course->shortname,
                         'fullname' => $course->fullname)
    ));

    $event->set_legacy_logdata(array($course->id, 'course', 'update', 'edit.php?id=' . $course->id, $course->id));
    $event->trigger();

    if ($oldcourse->format !== $course->format) {
        // Remove all options stored for the previous format
        // We assume that new course format migrated everything it needed watching trigger
        // 'course_updated' and in method format_XXX::update_course_format_options()
        $DB->delete_records('course_format_options',
                array('courseid' => $course->id, 'format' => $oldcourse->format));
    }
}

/**
 * Average number of participants
 * @return integer
 */
function average_number_of_participants() {
    global $DB, $SITE;

    //count total of enrolments for visible course (except front page)
    $sql = 'SELECT COUNT(*) FROM (
        SELECT DISTINCT ue.userid, e.courseid
        FROM {user_enrolments} ue, {enrol} e, {course} c
        WHERE ue.enrolid = e.id
            AND e.courseid <> :siteid
            AND c.id = e.courseid
            AND c.visible = 1) total';
    $params = array('siteid' => $SITE->id);
    $enrolmenttotal = $DB->count_records_sql($sql, $params);


    //count total of visible courses (minus front page)
    $coursetotal = $DB->count_records('course', array('visible' => 1));
    $coursetotal = $coursetotal - 1 ;

    //average of enrolment
    if (empty($coursetotal)) {
        $participantaverage = 0;
    } else {
        $participantaverage = $enrolmenttotal / $coursetotal;
    }

    return $participantaverage;
}

/**
 * Average number of course modules
 * @return integer
 */
function average_number_of_courses_modules() {
    global $DB, $SITE;

    //count total of visible course module (except front page)
    $sql = 'SELECT COUNT(*) FROM (
        SELECT cm.course, cm.module
        FROM {course} c, {course_modules} cm
        WHERE c.id = cm.course
            AND c.id <> :siteid
            AND cm.visible = 1
            AND c.visible = 1) total';
    $params = array('siteid' => $SITE->id);
    $moduletotal = $DB->count_records_sql($sql, $params);


    //count total of visible courses (minus front page)
    $coursetotal = $DB->count_records('course', array('visible' => 1));
    $coursetotal = $coursetotal - 1 ;

    //average of course module
    if (empty($coursetotal)) {
        $coursemoduleaverage = 0;
    } else {
        $coursemoduleaverage = $moduletotal / $coursetotal;
    }

    return $coursemoduleaverage;
}

/**
 * This class pertains to course requests and contains methods associated with
 * create, approving, and removing course requests.
 *
 * Please note we do not allow embedded images here because there is no context
 * to store them with proper access control.
 *
 * @copyright 2009 Sam Hemelryk
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since Moodle 2.0
 *
 * @property-read int $id
 * @property-read string $fullname
 * @property-read string $shortname
 * @property-read string $summary
 * @property-read int $summaryformat
 * @property-read int $summarytrust
 * @property-read string $reason
 * @property-read int $requester
 */
class course_request {

    /**
     * This is the stdClass that stores the properties for the course request
     * and is externally accessed through the __get magic method
     * @var stdClass
     */
    protected $properties;

    /**
     * An array of options for the summary editor used by course request forms.
     * This is initially set by {@link summary_editor_options()}
     * @var array
     * @static
     */
    protected static $summaryeditoroptions;

    /**
     * Static function to prepare the summary editor for working with a course
     * request.
     *
     * @static
     * @param null|stdClass $data Optional, an object containing the default values
     *                       for the form, these may be modified when preparing the
     *                       editor so this should be called before creating the form
     * @return stdClass An object that can be used to set the default values for
     *                   an mforms form
     */
    public static function prepare($data=null) {
        if ($data === null) {
            $data = new stdClass;
        }
        $data = file_prepare_standard_editor($data, 'summary', self::summary_editor_options());
        return $data;
    }

    /**
     * Static function to create a new course request when passed an array of properties
     * for it.
     *
     * This function also handles saving any files that may have been used in the editor
     *
     * @static
     * @param stdClass $data
     * @return course_request The newly created course request
     */
    public static function create($data) {
        global $USER, $DB, $CFG;
        $data->requester = $USER->id;

        // Setting the default category if none set.
        if (empty($data->category) || empty($CFG->requestcategoryselection)) {
            $data->category = $CFG->defaultrequestcategory;
        }

        // Summary is a required field so copy the text over
        $data->summary       = $data->summary_editor['text'];
        $data->summaryformat = $data->summary_editor['format'];

        $data->id = $DB->insert_record('course_request', $data);

        // Create a new course_request object and return it
        $request = new course_request($data);

        // Notify the admin if required.
        if ($users = get_users_from_config($CFG->courserequestnotify, 'moodle/site:approvecourse')) {

            $a = new stdClass;
            $a->link = "$CFG->wwwroot/course/pending.php";
            $a->user = fullname($USER);
            $subject = get_string('courserequest');
            $message = get_string('courserequestnotifyemail', 'admin', $a);
            foreach ($users as $user) {
                $request->notify($user, $USER, 'courserequested', $subject, $message);
            }
        }

        return $request;
    }

    /**
     * Returns an array of options to use with a summary editor
     *
     * @uses course_request::$summaryeditoroptions
     * @return array An array of options to use with the editor
     */
    public static function summary_editor_options() {
        global $CFG;
        if (self::$summaryeditoroptions === null) {
            self::$summaryeditoroptions = array('maxfiles' => 0, 'maxbytes'=>0);
        }
        return self::$summaryeditoroptions;
    }

    /**
     * Loads the properties for this course request object. Id is required and if
     * only id is provided then we load the rest of the properties from the database
     *
     * @param stdClass|int $properties Either an object containing properties
     *                      or the course_request id to load
     */
    public function __construct($properties) {
        global $DB;
        if (empty($properties->id)) {
            if (empty($properties)) {
                throw new coding_exception('You must provide a course request id when creating a course_request object');
            }
            $id = $properties;
            $properties = new stdClass;
            $properties->id = (int)$id;
            unset($id);
        }
        if (empty($properties->requester)) {
            if (!($this->properties = $DB->get_record('course_request', array('id' => $properties->id)))) {
                print_error('unknowncourserequest');
            }
        } else {
            $this->properties = $properties;
        }
        $this->properties->collision = null;
    }

    /**
     * Returns the requested property
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key) {
        return $this->properties->$key;
    }

    /**
     * Override this to ensure empty($request->blah) calls return a reliable answer...
     *
     * This is required because we define the __get method
     *
     * @param mixed $key
     * @return bool True is it not empty, false otherwise
     */
    public function __isset($key) {
        return (!empty($this->properties->$key));
    }

    /**
     * Returns the user who requested this course
     *
     * Uses a static var to cache the results and cut down the number of db queries
     *
     * @staticvar array $requesters An array of cached users
     * @return stdClass The user who requested the course
     */
    public function get_requester() {
        global $DB;
        static $requesters= array();
        if (!array_key_exists($this->properties->requester, $requesters)) {
            $requesters[$this->properties->requester] = $DB->get_record('user', array('id'=>$this->properties->requester));
        }
        return $requesters[$this->properties->requester];
    }

    /**
     * Checks that the shortname used by the course does not conflict with any other
     * courses that exist
     *
     * @param string|null $shortnamemark The string to append to the requests shortname
     *                     should a conflict be found
     * @return bool true is there is a conflict, false otherwise
     */
    public function check_shortname_collision($shortnamemark = '[*]') {
        global $DB;

        if ($this->properties->collision !== null) {
            return $this->properties->collision;
        }

        if (empty($this->properties->shortname)) {
            debugging('Attempting to check a course request shortname before it has been set', DEBUG_DEVELOPER);
            $this->properties->collision = false;
        } else if ($DB->record_exists('course', array('shortname' => $this->properties->shortname))) {
            if (!empty($shortnamemark)) {
                $this->properties->shortname .= ' '.$shortnamemark;
            }
            $this->properties->collision = true;
        } else {
            $this->properties->collision = false;
        }
        return $this->properties->collision;
    }

    /**
     * Returns the category where this course request should be created
     *
     * Note that we don't check here that user has a capability to view
     * hidden categories if he has capabilities 'moodle/site:approvecourse' and
     * 'moodle/course:changecategory'
     *
     * @return coursecat
     */
    public function get_category() {
        global $CFG;
        require_once($CFG->libdir.'/coursecatlib.php');
        // If the category is not set, if the current user does not have the rights to change the category, or if the
        // category does not exist, we set the default category to the course to be approved.
        // The system level is used because the capability moodle/site:approvecourse is based on a system level.
        if (empty($this->properties->category) || !has_capability('moodle/course:changecategory', context_system::instance()) ||
                (!$category = coursecat::get($this->properties->category, IGNORE_MISSING, true))) {
            $category = coursecat::get($CFG->defaultrequestcategory, IGNORE_MISSING, true);
        }
        if (!$category) {
            $category = coursecat::get_default();
        }
        return $category;
    }

    /**
     * This function approves the request turning it into a course
     *
     * This function converts the course request into a course, at the same time
     * transferring any files used in the summary to the new course and then removing
     * the course request and the files associated with it.
     *
     * @return int The id of the course that was created from this request
     */
    public function approve() {
        global $CFG, $DB, $USER;

        $user = $DB->get_record('user', array('id' => $this->properties->requester, 'deleted'=>0), '*', MUST_EXIST);

        $courseconfig = get_config('moodlecourse');

        // Transfer appropriate settings
        $data = clone($this->properties);
        unset($data->id);
        unset($data->reason);
        unset($data->requester);

        // Set category
        $category = $this->get_category();
        $data->category = $category->id;
        // Set misc settings
        $data->requested = 1;

        // Apply course default settings
        $data->format             = $courseconfig->format;
        $data->newsitems          = $courseconfig->newsitems;
        $data->showgrades         = $courseconfig->showgrades;
        $data->showreports        = $courseconfig->showreports;
        $data->maxbytes           = $courseconfig->maxbytes;
        $data->groupmode          = $courseconfig->groupmode;
        $data->groupmodeforce     = $courseconfig->groupmodeforce;
        $data->visible            = $courseconfig->visible;
        $data->visibleold         = $data->visible;
        $data->lang               = $courseconfig->lang;

        $course = create_course($data);
        $context = context_course::instance($course->id, MUST_EXIST);

        // add enrol instances
        if (!$DB->record_exists('enrol', array('courseid'=>$course->id, 'enrol'=>'manual'))) {
            if ($manual = enrol_get_plugin('manual')) {
                $manual->add_default_instance($course);
            }
        }

        // enrol the requester as teacher if necessary
        if (!empty($CFG->creatornewroleid) and !is_viewing($context, $user, 'moodle/role:assign') and !is_enrolled($context, $user, 'moodle/role:assign')) {
            enrol_try_internal_enrol($course->id, $user->id, $CFG->creatornewroleid);
        }

        $this->delete();

        $a = new stdClass();
        $a->name = format_string($course->fullname, true, array('context' => context_course::instance($course->id)));
        $a->url = $CFG->wwwroot.'/course/view.php?id=' . $course->id;
        $this->notify($user, $USER, 'courserequestapproved', get_string('courseapprovedsubject'), get_string('courseapprovedemail2', 'moodle', $a));

        return $course->id;
    }

    /**
     * Reject a course request
     *
     * This function rejects a course request, emailing the requesting user the
     * provided notice and then removing the request from the database
     *
     * @param string $notice The message to display to the user
     */
    public function reject($notice) {
        global $USER, $DB;
        $user = $DB->get_record('user', array('id' => $this->properties->requester), '*', MUST_EXIST);
        $this->notify($user, $USER, 'courserequestrejected', get_string('courserejectsubject'), get_string('courserejectemail', 'moodle', $notice));
        $this->delete();
    }

    /**
     * Deletes the course request and any associated files
     */
    public function delete() {
        global $DB;
        $DB->delete_records('course_request', array('id' => $this->properties->id));
    }

    /**
     * Send a message from one user to another using events_trigger
     *
     * @param object $touser
     * @param object $fromuser
     * @param string $name
     * @param string $subject
     * @param string $message
     */
    protected function notify($touser, $fromuser, $name='courserequested', $subject, $message) {
        $eventdata = new stdClass();
        $eventdata->component         = 'moodle';
        $eventdata->name              = $name;
        $eventdata->userfrom          = $fromuser;
        $eventdata->userto            = $touser;
        $eventdata->subject           = $subject;
        $eventdata->fullmessage       = $message;
        $eventdata->fullmessageformat = FORMAT_PLAIN;
        $eventdata->fullmessagehtml   = '';
        $eventdata->smallmessage      = '';
        $eventdata->notification      = 1;
        message_send($eventdata);
    }
}

/**
 * Return a list of page types
 * @param string $pagetype current page type
 * @param context $parentcontext Block's parent context
 * @param context $currentcontext Current context of block
 * @return array array of page types
 */
function course_page_type_list($pagetype, $parentcontext, $currentcontext) {
    if ($pagetype === 'course-index' || $pagetype === 'course-index-category') {
        // For courses and categories browsing pages (/course/index.php) add option to show on ANY category page
        $pagetypes = array('*' => get_string('page-x', 'pagetype'),
            'course-index-*' => get_string('page-course-index-x', 'pagetype'),
        );
    } else if ($currentcontext && (!($coursecontext = $currentcontext->get_course_context(false)) || $coursecontext->instanceid == SITEID)) {
        // We know for sure that despite pagetype starts with 'course-' this is not a page in course context (i.e. /course/search.php, etc.)
        $pagetypes = array('*' => get_string('page-x', 'pagetype'));
    } else {
        // Otherwise consider it a page inside a course even if $currentcontext is null
        $pagetypes = array('*' => get_string('page-x', 'pagetype'),
            'course-*' => get_string('page-course-x', 'pagetype'),
            'course-view-*' => get_string('page-course-view-x', 'pagetype')
        );
    }
    return $pagetypes;
}

/**
 * Determine whether course ajax should be enabled for the specified course
 *
 * @param stdClass $course The course to test against
 * @return boolean Whether course ajax is enabled or note
 */
function course_ajax_enabled($course) {
    global $CFG, $PAGE, $SITE;

    // The user must be editing for AJAX to be included
    if (!$PAGE->user_is_editing()) {
        return false;
    }

    // Check that the theme suports
    if (!$PAGE->theme->enablecourseajax) {
        return false;
    }

    // Check that the course format supports ajax functionality
    // The site 'format' doesn't have information on course format support
    if ($SITE->id !== $course->id) {
        $courseformatajaxsupport = course_format_ajax_support($course->format);
        if (!$courseformatajaxsupport->capable) {
            return false;
        }
    }

    // All conditions have been met so course ajax should be enabled
    return true;
}

/**
 * Include the relevant javascript and language strings for the resource
 * toolbox YUI module
 *
 * @param integer $id The ID of the course being applied to
 * @param array $usedmodules An array containing the names of the modules in use on the page
 * @param array $enabledmodules An array containing the names of the enabled (visible) modules on this site
 * @param stdClass $config An object containing configuration parameters for ajax modules including:
 *          * resourceurl   The URL to post changes to for resource changes
 *          * sectionurl    The URL to post changes to for section changes
 *          * pageparams    Additional parameters to pass through in the post
 * @return bool
 */
function include_course_ajax($course, $usedmodules = array(), $enabledmodules = null, $config = null) {
    global $CFG, $PAGE, $SITE;

    // Ensure that ajax should be included
    if (!course_ajax_enabled($course)) {
        return false;
    }

    if (!$config) {
        $config = new stdClass();
    }

    // The URL to use for resource changes
    if (!isset($config->resourceurl)) {
        $config->resourceurl = '/course/rest.php';
    }

    // The URL to use for section changes
    if (!isset($config->sectionurl)) {
        $config->sectionurl = '/course/rest.php';
    }

    // Any additional parameters which need to be included on page submission
    if (!isset($config->pageparams)) {
        $config->pageparams = array();
    }

    // Include toolboxes
    $PAGE->requires->yui_module('moodle-course-toolboxes',
            'M.course.init_resource_toolbox',
            array(array(
                'courseid' => $course->id,
                'ajaxurl' => $config->resourceurl,
                'config' => $config,
            ))
    );
    $PAGE->requires->yui_module('moodle-course-toolboxes',
            'M.course.init_section_toolbox',
            array(array(
                'courseid' => $course->id,
                'format' => $course->format,
                'ajaxurl' => $config->sectionurl,
                'config' => $config,
            ))
    );

    // Include course dragdrop
    if (course_format_uses_sections($course->format)) {
        $PAGE->requires->yui_module('moodle-course-dragdrop', 'M.course.init_section_dragdrop',
            array(array(
                'courseid' => $course->id,
                'ajaxurl' => $config->sectionurl,
                'config' => $config,
            )), null, true);

        $PAGE->requires->yui_module('moodle-course-dragdrop', 'M.course.init_resource_dragdrop',
            array(array(
                'courseid' => $course->id,
                'ajaxurl' => $config->resourceurl,
                'config' => $config,
            )), null, true);
    }

    // Require various strings for the command toolbox
    $PAGE->requires->strings_for_js(array(
            'moveleft',
            'deletechecktype',
            'deletechecktypename',
            'edittitle',
            'edittitleinstructions',
            'show',
            'hide',
            'groupsnone',
            'groupsvisible',
            'groupsseparate',
            'clicktochangeinbrackets',
            'markthistopic',
            'markedthistopic',
            'movesection',
            'movecoursemodule',
            'movecoursesection',
            'movecontent',
            'tocontent',
            'emptydragdropregion',
            'afterresource',
            'aftersection',
            'totopofsection',
        ), 'moodle');

    // Include section-specific strings for formats which support sections.
    if (course_format_uses_sections($course->format)) {
        $PAGE->requires->strings_for_js(array(
                'showfromothers',
                'hidefromothers',
            ), 'format_' . $course->format);
    }

    // For confirming resource deletion we need the name of the module in question
    foreach ($usedmodules as $module => $modname) {
        $PAGE->requires->string_for_js('pluginname', $module);
    }

    // Load drag and drop upload AJAX.
    require_once($CFG->dirroot.'/course/dnduploadlib.php');
    dndupload_add_to_course($course, $enabledmodules);

    return true;
}

/**
 * Returns the sorted list of available course formats, filtered by enabled if necessary
 *
 * @param bool $enabledonly return only formats that are enabled
 * @return array array of sorted format names
 */
function get_sorted_course_formats($enabledonly = false) {
    global $CFG;
    $formats = core_component::get_plugin_list('format');

    if (!empty($CFG->format_plugins_sortorder)) {
        $order = explode(',', $CFG->format_plugins_sortorder);
        $order = array_merge(array_intersect($order, array_keys($formats)),
                    array_diff(array_keys($formats), $order));
    } else {
        $order = array_keys($formats);
    }
    if (!$enabledonly) {
        return $order;
    }
    $sortedformats = array();
    foreach ($order as $formatname) {
        if (!get_config('format_'.$formatname, 'disabled')) {
            $sortedformats[] = $formatname;
        }
    }
    return $sortedformats;
}

/**
 * The URL to use for the specified course (with section)
 *
 * @param int|stdClass $courseorid The course to get the section name for (either object or just course id)
 * @param int|stdClass $section Section object from database or just field course_sections.section
 *     if omitted the course view page is returned
 * @param array $options options for view URL. At the moment core uses:
 *     'navigation' (bool) if true and section has no separate page, the function returns null
 *     'sr' (int) used by multipage formats to specify to which section to return
 * @return moodle_url The url of course
 */
function course_get_url($courseorid, $section = null, $options = array()) {
    return course_get_format($courseorid)->get_view_url($section, $options);
}

/**
 * Create a module.
 *
 * It includes:
 *      - capability checks and other checks
 *      - create the module from the module info
 *
 * @param object $module
 * @return object the created module info
 * @throws moodle_exception if user is not allowed to perform the action or module is not allowed in this course
 */
function create_module($moduleinfo) {
    global $DB, $CFG;

    require_once($CFG->dirroot . '/course/modlib.php');

    // Check manadatory attributs.
    $mandatoryfields = array('modulename', 'course', 'section', 'visible');
    if (plugin_supports('mod', $moduleinfo->modulename, FEATURE_MOD_INTRO, true)) {
        $mandatoryfields[] = 'introeditor';
    }
    foreach($mandatoryfields as $mandatoryfield) {
        if (!isset($moduleinfo->{$mandatoryfield})) {
            throw new moodle_exception('createmodulemissingattribut', '', '', $mandatoryfield);
        }
    }

    // Some additional checks (capability / existing instances).
    $course = $DB->get_record('course', array('id'=>$moduleinfo->course), '*', MUST_EXIST);
    list($module, $context, $cw) = can_add_moduleinfo($course, $moduleinfo->modulename, $moduleinfo->section);

    // Add the module.
    $moduleinfo->module = $module->id;
    $moduleinfo = add_moduleinfo($moduleinfo, $course, null);

    return $moduleinfo;
}

/**
 * Update a module.
 *
 * It includes:
 *      - capability and other checks
 *      - update the module
 *
 * @param object $module
 * @return object the updated module info
 * @throws moodle_exception if current user is not allowed to update the module
 */
function update_module($moduleinfo) {
    global $DB, $CFG;

    require_once($CFG->dirroot . '/course/modlib.php');

    // Check the course module exists.
    $cm = get_coursemodule_from_id('', $moduleinfo->coursemodule, 0, false, MUST_EXIST);

    // Check the course exists.
    $course = $DB->get_record('course', array('id'=>$cm->course), '*', MUST_EXIST);

    // Some checks (capaibility / existing instances).
    list($cm, $context, $module, $data, $cw) = can_update_moduleinfo($cm);

    // Retrieve few information needed by update_moduleinfo.
    $moduleinfo->modulename = $cm->modname;
    if (!isset($moduleinfo->scale)) {
        $moduleinfo->scale = 0;
    }
    $moduleinfo->type = 'mod';

    // Update the module.
    list($cm, $moduleinfo) = update_moduleinfo($cm, $moduleinfo, $course, null);

    return $moduleinfo;
}

/**
 * Duplicate a module on the course for ajax.
 *
 * @see mod_duplicate_module()
 * @param object $course The course
 * @param object $cm The course module to duplicate
 * @param int $sr The section to link back to (used for creating the links)
 * @throws moodle_exception if the plugin doesn't support duplication
 * @return Object containing:
 * - fullcontent: The HTML markup for the created CM
 * - cmid: The CMID of the newly created CM
 * - redirect: Whether to trigger a redirect following this change
 */
function mod_duplicate_activity($course, $cm, $sr = null) {
    global $PAGE;

    $newcm = duplicate_module($course, $cm);

    $resp = new stdClass();
    if ($newcm) {
        $courserenderer = $PAGE->get_renderer('core', 'course');
        $completioninfo = new completion_info($course);
        $modulehtml = $courserenderer->course_section_cm($course, $completioninfo,
                $newcm, null, array());

        $resp->fullcontent = $courserenderer->course_section_cm_list_item($course, $completioninfo, $newcm, $sr);
        $resp->cmid = $newcm->id;
    } else {
        // Trigger a redirect.
        $resp->redirect = true;
    }
    return $resp;
}

/**
 * Api to duplicate a module.
 *
 * @param object $course course object.
 * @param object $cm course module object to be duplicated.
 * @since Moodle 2.8
 *
 * @throws Exception
 * @throws coding_exception
 * @throws moodle_exception
 * @throws restore_controller_exception
 *
 * @return cm_info|null cminfo object if we sucessfully duplicated the mod and found the new cm.
 */
function duplicate_module($course, $cm) {
    global $CFG, $DB, $USER;
    require_once($CFG->dirroot . '/backup/util/includes/backup_includes.php');
    require_once($CFG->dirroot . '/backup/util/includes/restore_includes.php');
    require_once($CFG->libdir . '/filelib.php');

    $a          = new stdClass();
    $a->modtype = get_string('modulename', $cm->modname);
    $a->modname = format_string($cm->name);

    if (!plugin_supports('mod', $cm->modname, FEATURE_BACKUP_MOODLE2)) {
        throw new moodle_exception('duplicatenosupport', 'error', '', $a);
    }

    // Backup the activity.

    $bc = new backup_controller(backup::TYPE_1ACTIVITY, $cm->id, backup::FORMAT_MOODLE,
            backup::INTERACTIVE_NO, backup::MODE_IMPORT, $USER->id);

    $backupid       = $bc->get_backupid();
    $backupbasepath = $bc->get_plan()->get_basepath();

    $bc->execute_plan();

    $bc->destroy();

    // Restore the backup immediately.

    $rc = new restore_controller($backupid, $course->id,
            backup::INTERACTIVE_NO, backup::MODE_IMPORT, $USER->id, backup::TARGET_CURRENT_ADDING);

    $cmcontext = context_module::instance($cm->id);
    if (!$rc->execute_precheck()) {
        $precheckresults = $rc->get_precheck_results();
        if (is_array($precheckresults) && !empty($precheckresults['errors'])) {
            if (empty($CFG->keeptempdirectoriesonbackup)) {
                fulldelete($backupbasepath);
            }
        }
    }

    $rc->execute_plan();

    // Now a bit hacky part follows - we try to get the cmid of the newly
    // restored copy of the module.
    $newcmid = null;
    $tasks = $rc->get_plan()->get_tasks();
    foreach ($tasks as $task) {
        if (is_subclass_of($task, 'restore_activity_task')) {
            if ($task->get_old_contextid() == $cmcontext->id) {
                $newcmid = $task->get_moduleid();
                break;
            }
        }
    }

    // If we know the cmid of the new course module, let us move it
    // right below the original one. otherwise it will stay at the
    // end of the section.
    if ($newcmid) {
        $info = get_fast_modinfo($course);
        $newcm = $info->get_cm($newcmid);
        $section = $DB->get_record('course_sections', array('id' => $cm->section, 'course' => $cm->course));
        moveto_module($newcm, $section, $cm);
        moveto_module($cm, $section, $newcm);

        // Trigger course module created event. We can trigger the event only if we know the newcmid.
        $event = \core\event\course_module_created::create_from_cm($newcm);
        $event->trigger();
    }
    rebuild_course_cache($cm->course);

    $rc->destroy();

    if (empty($CFG->keeptempdirectoriesonbackup)) {
        fulldelete($backupbasepath);
    }

    return isset($newcm) ? $newcm : null;
}

/**
 * Compare two objects to find out their correct order based on timestamp (to be used by usort).
 * Sorts by descending order of time.
 *
 * @param stdClass $a First object
 * @param stdClass $b Second object
 * @return int 0,1,-1 representing the order
 */
function compare_activities_by_time_desc($a, $b) {
    // Make sure the activities actually have a timestamp property.
    if ((!property_exists($a, 'timestamp')) && (!property_exists($b, 'timestamp'))) {
        return 0;
    }
    // We treat instances without timestamp as if they have a timestamp of 0.
    if ((!property_exists($a, 'timestamp')) && (property_exists($b,'timestamp'))) {
        return 1;
    }
    if ((property_exists($a, 'timestamp')) && (!property_exists($b, 'timestamp'))) {
        return -1;
    }
    if ($a->timestamp == $b->timestamp) {
        return 0;
    }
    return ($a->timestamp > $b->timestamp) ? -1 : 1;
}

/**
 * Compare two objects to find out their correct order based on timestamp (to be used by usort).
 * Sorts by ascending order of time.
 *
 * @param stdClass $a First object
 * @param stdClass $b Second object
 * @return int 0,1,-1 representing the order
 */
function compare_activities_by_time_asc($a, $b) {
    // Make sure the activities actually have a timestamp property.
    if ((!property_exists($a, 'timestamp')) && (!property_exists($b, 'timestamp'))) {
      return 0;
    }
    // We treat instances without timestamp as if they have a timestamp of 0.
    if ((!property_exists($a, 'timestamp')) && (property_exists($b, 'timestamp'))) {
        return -1;
    }
    if ((property_exists($a, 'timestamp')) && (!property_exists($b, 'timestamp'))) {
        return 1;
    }
    if ($a->timestamp == $b->timestamp) {
        return 0;
    }
    return ($a->timestamp < $b->timestamp) ? -1 : 1;
}

/**
 * Changes the visibility of a course.
 *
 * @param int $courseid The course to change.
 * @param bool $show True to make it visible, false otherwise.
 * @return bool
 */
function course_change_visibility($courseid, $show = true) {
    $course = new stdClass;
    $course->id = $courseid;
    $course->visible = ($show) ? '1' : '0';
    $course->visibleold = $course->visible;
    update_course($course);
    return true;
}

/**
 * Changes the course sortorder by one, moving it up or down one in respect to sort order.
 *
 * @param stdClass|course_in_list $course
 * @param bool $up If set to true the course will be moved up one. Otherwise down one.
 * @return bool
 */
function course_change_sortorder_by_one($course, $up) {
    global $DB;
    $params = array($course->sortorder, $course->category);
    if ($up) {
        $select = 'sortorder < ? AND category = ?';
        $sort = 'sortorder DESC';
    } else {
        $select = 'sortorder > ? AND category = ?';
        $sort = 'sortorder ASC';
    }
    fix_course_sortorder();
    $swapcourse = $DB->get_records_select('course', $select, $params, $sort, '*', 0, 1);
    if ($swapcourse) {
        $swapcourse = reset($swapcourse);
        $DB->set_field('course', 'sortorder', $swapcourse->sortorder, array('id' => $course->id));
        $DB->set_field('course', 'sortorder', $course->sortorder, array('id' => $swapcourse->id));
        // Finally reorder courses.
        fix_course_sortorder();
        cache_helper::purge_by_event('changesincourse');
        return true;
    }
    return false;
}

/**
 * Changes the sort order of courses in a category so that the first course appears after the second.
 *
 * @param int|stdClass $courseorid The course to focus on.
 * @param int $moveaftercourseid The course to shifter after or 0 if you want it to be the first course in the category.
 * @return bool
 */
function course_change_sortorder_after_course($courseorid, $moveaftercourseid) {
    global $DB;

    if (!is_object($courseorid)) {
        $course = get_course($courseorid);
    } else {
        $course = $courseorid;
    }

    if ((int)$moveaftercourseid === 0) {
        // We've moving the course to the start of the queue.
        $sql = 'SELECT sortorder
                      FROM {course}
                     WHERE category = :categoryid
                  ORDER BY sortorder';
        $params = array(
            'categoryid' => $course->category
        );
        $sortorder = $DB->get_field_sql($sql, $params, IGNORE_MULTIPLE);

        $sql = 'UPDATE {course}
                   SET sortorder = sortorder + 1
                 WHERE category = :categoryid
                   AND id <> :id';
        $params = array(
            'categoryid' => $course->category,
            'id' => $course->id,
        );
        $DB->execute($sql, $params);
        $DB->set_field('course', 'sortorder', $sortorder, array('id' => $course->id));
    } else if ($course->id === $moveaftercourseid) {
        // They're the same - moronic.
        debugging("Invalid move after course given.", DEBUG_DEVELOPER);
        return false;
    } else {
        // Moving this course after the given course. It could be before it could be after.
        $moveaftercourse = get_course($moveaftercourseid);
        if ($course->category !== $moveaftercourse->category) {
            debugging("Cannot re-order courses. The given courses do not belong to the same category.", DEBUG_DEVELOPER);
            return false;
        }
        // Increment all courses in the same category that are ordered after the moveafter course.
        // This makes a space for the course we're moving.
        $sql = 'UPDATE {course}
                       SET sortorder = sortorder + 1
                     WHERE category = :categoryid
                       AND sortorder > :sortorder';
        $params = array(
            'categoryid' => $moveaftercourse->category,
            'sortorder' => $moveaftercourse->sortorder
        );
        $DB->execute($sql, $params);
        $DB->set_field('course', 'sortorder', $moveaftercourse->sortorder + 1, array('id' => $course->id));
    }
    fix_course_sortorder();
    cache_helper::purge_by_event('changesincourse');
    return true;
}
