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

define('CLI_SCRIPT', true);
define('CACHE_DISABLE_ALL', true); // This prevents reading of existing caches.
define('IGNORE_COMPONENT_CACHE', true);

/*
 * Given .csv file of courses to be archived (generated by
 * archive_course_lists.php), sends notification emails to instructors.
 * Use "-n" as first parameter to print messages without sending.
 * Suggested usage:
 * $ cat archive_list.csv | php ./generate_archive_emails.php > arch_email.log 2>&1
 * Test with:
 * $ cat archive_list.csv | php ./generate_archive_emails.php -n
 */

$line = fgets(STDIN);

$line = rtrim($line);

if ($line == "No records found.") {
    echo "No courses to be archived.  No mail to send.\n";
    die;
}

if ($line == '') {
    echo "Please supply an archive list as input.\n";
    echo "Usage: php generate_archive_emails.php < 'archive-list.csv'\n";
    die;
}

if ($line != "Course ID,Course shortname,Course fullname,First name(s),Last name(s),".
             "Email(s),CCIDs,Opt-Out flag") {
    echo "Input does not seem to be a valid archive course list.  Has the format changed?\n";
    die;
}

$coursesbyemail = array();
$firstnamebyemail = array();
$lastnamebyemail = array();

function parseline($line) {
    global $coursesbyemail, $firstnamebyemail, $lastnamebyemail;

    $fields = array();
    $fields = str_getcsv($line);
    $firstnames = array();
    $firstnames = str_getcsv($fields[3]);
    $lasttnames = array();
    $lastnames = str_getcsv($fields[4]);
    $emails = array();
    $emails = str_getcsv($fields[5]);

    if ($fields[7] == '') {
        foreach ($emails as $key => $email) {
            if ($email != '') {
                $firstnamebyemail[$email] = $firstnames[$key];
                $lastnamebyemail[$email] = $lastnames[$key];
                if (!isset($coursesbyemail[$email])) {
                    $coursesbyemail[$email] = $fields[2];
                } else {
                    $coursesbyemail[$email] .= PHP_EOL . "$fields[2]";
                }
            }
        }
    } else {
        echo "Course \"$fields[2]\" is opted out of archiving ($fields[7]).\n";
    }
}

function generate_messagebody($firstname, $lastname, $courselist) {
    $messagetext = <<<MSG
Hello $firstname $lastname,

The following eClass course(s) in which you are enrolled as an instructor are scheduled for archiving within 2 weeks:

$courselist

After archiving, you will still be able to access the course contents through the eClass Section Management system.

If you would like to opt-out of archiving to leave the courses live on eClass, please contact eclass@ualberta.ca.

Thank you,
The eClass Team
MSG;

    return $messagetext;
}

function print_notification($fromaddress, $toaddress, $subject, $messagetext) {
    echo "From: $fromaddress\n";
    echo "To: $toaddress\n";
    echo "Subject: $subject\n";
    echo "$messagetext\n";
    echo ".\n";
}

function send_notification($fromaddress, $toaddress, $subject, $messagetext) {
    echo "Sending notification to $toaddress.\n";

    $smtpsession = "mail from: <$fromaddress>\n" .
                    "rcpt to: <$toaddress>\n" .
                    "data\n" .
                    "Subject: $subject\n" .
                    "$messagetext\n" .
                    ".\n" .
                    "quit\n";

    $pipe = popen('telnet smtp.srv.ualberta.ca 25', 'w');
    if ($pipe == false) {
        echo "Error occurred opening pipe connection to smtp server.\n";
    } else {
        $bytes = fwrite($pipe, $smtpsession);
        if ($bytes = false) {
            echo "Error writing to smtp server.\n";
        }
        pclose($pipe);
    }

    echo "Mail sent.\n";
}

while ($line = fgets(STDIN)) {
    parseline($line);
}

if (isset($argv[1]) && ($argv[1] == '-n')) {
    foreach ($coursesbyemail as $email => $courses) {
        $messagebody = generate_messagebody($firstnamebyemail[$email], $lastnamebyemail[$email],
                                            $coursesbyemail[$email]);
        print_notification('eclass@ualberta.ca', $email, 'eClass course archiving', $messagebody);
    }
} else {
    foreach ($coursesbyemail as $email => $courses) {
        $messagebody = generate_messagebody($firstnamebyemail[$email], $lastnamebyemail[$email],
                $coursesbyemail[$email]);
        send_notification('eclass@ualberta.ca', $email, 'eClass course archiving', $messagebody);
    }
}
