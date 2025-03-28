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
 * Collapsed Topics with Collapsed Labels Information
 *
 * A topic based format that solves the issue of the 'Scroll of Death' when a course has many topics. All topics
 * except zero have a toggle that displays that topic. One or more topics can be displayed at any given time.
 * Toggles are persistent on a per browser session per course basis but can be made to persist longer by a small
 * code change. Full installation instructions, code adaptions and credits are included in the 'Readme.md' file.
 *
 * @package    course/format
 * @subpackage collblct
 * @version    See the value of '$plugin->version' in below.
 * @copyright  &copy; 2009-onwards G J Barnard in respect to modifications of standard topics format.
 * @author     G J Barnard - gjbarnard at gmail dot com and {@link http://moodle.org/user/profile.php?id=442195}
 * @link       http://docs.moodle.org/en/Collapsed_Topics_course_format
 * @license    http://www.gnu.org/copyleft/gpl.html GNU Public License
 *
 */
// Used by the Moodle Core for identifing the format and displaying in the list of formats for a course in its settings.
// Possibly legacy to be removed after Moodle 2.0 is stable.
$string['namecollblct'] = 'Collapsed Topics with Collapsed Labels';
$string['formatcollblct'] = 'Collapsed Topics with Collapsed Labels';

// Used in format.php.
$string['collblcttoggle'] = 'Toggle';
$string['collblctsidewidth'] = '28px';

// Toggle all - Moodle Tracker CONTRIB-3190.
$string['collblctall'] = 'sections.';  // Leave as AMOS maintains only the latest translation - so previous versions are still supported.
$string['collblctopened'] = 'Open all';
$string['collblctclosed'] = 'Close all';

// Moodle 2.0 Enhancement - Moodle Tracker MDL-15252, MDL-21693 & MDL-22056 - http://docs.moodle.org/en/Development:Languages.
$string['sectionname'] = 'Section';
$string['pluginname'] = 'Collapsed Topics with Collapsed Labels';
$string['section0name'] = 'General';

// MDL-26105.
$string['page-course-view-collblct'] = 'Any course main page in the Collapsed Topics with Collapsed Labels format';
$string['page-course-view-collblct-x'] = 'Any course page in the Collapsed Topics with Collapsed Labels format';

// Moodle 2.3 Enhancement.
$string['hidefromothers'] = 'Hide section';
$string['showfromothers'] = 'Show section';
$string['currentsection'] = 'This section';
// These are 'topic' as they are only shown in 'topic' based structures.
$string['markedthissection'] = 'This topic is highlighted as the current topic';
$string['markthissection'] = 'Highlight this topic as the current topic';

// Reset.
$string['resetgrp'] = 'Reset:';
$string['resetallgrp'] = 'Reset all:';

// Layout enhancement - Moodle Tracker CONTRIB-3378.
$string['formatsettings'] = 'Format reset settings'; // CONTRIB-3529.
$string['formatsettingsinformation'] = '<br />To reset the settings of the course format to the defaults, click on the icon to the right.';
$string['setlayout'] = 'Set layout';

// Negative view of layout, kept for previous versions until such time as they are updated.
$string['setlayout_default'] = 'Default'; // 1.
$string['setlayout_no_toggle_section_x'] = 'No toggle section x'; // 2.
$string['setlayout_no_section_no'] = 'No section number'; // 3.
$string['setlayout_no_toggle_section_x_section_no'] = 'No toggle section x and section number'; // 4.
$string['setlayout_no_toggle_word'] = 'No toggle word'; // 5.
$string['setlayout_no_toggle_word_toggle_section_x'] = 'No toggle word and toggle section x'; // 6.
$string['setlayout_no_toggle_word_toggle_section_x_section_no'] = 'No toggle word, toggle section x and section number'; // 7.
// Positive view of layout.
$string['setlayout_all'] = "Toggle word, 'Topic x' / 'Week x' / 'Day x' and section number"; // 1.
$string['setlayout_toggle_word_section_number'] = 'Toggle word and section number'; // 2.
$string['setlayout_toggle_word_section_x'] = "Toggle word and 'Topic x' / 'Week x' / 'Day x'"; // 3.
$string['setlayout_toggle_word'] = 'Toggle word'; // 4.
$string['setlayout_toggle_section_x_section_number'] = "'Topic x' / 'Week x' / 'Day x' and section number"; // 5.
$string['setlayout_section_number'] = 'Section number'; // 6.
$string['setlayout_no_additions'] = 'No additions'; // 7.
$string['setlayout_toggle_section_x'] = "'Topic x' / 'Week x' / 'Day x'"; // 8.

$string['setlayoutelements'] = 'Set elements';
$string['setlayoutstructure'] = 'Set structure';
$string['setlayoutstructuretopic'] = 'Topic';
$string['setlayoutstructureweek'] = 'Week';
$string['setlayoutstructurelatweekfirst'] = 'Current Week First';
$string['setlayoutstructurecurrenttopicfirst'] = 'Current Topic First';
$string['setlayoutstructureday'] = 'Day';
$string['resetlayout'] = 'Layout'; // CONTRIB-3529.
$string['resetalllayout'] = 'Layouts';

// Colour enhancement - Moodle Tracker CONTRIB-3529.
$string['setcolour'] = 'Set colour';
$string['colourrule'] = "Please enter a valid RGB colour, six hexadecimal digits.";
$string['settoggleforegroundcolour'] = 'Toggle foreground';
$string['settoggleforegroundhovercolour'] = 'Toggle foreground hover';
$string['settogglebackgroundcolour'] = 'Toggle background';
$string['settogglebackgroundhovercolour'] = 'Toggle background hover';
$string['resetcolour'] = 'Colour';
$string['resetallcolour'] = 'Colours';

// Columns enhancement.
$string['setlayoutcolumns'] = 'Set columns';
$string['one'] = 'One';
$string['two'] = 'Two';
$string['three'] = 'Three';
$string['four'] = 'Four';
$string['setlayoutcolumnorientation'] = 'Set column orientation';
$string['columnvertical'] = 'Vertical';
$string['columnhorizontal'] = 'Horizontal';

// MDL-34917 - implemented in M2.5 but needs to be here to support M2.4- versions.
$string['maincoursepage'] = 'Main course page';

// Help.
$string['setlayoutelements_help'] = 'How much information about the toggles / sections you wish to be displayed.';
$string['setlayoutstructure_help'] = "The layout structure of the course.  You can choose between:

'Topics' - where each section is presented as a topic in section number order.

'Weeks' - where each section is presented as a week in ascending week order from the start date of the course.

'Current Week First' - which is the same as weeks but the current week is shown at the top and preceding weeks in descending order are displayed below except in editing mode where the structure is the same as 'Weeks'.

'Current Topic First' - which is the same as 'Topics' except that the current topic is shown at the top if it has been set.

'Day' - where each section is presented as a day in ascending day order from the start date of the course.";
$string['setlayout_help'] = 'Contains the settings to do with the layout of the format within the course.';
$string['resetlayout_help'] = 'Resets the layout element, structure, coloumns, icon position and shown section summary to the default values so it will be the same as a course the first time it is in the \'Collapsed Topics with Collapsed Labels\' format.';
$string['resetalllayout_help'] = 'Resets the layout to the default values for all courses so it will be the same as a course the first time it is in the \'Collapsed Topics with Collapsed Labels \'format.';
// Moodle Tracker CONTRIB-3529.
$string['setcolour_help'] = 'Contains the settings to do with the colour of the format within the course.';
$string['settoggleforegroundcolour_help'] = 'Sets the colour of the text on the toggle.';
$string['settoggleforegroundhovercolour_help'] = 'Sets the colour of the text on the toggle when the mouse moves over it.';
$string['settogglebackgroundcolour_help'] = 'Sets the background colour of the toggle.';
$string['settogglebackgroundhovercolour_help'] = 'Sets the background colour of the toggle when the mouse moves over it.';
$string['resetcolour_help'] = 'Resets the colours to the default values so it will be the same as a course the first time it is in the \'Collapsed Topics with Collapsed Labels\' format.';
$string['resetallcolour_help'] = 'Resets the colours to the default values for all courses so it will be the same as a course the first time it is in the \'Collapsed Topics with Collapsed Labels\' format.';
// Columns enhancement.
$string['setlayoutcolumns_help'] = 'How many columns to use.';
$string['setlayoutcolumnorientation_help'] =
'Vertical - Sections go top to bottom.

Horizontal - Sections go left to right.';

// Moodle 2.4 Course format refactoring - MDL-35218.
$string['numbersections'] = 'Number of sections';
$string['ctreset'] = 'Collapsed Topics with Collapsed Labels reset options';
$string['ctreset_help'] = 'Reset to Collapsed Topics with Collapsed Labels defaults.';

// Toggle alignment - CONTRIB-4098.
$string['settogglealignment'] = 'Set the toggle text alignment';
$string['settogglealignment_help'] = 'Sets the alignment of the text in the toggle.';
$string['left'] = 'Left';
$string['center'] = 'Centre';
$string['right'] = 'Right';
$string['resettogglealignment'] = 'Toggle alignment';
$string['resetalltogglealignment'] = 'Toggle alignments';
$string['resettogglealignment_help'] = 'Resets the toggle alignment to the default values so it will be the same as a course the first time it is in the \'Collapsed Topics with Collapsed Labels\' format.';
$string['resetalltogglealignment_help'] = 'Resets the toggle alignment to the default values for all courses so it will be the same as a course the first time it is in the \'Collapsed Topics with Collapsed Labels\' format.';

// Icon position - CONTRIB-4470.
$string['settoggleiconposition'] = 'Set icon position';
$string['settoggleiconposition_help'] = 'States that the icon should be on the left or the right of the toggle text.';
$string['defaulttoggleiconposition'] = 'Icon position';
$string['defaulttoggleiconposition_desc'] = 'States if the icon should be on the left or the right of the toggle text.';

// Icon set enhancement.
$string['settoggleiconset'] = 'Set icon set';
$string['settoggleiconset_help'] = 'Sets the icon set of the toggle.';
$string['settoggleallhover'] = 'Set toggle all icon hover';
$string['settoggleallhover_help'] = 'Sets if the toggle all icons will change when the mouse moves over them.';
$string['arrow'] = 'Arrow';
$string['bulb'] = 'Bulb';
$string['cloud'] = 'Cloud';
$string['eye'] = 'Eye';
$string['groundsignal'] = 'Ground signal';
$string['led'] = 'Light emitting diode';
$string['point'] = 'Point';
$string['power'] = 'Power';
$string['radio'] = 'Radio';
$string['smiley'] = 'Smiley';
$string['square'] = 'Square';
$string['sunmoon'] = 'Sun / Moon';
$string['switch'] = 'Switch';
$string['resettoggleiconset'] = 'Toggle icon set';
$string['resetalltoggleiconset'] = 'Toggle icon sets';
$string['resettoggleiconset_help'] = 'Resets the toggle icon set and toggle all hover to the default values so it will be the same as a course the first time it is in the \'Collapsed Topics with Collapsed Labels\' format.';
$string['resetalltoggleiconset_help'] = 'Resets the toggle icon set and toggle all hover to the default values for all courses so it will be the same as a course the first time it is in the \'Collapsed Topics with Collapsed Labels\' format.';

// Site Administration -> Plugins -> Course formats -> Collapsed Topics with Collapsed Labels.
$string['defaultheadingsub'] = 'Defaults';
$string['defaultheadingsubdesc'] = 'Default settings';
$string['configurationheadingsub'] = 'Configuration';
$string['configurationheadingsubdesc'] = 'Configuration settings';

$string['off'] = 'Off';
$string['on'] = 'On';
$string['defaultcoursedisplay'] = 'Course display';
$string['defaultcoursedisplay_desc'] = "Either show all the sections on a single page or section zero and the chosen section on page.";
$string['defaultlayoutelement'] = 'Layout';
// Negative view of layout, kept for previous versions until such time as they are updated.
$string['defaultlayoutelement_desc'] = "The layout setting can be one of:

'Default' with everything displayed.

No 'Topic x' / 'Week x' / 'Day x'.

No section number.

No 'Topic x' / 'Week x' / 'Day x' and no section number.

No 'Toggle' word.

No 'Toggle' word and no 'Topic x' / 'Week x' / 'Day x'.

No 'Toggle' word, no 'Topic x' / 'Week x' / 'Day x' and no section number.";
// Positive view of layout.
$string['defaultlayoutelement_descpositive'] = "The layout setting can be one of:

Toggle word, 'Topic x' / 'Week x' / 'Day x' and section number.

Toggle word and 'Topic x' / 'Week x' / 'Day x'.

Toggle word and section number.

'Topic x' / 'Week x' / 'Day x' and section number.

Toggle word.

'Topic x' / 'Week x' / 'Day x'.

Section number.

No additions.";

$string['defaultlayoutstructure'] = 'Structure configuration';
$string['defaultlayoutstructure_desc'] = "The structure setting can be one of:

Topic

Week

Latest Week First

Current Topic First

Day";

$string['defaultlayoutcolumns'] = 'Number of columns';
$string['defaultlayoutcolumns_desc'] = "Number of columns between one and four.";

$string['defaultlayoutcolumnorientation'] = 'Column orientation';
$string['defaultlayoutcolumnorientation_desc'] = "The default column orientation: Vertical or Horizontal.";

$string['defaulttgfgcolour'] = 'Toggle foreground colour';
$string['defaulttgfgcolour_desc'] = "Toggle foreground colour in hexidecimal RGB.";

$string['defaulttgfghvrcolour'] = 'Toggle foreground hover colour';
$string['defaulttgfghvrcolour_desc'] = "Toggle foreground hover colour in hexidecimal RGB.";

$string['defaulttgbgcolour'] = 'Toggle background colour';
$string['defaulttgbgcolour_desc'] = "Toggle background colour in hexidecimal RGB.";

$string['defaulttgbghvrcolour'] = 'Toggle background hover colour';
$string['defaulttgbghvrcolour_desc'] = "Toggle background hover colour in hexidecimal RGB.";

$string['defaulttogglealignment'] = 'Toggle text alignment';
$string['defaulttogglealignment_desc'] = "'Left', 'Centre' or 'Right'.";

$string['defaulttoggleiconset'] = 'Toggle icon set';
$string['defaulttoggleiconset_desc'] = "'Arrow'                => Arrow icon set.

'Bulb'                 => Bulb icon set.

'Cloud'                => Cloud icon set.

'Eye'                  => Eye icon set.

'Light Emitting Diode' => LED icon set.

'Point'                => Point icon set.

'Power'                => Power icon set.

'Radio'                => Radio icon set.

'Smiley'               => Smiley icon set.

'Square'               => Square icon set.

'Sun / Moon'           => Sun / Moon icon set.

'Switch'               => Switch icon set.";

$string['defaulttoggleallhover'] = 'Toggle all icon hovers';
$string['defaulttoggleallhover_desc'] = "'No' or 'Yes'.";

$string['defaulttogglepersistence'] = 'Toggle persistence';
$string['defaulttogglepersistence_desc'] = "'On' or 'Off'.  Turn off for an AJAX performance increase but user toggle selections will not be remembered on page refresh or revisit.

Note: When turning persistence off, please remove any rows containing 'collblct_toggle_x' in the 'name' field
      of the 'user_preferences' table in the database.  Where the 'x' in 'collblct_toggle_x' will be
      a course id.  This is to save space if you do not intend to turn it back on.";

$string['defaultuserpreference'] = 'What to do with the toggles when the user first accesses the course, adds more sections or toggle peristence is off';
$string['defaultuserpreference_desc'] = 'States what to do with the toggles when the user first accesses the course, the state of additional sections when they are added or toggle persistence id off.';

// Toggle icon size.
$string['defaulttoggleiconsize'] = 'Toggle icon size';
$string['defaulttoggleiconsize_desc'] = "Icon size: Small = 16px, Medium = 24px and Large = 32px.";
$string['small'] = 'Small';
$string['medium'] = 'Medium';
$string['large'] = 'Large';

// Toggle border radius.
$string['defaulttoggleborderradiustl'] = 'Toggle top left border radius';
$string['defaulttoggleborderradiustl_desc'] = 'Border top left radius of the toggle.';
$string['defaulttoggleborderradiustr'] = 'Toggle top right border radius';
$string['defaulttoggleborderradiustr_desc'] = 'Border top right radius of the toggle.';
$string['defaulttoggleborderradiusbr'] = 'Toggle bottom right border radius';
$string['defaulttoggleborderradiusbr_desc'] = 'Border bottom right radius of the toggle.';
$string['defaulttoggleborderradiusbl'] = 'Toggle bottom left border radius';
$string['defaulttoggleborderradiusbl_desc'] = 'Border bottom left radius of the toggle.';
$string['em0_0'] = '0.0em';
$string['em0_1'] = '0.1em';
$string['em0_2'] = '0.2em';
$string['em0_3'] = '0.3em';
$string['em0_4'] = '0.4em';
$string['em0_5'] = '0.5em';
$string['em0_6'] = '0.6em';
$string['em0_7'] = '0.7em';
$string['em0_8'] = '0.8em';
$string['em0_9'] = '0.9em';
$string['em1_0'] = '1.0em';
$string['em1_1'] = '1.1em';
$string['em1_2'] = '1.2em';
$string['em1_3'] = '1.3em';
$string['em1_4'] = '1.4em';
$string['em1_5'] = '1.5em';
$string['em1_6'] = '1.6em';
$string['em1_7'] = '1.7em';
$string['em1_8'] = '1.8em';
$string['em1_9'] = '1.9em';
$string['em2_0'] = '2.0em';
$string['em2_1'] = '2.1em';
$string['em2_2'] = '2.2em';
$string['em2_3'] = '2.3em';
$string['em2_4'] = '2.4em';
$string['em2_5'] = '2.5em';
$string['em2_6'] = '2.6em';
$string['em2_7'] = '2.7em';
$string['em2_8'] = '2.8em';
$string['em2_9'] = '2.9em';
$string['em3_0'] = '3.0em';
$string['em3_1'] = '3.1em';
$string['em3_2'] = '3.2em';
$string['em3_3'] = '3.3em';
$string['em3_4'] = '3.4em';
$string['em3_5'] = '3.5em';
$string['em3_6'] = '3.6em';
$string['em3_7'] = '3.7em';
$string['em3_8'] = '3.8em';
$string['em3_9'] = '3.9em';
$string['em4_0'] = '4.0em';

// Show section summary when collapsed.
$string['setshowsectionsummary'] = 'Show the section summary when collapsed';
$string['setshowsectionsummary_help'] = 'States if the section summary will always be shown regardless of toggle state.';
$string['defaultshowsectionsummary'] = 'Show the section summary when collapsed';
$string['defaultshowsectionsummary_desc'] = 'States if the section summary will always be shown regardless of toggle state.';

// Do not show date.
$string['donotshowdate'] = 'Do not show the date';
$string['donotshowdate_help'] = 'Do not show the date when using a weekly based structure and \'Use default section name\' has been un-ticked.';

// Capabilities.
$string['collblct:changelayout'] = 'Change or reset the layout';
$string['collblct:changecolour'] = 'Change or reset the colour';
$string['collblct:changetogglealignment'] = 'Change or reset the toggle alignment';
$string['collblct:changetoggleiconset'] = 'Change or reset the toggle icon set';

// Instructions.
$string['instructions'] = 'Instructions: Clicking on the section name will show / hide the section.';
$string['displayinstructions'] = 'Display instructions';
$string['displayinstructions_help'] = 'States that the instructions should be displayed to the user or not.';
$string['defaultdisplayinstructions'] = 'Display instructions to users';
$string['defaultdisplayinstructions_desc'] = "Display instructions to users informing them how to use the toggles.  Can be yes or no.";
$string['resetdisplayinstructions'] = 'Display instructions';
$string['resetalldisplayinstructions'] = 'Display instructions';
$string['resetdisplayinstructions_help'] = 'Resets the display instructions to the default value so it will be the same as a course the first time it is in the Collapsed Topics with Collapsed Labels format.';
$string['resetalldisplayinstructions_help'] = 'Resets the display instructions to the default value for all courses so it will be the same as a course the first time it is in the Collapsed Topics with Collapsed Labels format.';

// Readme.
$string['readme_title'] = 'Collapsed Topics with Collapsed Labels read-me';
$string['readme_desc'] = 'Please click on \'{$a->url}\' for lots more information about Collapsed Topics with Collapsed Labels.';

// HOOK: Craig's Strings.
// The color editing page.
$string['editsettingslink'] = "Change collapsed label settings for this course";
$string['editcolorspageheader'] = "Change foreground and background label colors for course";
$string['editcolorsheader'] = "Input foreground and background colors:";
$string['foregroundcolor'] = "Foreground (text) color for labels:";
$string['backgroundcolor'] = "Background color for labels:";
$string['returndefault'] = "Reset course colors to default values:";

// Added for access to color editor.
$string['noaccess'] = 'You have no access to this page.  Please contact a system administrator if you believe this is an error.';
$string['invalidcolor'] = 'Enter a valid RGB color - # and then 6 characters';

// Capability.
$string['collblct:caneditcollapsedlabelcolors'] = 'Can change label colors';