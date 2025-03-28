<?php
require_once '../../config.php';
require_once $CFG->dirroot.'/lib/formslib.php';

require_once 'lib.php';


/**
 * A form to work around a bug in moodle, where a warning will occur for a nosubmit button which is in a recurring element.
 *
 * For the implementation, the warning occurs when calling the function no_submit_button_pressed, since a recurring element
 *  button will be set as an array, rather than as a single variable when submitted in the POST data.
 *
 *
 */
class metadata_form extends moodleform {
    /**
     * @var array _recurring_nosubmit_buttons button ids of all recurring nosubmit buttons
     */
    private $_recurring_nosubmit_buttons;
    
    /**
     * Will set up form internal state
     *
     * @see lib/moodleform#definition()
     */
    function definition() {
        $this->_recurring_nosubmit_buttons = array();
    }
    
    /**
     *  Mark the given button as being a recurring element nosubmit button
     *
     *  @param object $form form that the button was added to
     *  @param string $button_id the id of the button that was added
     */
    protected function add_recurring_element_nosubmit_button($form, $button_id) {
        $form->registerNoSubmitButton($button_id);
        $this->_recurring_nosubmit_buttons[] = $button_id;
    }
    
    
    /**
     * Checks if button pressed is not for submitting the form
     *
     *   This overrides moodleform, and is a hack to fix the issue where recurring element buttons
     *     will be stored as an array, rather than a single item, in the url which causes a warning and causes our tests to fail
     *
     *   Most of the code was copied from moodleform, with the addition of the in_array check 
     *
     *
     * @staticvar bool $nosubmit keeps track of no submit button
     * @return bool
     */
    function no_submit_button_pressed() {
        static $nosubmit = null; // one check is enough
        if (!is_null($nosubmit)){
            return $nosubmit;
        }
        $mform =& $this->_form;
        $nosubmit = false;
        if (!$this->is_submitted()){
            return false;
        }
        foreach ($mform->_noSubmitButtons as $nosubmitbutton){
            // Need to handle this specially, since will be an array
            if (in_array($nosubmitbutton, $this->_recurring_nosubmit_buttons)) {
                if (optional_param_array($nosubmitbutton, 0, PARAM_RAW)){
                    $nosubmit = true;
                    break;
                }

            } else {
                if (optional_param($nosubmitbutton, 0, PARAM_RAW)){
                    $nosubmit = true;
                    break;
                }
            }
        }
        return $nosubmit;
    }
}