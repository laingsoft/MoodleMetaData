<?php

// This file defines settingpages and externalpages under the "server" category

if ($hassiteconfig) { // speedup for non-admins, add all caps used on this page


// "systempaths" settingpage
$temp = new admin_settingpage('systempaths', new lang_string('systempaths','admin'));

$temp->add(new admin_setting_configexecutable('pathtodu', new lang_string('pathtodu', 'admin'), new lang_string('configpathtodu', 'admin'), ''));
$temp->add(new admin_setting_configexecutable('aspellpath', new lang_string('aspellpath', 'admin'), new lang_string('edhelpaspellpath'), ''));
$temp->add(new admin_setting_configexecutable('pathtodot', new lang_string('pathtodot', 'admin'), new lang_string('pathtodot_help', 'admin'), ''));
$temp->add(new admin_setting_configexecutable('pathtogs', new lang_string('pathtogs', 'admin'), new lang_string('pathtogs_help', 'admin'), '/usr/bin/gs'));
$ADMIN->add('server', $temp);



// "supportcontact" settingpage
$temp = new admin_settingpage('supportcontact', new lang_string('supportcontact','admin'));
if (isloggedin()) {
    global $USER;
    $primaryadminemail = $USER->email;
    $primaryadminname  = fullname($USER, true);

} else {
    // no defaults during installation - admin user must be created first
    $primaryadminemail = NULL;
    $primaryadminname  = NULL;
}
$temp->add(new admin_setting_configtext('supportname', new lang_string('supportname', 'admin'), new lang_string('configsupportname', 'admin'), $primaryadminname, PARAM_NOTAGS));
$temp->add(new admin_setting_configtext('supportemail', new lang_string('supportemail', 'admin'), new lang_string('configsupportemail', 'admin'), $primaryadminemail, PARAM_NOTAGS));
$temp->add(new admin_setting_configtext('supportpage', new lang_string('supportpage', 'admin'), new lang_string('configsupportpage', 'admin'), '', PARAM_URL));
$ADMIN->add('server', $temp);


// "sessionhandling" settingpage
$temp = new admin_settingpage('sessionhandling', new lang_string('sessionhandling', 'admin'));
if (empty($CFG->session_handler_class) and $DB->session_lock_supported()) {
    $temp->add(new admin_setting_configcheckbox('dbsessions', new lang_string('dbsessions', 'admin'), new lang_string('configdbsessions', 'admin'), 0));
}
$temp->add(new admin_setting_configselect('sessiontimeout', new lang_string('sessiontimeout', 'admin'), new lang_string('configsessiontimeout', 'admin'), 7200, array(14400 => new lang_string('numhours', '', 4),
                                                                                                                                                      10800 => new lang_string('numhours', '', 3),
                                                                                                                                                      7200 => new lang_string('numhours', '', 2),
                                                                                                                                                      5400 => new lang_string('numhours', '', '1.5'),
                                                                                                                                                      3600 => new lang_string('numminutes', '', 60),
                                                                                                                                                      2700 => new lang_string('numminutes', '', 45),
                                                                                                                                                      1800 => new lang_string('numminutes', '', 30),
                                                                                                                                                      900 => new lang_string('numminutes', '', 15),
                                                                                                                                                      300 => new lang_string('numminutes', '', 5))));
$temp->add(new admin_setting_configtext('sessioncookie', new lang_string('sessioncookie', 'admin'), new lang_string('configsessioncookie', 'admin'), '', PARAM_ALPHANUM));
$temp->add(new admin_setting_configtext('sessioncookiepath', new lang_string('sessioncookiepath', 'admin'), new lang_string('configsessioncookiepath', 'admin'), '', PARAM_RAW));
$temp->add(new admin_setting_configtext('sessioncookiedomain', new lang_string('sessioncookiedomain', 'admin'), new lang_string('configsessioncookiedomain', 'admin'), '', PARAM_RAW, 50));
$ADMIN->add('server', $temp);


// "stats" settingpage
$temp = new admin_settingpage('stats', new lang_string('stats'), 'moodle/site:config', empty($CFG->enablestats));
$temp->add(new admin_setting_configselect('statsfirstrun', new lang_string('statsfirstrun', 'admin'), new lang_string('configstatsfirstrun', 'admin'), 'none', array('none' => new lang_string('none'),
                                                                                                                                                           60*60*24*7 => new lang_string('numweeks','moodle',1),
                                                                                                                                                           60*60*24*14 => new lang_string('numweeks','moodle',2),
                                                                                                                                                           60*60*24*21 => new lang_string('numweeks','moodle',3),
                                                                                                                                                           60*60*24*28 => new lang_string('nummonths','moodle',1),
                                                                                                                                                           60*60*24*56 => new lang_string('nummonths','moodle',2),
                                                                                                                                                           60*60*24*84 => new lang_string('nummonths','moodle',3),
                                                                                                                                                           60*60*24*112 => new lang_string('nummonths','moodle',4),
                                                                                                                                                           60*60*24*140 => new lang_string('nummonths','moodle',5),
                                                                                                                                                           60*60*24*168 => new lang_string('nummonths','moodle',6),
                                                                                                                                                           'all' => new lang_string('all') )));
$temp->add(new admin_setting_configselect('statsmaxruntime', new lang_string('statsmaxruntime', 'admin'), new lang_string('configstatsmaxruntime3', 'admin'), 0, array(0 => new lang_string('untilcomplete'),
                                                                                                                                                            60*30 => '10 '.new lang_string('minutes'),
                                                                                                                                                            60*30 => '30 '.new lang_string('minutes'),
                                                                                                                                                            60*60 => '1 '.new lang_string('hour'),
                                                                                                                                                            60*60*2 => '2 '.new lang_string('hours'),
                                                                                                                                                            60*60*3 => '3 '.new lang_string('hours'),
                                                                                                                                                            60*60*4 => '4 '.new lang_string('hours'),
                                                                                                                                                            60*60*5 => '5 '.new lang_string('hours'),
                                                                                                                                                            60*60*6 => '6 '.new lang_string('hours'),
                                                                                                                                                            60*60*7 => '7 '.new lang_string('hours'),
                                                                                                                                                            60*60*8 => '8 '.new lang_string('hours') )));
$temp->add(new admin_setting_configtext('statsruntimedays', new lang_string('statsruntimedays', 'admin'), new lang_string('configstatsruntimedays', 'admin'), 31, PARAM_INT));
$temp->add(new admin_setting_configtime('statsruntimestarthour', 'statsruntimestartminute', new lang_string('statsruntimestart', 'admin'), new lang_string('configstatsruntimestart', 'admin'), array('h' => 0, 'm' => 0)));
$temp->add(new admin_setting_configtext('statsuserthreshold', new lang_string('statsuserthreshold', 'admin'), new lang_string('configstatsuserthreshold', 'admin'), 0, PARAM_INT));
$ADMIN->add('server', $temp);


// "http" settingpage
$temp = new admin_settingpage('http', new lang_string('http', 'admin'));
$temp->add(new admin_setting_configcheckbox('slasharguments', new lang_string('slasharguments', 'admin'), new lang_string('configslasharguments', 'admin'), 1));
$temp->add(new admin_setting_heading('reverseproxy', new lang_string('reverseproxy', 'admin'), '', ''));
$options = array(
    0 => 'HTTP_CLIENT_IP, HTTP_X_FORWARDED_FOR, REMOTE_ADDR',
    GETREMOTEADDR_SKIP_HTTP_CLIENT_IP => 'HTTP_X_FORWARDED_FOR, REMOTE_ADDR',
    GETREMOTEADDR_SKIP_HTTP_X_FORWARDED_FOR => 'HTTP_CLIENT, REMOTE_ADDR',
    GETREMOTEADDR_SKIP_HTTP_X_FORWARDED_FOR|GETREMOTEADDR_SKIP_HTTP_CLIENT_IP => 'REMOTE_ADDR');
$temp->add(new admin_setting_configselect('getremoteaddrconf', new lang_string('getremoteaddrconf', 'admin'), new lang_string('configgetremoteaddrconf', 'admin'), 0, $options));

$temp->add(new admin_setting_heading('webproxy', new lang_string('webproxy', 'admin'), new lang_string('webproxyinfo', 'admin')));
$temp->add(new admin_setting_configtext('proxyhost', new lang_string('proxyhost', 'admin'), new lang_string('configproxyhost', 'admin'), '', PARAM_HOST));
$temp->add(new admin_setting_configtext('proxyport', new lang_string('proxyport', 'admin'), new lang_string('configproxyport', 'admin'), 0, PARAM_INT));
$options = array('HTTP'=>'HTTP');
if (defined('CURLPROXY_SOCKS5')) {
    $options['SOCKS5'] = 'SOCKS5';
}
$temp->add(new admin_setting_configselect('proxytype', new lang_string('proxytype', 'admin'), new lang_string('configproxytype','admin'), 'HTTP', $options));
$temp->add(new admin_setting_configtext('proxyuser', new lang_string('proxyuser', 'admin'), new lang_string('configproxyuser', 'admin'), ''));
$temp->add(new admin_setting_configpasswordunmask('proxypassword', new lang_string('proxypassword', 'admin'), new lang_string('configproxypassword', 'admin'), ''));
$temp->add(new admin_setting_configtext('proxybypass', new lang_string('proxybypass', 'admin'), new lang_string('configproxybypass', 'admin'), 'localhost, 127.0.0.1'));
$ADMIN->add('server', $temp);

$temp = new admin_settingpage('maintenancemode', new lang_string('sitemaintenancemode', 'admin'));
$options = array(0=>new lang_string('disable'), 1=>new lang_string('enable'));
/** eClass modification: LMS-206 Disable dangerous functions from GUI. */
if (!isset($CFG->eclassdisabledisasters)) {
    $temp->add(new admin_setting_configselect('maintenance_enabled', new lang_string('sitemaintenancemode', 'admin'),
                                          new lang_string('helpsitemaintenance', 'admin'), 0, $options));
}
$temp->add(new admin_setting_confightmleditor('maintenance_message', new lang_string('optionalmaintenancemessage', 'admin'),
                                              '', ''));
$ADMIN->add('server', $temp);

$temp = new admin_settingpage('cleanup', new lang_string('cleanup', 'admin'));
$temp->add(new admin_setting_configselect('deleteunconfirmed', new lang_string('deleteunconfirmed', 'admin'), new lang_string('configdeleteunconfirmed', 'admin'), 168, array(0 => new lang_string('never'),
                                                                                                                                                                    168 => new lang_string('numdays', '', 7),
                                                                                                                                                                    144 => new lang_string('numdays', '', 6),
                                                                                                                                                                    120 => new lang_string('numdays', '', 5),
                                                                                                                                                                    96 => new lang_string('numdays', '', 4),
                                                                                                                                                                    72 => new lang_string('numdays', '', 3),
                                                                                                                                                                    48 => new lang_string('numdays', '', 2),
                                                                                                                                                                    24 => new lang_string('numdays', '', 1),
                                                                                                                                                                    12 => new lang_string('numhours', '', 12),
                                                                                                                                                                    6 => new lang_string('numhours', '', 6),
                                                                                                                                                                    1 => new lang_string('numhours', '', 1))));

$temp->add(new admin_setting_configselect('deleteincompleteusers', new lang_string('deleteincompleteusers', 'admin'), new lang_string('configdeleteincompleteusers', 'admin'), 0, array(0 => new lang_string('never'),
                                                                                                                                                                    168 => new lang_string('numdays', '', 7),
                                                                                                                                                                    144 => new lang_string('numdays', '', 6),
                                                                                                                                                                    120 => new lang_string('numdays', '', 5),
                                                                                                                                                                    96 => new lang_string('numdays', '', 4),
                                                                                                                                                                    72 => new lang_string('numdays', '', 3),
                                                                                                                                                                    48 => new lang_string('numdays', '', 2),
                                                                                                                                                                    24 => new lang_string('numdays', '', 1))));


$temp->add(new admin_setting_configcheckbox('disablegradehistory', new lang_string('disablegradehistory', 'grades'),
                                            new lang_string('disablegradehistory_help', 'grades'), 0));

$temp->add(new admin_setting_configselect('gradehistorylifetime', new lang_string('gradehistorylifetime', 'grades'),
                                          new lang_string('gradehistorylifetime_help', 'grades'), 0, array(0 => new lang_string('neverdeletehistory', 'grades'),
                                                                                                   1000 => new lang_string('numdays', '', 1000),
                                                                                                    365 => new lang_string('numdays', '', 365),
                                                                                                    180 => new lang_string('numdays', '', 180),
                                                                                                    150 => new lang_string('numdays', '', 150),
                                                                                                    120 => new lang_string('numdays', '', 120),
                                                                                                     90 => new lang_string('numdays', '', 90),
                                                                                                     60 => new lang_string('numdays', '', 60),
                                                                                                     30 => new lang_string('numdays', '', 30))));

$ADMIN->add('server', $temp);



$ADMIN->add('server', new admin_externalpage('environment', new lang_string('environment','admin'), "$CFG->wwwroot/$CFG->admin/environment.php"));
$ADMIN->add('server', new admin_externalpage('phpinfo', new lang_string('phpinfo'), "$CFG->wwwroot/$CFG->admin/phpinfo.php"));


// "performance" settingpage
$temp = new admin_settingpage('performance', new lang_string('performance', 'admin'));

// Memory limit options for large administration tasks.
$memoryoptions = array(
    '64M' => '64M',
    '128M' => '128M',
    '256M' => '256M',
    '512M' => '512M',
    '1024M' => '1024M',
    '2048M' => '2048M');

// Allow larger memory usage for 64-bit sites only.
if (PHP_INT_SIZE === 8) {
    $memoryoptions['3072M'] = '3072M';
    $memoryoptions['4096M'] = '4096M';
}

$temp->add(new admin_setting_configselect('extramemorylimit', new lang_string('extramemorylimit', 'admin'),
                                          new lang_string('configextramemorylimit', 'admin'), '512M',
                                          $memoryoptions));
$temp->add(new admin_setting_configtext('maxtimelimit', new lang_string('maxtimelimit', 'admin'),
        new lang_string('maxtimelimit_desc', 'admin'), 0, PARAM_INT));

$temp->add(new admin_setting_configtext('curlcache', new lang_string('curlcache', 'admin'),
                                        new lang_string('configcurlcache', 'admin'), 120, PARAM_INT));

$temp->add(new admin_setting_configtext('curltimeoutkbitrate', new lang_string('curltimeoutkbitrate', 'admin'),
                                        new lang_string('curltimeoutkbitrate_help', 'admin'), 56, PARAM_INT));

$ADMIN->add('server', $temp);


$ADMIN->add('server', new admin_externalpage('adminregistration', new lang_string('hubs', 'admin'),
    "$CFG->wwwroot/$CFG->admin/registration/index.php"));

// "update notifications" settingpage
if (empty($CFG->disableupdatenotifications)) {
    $temp = new admin_settingpage('updatenotifications', new lang_string('updatenotifications', 'core_admin'));
    $temp->add(new admin_setting_configcheckbox('updateautocheck', new lang_string('updateautocheck', 'core_admin'),
                                                new lang_string('updateautocheck_desc', 'core_admin'), 1));
    if (empty($CFG->disableupdateautodeploy)) {
        $temp->add(new admin_setting_configcheckbox('updateautodeploy', new lang_string('updateautodeploy', 'core_admin'),
                                                    new lang_string('updateautodeploy_desc', 'core_admin'), 0));
    }
    $temp->add(new admin_setting_configselect('updateminmaturity', new lang_string('updateminmaturity', 'core_admin'),
                                              new lang_string('updateminmaturity_desc', 'core_admin'), MATURITY_STABLE,
                                              array(
                                                  MATURITY_ALPHA  => new lang_string('maturity'.MATURITY_ALPHA, 'core_admin'),
                                                  MATURITY_BETA   => new lang_string('maturity'.MATURITY_BETA, 'core_admin'),
                                                  MATURITY_RC     => new lang_string('maturity'.MATURITY_RC, 'core_admin'),
                                                  MATURITY_STABLE => new lang_string('maturity'.MATURITY_STABLE, 'core_admin'),
                                              )));
    $temp->add(new admin_setting_configcheckbox('updatenotifybuilds', new lang_string('updatenotifybuilds', 'core_admin'),
                                                new lang_string('updatenotifybuilds_desc', 'core_admin'), 0));
    $ADMIN->add('server', $temp);
}

} // end of speedup
