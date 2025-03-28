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

// English Pirate Translation of Collapsed Topics with Collapsed Labels Course Format.

// Used in format.php.
$string['collblctsidewidth']='40px';

// These are 'topic' as they are only shown in 'topic' based structures.
$string['markedthissection'] = 'Thy topic is illuminated as thee current topic';
$string['markthissection'] = 'Illuminate thy topic as thee current topic';

// Toggle all - Moodle Tracker CONTRIB-3190.
$string['collblctopened']='Untie';
$string['collblctclosed']='Tie';

// Layout enhancement - Moodle Tracker CONTRIB-3378.
$string['formatsettings'] = 'Ye format settings'; // CONTRIB-3529.
$string['setlayout'] = 'Set thee layout';
$string['setlayout_default'] = 'Default';
$string['setlayoutelements'] = 'Set thee elements';
// Negative view of layout, kept for previous versions until such time as they are updated.
$string['setlayout_no_toggle_section_x'] = "Nay 'Treasure Chest x' / 'Sailing Week x' / 'Sailing Day x'"; // 2.
$string['setlayout_no_toggle_section_x_section_no'] = "Nay 'Treasure Chest x' / 'Sailing Week x' / 'Sailing Day x' and section number"; // 4.
$string['setlayout_no_toggle_word_toggle_section_x'] = "Nay toggle word and 'Treasure Chest x' / 'Sailing Week x' / 'Sailing Day x'"; // 6.
$string['setlayout_no_toggle_word_toggle_section_x_section_no'] = "Nay toggle word, 'Treasure Chest x' / 'Sailing Week x' / 'Sailing Day x' and section number"; // 7.
// Positive view of layout.
$string['setlayout_all'] = "Toggle word, 'Treasure Chest x' / 'Sailing Week x' / 'Sailing Day x' and section number"; // 1.
$string['setlayout_toggle_word_section_x'] = "Toggle word and 'Treasure Chest x' / 'Sailing Week x' / 'Sailing Day x'"; // 3.
$string['setlayout_toggle_section_x'] = "'Treasure Chest x' / 'Sailing Week x' / 'Sailing Day x' and section number"; // 5.
$string['setlayout_toggle_section_x'] = "'Treasure Chest x' / 'Sailing Week x' / 'Sailing Day x'"; // 8.

$string['setlayoutstructure'] = 'Set thee structure';
$string['setlayoutstructuretopic']='Treasure Chest';
$string['setlayoutstructureweek']='Sailing Week';
$string['setlayoutstructureday'] = 'Sailing Day';
$string['setlayoutstructurelatweekfirst']='Current Sailing Week First';
$string['setlayoutstructurecurrenttopicfirst']='Current Treasure Chest First';
$string['resetlayout'] = 'Thee layout'; // CONTRIB-3529.
$string['resetalllayout'] = 'Thee layouts';

// Colour enhancement - Moodle Tracker CONTRIB-3529.
$string['setcolour'] = 'Set thee colour';
$string['colourrule'] = "Enter a valid RGB colour, a '#' and then six hexadecimal digits or walk thy plank.";
$string['settoggleforegroundcolour'] = 'Thy toggle foreground';
$string['settogglebackgroundhovercolour'] = 'Thy toggle foreground hover';
$string['settoggleforegroundcolour'] = 'Thy toggle foreground';
$string['settogglebackgroundhovercolour'] = 'Thy toggle background hover';
$string['resetcolour'] = 'Thee colour';
$string['resetallcolour'] = 'Thee colours';

// Columns enhancement.
$string['setlayoutcolumns'] = 'Set thee columns';
$string['one'] = 'One';
$string['two'] = 'Two';
$string['three'] = 'Three';
$string['four'] = 'Four';
$string['setlayoutcolumnorientation'] = 'Set the column orientation';
$string['columnvertical'] = 'Vertical as a mast';
$string['columnhorizontal'] = 'Horizontal as a cannon';

// Temporary until MDL-34917 in core.
$string['maincoursepage'] = 'Ye main course page';

// Help.
$string['setlayoutelements_help']='How much information about thee toggles / sections you wish to be displayed.';
$string['setlayoutstructure_help']="Avast ye landlubbers, this be thee layout structure of thee course.  Ye choose between:

'Treasure Chest' - where each section is presented as thy treasure chest in section number order.

'Sailing Week' - where each section is presented as thy week in ascending week order.

'Current Sailing Week First' - which is the same as weeks but thee current week is shown at thee top and preceding weeks in descending order are displayed below except in editing mode where thee structure is thy same as 'Weeks'.

'Current Treasure Chest First' - which is thee same as 'Treasure Chest' except that thee current treasure chest is shown at thee top if it has been set.

'Sailing Day' - where each section is presented as a day in thy ascending day order from thee start date of thee course.";
$string['setlayout_help'] = 'Contains thee settings to do with thee layout of the format within thy course.';
$string['resetlayout_help'] = 'Resets thee layout to thee default so it will be the same as a course the first time it is in thy Collapsed Topics with Collapsed Labels format';
$string['resetalllayout_help'] = 'Resets the layout to the default values for all courses so it will be the same as a course the first time it is in the Collapsed Topics with Collapsed Labels format.';
// Moodle Tracker CONTRIB-3529.
$string['setcolour_help'] = 'Contains thee settings to do with thy colour of the format within the course.';
$string['settoggleforegroundcolour_help'] = 'Sets thee colour of thy text on the toggle.';
$string['settoggleforegroundhovercolour_help'] = 'Sets thee colour of thy text on thy toggle when thee mouse scuttles over it.';
$string['settogglebackgroundcolour_help'] = 'Sets thee background of thy toggle.';
$string['settogglebackgroundhovercolour_help'] = 'Sets thee background of thy toggle when thee mouse scuttles over it.';
$string['resetcolour_help'] = 'Resets thee colours to thee default values so it will be thee same as a course thy first time it is in thee Collapsed Topics with Collapsed Labels format';
$string['resetallcolour_help'] = 'Resets thee colours to the default values for all courses so it will be thy same as a course the first time it is in thee Collapsed Topics with Collapsed Labels format.';
// Columns enhancement.
$string['setlayoutcolumns_help'] = 'How many columns to use.';

// Toggle alignment - CONTRIB-4098.
$string['settogglealignment'] = 'Set thee toggle text alignment';
$string['settogglealignment_help'] = 'Sets thee alignment of thee text in thy toggle.';
$string['left'] = 'Port';
$string['center'] = 'Midships';
$string['right'] = 'Starboard';
$string['resettogglealignment'] = 'Thee toggle alignment';
$string['resetalltogglealignment'] = 'Thee toggle alignments';
$string['resettogglealignment_help'] = 'Resets thee toggle alignment to thy default values so thy will be thy same as a course thee first time it is in thee Collapsed Topics with Collapsed Labels format.';
$string['resetalltogglealignment_help'] = 'Resets thee toggle alignment to thy default values for all courses so it will be thy same as a course thee first time it is in thee Collapsed Topics with Collapsed Labels format.';

// Icon position - CONTRIB-4470.
$string['settoggleiconposition'] = 'Set icon position';
$string['settoggleiconposition_help'] = 'States that thee icon should be on thy left or thee right of thy toggle text.';
$string['defaulttoggleiconposition'] = 'Icon position';
$string['defaulttoggleiconposition_desc'] = 'States if thee icon should be on thy left or thee right of thy toggle text.';

// Icon set enhancement.
$string['settoggleiconset'] = 'Set thee icon set';
$string['settoggleiconset_help'] = 'Sets thee icon set of thy toggle.';
$string['settoggleallhover'] = 'Set thee toggle all icon hover';
$string['settoggleallhover_help'] = 'Sets if thee toggle all icons will change when thy mouse moves over them.';
$string['arrow'] = 'Straight as an arrow';
$string['bulb'] = 'Lantern';
$string['cloud'] = 'Cloud';
$string['eye'] = 'Eyeball';
$string['led'] = 'LED from thee future';
$string['point'] = 'Point thee bow towards thy treasure';
$string['power'] = 'Power mee hearties';
$string['radio'] = 'Wireless';
$string['smiley'] = 'Smiley they bee not';
$string['square'] = 'Square riggin';
$string['sunmoon'] = 'Sun / Moon';
$string['switch'] = 'Switch thy flag';
$string['resettoggleiconset'] = 'Thee toggle icon set';
$string['resetalltoggleiconset'] = 'Thee toggle icon sets';
$string['resettoggleiconset_help'] = 'Resets thee toggle icon set and thy toggle all hover to thy default values so thy will be thee same as a course thee first time it is in thy Collapsed Topics with Collapsed Labels format.';
$string['resetalltoggleiconset_help'] = 'Resets thee toggle icon set and thy toggle all hover to thy default values for all courses so it will be thy same as a course thee first time it is in thy Collapsed Topics with Collapsed Labels format.';

// Site Administration -> Plugins -> Course formats -> Collapsed Topics with Collapsed Labels or Manage course formats - Settings.
$string['off'] = 'Off';
$string['on'] = 'On';
$string['defaultcoursedisplay'] = 'Course display default';
$string['defaultcoursedisplay_desc'] = "Either show all thee sections on a single page or section zero and thee chosen section on page.";
$string['defaultlayoutelement'] = 'Layout configuration';
// Negative view of layout, kept for previous versions until such time as they are updated.
$string['defaultlayoutelement_desc'] = "Thee layout setting can be one of:

'Default' with everything displayed.

Nay 'Treasure Chest x' / 'Sailing Week x' / 'Sailing Day x'.

Nay section number.

Nay 'Treasure Chest x' / 'Sailing Week x' / 'Sailing Day x' and nay section number.

Nay 'Toggle' word.

Nay 'Toggle' word and nay 'Treasure Chest x' / 'Sailing Week x' / 'Sailing Day x'.

Nay 'Toggle' word, nay 'Treasure Chest x' / 'Sailing Week x' / 'Sailing Day x' and nay section number.";
// Positive view of layout.
$string['defaultlayoutelement_descpositive'] = "The layout setting can be one of:

Toggle word, 'Treasure Chest x' / 'Sailing Week x' / 'Sailing Day x' and section number.

Toggle word and 'Treasure Chest x' / 'Sailing Week x' / 'Sailing Day x'.

Toggle word and section number.

'Treasure Chest x' / 'Sailing Week x' / 'Sailing Day x' and section number.

Toggle word.

'Treasure Chest x' / 'Sailing Week x' / 'Sailing Day x'.

Section number.

Nay additions.";

$string['defaultlayoutstructure'] = 'Structure configuration';
$string['defaultlayoutstructure_desc'] = "Thee structure setting can be one of:

Treasure Chest

Sailing Week

Current Sailing Week First

Current Treasure Chest First

Sailing Day";

$string['defaultlayoutcolumns'] = 'Number of columns';
$string['defaultlayoutcolumns_desc'] = "Number of columns between one and four.";

$string['defaultlayoutcolumnorientation'] = 'Column orientation';
$string['defaultlayoutcolumnorientation_desc'] = "Thee default column orientation: Vertical or Horizontal.";

$string['defaulttgfgcolour'] = 'Toggle foreground colour';
$string['defaulttgfgcolour_desc'] = "Toggle foreground colour in hexidecimal RGB.";

$string['defaulttgbgcolour'] = 'Toggle background colour';
$string['defaulttgbgcolour_desc'] = "Toggle background colour in hexidecimal RGB.";

$string['defaulttgbghvrcolour'] = 'Toggle background hover colour';
$string['defaulttgbghvrcolour_desc'] = "Toggle background hover colour in hexidecimal RGB.";

$string['defaulttogglepersistence'] = 'Toggle persistence';
$string['defaulttogglepersistence_desc'] = "'On' or 'Off'.  You may wish to turn off for an AJAX performance increase but sailor toggle selections will not be recalled on page refresh or revisit.

Note: If turning persistence off remove any rows containing 'collblct_toggle_x' in the 'name' field
      of the 'user_preferences' table in the database.  Where thee 'x' in 'collblct_toggle_x' will be
      a course id.";

$string['defaulttogglealignment'] = 'Toggle text alignment';
$string['defaulttogglealignment_desc'] = "'Left', 'Centre' or 'Right'.";

$string['defaulttoggleiconset'] = 'Toggle icon set';
$string['defaulttoggleiconset_desc'] = "'Straight as an arrow'                => Arrow icon set.

'Lantern'                             => Bulb icon set.

'Cloud'                               => Cloud icon set.

'Eyeball'                             => Eye icon set.

'LED from thee future'                => LED icon set.

'Point thee bow towards thy treasure' => Point icon set.

'Power mee hearties'                  => Power icon set.

'Wireless'                            => Radio icon set.

'Smiley they bee not'                 => Smiley icon set.

'Square riggin'                       => Square icon set.

'Sun / Moon'                          => Sun / Moon icon set.

'Switch thy flag'                     => Switch icon set.";

$string['defaulttoggleallhover'] = 'Toggle all icon hovers';
$string['defaulttoggleallhover_desc'] = "'Nay' or 'Aye'.";

// Default sailor preference.
$string['defaultuserpreference'] = 'What to do with thee toggles when thy sailor first accesses thee course or adds more sections';
$string['defaultuserpreference_desc'] = 'States what to do with thee toggles when thy sailor first accesses thee course or thee state of additional sections when they are added mee hearties.';

// Capabilities.
$string['collblct:changelayout'] = 'Change or reset thee layout';
$string['collblct:changecolour'] = 'Change or reset thee colour';
$string['collblct:changetogglealignment'] = 'Change or reset thee toggle alignment';
$string['collblct:changetoggleiconset'] = 'Change or reset thee toggle icon set';

// Instructions
$string['instructions'] = 'Orders: Avast! Clicking on thee section name will show / hide thy section.  And yee betin not forgetin dat!';
$string['displayinstructions'] = 'Display orders';
$string['displayinstructions_help'] = 'States that thee orders should be displayed to thy crew or not.';
$string['defaultdisplayinstructions'] = 'Display orders to crew';
$string['defaultdisplayinstructions_desc'] = "Display orders to crew informing them how to use thee toggles.  Can bee aye or nay.";
$string['resetdisplayinstructions'] = 'Display orders';
$string['resetalldisplayinstructions'] = 'Display orders';
$string['resetdisplayinstructions_help'] = 'Resets thy display orders to thee default value so it will be thy same as a course thee first time it is in thy Collapsed Topics with Collapsed Labels format.';
$string['resetalldisplayinstructions_help'] = 'Resets thy display orders to thee default value for all courses so it will be thee same as a course thee first time it is in thy Collapsed Topics with Collapsed Labels format.';

// Toggle icon size.
$string['defaulttoggleiconsize'] = 'Toggle icon size';
$string['defaulttoggleiconsize_desc'] = "Icon size: Cutter = 16px, Brig = 24px and Barque = 32px.";
$string['small'] = 'Cutter';
$string['medium'] = 'Brig';
$string['large'] = 'Barque';

// Toggle border radius.
$string['defaulttoggleborderradiustl'] = 'Toggle top left border radius';
$string['defaulttoggleborderradiustl_desc'] = 'Border top left radius of thy toggle.';
$string['defaulttoggleborderradiustr'] = 'Toggle top right border radius';
$string['defaulttoggleborderradiustr_desc'] = 'Border top right radius of thy toggle.';
$string['defaulttoggleborderradiusbr'] = 'Toggle bottom right border radius';
$string['defaulttoggleborderradiusbr_desc'] = 'Border bottom right radius of thy toggle.';
$string['defaulttoggleborderradiusbl'] = 'Toggle bottom left border radius';
$string['defaulttoggleborderradiusbl_desc'] = 'Border bottom left radius of thy toggle.';
