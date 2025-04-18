YUI.add('moodle-mod_quiz-autosave', function (Y, NAME) {

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
 * Auto-save functionality for during quiz attempts.
 *
 * @module moodle-mod_quiz-autosave
 */

/**
 * Auto-save functionality for during quiz attempts.
 *
 * @class M.mod_quiz.autosave
 */

M.mod_quiz = M.mod_quiz || {};
M.mod_quiz.autosave = {
    /**
     * The amount of time (in milliseconds) to wait between TinyMCE detections.
     *
     * @property TINYMCE_DETECTION_DELAY
     * @type Number
     * @default 500
     * @private
     */
    TINYMCE_DETECTION_DELAY:  500,

    /**
     * The number of times to try redetecting TinyMCE.
     *
     * @property TINYMCE_DETECTION_REPEATS
     * @type Number
     * @default 20
     * @private
     */
    TINYMCE_DETECTION_REPEATS: 20,

    /**
     * The delay (in milliseconds) between checking hidden input fields.
     *
     * @property WATCH_HIDDEN_DELAY
     * @type Number
     * @default 1000
     * @private
     */
    WATCH_HIDDEN_DELAY:      1000,

    /**
     * The number of failures to ignore before notifying the user.
     *
     * @property FAILURES_BEFORE_NOTIFY
     * @type Number
     * @default 1
     * @private
     */
    FAILURES_BEFORE_NOTIFY:     1,

    /**
     * The value to use when resetting the successful save counter.
     *
     * @property FIRST_SUCCESSFUL_SAVE
     * @static
     * @type Number
     * @default -1
     * @private
     */
    FIRST_SUCCESSFUL_SAVE:     -1,

    /**
     * The selectors used throughout this class.
     *
     * @property SELECTORS
     * @private
     * @type Object
     * @static
     */
    SELECTORS: {
        QUIZ_FORM:             '#responseform',
        VALUE_CHANGE_ELEMENTS: 'input, textarea, [contenteditable="true"]',
        CHANGE_ELEMENTS:       'input, select',
        HIDDEN_INPUTS:         'input[type=hidden]',
        CONNECTION_ERROR:      '#connection-error',
        CONNECTION_OK:         '#connection-ok'
    },

    /**
     * The script which handles the autosaves.
     *
     * @property AUTOSAVE_HANDLER
     * @type String
     * @default M.cfg.wwwroot + '/mod/quiz/autosave.ajax.php'
     * @private
     */
    AUTOSAVE_HANDLER: M.cfg.wwwroot + '/mod/quiz/autosave.ajax.php',

    /**
     * The delay (in milliseconds) between a change being made, and it being auto-saved.
     *
     * @property delay
     * @type Number
     * @default 120000
     * @private
     */
    delay: 120000,

    /**
     * A Node reference to the form we are monitoring.
     *
     * @property form
     * @type Node
     * @default null
     */
    form: null,

    /**
     * Whether the form has been modified since the last save started.
     *
     * @property dirty
     * @type boolean
     * @default false
     */
    dirty: false,

    /**
     * Timer object for the delay between form modifaction and the save starting.
     *
     * @property delay_timer
     * @type Object
     * @default null
     * @private
     */
    delay_timer: null,

    /**
     * Y.io transaction for the save ajax request.
     *
     * @property save_transaction
     * @type object
     * @default null
     */
    save_transaction: null,

    /**
     * Failed saves count.
     *
     * @property savefailures
     * @type Number
     * @default 0
     * @private
     */
    savefailures: 0,

    /**
     * Properly bound key change handler.
     *
     * @property editor_change_handler
     * @type EventHandle
     * @default null
     * @private
     */
    editor_change_handler: null,

    /**
     * Record of the value of all the hidden fields, last time they were checked.
     *
     * @property hidden_field_values
     * @type Object
     * @default {}
     */
    hidden_field_values: {},

    /**
     *  Save progress button to manually trigger a save.
     *  @type Object
     *  @default null
     */
    save_button: null,

    /**
     * Initialise the autosave code.
     *
     * @method init
     * @param {Number} delay the delay, in seconds, between a change being detected, and
     * a save happening.
     */
    init: function(delay) {
        this.form = Y.one(this.SELECTORS.QUIZ_FORM);
        if (!this.form) {
            return;
        }

        this.delay = delay * 1000;

        this.form.delegate('valuechange', this.value_changed, this.SELECTORS.VALUE_CHANGE_ELEMENTS, this);
        this.form.delegate('change',      this.value_changed, this.SELECTORS.CHANGE_ELEMENTS,       this);
        this.form.on('submit', this.stop_autosaving, this);

        this.init_tinymce(this.TINYMCE_DETECTION_REPEATS);

        this.save_hidden_field_values();
        this.watch_hidden_fields();
        this.save_clicked();
        this.display_saved();
    },

    save_hidden_field_values: function() {
        this.form.all(this.SELECTORS.HIDDEN_INPUTS).each(function(hidden) {
            var name  = hidden.get('name');
            if (!name) {
                return;
            }
            this.hidden_field_values[name] = hidden.get('value');
        }, this);
    },

    watch_hidden_fields: function() {
        this.detect_hidden_field_changes();
        Y.later(this.WATCH_HIDDEN_DELAY, this, this.watch_hidden_fields);
    },

    detect_hidden_field_changes: function() {
        this.form.all(this.SELECTORS.HIDDEN_INPUTS).each(function(hidden) {
            var name  = hidden.get('name'),
                value = hidden.get('value');
            if (!name) {
                return;
            }
            if (!(name in this.hidden_field_values) || value !== this.hidden_field_values[name]) {
                this.hidden_field_values[name] = value;
                this.value_changed({target: hidden});
            }
        }, this);
    },

    /**
     * Initialise watching of TinyMCE specifically.
     *
     * Because TinyMCE might load slowly, and after us, we need to keep
     * trying, until we detect TinyMCE is there, or enough time has passed.
     * This is based on the TINYMCE_DETECTION_DELAY and
     * TINYMCE_DETECTION_REPEATS properties.
     *
     *
     * @method init_tinymce
     * @param {Number} repeatcount The number of attempts made so far.
     */
    init_tinymce: function(repeatcount) {
        if (typeof tinyMCE === 'undefined') {
            if (repeatcount > 0) {
                Y.later(this.TINYMCE_DETECTION_DELAY, this, this.init_tinymce, [repeatcount - 1]);
            } else {
            }
            return;
        }

        this.editor_change_handler = Y.bind(this.editor_changed, this);
        tinyMCE.onAddEditor.add(Y.bind(this.init_tinymce_editor, this));
    },

    /**
     * Initialise watching of a specific TinyMCE editor.
     *
     * @method init_tinymce_editor
     * @param {EventFacade} e
     * @param {Object} editor The TinyMCE editor object
     */
    init_tinymce_editor: function(e, editor) {
        editor.onChange.add(this.editor_change_handler);
        editor.onRedo.add(this.editor_change_handler);
        editor.onUndo.add(this.editor_change_handler);
        editor.onKeyDown.add(this.editor_change_handler);
    },

    value_changed: function(e) {
        var name = e.target.getAttribute('name');
        if (name === 'thispage' || name === 'scrollpos' || (name && name.match(/_:flagged$/))) {
            return; // Not interesting.
        }

        // Fallback to the ID when the name is not present (in the case of content editable).
        name = name || '#' + e.target.getAttribute('id');
        this.start_save_timer_if_necessary();
    },

    editor_changed: function(editor) {
        this.start_save_timer_if_necessary();
    },

    start_save_timer_if_necessary: function() {
        this.dirty = true;

        if (this.delay_timer || this.save_transaction) {
            // Already counting down or daving.
            return;
        }

        this.start_save_timer();
    },

    start_save_timer: function() {
        this.cancel_delay();
        this.delay_timer = Y.later(this.delay, this, this.save_changes);
    },

    cancel_delay: function() {
        if (this.delay_timer && this.delay_timer !== true) {
            this.delay_timer.cancel();
        }
        this.delay_timer = null;
    },

    save_changes: function() {
        this.cancel_delay();
        this.dirty = false;

        if (this.is_time_nearly_over()) {
            this.stop_autosaving();
            return;
        }

        if (typeof tinyMCE !== 'undefined') {
            tinyMCE.triggerSave();
        }
        this.save_transaction = Y.io(this.AUTOSAVE_HANDLER, {
            method:  'POST',
            form:    {id: this.form},
            on:      {
                success: this.save_done,
                failure: this.save_failed
            },
            context: this
        });
    },

    save_done: function(transactionid, response) {
        if (response.responseText !== 'OK') {
            // Because IIS is useless, Moodle can't send proper HTTP response
            // codes, so we have to detect failures manually.
            this.save_failed(transactionid, response);
            return;
        }

        this.display_saved();
        this.save_transaction = null;

        if (this.dirty) {
            this.start_save_timer();
        }

        if (this.savefailures > 0) {
            Y.one(this.SELECTORS.CONNECTION_ERROR).hide();
            Y.one(this.SELECTORS.CONNECTION_OK).show();
            this.savefailures = this.FIRST_SUCCESSFUL_SAVE;
        } else if (this.savefailures === this.FIRST_SUCCESSFUL_SAVE) {
            Y.one(this.SELECTORS.CONNECTION_OK).hide();
            this.savefailures = 0;
        }
    },

    save_failed: function() {
        this.save_transaction = null;

        // We want to retry soon.
        this.start_save_timer();

        this.savefailures = Math.max(1, this.savefailures + 1);
        if (this.savefailures === this.FAILURES_BEFORE_NOTIFY) {
            Y.one(this.SELECTORS.CONNECTION_ERROR).show();
            Y.one(this.SELECTORS.CONNECTION_OK).hide();
        }
    },

    is_time_nearly_over: function() {
        return M.mod_quiz.timer && M.mod_quiz.timer.endtime &&
                (new Date().getTime() + 2*this.delay) > M.mod_quiz.timer.endtime;
    },

    stop_autosaving: function() {
        this.cancel_delay();
        this.delay_timer = true;
        if (this.save_transaction) {
            this.save_transaction.abort();
        }
    },

    get_time: function() {
        var currentTime = new Date();
        var hours = currentTime.getHours();
        var minutes = currentTime.getMinutes();
        var seconds = currentTime.getSeconds();
        if (minutes < 10){
            minutes = "0" + minutes;
        }
        if (seconds < 10){
            seconds = "0" + seconds;
        }
        var now = hours + ":" + minutes + ":" + seconds;

        return now;
    },

    display_saved: function() {
        var saved = Y.one('#last-saved');
        saved.setContent(M.util.get_string('lastsaved','quiz') + this.get_time());
    },

    save_clicked: function() {
        this.save_button = Y.one('.savequiz');
        this.save_button.on('click', function(e) {
            this.save_changes();
            this.update_saved();
            e.stopPropagation();
            e.preventDefault();
        }, this);
        this.save_button.on('mouseup', function(e) {
            e.target.blur();
        });
    },

    update_saved: function() {
        var saved = Y.one('#last-saved');
        var newsaved = saved.cloneNode(true);

        saved.replace(newsaved);
    }
};


}, '@VERSION@', {"requires": ["base", "node", "event", "event-valuechange", "node-event-delegate", "io-form"]});
