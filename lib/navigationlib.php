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
 * This file contains classes used to manage the navigation structures within Moodle.
 *
 * @since      Moodle 2.0
 * @package    core
 * @copyright  2009 Sam Hemelryk
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * The name that will be used to separate the navigation cache within SESSION
 */
define('NAVIGATION_CACHE_NAME', 'navigation');
define('NAVIGATION_SITE_ADMIN_CACHE_NAME', 'navigationsiteadmin');

/**
 * This class is used to represent a node in a navigation tree
 *
 * This class is used to represent a node in a navigation tree within Moodle,
 * the tree could be one of global navigation, settings navigation, or the navbar.
 * Each node can be one of two types either a Leaf (default) or a branch.
 * When a node is first created it is created as a leaf, when/if children are added
 * the node then becomes a branch.
 *
 * @package   core
 * @category  navigation
 * @copyright 2009 Sam Hemelryk
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class navigation_node implements renderable {
    /** @var int Used to identify this node a leaf (default) 0 */
    const NODETYPE_LEAF =   0;
    /** @var int Used to identify this node a branch, happens with children  1 */
    const NODETYPE_BRANCH = 1;
    /** @var null Unknown node type null */
    const TYPE_UNKNOWN =    null;
    /** @var int System node type 0 */
    const TYPE_ROOTNODE =   0;
    /** @var int System node type 1 */
    const TYPE_SYSTEM =     1;
    /** @var int Category node type 10 */
    const TYPE_CATEGORY =   10;
    /** var int Category displayed in MyHome navigation node */
    const TYPE_MY_CATEGORY = 11;
    /** @var int Course node type 20 */
    const TYPE_COURSE =     20;
    /** @var int Course Structure node type 30 */
    const TYPE_SECTION =    30;
    /** @var int Activity node type, e.g. Forum, Quiz 40 */
    const TYPE_ACTIVITY =   40;
    /** @var int Resource node type, e.g. Link to a file, or label 50 */
    const TYPE_RESOURCE =   50;
    /** @var int A custom node type, default when adding without specifing type 60 */
    const TYPE_CUSTOM =     60;
    /** @var int Setting node type, used only within settings nav 70 */
    const TYPE_SETTING =    70;
    /** @var int site admin branch node type, used only within settings nav 71 */
    const TYPE_SITE_ADMIN = 71;
    /** @var int Setting node type, used only within settings nav 80 */
    const TYPE_USER =       80;
    /** @var int Setting node type, used for containers of no importance 90 */
    const TYPE_CONTAINER =  90;
    /** var int Course the current user is not enrolled in */
    const COURSE_OTHER = 0;
    /** var int Course the current user is enrolled in but not viewing */
    const COURSE_MY = 1;
    /** var int Course the current user is currently viewing */
    const COURSE_CURRENT = 2;

    /** @var int Parameter to aid the coder in tracking [optional] */
    public $id = null;
    /** @var string|int The identifier for the node, used to retrieve the node */
    public $key = null;
    /** @var string The text to use for the node */
    public $text = null;
    /** @var string Short text to use if requested [optional] */
    public $shorttext = null;
    /** @var string The title attribute for an action if one is defined */
    public $title = null;
    /** @var string A string that can be used to build a help button */
    public $helpbutton = null;
    /** @var moodle_url|action_link|null An action for the node (link) */
    public $action = null;
    /** @var pix_icon The path to an icon to use for this node */
    public $icon = null;
    /** @var int See TYPE_* constants defined for this class */
    public $type = self::TYPE_UNKNOWN;
    /** @var int See NODETYPE_* constants defined for this class */
    public $nodetype = self::NODETYPE_LEAF;
    /** @var bool If set to true the node will be collapsed by default */
    public $collapse = false;
    /** @var bool If set to true the node will be expanded by default */
    public $forceopen = false;
    /** @var array An array of CSS classes for the node */
    public $classes = array();
    /** @var navigation_node_collection An array of child nodes */
    public $children = array();
    /** @var bool If set to true the node will be recognised as active */
    public $isactive = false;
    /** @var bool If set to true the node will be dimmed */
    public $hidden = false;
    /** @var bool If set to false the node will not be displayed */
    public $display = true;
    /** @var bool If set to true then an HR will be printed before the node */
    public $preceedwithhr = false;
    /** @var bool If set to true the the navigation bar should ignore this node */
    public $mainnavonly = false;
    /** @var bool If set to true a title will be added to the action no matter what */
    public $forcetitle = false;
    /** @var navigation_node A reference to the node parent, you should never set this directly you should always call set_parent */
    public $parent = null;
    /** @var bool Override to not display the icon even if one is provided **/
    public $hideicon = false;
    /** @var bool Set to true if we KNOW that this node can be expanded.  */
    public $isexpandable = false;
    /** @var array */
    protected $namedtypes = array(0=>'system',10=>'category',20=>'course',30=>'structure',40=>'activity',50=>'resource',60=>'custom',70=>'setting',71=>'siteadmin', 80=>'user');
    /** @var moodle_url */
    protected static $fullmeurl = null;
    /** @var bool toogles auto matching of active node */
    public static $autofindactive = true;
    /** @var bool should we load full admin tree or rely on AJAX for performance reasons */
    protected static $loadadmintree = false;
    /** @var mixed If set to an int, that section will be included even if it has no activities */
    public $includesectionnum = false;

    /**
     * Constructs a new navigation_node
     *
     * @param array|string $properties Either an array of properties or a string to use
     *                     as the text for the node
     */
    public function __construct($properties) {
        if (is_array($properties)) {
            // Check the array for each property that we allow to set at construction.
            // text         - The main content for the node
            // shorttext    - A short text if required for the node
            // icon         - The icon to display for the node
            // type         - The type of the node
            // key          - The key to use to identify the node
            // parent       - A reference to the nodes parent
            // action       - The action to attribute to this node, usually a URL to link to
            if (array_key_exists('text', $properties)) {
                $this->text = $properties['text'];
            }
            if (array_key_exists('shorttext', $properties)) {
                $this->shorttext = $properties['shorttext'];
            }
            if (!array_key_exists('icon', $properties)) {
                $properties['icon'] = new pix_icon('i/navigationitem', '');
            }
            $this->icon = $properties['icon'];
            if ($this->icon instanceof pix_icon) {
                if (empty($this->icon->attributes['class'])) {
                    $this->icon->attributes['class'] = 'navicon';
                } else {
                    $this->icon->attributes['class'] .= ' navicon';
                }
            }
            if (array_key_exists('type', $properties)) {
                $this->type = $properties['type'];
            } else {
                $this->type = self::TYPE_CUSTOM;
            }
            if (array_key_exists('key', $properties)) {
                $this->key = $properties['key'];
            }
            // This needs to happen last because of the check_if_active call that occurs
            if (array_key_exists('action', $properties)) {
                $this->action = $properties['action'];
                if (is_string($this->action)) {
                    $this->action = new moodle_url($this->action);
                }
                if (self::$autofindactive) {
                    $this->check_if_active();
                }
            }
            if (array_key_exists('parent', $properties)) {
                $this->set_parent($properties['parent']);
            }
        } else if (is_string($properties)) {
            $this->text = $properties;
        }
        if ($this->text === null) {
            throw new coding_exception('You must set the text for the node when you create it.');
        }
        // Instantiate a new navigation node collection for this nodes children
        $this->children = new navigation_node_collection();
    }

    /**
     * Checks if this node is the active node.
     *
     * This is determined by comparing the action for the node against the
     * defined URL for the page. A match will see this node marked as active.
     *
     * @param int $strength One of URL_MATCH_EXACT, URL_MATCH_PARAMS, or URL_MATCH_BASE
     * @return bool
     */
    public function check_if_active($strength=URL_MATCH_EXACT) {
        global $FULLME, $PAGE;
        // Set fullmeurl if it hasn't already been set
        if (self::$fullmeurl == null) {
            if ($PAGE->has_set_url()) {
                self::override_active_url(new moodle_url($PAGE->url));
            } else {
                self::override_active_url(new moodle_url($FULLME));
            }
        }

        // Compare the action of this node against the fullmeurl
        if ($this->action instanceof moodle_url && $this->action->compare(self::$fullmeurl, $strength)) {
            $this->make_active();
            return true;
        }
        return false;
    }

    /**
     * This sets the URL that the URL of new nodes get compared to when locating
     * the active node.
     *
     * The active node is the node that matches the URL set here. By default this
     * is either $PAGE->url or if that hasn't been set $FULLME.
     *
     * @param moodle_url $url The url to use for the fullmeurl.
     * @param bool $loadadmintree use true if the URL point to administration tree
     */
    public static function override_active_url(moodle_url $url, $loadadmintree = false) {
        // Clone the URL, in case the calling script changes their URL later.
        self::$fullmeurl = new moodle_url($url);
        // True means we do not want AJAX loaded admin tree, required for all admin pages.
        if ($loadadmintree) {
            // Do not change back to false if already set.
            self::$loadadmintree = true;
        }
    }

    /**
     * Use when page is linked from the admin tree,
     * if not used navigation could not find the page using current URL
     * because the tree is not fully loaded.
     */
    public static function require_admin_tree() {
        self::$loadadmintree = true;
    }

    /**
     * Creates a navigation node, ready to add it as a child using add_node
     * function. (The created node needs to be added before you can use it.)
     * @param string $text
     * @param moodle_url|action_link $action
     * @param int $type
     * @param string $shorttext
     * @param string|int $key
     * @param pix_icon $icon
     * @return navigation_node
     */
    public static function create($text, $action=null, $type=self::TYPE_CUSTOM,
            $shorttext=null, $key=null, pix_icon $icon=null) {
        // Properties array used when creating the new navigation node
        $itemarray = array(
            'text' => $text,
            'type' => $type
        );
        // Set the action if one was provided
        if ($action!==null) {
            $itemarray['action'] = $action;
        }
        // Set the shorttext if one was provided
        if ($shorttext!==null) {
            $itemarray['shorttext'] = $shorttext;
        }
        // Set the icon if one was provided
        if ($icon!==null) {
            $itemarray['icon'] = $icon;
        }
        // Set the key
        $itemarray['key'] = $key;
        // Construct and return
        return new navigation_node($itemarray);
    }

    /**
     * Adds a navigation node as a child of this node.
     *
     * @param string $text
     * @param moodle_url|action_link $action
     * @param int $type
     * @param string $shorttext
     * @param string|int $key
     * @param pix_icon $icon
     * @return navigation_node
     */
    public function add($text, $action=null, $type=self::TYPE_CUSTOM, $shorttext=null, $key=null, pix_icon $icon=null) {
        // Create child node
        $childnode = self::create($text, $action, $type, $shorttext, $key, $icon);

        // Add the child to end and return
        return $this->add_node($childnode);
    }

    /**
     * Adds a navigation node as a child of this one, given a $node object
     * created using the create function.
     * @param navigation_node $childnode Node to add
     * @param string $beforekey
     * @return navigation_node The added node
     */
    public function add_node(navigation_node $childnode, $beforekey=null) {
        // First convert the nodetype for this node to a branch as it will now have children
        if ($this->nodetype !== self::NODETYPE_BRANCH) {
            $this->nodetype = self::NODETYPE_BRANCH;
        }
        // Set the parent to this node
        $childnode->set_parent($this);

        // Default the key to the number of children if not provided
        if ($childnode->key === null) {
            $childnode->key = $this->children->count();
        }

        // Add the child using the navigation_node_collections add method
        $node = $this->children->add($childnode, $beforekey);

        // If added node is a category node or the user is logged in and it's a course
        // then mark added node as a branch (makes it expandable by AJAX)
        $type = $childnode->type;
        if (($type == self::TYPE_CATEGORY) || (isloggedin() && ($type == self::TYPE_COURSE)) || ($type == self::TYPE_MY_CATEGORY) ||
                ($type === self::TYPE_SITE_ADMIN)) {
            $node->nodetype = self::NODETYPE_BRANCH;
        }
        // If this node is hidden mark it's children as hidden also
        if ($this->hidden) {
            $node->hidden = true;
        }
        // Return added node (reference returned by $this->children->add()
        return $node;
    }

    /**
     * Return a list of all the keys of all the child nodes.
     * @return array the keys.
     */
    public function get_children_key_list() {
        return $this->children->get_key_list();
    }

    /**
     * Searches for a node of the given type with the given key.
     *
     * This searches this node plus all of its children, and their children....
     * If you know the node you are looking for is a child of this node then please
     * use the get method instead.
     *
     * @param int|string $key The key of the node we are looking for
     * @param int $type One of navigation_node::TYPE_*
     * @return navigation_node|false
     */
    public function find($key, $type) {
        return $this->children->find($key, $type);
    }

    /**
     * Get the child of this node that has the given key + (optional) type.
     *
     * If you are looking for a node and want to search all children + thier children
     * then please use the find method instead.
     *
     * @param int|string $key The key of the node we are looking for
     * @param int $type One of navigation_node::TYPE_*
     * @return navigation_node|false
     */
    public function get($key, $type=null) {
        return $this->children->get($key, $type);
    }

    /**
     * Removes this node.
     *
     * @return bool
     */
    public function remove() {
        return $this->parent->children->remove($this->key, $this->type);
    }

    /**
     * Checks if this node has or could have any children
     *
     * @return bool Returns true if it has children or could have (by AJAX expansion)
     */
    public function has_children() {
        return ($this->nodetype === navigation_node::NODETYPE_BRANCH || $this->children->count()>0 || $this->isexpandable);
    }

    /**
     * Marks this node as active and forces it open.
     *
     * Important: If you are here because you need to mark a node active to get
     * the navigation to do what you want have you looked at {@link navigation_node::override_active_url()}?
     * You can use it to specify a different URL to match the active navigation node on
     * rather than having to locate and manually mark a node active.
     */
    public function make_active() {
        $this->isactive = true;
        $this->add_class('active_tree_node');
        $this->force_open();
        if ($this->parent !== null) {
            $this->parent->make_inactive();
        }
    }

    /**
     * Marks a node as inactive and recusised back to the base of the tree
     * doing the same to all parents.
     */
    public function make_inactive() {
        $this->isactive = false;
        $this->remove_class('active_tree_node');
        if ($this->parent !== null) {
            $this->parent->make_inactive();
        }
    }

    /**
     * Forces this node to be open and at the same time forces open all
     * parents until the root node.
     *
     * Recursive.
     */
    public function force_open() {
        $this->forceopen = true;
        if ($this->parent !== null) {
            $this->parent->force_open();
        }
    }

    /**
     * Adds a CSS class to this node.
     *
     * @param string $class
     * @return bool
     */
    public function add_class($class) {
        if (!in_array($class, $this->classes)) {
            $this->classes[] = $class;
        }
        return true;
    }

    /**
     * Removes a CSS class from this node.
     *
     * @param string $class
     * @return bool True if the class was successfully removed.
     */
    public function remove_class($class) {
        if (in_array($class, $this->classes)) {
            $key = array_search($class,$this->classes);
            if ($key!==false) {
                unset($this->classes[$key]);
                return true;
            }
        }
        return false;
    }

    /**
     * Sets the title for this node and forces Moodle to utilise it.
     * @param string $title
     */
    public function title($title) {
        $this->title = $title;
        $this->forcetitle = true;
    }

    /**
     * Resets the page specific information on this node if it is being unserialised.
     */
    public function __wakeup(){
        $this->forceopen = false;
        $this->isactive = false;
        $this->remove_class('active_tree_node');
    }

    /**
     * Checks if this node or any of its children contain the active node.
     *
     * Recursive.
     *
     * @return bool
     */
    public function contains_active_node() {
        if ($this->isactive) {
            return true;
        } else {
            foreach ($this->children as $child) {
                if ($child->isactive || $child->contains_active_node()) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Finds the active node.
     *
     * Searches this nodes children plus all of the children for the active node
     * and returns it if found.
     *
     * Recursive.
     *
     * @return navigation_node|false
     */
    public function find_active_node() {
        if ($this->isactive) {
            return $this;
        } else {
            foreach ($this->children as &$child) {
                $outcome = $child->find_active_node();
                if ($outcome !== false) {
                    return $outcome;
                }
            }
        }
        return false;
    }

    /**
     * Searches all children for the best matching active node
     * @return navigation_node|false
     */
    public function search_for_active_node() {
        if ($this->check_if_active(URL_MATCH_BASE)) {
            return $this;
        } else {
            foreach ($this->children as &$child) {
                $outcome = $child->search_for_active_node();
                if ($outcome !== false) {
                    return $outcome;
                }
            }
        }
        return false;
    }

    /**
     * Gets the content for this node.
     *
     * @param bool $shorttext If true shorttext is used rather than the normal text
     * @return string
     */
    public function get_content($shorttext=false) {
        if ($shorttext && $this->shorttext!==null) {
            return format_string($this->shorttext);
        } else {
            return format_string($this->text);
        }
    }

    /**
     * Gets the title to use for this node.
     *
     * @return string
     */
    public function get_title() {
        if ($this->forcetitle || $this->action != null){
            return $this->title;
        } else {
            return '';
        }
    }

    /**
     * Gets the CSS class to add to this node to describe its type
     *
     * @return string
     */
    public function get_css_type() {
        if (array_key_exists($this->type, $this->namedtypes)) {
            return 'type_'.$this->namedtypes[$this->type];
        }
        return 'type_unknown';
    }

    /**
     * Finds all nodes that are expandable by AJAX
     *
     * @param array $expandable An array by reference to populate with expandable nodes.
     */
    public function find_expandable(array &$expandable) {
        foreach ($this->children as &$child) {
            if ($child->display && $child->has_children() && $child->children->count() == 0) {
                $child->id = 'expandable_branch_'.$child->type.'_'.clean_param($child->key, PARAM_ALPHANUMEXT);
                $this->add_class('canexpand');
                $expandable[] = array('id' => $child->id, 'key' => $child->key, 'type' => $child->type);
            }
            $child->find_expandable($expandable);
        }
    }

    /**
     * Finds all nodes of a given type (recursive)
     *
     * @param int $type One of navigation_node::TYPE_*
     * @return array
     */
    public function find_all_of_type($type) {
        $nodes = $this->children->type($type);
        foreach ($this->children as &$node) {
            $childnodes = $node->find_all_of_type($type);
            $nodes = array_merge($nodes, $childnodes);
        }
        return $nodes;
    }

    /**
     * Removes this node if it is empty
     */
    public function trim_if_empty() {
        if ($this->children->count() == 0) {
            $this->remove();
        }
    }

    /**
     * Creates a tab representation of this nodes children that can be used
     * with print_tabs to produce the tabs on a page.
     *
     * call_user_func_array('print_tabs', $node->get_tabs_array());
     *
     * @param array $inactive
     * @param bool $return
     * @return array Array (tabs, selected, inactive, activated, return)
     */
    public function get_tabs_array(array $inactive=array(), $return=false) {
        $tabs = array();
        $rows = array();
        $selected = null;
        $activated = array();
        foreach ($this->children as $node) {
            $tabs[] = new tabobject($node->key, $node->action, $node->get_content(), $node->get_title());
            if ($node->contains_active_node()) {
                if ($node->children->count() > 0) {
                    $activated[] = $node->key;
                    foreach ($node->children as $child) {
                        if ($child->contains_active_node()) {
                            $selected = $child->key;
                        }
                        $rows[] = new tabobject($child->key, $child->action, $child->get_content(), $child->get_title());
                    }
                } else {
                    $selected = $node->key;
                }
            }
        }
        return array(array($tabs, $rows), $selected, $inactive, $activated, $return);
    }

    /**
     * Sets the parent for this node and if this node is active ensures that the tree is properly
     * adjusted as well.
     *
     * @param navigation_node $parent
     */
    public function set_parent(navigation_node $parent) {
        // Set the parent (thats the easy part)
        $this->parent = $parent;
        // Check if this node is active (this is checked during construction)
        if ($this->isactive) {
            // Force all of the parent nodes open so you can see this node
            $this->parent->force_open();
            // Make all parents inactive so that its clear where we are.
            $this->parent->make_inactive();
        }
    }

    /**
     * Hides the node and any children it has.
     *
     * @since Moodle 2.5
     * @param array $typestohide Optional. An array of node types that should be hidden.
     *      If null all nodes will be hidden.
     *      If an array is given then nodes will only be hidden if their type mtatches an element in the array.
     *          e.g. array(navigation_node::TYPE_COURSE) would hide only course nodes.
     */
    public function hide(array $typestohide = null) {
        if ($typestohide === null || in_array($this->type, $typestohide)) {
            $this->display = false;
            if ($this->has_children()) {
                foreach ($this->children as $child) {
                    $child->hide($typestohide);
                }
            }
        }
    }
}

/**
 * Navigation node collection
 *
 * This class is responsible for managing a collection of navigation nodes.
 * It is required because a node's unique identifier is a combination of both its
 * key and its type.
 *
 * Originally an array was used with a string key that was a combination of the two
 * however it was decided that a better solution would be to use a class that
 * implements the standard IteratorAggregate interface.
 *
 * @package   core
 * @category  navigation
 * @copyright 2010 Sam Hemelryk
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class navigation_node_collection implements IteratorAggregate {
    /**
     * A multidimensional array to where the first key is the type and the second
     * key is the nodes key.
     * @var array
     */
    protected $collection = array();
    /**
     * An array that contains references to nodes in the same order they were added.
     * This is maintained as a progressive array.
     * @var array
     */
    protected $orderedcollection = array();
    /**
     * A reference to the last node that was added to the collection
     * @var navigation_node
     */
    protected $last = null;
    /**
     * The total number of items added to this array.
     * @var int
     */
    protected $count = 0;

    /**
     * Adds a navigation node to the collection
     *
     * @param navigation_node $node Node to add
     * @param string $beforekey If specified, adds before a node with this key,
     *   otherwise adds at end
     * @return navigation_node Added node
     */
    public function add(navigation_node $node, $beforekey=null) {
        global $CFG;
        $key = $node->key;
        $type = $node->type;

        // First check we have a 2nd dimension for this type
        if (!array_key_exists($type, $this->orderedcollection)) {
            $this->orderedcollection[$type] = array();
        }
        // Check for a collision and report if debugging is turned on
        if ($CFG->debug && array_key_exists($key, $this->orderedcollection[$type])) {
            debugging('Navigation node intersect: Adding a node that already exists '.$key, DEBUG_DEVELOPER);
        }

        // Find the key to add before
        $newindex = $this->count;
        $last = true;
        if ($beforekey !== null) {
            foreach ($this->collection as $index => $othernode) {
                if ($othernode->key === $beforekey) {
                    $newindex = $index;
                    $last = false;
                    break;
                }
            }
            if ($newindex === $this->count) {
                debugging('Navigation node add_before: Reference node not found ' . $beforekey .
                        ', options: ' . implode(' ', $this->get_key_list()), DEBUG_DEVELOPER);
            }
        }

        // Add the node to the appropriate place in the by-type structure (which
        // is not ordered, despite the variable name)
        $this->orderedcollection[$type][$key] = $node;
        if (!$last) {
            // Update existing references in the ordered collection (which is the
            // one that isn't called 'ordered') to shuffle them along if required
            for ($oldindex = $this->count; $oldindex > $newindex; $oldindex--) {
                $this->collection[$oldindex] = $this->collection[$oldindex - 1];
            }
        }
        // Add a reference to the node to the progressive collection.
        $this->collection[$newindex] = $this->orderedcollection[$type][$key];
        // Update the last property to a reference to this new node.
        $this->last = $this->orderedcollection[$type][$key];

        // Reorder the array by index if needed
        if (!$last) {
            ksort($this->collection);
        }
        $this->count++;
        // Return the reference to the now added node
        return $node;
    }

    /**
     * Return a list of all the keys of all the nodes.
     * @return array the keys.
     */
    public function get_key_list() {
        $keys = array();
        foreach ($this->collection as $node) {
            $keys[] = $node->key;
        }
        return $keys;
    }

    /**
     * Fetches a node from this collection.
     *
     * @param string|int $key The key of the node we want to find.
     * @param int $type One of navigation_node::TYPE_*.
     * @return navigation_node|null
     */
    public function get($key, $type=null) {
        if ($type !== null) {
            // If the type is known then we can simply check and fetch
            if (!empty($this->orderedcollection[$type][$key])) {
                return $this->orderedcollection[$type][$key];
            }
        } else {
            // Because we don't know the type we look in the progressive array
            foreach ($this->collection as $node) {
                if ($node->key === $key) {
                    return $node;
                }
            }
        }
        return false;
    }

    /**
     * Searches for a node with matching key and type.
     *
     * This function searches both the nodes in this collection and all of
     * the nodes in each collection belonging to the nodes in this collection.
     *
     * Recursive.
     *
     * @param string|int $key  The key of the node we want to find.
     * @param int $type  One of navigation_node::TYPE_*.
     * @return navigation_node|null
     */
    public function find($key, $type=null) {
        if ($type !== null && array_key_exists($type, $this->orderedcollection) && array_key_exists($key, $this->orderedcollection[$type])) {
            return $this->orderedcollection[$type][$key];
        } else {
            $nodes = $this->getIterator();
            // Search immediate children first
            foreach ($nodes as &$node) {
                if ($node->key === $key && ($type === null || $type === $node->type)) {
                    return $node;
                }
            }
            // Now search each childs children
            foreach ($nodes as &$node) {
                $result = $node->children->find($key, $type);
                if ($result !== false) {
                    return $result;
                }
            }
        }
        return false;
    }

    /**
     * Fetches the last node that was added to this collection
     *
     * @return navigation_node
     */
    public function last() {
        return $this->last;
    }

    /**
     * Fetches all nodes of a given type from this collection
     *
     * @param string|int $type  node type being searched for.
     * @return array ordered collection
     */
    public function type($type) {
        if (!array_key_exists($type, $this->orderedcollection)) {
            $this->orderedcollection[$type] = array();
        }
        return $this->orderedcollection[$type];
    }
    /**
     * Removes the node with the given key and type from the collection
     *
     * @param string|int $key The key of the node we want to find.
     * @param int $type
     * @return bool
     */
    public function remove($key, $type=null) {
        $child = $this->get($key, $type);
        if ($child !== false) {
            foreach ($this->collection as $colkey => $node) {
                if ($node->key === $key && $node->type == $type) {
                    unset($this->collection[$colkey]);
                    $this->collection = array_values($this->collection);
                    break;
                }
            }
            unset($this->orderedcollection[$child->type][$child->key]);
            $this->count--;
            return true;
        }
        return false;
    }

    /**
     * Gets the number of nodes in this collection
     *
     * This option uses an internal count rather than counting the actual options to avoid
     * a performance hit through the count function.
     *
     * @return int
     */
    public function count() {
        return $this->count;
    }
    /**
     * Gets an array iterator for the collection.
     *
     * This is required by the IteratorAggregator interface and is used by routines
     * such as the foreach loop.
     *
     * @return ArrayIterator
     */
    public function getIterator() {
        return new ArrayIterator($this->collection);
    }
}

/**
 * The global navigation class used for... the global navigation
 *
 * This class is used by PAGE to store the global navigation for the site
 * and is then used by the settings nav and navbar to save on processing and DB calls
 *
 * See
 * {@link lib/pagelib.php} {@link moodle_page::initialise_theme_and_output()}
 * {@link lib/ajax/getnavbranch.php} Called by ajax
 *
 * @package   core
 * @category  navigation
 * @copyright 2009 Sam Hemelryk
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class global_navigation extends navigation_node {
    /** @var moodle_page The Moodle page this navigation object belongs to. */
    protected $page;
    /** @var bool switch to let us know if the navigation object is initialised*/
    protected $initialised = false;
    /** @var array An array of course information */
    protected $mycourses = array();
    /** @var navigation_node[] An array for containing  root navigation nodes */
    protected $rootnodes = array();
    /** @var bool A switch for whether to show empty sections in the navigation */
    protected $showemptysections = true;
    /** @var bool A switch for whether courses should be shown within categories on the navigation. */
    protected $showcategories = null;
    /** @var null@var bool A switch for whether or not to show categories in the my courses branch. */
    protected $showmycategories = null;
    /** @var array An array of stdClasses for users that the navigation is extended for */
    protected $extendforuser = array();
    /** @var navigation_cache */
    protected $cache;
    /** @var array An array of course ids that are present in the navigation */
    protected $addedcourses = array();
    /** @var bool */
    protected $allcategoriesloaded = false;
    /** @var array An array of category ids that are included in the navigation */
    protected $addedcategories = array();
    /** @var int expansion limit */
    protected $expansionlimit = 0;
    /** @var int userid to allow parent to see child's profile page navigation */
    protected $useridtouseforparentchecks = 0;
    /** @var cache_session A cache that stores information on expanded courses */
    protected $cacheexpandcourse = null;

    /** Used when loading categories to load all top level categories [parent = 0] **/
    const LOAD_ROOT_CATEGORIES = 0;
    /** Used when loading categories to load all categories **/
    const LOAD_ALL_CATEGORIES = -1;

    /**
     * Constructs a new global navigation
     *
     * @param moodle_page $page The page this navigation object belongs to
     */
    public function __construct(moodle_page $page) {
        global $CFG, $SITE, $USER;

        if (during_initial_install()) {
            return;
        }

        if (get_home_page() == HOMEPAGE_SITE) {
            // We are using the site home for the root element
            $properties = array(
                'key' => 'home',
                'type' => navigation_node::TYPE_SYSTEM,
                'text' => get_string('home'),
                'action' => new moodle_url('/')
            );
        } else {
            // We are using the users my moodle for the root element
            $properties = array(
                'key' => 'myhome',
                'type' => navigation_node::TYPE_SYSTEM,
                'text' => get_string('myhome'),
                'action' => new moodle_url('/my/')
            );
        }

        // Use the parents constructor.... good good reuse
        parent::__construct($properties);

        // Initalise and set defaults
        $this->page = $page;
        $this->forceopen = true;
        $this->cache = new navigation_cache(NAVIGATION_CACHE_NAME);
    }

    /**
     * Mutator to set userid to allow parent to see child's profile
     * page navigation. See MDL-25805 for initial issue. Linked to it
     * is an issue explaining why this is a REALLY UGLY HACK thats not
     * for you to use!
     *
     * @param int $userid userid of profile page that parent wants to navigate around.
     */
    public function set_userid_for_parent_checks($userid) {
        $this->useridtouseforparentchecks = $userid;
    }


    /**
     * Initialises the navigation object.
     *
     * This causes the navigation object to look at the current state of the page
     * that it is associated with and then load the appropriate content.
     *
     * This should only occur the first time that the navigation structure is utilised
     * which will normally be either when the navbar is called to be displayed or
     * when a block makes use of it.
     *
     * @return bool
     */
    public function initialise() {
        global $CFG, $SITE, $USER;
        // Check if it has already been initialised
        if ($this->initialised || during_initial_install()) {
            return true;
        }
        $this->initialised = true;

        // Set up the five base root nodes. These are nodes where we will put our
        // content and are as follows:
        // site: Navigation for the front page.
        // myprofile: User profile information goes here.
        // currentcourse: The course being currently viewed.
        // mycourses: The users courses get added here.
        // courses: Additional courses are added here.
        // users: Other users information loaded here.
        $this->rootnodes = array();
        if (get_home_page() == HOMEPAGE_SITE) {
            // The home element should be my moodle because the root element is the site
            if (isloggedin() && !isguestuser()) {  // Makes no sense if you aren't logged in
                $this->rootnodes['home'] = $this->add(get_string('myhome'), new moodle_url('/my/'), self::TYPE_SETTING, null, 'home');
            }
        } else {
            // The home element should be the site because the root node is my moodle
            $this->rootnodes['home'] = $this->add(get_string('sitehome'), new moodle_url('/'), self::TYPE_SETTING, null, 'home');
            if (!empty($CFG->defaulthomepage) && ($CFG->defaulthomepage == HOMEPAGE_MY)) {
                // We need to stop automatic redirection
                $this->rootnodes['home']->action->param('redirect', '0');
            }
        }
        $this->rootnodes['site'] = $this->add_course($SITE);
        $this->rootnodes['myprofile'] = $this->add(get_string('myprofile'), null, self::TYPE_USER, null, 'myprofile');
        $this->rootnodes['currentcourse'] = $this->add(get_string('currentcourse'), null, self::TYPE_ROOTNODE, null, 'currentcourse');
        $this->rootnodes['mycourses'] = $this->add(get_string('mycourses'), new moodle_url('/my/'), self::TYPE_ROOTNODE, null, 'mycourses');
        $this->rootnodes['courses'] = $this->add(get_string('courses'), new moodle_url('/course/index.php'), self::TYPE_ROOTNODE, null, 'courses');
        $this->rootnodes['users'] = $this->add(get_string('users'), null, self::TYPE_ROOTNODE, null, 'users');

        // We always load the frontpage course to ensure it is available without
        // JavaScript enabled.
        $this->add_front_page_course_essentials($this->rootnodes['site'], $SITE);
        $this->load_course_sections($SITE, $this->rootnodes['site']);

        $course = $this->page->course;

        // $issite gets set to true if the current pages course is the sites frontpage course
        $issite = ($this->page->course->id == $SITE->id);
        // Determine if the user is enrolled in any course.
        $enrolledinanycourse = enrol_user_sees_own_courses();

        $this->rootnodes['currentcourse']->mainnavonly = true;
        if ($enrolledinanycourse) {
            $this->rootnodes['mycourses']->isexpandable = true;
            if ($CFG->navshowallcourses) {
                // When we show all courses we need to show both the my courses and the regular courses branch.
                $this->rootnodes['courses']->isexpandable = true;
            }
        } else {
            $this->rootnodes['courses']->isexpandable = true;
        }

        // Load the users enrolled courses if they are viewing the My Moodle page AND the admin has not
        // set that they wish to keep the My Courses branch collapsed by default.
        if (!empty($CFG->navexpandmycourses) && $this->rootnodes['mycourses']->isactive){
            $this->load_courses_enrolled();
        } else {
            $this->rootnodes['mycourses']->collapse = true;
            $this->rootnodes['mycourses']->make_inactive();
        }

        $canviewcourseprofile = true;

        // Next load context specific content into the navigation
        switch ($this->page->context->contextlevel) {
            case CONTEXT_SYSTEM :
                // Nothing left to do here I feel.
                break;
            case CONTEXT_COURSECAT :
                // This is essential, we must load categories.
                $this->load_all_categories($this->page->context->instanceid, true);
                break;
            case CONTEXT_BLOCK :
            case CONTEXT_COURSE :
                if ($issite) {
                    // Nothing left to do here.
                    break;
                }

                // Load the course associated with the current page into the navigation.
                $coursenode = $this->add_course($course, false, self::COURSE_CURRENT);
                // If the course wasn't added then don't try going any further.
                if (!$coursenode) {
                    $canviewcourseprofile = false;
                    break;
                }

                // If the user is not enrolled then we only want to show the
                // course node and not populate it.

                // Not enrolled, can't view, and hasn't switched roles
                if (!can_access_course($course)) {
                    if ($coursenode->isexpandable === true) {
                        // Obviously the situation has changed, update the cache and adjust the node.
                        // This occurs if the user access to a course has been revoked (one way or another) after
                        // initially logging in for this session.
                        $this->get_expand_course_cache()->set($course->id, 1);
                        $coursenode->isexpandable = true;
                        $coursenode->nodetype = self::NODETYPE_BRANCH;
                    }
                    // Very ugly hack - do not force "parents" to enrol into course their child is enrolled in,
                    // this hack has been propagated from user/view.php to display the navigation node. (MDL-25805)
                    if (!$this->current_user_is_parent_role()) {
                        $coursenode->make_active();
                        $canviewcourseprofile = false;
                        break;
                    }
                }

                if ($coursenode->isexpandable === false) {
                    // Obviously the situation has changed, update the cache and adjust the node.
                    // This occurs if the user has been granted access to a course (one way or another) after initially
                    // logging in for this session.
                    $this->get_expand_course_cache()->set($course->id, 1);
                    $coursenode->isexpandable = true;
                    $coursenode->nodetype = self::NODETYPE_BRANCH;
                }

                // Add the essentials such as reports etc...
                $this->add_course_essentials($coursenode, $course);
                // Extend course navigation with it's sections/activities
                $this->load_course_sections($course, $coursenode);
                if (!$coursenode->contains_active_node() && !$coursenode->search_for_active_node()) {
                    $coursenode->make_active();
                }

                break;
            case CONTEXT_MODULE :
                if ($issite) {
                    // If this is the site course then most information will have
                    // already been loaded.
                    // However we need to check if there is more content that can
                    // yet be loaded for the specific module instance.
                    $activitynode = $this->rootnodes['site']->find($this->page->cm->id, navigation_node::TYPE_ACTIVITY);
                    if ($activitynode) {
                        $this->load_activity($this->page->cm, $this->page->course, $activitynode);
                    }
                    break;
                }

                $course = $this->page->course;
                $cm = $this->page->cm;

                // Load the course associated with the page into the navigation
                $coursenode = $this->add_course($course, false, self::COURSE_CURRENT);

                // If the course wasn't added then don't try going any further.
                if (!$coursenode) {
                    $canviewcourseprofile = false;
                    break;
                }

                // If the user is not enrolled then we only want to show the
                // course node and not populate it.
                if (!can_access_course($course)) {
                    $coursenode->make_active();
                    $canviewcourseprofile = false;
                    break;
                }

                $this->add_course_essentials($coursenode, $course);

                // Load the course sections into the page
                $this->load_course_sections($course, $coursenode, null, $cm);
                $activity = $coursenode->find($cm->id, navigation_node::TYPE_ACTIVITY);
                if (!empty($activity)) {
                    // Finally load the cm specific navigaton information
                    $this->load_activity($cm, $course, $activity);
                    // Check if we have an active ndoe
                    if (!$activity->contains_active_node() && !$activity->search_for_active_node()) {
                        // And make the activity node active.
                        $activity->make_active();
                    }
                }
                break;
            case CONTEXT_USER :
                if ($issite) {
                    // The users profile information etc is already loaded
                    // for the front page.
                    break;
                }
                $course = $this->page->course;
                // Load the course associated with the user into the navigation
                $coursenode = $this->add_course($course, false, self::COURSE_CURRENT);

                // If the course wasn't added then don't try going any further.
                if (!$coursenode) {
                    $canviewcourseprofile = false;
                    break;
                }

                // If the user is not enrolled then we only want to show the
                // course node and not populate it.
                if (!can_access_course($course)) {
                    $coursenode->make_active();
                    $canviewcourseprofile = false;
                    break;
                }
                $this->add_course_essentials($coursenode, $course);
                $this->load_course_sections($course, $coursenode);
                break;
        }

        // Load for the current user
        $this->load_for_user();
        if ($this->page->context->contextlevel >= CONTEXT_COURSE && $this->page->context->instanceid != $SITE->id && $canviewcourseprofile) {
            $this->load_for_user(null, true);
        }
        // Load each extending user into the navigation.
        foreach ($this->extendforuser as $user) {
            if ($user->id != $USER->id) {
                $this->load_for_user($user);
            }
        }

        // Give the local plugins a chance to include some navigation if they want.
        foreach (core_component::get_plugin_list_with_file('local', 'lib.php', true) as $plugin => $file) {
            $function = "local_{$plugin}_extends_navigation";
            $oldfunction = "{$plugin}_extends_navigation";
            if (function_exists($function)) {
                // This is the preferred function name as there is less chance of conflicts
                $function($this);
            } else if (function_exists($oldfunction)) {
                // We continue to support the old function name to ensure backwards compatibility
                debugging("Deprecated local plugin navigation callback: Please rename '{$oldfunction}' to '{$function}'. Support for the old callback will be dropped after the release of 2.4", DEBUG_DEVELOPER);
                $oldfunction($this);
            }
        }

        // Remove any empty root nodes
        foreach ($this->rootnodes as $node) {
            // Dont remove the home node
            /** @var navigation_node $node */
            if ($node->key !== 'home' && !$node->has_children() && !$node->isactive) {
                $node->remove();
            }
        }

        if (!$this->contains_active_node()) {
            $this->search_for_active_node();
        }

        // If the user is not logged in modify the navigation structure as detailed
        // in {@link http://docs.moodle.org/dev/Navigation_2.0_structure}
        if (!isloggedin()) {
            $activities = clone($this->rootnodes['site']->children);
            $this->rootnodes['site']->remove();
            $children = clone($this->children);
            $this->children = new navigation_node_collection();
            foreach ($activities as $child) {
                $this->children->add($child);
            }
            foreach ($children as $child) {
                $this->children->add($child);
            }
        }
        return true;
    }

    /**
     * Returns true if the current user is a parent of the user being currently viewed.
     *
     * If the current user is not viewing another user, or if the current user does not hold any parent roles over the
     * other user being viewed this function returns false.
     * In order to set the user for whom we are checking against you must call {@link set_userid_for_parent_checks()}
     *
     * @since Moodle 2.4
     * @return bool
     */
    protected function current_user_is_parent_role() {
        global $USER, $DB;
        if ($this->useridtouseforparentchecks && $this->useridtouseforparentchecks != $USER->id) {
            $usercontext = context_user::instance($this->useridtouseforparentchecks, MUST_EXIST);
            if (!has_capability('moodle/user:viewdetails', $usercontext)) {
                return false;
            }
            if ($DB->record_exists('role_assignments', array('userid' => $USER->id, 'contextid' => $usercontext->id))) {
                return true;
            }
        }
        return false;
    }

    /**
     * Returns true if courses should be shown within categories on the navigation.
     *
     * @param bool $ismycourse Set to true if you are calculating this for a course.
     * @return bool
     */
    protected function show_categories($ismycourse = false) {
        global $CFG, $DB;
        if ($ismycourse) {
            return $this->show_my_categories();
        }
        if ($this->showcategories === null) {
            $show = false;
            if ($this->page->context->contextlevel == CONTEXT_COURSECAT) {
                $show = true;
            } else if (!empty($CFG->navshowcategories) && $DB->count_records('course_categories') > 1) {
                $show = true;
            }
            $this->showcategories = $show;
        }
        return $this->showcategories;
    }

    /**
     * Returns true if we should show categories in the My Courses branch.
     * @return bool
     */
    protected function show_my_categories() {
        global $CFG, $DB;
        if ($this->showmycategories === null) {
            $this->showmycategories = !empty($CFG->navshowmycoursecategories) && $DB->count_records('course_categories') > 1;
        }
        return $this->showmycategories;
    }

    /**
     * Loads the courses in Moodle into the navigation.
     *
     * @global moodle_database $DB
     * @param string|array $categoryids An array containing categories to load courses
     *                     for, OR null to load courses for all categories.
     * @return array An array of navigation_nodes one for each course
     */
    protected function load_all_courses($categoryids = null) {
        global $CFG, $DB, $SITE;

        // Work out the limit of courses.
        $limit = 20;
        if (!empty($CFG->navcourselimit)) {
            $limit = $CFG->navcourselimit;
        }

        $toload = (empty($CFG->navshowallcourses))?self::LOAD_ROOT_CATEGORIES:self::LOAD_ALL_CATEGORIES;

        // If we are going to show all courses AND we are showing categories then
        // to save us repeated DB calls load all of the categories now
        if ($this->show_categories()) {
            $this->load_all_categories($toload);
        }

        // Will be the return of our efforts
        $coursenodes = array();

        // Check if we need to show categories.
        if ($this->show_categories()) {
            // Hmmm we need to show categories... this is going to be painful.
            // We now need to fetch up to $limit courses for each category to
            // be displayed.
            if ($categoryids !== null) {
                if (!is_array($categoryids)) {
                    $categoryids = array($categoryids);
                }
                list($categorywhere, $categoryparams) = $DB->get_in_or_equal($categoryids, SQL_PARAMS_NAMED, 'cc');
                $categorywhere = 'WHERE cc.id '.$categorywhere;
            } else if ($toload == self::LOAD_ROOT_CATEGORIES) {
                $categorywhere = 'WHERE cc.depth = 1 OR cc.depth = 2';
                $categoryparams = array();
            } else {
                $categorywhere = '';
                $categoryparams = array();
            }

            // First up we are going to get the categories that we are going to
            // need so that we can determine how best to load the courses from them.
            $sql = "SELECT cc.id, COUNT(c.id) AS coursecount
                        FROM {course_categories} cc
                    LEFT JOIN {course} c ON c.category = cc.id
                            {$categorywhere}
                    GROUP BY cc.id";
            $categories = $DB->get_recordset_sql($sql, $categoryparams);
            $fullfetch = array();
            $partfetch = array();
            foreach ($categories as $category) {
                if (!$this->can_add_more_courses_to_category($category->id)) {
                    continue;
                }
                if ($category->coursecount > $limit * 5) {
                    $partfetch[] = $category->id;
                } else if ($category->coursecount > 0) {
                    $fullfetch[] = $category->id;
                }
            }
            $categories->close();

            if (count($fullfetch)) {
                // First up fetch all of the courses in categories where we know that we are going to
                // need the majority of courses.
                list($categoryids, $categoryparams) = $DB->get_in_or_equal($fullfetch, SQL_PARAMS_NAMED, 'lcategory');
                $ccselect = ', ' . context_helper::get_preload_record_columns_sql('ctx');
                $ccjoin = "LEFT JOIN {context} ctx ON (ctx.instanceid = c.id AND ctx.contextlevel = :contextlevel)";
                $categoryparams['contextlevel'] = CONTEXT_COURSE;
                $sql = "SELECT c.id, c.sortorder, c.visible, c.fullname, c.shortname, c.category $ccselect
                            FROM {course} c
                                $ccjoin
                            WHERE c.category {$categoryids}
                        ORDER BY c.sortorder ASC";
                $coursesrs = $DB->get_recordset_sql($sql, $categoryparams);
                foreach ($coursesrs as $course) {
                    if ($course->id == $SITE->id) {
                        // This should not be necessary, frontpage is not in any category.
                        continue;
                    }
                    if (array_key_exists($course->id, $this->addedcourses)) {
                        // It is probably better to not include the already loaded courses
                        // directly in SQL because inequalities may confuse query optimisers
                        // and may interfere with query caching.
                        continue;
                    }
                    if (!$this->can_add_more_courses_to_category($course->category)) {
                        continue;
                    }
                    context_helper::preload_from_record($course);
                    if (!$course->visible && !is_role_switched($course->id) && !has_capability('moodle/course:viewhiddencourses', context_course::instance($course->id))) {
                        continue;
                    }
                    $coursenodes[$course->id] = $this->add_course($course);
                }
                $coursesrs->close();
            }

            if (count($partfetch)) {
                // Next we will work our way through the categories where we will likely only need a small
                // proportion of the courses.
                foreach ($partfetch as $categoryid) {
                    $ccselect = ', ' . context_helper::get_preload_record_columns_sql('ctx');
                    $ccjoin = "LEFT JOIN {context} ctx ON (ctx.instanceid = c.id AND ctx.contextlevel = :contextlevel)";
                    $sql = "SELECT c.id, c.sortorder, c.visible, c.fullname, c.shortname, c.category $ccselect
                                FROM {course} c
                                    $ccjoin
                                WHERE c.category = :categoryid
                            ORDER BY c.sortorder ASC";
                    $courseparams = array('categoryid' => $categoryid, 'contextlevel' => CONTEXT_COURSE);
                    $coursesrs = $DB->get_recordset_sql($sql, $courseparams, 0, $limit * 5);
                    foreach ($coursesrs as $course) {
                        if ($course->id == $SITE->id) {
                            // This should not be necessary, frontpage is not in any category.
                            continue;
                        }
                        if (array_key_exists($course->id, $this->addedcourses)) {
                            // It is probably better to not include the already loaded courses
                            // directly in SQL because inequalities may confuse query optimisers
                            // and may interfere with query caching.
                            // This also helps to respect expected $limit on repeated executions.
                            continue;
                        }
                        if (!$this->can_add_more_courses_to_category($course->category)) {
                            break;
                        }
                        context_helper::preload_from_record($course);
                        if (!$course->visible && !is_role_switched($course->id) && !has_capability('moodle/course:viewhiddencourses', context_course::instance($course->id))) {
                            continue;
                        }
                        $coursenodes[$course->id] = $this->add_course($course);
                    }
                    $coursesrs->close();
                }
            }
        } else {
            // Prepare the SQL to load the courses and their contexts
            list($courseids, $courseparams) = $DB->get_in_or_equal(array_keys($this->addedcourses), SQL_PARAMS_NAMED, 'lc', false);
            $ccselect = ', ' . context_helper::get_preload_record_columns_sql('ctx');
            $ccjoin = "LEFT JOIN {context} ctx ON (ctx.instanceid = c.id AND ctx.contextlevel = :contextlevel)";
            $courseparams['contextlevel'] = CONTEXT_COURSE;
            $sql = "SELECT c.id, c.sortorder, c.visible, c.fullname, c.shortname, c.category $ccselect
                        FROM {course} c
                            $ccjoin
                        WHERE c.id {$courseids}
                    ORDER BY c.sortorder ASC";
            $coursesrs = $DB->get_recordset_sql($sql, $courseparams);
            foreach ($coursesrs as $course) {
                if ($course->id == $SITE->id) {
                    // frotpage is not wanted here
                    continue;
                }
                if ($this->page->course && ($this->page->course->id == $course->id)) {
                    // Don't include the currentcourse in this nodelist - it's displayed in the Current course node
                    continue;
                }
                context_helper::preload_from_record($course);
                if (!$course->visible && !is_role_switched($course->id) && !has_capability('moodle/course:viewhiddencourses', context_course::instance($course->id))) {
                    continue;
                }
                $coursenodes[$course->id] = $this->add_course($course);
                if (count($coursenodes) >= $limit) {
                    break;
                }
            }
            $coursesrs->close();
        }

        return $coursenodes;
    }

    /**
     * Returns true if more courses can be added to the provided category.
     *
     * @param int|navigation_node|stdClass $category
     * @return bool
     */
    protected function can_add_more_courses_to_category($category) {
        global $CFG;
        $limit = 20;
        if (!empty($CFG->navcourselimit)) {
            $limit = (int)$CFG->navcourselimit;
        }
        if (is_numeric($category)) {
            if (!array_key_exists($category, $this->addedcategories)) {
                return true;
            }
            $coursecount = count($this->addedcategories[$category]->children->type(self::TYPE_COURSE));
        } else if ($category instanceof navigation_node) {
            if (($category->type != self::TYPE_CATEGORY) || ($category->type != self::TYPE_MY_CATEGORY)) {
                return false;
            }
            $coursecount = count($category->children->type(self::TYPE_COURSE));
        } else if (is_object($category) && property_exists($category,'id')) {
            $coursecount = count($this->addedcategories[$category->id]->children->type(self::TYPE_COURSE));
        }
        return ($coursecount <= $limit);
    }

    /**
     * Loads all categories (top level or if an id is specified for that category)
     *
     * @param int $categoryid The category id to load or null/0 to load all base level categories
     * @param bool $showbasecategories If set to true all base level categories will be loaded as well
     *        as the requested category and any parent categories.
     * @return navigation_node|void returns a navigation node if a category has been loaded.
     */
    protected function load_all_categories($categoryid = self::LOAD_ROOT_CATEGORIES, $showbasecategories = false) {
        global $CFG, $DB;

        // Check if this category has already been loaded
        if ($this->allcategoriesloaded || ($categoryid < 1 && $this->is_category_fully_loaded($categoryid))) {
            return true;
        }

        $catcontextsql = context_helper::get_preload_record_columns_sql('ctx');
        $sqlselect = "SELECT cc.*, $catcontextsql
                      FROM {course_categories} cc
                      JOIN {context} ctx ON cc.id = ctx.instanceid";
        $sqlwhere = "WHERE ctx.contextlevel = ".CONTEXT_COURSECAT;
        $sqlorder = "ORDER BY cc.depth ASC, cc.sortorder ASC, cc.id ASC";
        $params = array();

        $categoriestoload = array();
        if ($categoryid == self::LOAD_ALL_CATEGORIES) {
            // We are going to load all categories regardless... prepare to fire
            // on the database server!
        } else if ($categoryid == self::LOAD_ROOT_CATEGORIES) { // can be 0
            // We are going to load all of the first level categories (categories without parents)
            $sqlwhere .= " AND cc.parent = 0";
        } else if (array_key_exists($categoryid, $this->addedcategories)) {
            // The category itself has been loaded already so we just need to ensure its subcategories
            // have been loaded
            $addedcategories = $this->addedcategories;
            unset($addedcategories[$categoryid]);
            if (count($addedcategories) > 0) {
                list($sql, $params) = $DB->get_in_or_equal(array_keys($addedcategories), SQL_PARAMS_NAMED, 'parent', false);
                if ($showbasecategories) {
                    // We need to include categories with parent = 0 as well
                    $sqlwhere .= " AND (cc.parent = :categoryid OR cc.parent = 0) AND cc.parent {$sql}";
                } else {
                    // All we need is categories that match the parent
                    $sqlwhere .= " AND cc.parent = :categoryid AND cc.parent {$sql}";
                }
            }
            $params['categoryid'] = $categoryid;
        } else {
            // This category hasn't been loaded yet so we need to fetch it, work out its category path
            // and load this category plus all its parents and subcategories
            $category = $DB->get_record('course_categories', array('id' => $categoryid), 'path', MUST_EXIST);
            $categoriestoload = explode('/', trim($category->path, '/'));
            list($select, $params) = $DB->get_in_or_equal($categoriestoload);
            // We are going to use select twice so double the params
            $params = array_merge($params, $params);
            $basecategorysql = ($showbasecategories)?' OR cc.depth = 1':'';
            $sqlwhere .= " AND (cc.id {$select} OR cc.parent {$select}{$basecategorysql})";
        }

        $categoriesrs = $DB->get_recordset_sql("$sqlselect $sqlwhere $sqlorder", $params);
        $categories = array();
        foreach ($categoriesrs as $category) {
            // Preload the context.. we'll need it when adding the category in order
            // to format the category name.
            context_helper::preload_from_record($category);
            if (array_key_exists($category->id, $this->addedcategories)) {
                // Do nothing, its already been added.
            } else if ($category->parent == '0') {
                // This is a root category lets add it immediately
                $this->add_category($category, $this->rootnodes['courses']);
            } else if (array_key_exists($category->parent, $this->addedcategories)) {
                // This categories parent has already been added we can add this immediately
                $this->add_category($category, $this->addedcategories[$category->parent]);
            } else {
                $categories[] = $category;
            }
        }
        $categoriesrs->close();

        // Now we have an array of categories we need to add them to the navigation.
        while (!empty($categories)) {
            $category = reset($categories);
            if (array_key_exists($category->id, $this->addedcategories)) {
                // Do nothing
            } else if ($category->parent == '0') {
                $this->add_category($category, $this->rootnodes['courses']);
            } else if (array_key_exists($category->parent, $this->addedcategories)) {
                $this->add_category($category, $this->addedcategories[$category->parent]);
            } else {
                // This category isn't in the navigation and niether is it's parent (yet).
                // We need to go through the category path and add all of its components in order.
                $path = explode('/', trim($category->path, '/'));
                foreach ($path as $catid) {
                    if (!array_key_exists($catid, $this->addedcategories)) {
                        // This category isn't in the navigation yet so add it.
                        $subcategory = $categories[$catid];
                        if ($subcategory->parent == '0') {
                            // Yay we have a root category - this likely means we will now be able
                            // to add categories without problems.
                            $this->add_category($subcategory, $this->rootnodes['courses']);
                        } else if (array_key_exists($subcategory->parent, $this->addedcategories)) {
                            // The parent is in the category (as we'd expect) so add it now.
                            $this->add_category($subcategory, $this->addedcategories[$subcategory->parent]);
                            // Remove the category from the categories array.
                            unset($categories[$catid]);
                        } else {
                            // We should never ever arrive here - if we have then there is a bigger
                            // problem at hand.
                            throw new coding_exception('Category path order is incorrect and/or there are missing categories');
                        }
                    }
                }
            }
            // Remove the category from the categories array now that we know it has been added.
            unset($categories[$category->id]);
        }
        if ($categoryid === self::LOAD_ALL_CATEGORIES) {
            $this->allcategoriesloaded = true;
        }
        // Check if there are any categories to load.
        if (count($categoriestoload) > 0) {
            $readytoloadcourses = array();
            foreach ($categoriestoload as $category) {
                if ($this->can_add_more_courses_to_category($category)) {
                    $readytoloadcourses[] = $category;
                }
            }
            if (count($readytoloadcourses)) {
                $this->load_all_courses($readytoloadcourses);
            }
        }

        // Look for all categories which have been loaded
        if (!empty($this->addedcategories)) {
            $categoryids = array();
            foreach ($this->addedcategories as $category) {
                if ($this->can_add_more_courses_to_category($category)) {
                    $categoryids[] = $category->key;
                }
            }
            if ($categoryids) {
                list($categoriessql, $params) = $DB->get_in_or_equal($categoryids, SQL_PARAMS_NAMED);
                $params['limit'] = (!empty($CFG->navcourselimit))?$CFG->navcourselimit:20;
                $sql = "SELECT cc.id, COUNT(c.id) AS coursecount
                          FROM {course_categories} cc
                          JOIN {course} c ON c.category = cc.id
                         WHERE cc.id {$categoriessql}
                      GROUP BY cc.id
                        HAVING COUNT(c.id) > :limit";
                $excessivecategories = $DB->get_records_sql($sql, $params);
                foreach ($categories as &$category) {
                    if (array_key_exists($category->key, $excessivecategories) && !$this->can_add_more_courses_to_category($category)) {
                        $url = new moodle_url('/course/index.php', array('categoryid' => $category->key));
                        $category->add(get_string('viewallcourses'), $url, self::TYPE_SETTING);
                    }
                }
            }
        }
    }

    /**
     * Adds a structured category to the navigation in the correct order/place
     *
     * @param stdClass $category category to be added in navigation.
     * @param navigation_node $parent parent navigation node
     * @param int $nodetype type of node, if category is under MyHome then it's TYPE_MY_CATEGORY
     * @return void.
     */
    protected function add_category(stdClass $category, navigation_node $parent, $nodetype = self::TYPE_CATEGORY) {
        if (array_key_exists($category->id, $this->addedcategories)) {
            return;
        }
        $url = new moodle_url('/course/index.php', array('categoryid' => $category->id));
        $context = context_coursecat::instance($category->id);
        $categoryname = format_string($category->name, true, array('context' => $context));
        $categorynode = $parent->add($categoryname, $url, $nodetype, $categoryname, $category->id);
        if (empty($category->visible)) {
            if (has_capability('moodle/category:viewhiddencategories', context_system::instance())) {
                $categorynode->hidden = true;
            } else {
                $categorynode->display = false;
            }
        }
        $this->addedcategories[$category->id] = $categorynode;
    }

    /**
     * Loads the given course into the navigation
     *
     * @param stdClass $course
     * @return navigation_node
     */
    protected function load_course(stdClass $course) {
        global $SITE;
        if ($course->id == $SITE->id) {
            // This is always loaded during initialisation
            return $this->rootnodes['site'];
        } else if (array_key_exists($course->id, $this->addedcourses)) {
            // The course has already been loaded so return a reference
            return $this->addedcourses[$course->id];
        } else {
            // Add the course
            return $this->add_course($course);
        }
    }

    /**
     * Loads all of the courses section into the navigation.
     *
     * This function calls method from current course format, see
     * {@link format_base::extend_course_navigation()}
     * If course module ($cm) is specified but course format failed to create the node,
     * the activity node is created anyway.
     *
     * By default course formats call the method {@link global_navigation::load_generic_course_sections()}
     *
     * @param stdClass $course Database record for the course
     * @param navigation_node $coursenode The course node within the navigation
     * @param null|int $sectionnum If specified load the contents of section with this relative number
     * @param null|cm_info $cm If specified make sure that activity node is created (either
     *    in containg section or by calling load_stealth_activity() )
     */
    protected function load_course_sections(stdClass $course, navigation_node $coursenode, $sectionnum = null, $cm = null) {
        global $CFG, $SITE;
        require_once($CFG->dirroot.'/course/lib.php');
        if (isset($cm->sectionnum)) {
            $sectionnum = $cm->sectionnum;
        }
        if ($sectionnum !== null) {
            $this->includesectionnum = $sectionnum;
        }
        course_get_format($course)->extend_course_navigation($this, $coursenode, $sectionnum, $cm);
        if (isset($cm->id)) {
            $activity = $coursenode->find($cm->id, self::TYPE_ACTIVITY);
            if (empty($activity)) {
                $activity = $this->load_stealth_activity($coursenode, get_fast_modinfo($course));
            }
        }
   }

    /**
     * Generates an array of sections and an array of activities for the given course.
     *
     * This method uses the cache to improve performance and avoid the get_fast_modinfo call
     *
     * @param stdClass $course
     * @return array Array($sections, $activities)
     */
    protected function generate_sections_and_activities(stdClass $course) {
        global $CFG;
        require_once($CFG->dirroot.'/course/lib.php');

        $modinfo = get_fast_modinfo($course);
        $sections = $modinfo->get_section_info_all();

        // For course formats using 'numsections' trim the sections list
        $courseformatoptions = course_get_format($course)->get_format_options();
        if (isset($courseformatoptions['numsections'])) {
            $sections = array_slice($sections, 0, $courseformatoptions['numsections']+1, true);
        }

        $activities = array();

        foreach ($sections as $key => $section) {
            // Clone and unset summary to prevent $SESSION bloat (MDL-31802).
            $sections[$key] = clone($section);
            unset($sections[$key]->summary);
            $sections[$key]->hasactivites = false;
            if (!array_key_exists($section->section, $modinfo->sections)) {
                continue;
            }
            foreach ($modinfo->sections[$section->section] as $cmid) {
                $cm = $modinfo->cms[$cmid];
                $activity = new stdClass;
                $activity->id = $cm->id;
                $activity->course = $course->id;
                $activity->section = $section->section;
                $activity->name = $cm->name;
                $activity->icon = $cm->icon;
                $activity->iconcomponent = $cm->iconcomponent;
                $activity->hidden = (!$cm->visible);
                $activity->modname = $cm->modname;
                $activity->nodetype = navigation_node::NODETYPE_LEAF;
                $activity->onclick = $cm->onclick;
                $url = $cm->url;
                if (!$url) {
                    $activity->url = null;
                    $activity->display = false;
                } else {
                    $activity->url = $url->out();
                    $activity->display = $cm->uservisible ? true : false;
                    if (self::module_extends_navigation($cm->modname)) {
                        $activity->nodetype = navigation_node::NODETYPE_BRANCH;
                    }
                }
                $activities[$cmid] = $activity;
                if ($activity->display) {
                    $sections[$key]->hasactivites = true;
                }
            }
        }

        return array($sections, $activities);
    }

    /**
     * Generically loads the course sections into the course's navigation.
     *
     * @param stdClass $course
     * @param navigation_node $coursenode
     * @return array An array of course section nodes
     */
    public function load_generic_course_sections(stdClass $course, navigation_node $coursenode) {
        global $CFG, $DB, $USER, $SITE;
        require_once($CFG->dirroot.'/course/lib.php');

        list($sections, $activities) = $this->generate_sections_and_activities($course);

        $navigationsections = array();
        foreach ($sections as $sectionid => $section) {
            $section = clone($section);
            if ($course->id == $SITE->id) {
                $this->load_section_activities($coursenode, $section->section, $activities);
            } else {
                if (!$section->uservisible || (!$this->showemptysections &&
                        !$section->hasactivites && $this->includesectionnum !== $section->section)) {
                    continue;
                }

                $sectionname = get_section_name($course, $section);
                $url = course_get_url($course, $section->section, array('navigation' => true));

                $sectionnode = $coursenode->add($sectionname, $url, navigation_node::TYPE_SECTION, null, $section->id);
                $sectionnode->nodetype = navigation_node::NODETYPE_BRANCH;
                $sectionnode->hidden = (!$section->visible || !$section->available);
                if ($this->includesectionnum !== false && $this->includesectionnum == $section->section) {
                    $this->load_section_activities($sectionnode, $section->section, $activities);
                }
                $section->sectionnode = $sectionnode;
                $navigationsections[$sectionid] = $section;
            }
        }
        return $navigationsections;
    }

    /**
     * Loads all of the activities for a section into the navigation structure.
     *
     * @param navigation_node $sectionnode
     * @param int $sectionnumber
     * @param array $activities An array of activites as returned by {@link global_navigation::generate_sections_and_activities()}
     * @param stdClass $course The course object the section and activities relate to.
     * @return array Array of activity nodes
     */
    protected function load_section_activities(navigation_node $sectionnode, $sectionnumber, array $activities, $course = null) {
        global $CFG, $SITE;
        // A static counter for JS function naming
        static $legacyonclickcounter = 0;

        $activitynodes = array();
        if (empty($activities)) {
            return $activitynodes;
        }

        if (!is_object($course)) {
            $activity = reset($activities);
            $courseid = $activity->course;
        } else {
            $courseid = $course->id;
        }
        $showactivities = ($courseid != $SITE->id || !empty($CFG->navshowfrontpagemods));

        foreach ($activities as $activity) {
            if ($activity->section != $sectionnumber) {
                continue;
            }
            if ($activity->icon) {
                $icon = new pix_icon($activity->icon, get_string('modulename', $activity->modname), $activity->iconcomponent);
            } else {
                $icon = new pix_icon('icon', get_string('modulename', $activity->modname), $activity->modname);
            }

            // Prepare the default name and url for the node
            $activityname = format_string($activity->name, true, array('context' => context_module::instance($activity->id)));
            $action = new moodle_url($activity->url);

            // Check if the onclick property is set (puke!)
            if (!empty($activity->onclick)) {
                // Increment the counter so that we have a unique number.
                $legacyonclickcounter++;
                // Generate the function name we will use
                $functionname = 'legacy_activity_onclick_handler_'.$legacyonclickcounter;
                $propogrationhandler = '';
                // Check if we need to cancel propogation. Remember inline onclick
                // events would return false if they wanted to prevent propogation and the
                // default action.
                if (strpos($activity->onclick, 'return false')) {
                    $propogrationhandler = 'e.halt();';
                }
                // Decode the onclick - it has already been encoded for display (puke)
                $onclick = htmlspecialchars_decode($activity->onclick, ENT_QUOTES);
                // Build the JS function the click event will call
                $jscode = "function {$functionname}(e) { $propogrationhandler $onclick }";
                $this->page->requires->js_init_code($jscode);
                // Override the default url with the new action link
                $action = new action_link($action, $activityname, new component_action('click', $functionname));
            }

            $activitynode = $sectionnode->add($activityname, $action, navigation_node::TYPE_ACTIVITY, null, $activity->id, $icon);
            $activitynode->title(get_string('modulename', $activity->modname));
            $activitynode->hidden = $activity->hidden;
            $activitynode->display = $showactivities && $activity->display;
            $activitynode->nodetype = $activity->nodetype;
            $activitynodes[$activity->id] = $activitynode;
        }

        return $activitynodes;
    }
    /**
     * Loads a stealth module from unavailable section
     * @param navigation_node $coursenode
     * @param stdClass $modinfo
     * @return navigation_node or null if not accessible
     */
    protected function load_stealth_activity(navigation_node $coursenode, $modinfo) {
        if (empty($modinfo->cms[$this->page->cm->id])) {
            return null;
        }
        $cm = $modinfo->cms[$this->page->cm->id];
        if ($cm->icon) {
            $icon = new pix_icon($cm->icon, get_string('modulename', $cm->modname), $cm->iconcomponent);
        } else {
            $icon = new pix_icon('icon', get_string('modulename', $cm->modname), $cm->modname);
        }
        $url = $cm->url;
        $activitynode = $coursenode->add(format_string($cm->name), $url, navigation_node::TYPE_ACTIVITY, null, $cm->id, $icon);
        $activitynode->title(get_string('modulename', $cm->modname));
        $activitynode->hidden = (!$cm->visible);
        if (!$cm->uservisible) {
            // Do not show any error here, let the page handle exception that activity is not visible for the current user.
            // Also there may be no exception at all in case when teacher is logged in as student.
            $activitynode->display = false;
        } else if (!$url) {
            // Don't show activities that don't have links!
            $activitynode->display = false;
        } else if (self::module_extends_navigation($cm->modname)) {
            $activitynode->nodetype = navigation_node::NODETYPE_BRANCH;
        }
        return $activitynode;
    }
    /**
     * Loads the navigation structure for the given activity into the activities node.
     *
     * This method utilises a callback within the modules lib.php file to load the
     * content specific to activity given.
     *
     * The callback is a method: {modulename}_extend_navigation()
     * Examples:
     *  * {@link forum_extend_navigation()}
     *  * {@link workshop_extend_navigation()}
     *
     * @param cm_info|stdClass $cm
     * @param stdClass $course
     * @param navigation_node $activity
     * @return bool
     */
    protected function load_activity($cm, stdClass $course, navigation_node $activity) {
        global $CFG, $DB;

        // make sure we have a $cm from get_fast_modinfo as this contains activity access details
        if (!($cm instanceof cm_info)) {
            $modinfo = get_fast_modinfo($course);
            $cm = $modinfo->get_cm($cm->id);
        }
        $activity->nodetype = navigation_node::NODETYPE_LEAF;
        $activity->make_active();
        $file = $CFG->dirroot.'/mod/'.$cm->modname.'/lib.php';
        $function = $cm->modname.'_extend_navigation';

        if (file_exists($file)) {
            require_once($file);
            if (function_exists($function)) {
                $activtyrecord = $DB->get_record($cm->modname, array('id' => $cm->instance), '*', MUST_EXIST);
                $function($activity, $course, $activtyrecord, $cm);
            }
        }

        // Allow the active advanced grading method plugin to append module navigation
        $featuresfunc = $cm->modname.'_supports';
        if (function_exists($featuresfunc) && $featuresfunc(FEATURE_ADVANCED_GRADING)) {
            require_once($CFG->dirroot.'/grade/grading/lib.php');
            $gradingman = get_grading_manager($cm->context,  'mod_'.$cm->modname);
            $gradingman->extend_navigation($this, $activity);
        }

        return $activity->has_children();
    }
    /**
     * Loads user specific information into the navigation in the appropriate place.
     *
     * If no user is provided the current user is assumed.
     *
     * @param stdClass $user
     * @param bool $forceforcontext probably force something to be loaded somewhere (ask SamH if not sure what this means)
     * @return bool
     */
    protected function load_for_user($user=null, $forceforcontext=false) {
        global $DB, $CFG, $USER, $SITE;

        if ($user === null) {
            // We can't require login here but if the user isn't logged in we don't
            // want to show anything
            if (!isloggedin() || isguestuser()) {
                return false;
            }
            $user = $USER;
        } else if (!is_object($user)) {
            // If the user is not an object then get them from the database
            $select = context_helper::get_preload_record_columns_sql('ctx');
            $sql = "SELECT u.*, $select
                      FROM {user} u
                      JOIN {context} ctx ON u.id = ctx.instanceid
                     WHERE u.id = :userid AND
                           ctx.contextlevel = :contextlevel";
            $user = $DB->get_record_sql($sql, array('userid' => (int)$user, 'contextlevel' => CONTEXT_USER), MUST_EXIST);
            context_helper::preload_from_record($user);
        }

        $iscurrentuser = ($user->id == $USER->id);

        $usercontext = context_user::instance($user->id);

        // Get the course set against the page, by default this will be the site
        $course = $this->page->course;
        $baseargs = array('id'=>$user->id);
        if ($course->id != $SITE->id && (!$iscurrentuser || $forceforcontext)) {
            $coursenode = $this->add_course($course, false, self::COURSE_CURRENT);
            $baseargs['course'] = $course->id;
            $coursecontext = context_course::instance($course->id);
            $issitecourse = false;
        } else {
            // Load all categories and get the context for the system
            $coursecontext = context_system::instance();
            $issitecourse = true;
        }

        // Create a node to add user information under.
        if ($iscurrentuser && !$forceforcontext) {
            // If it's the current user the information will go under the profile root node
            $usernode = $this->rootnodes['myprofile'];
            $course = get_site();
            $coursecontext = context_course::instance($course->id);
            $issitecourse = true;
        } else {
            if (!$issitecourse) {
                // Not the current user so add it to the participants node for the current course
                $usersnode = $coursenode->get('participants', navigation_node::TYPE_CONTAINER);
                $userviewurl = new moodle_url('/user/view.php', $baseargs);
            } else {
                // This is the site so add a users node to the root branch
                $usersnode = $this->rootnodes['users'];
                if (has_capability('moodle/course:viewparticipants', $coursecontext)) {
                    $usersnode->action = new moodle_url('/user/index.php', array('id'=>$course->id));
                }
                $userviewurl = new moodle_url('/user/profile.php', $baseargs);
            }
            if (!$usersnode) {
                // We should NEVER get here, if the course hasn't been populated
                // with a participants node then the navigaiton either wasn't generated
                // for it (you are missing a require_login or set_context call) or
                // you don't have access.... in the interests of no leaking informatin
                // we simply quit...
                return false;
            }
            // Add a branch for the current user
            $canseefullname = has_capability('moodle/site:viewfullnames', $coursecontext);
            $usernode = $usersnode->add(fullname($user, $canseefullname), $userviewurl, self::TYPE_USER, null, $user->id);

            if ($this->page->context->contextlevel == CONTEXT_USER && $user->id == $this->page->context->instanceid) {
                $usernode->make_active();
            }
        }

        // If the user is the current user or has permission to view the details of the requested
        // user than add a view profile link.
        if ($iscurrentuser || has_capability('moodle/user:viewdetails', $coursecontext) || has_capability('moodle/user:viewdetails', $usercontext)) {
            if ($issitecourse || ($iscurrentuser && !$forceforcontext)) {
                $usernode->add(get_string('viewprofile'), new moodle_url('/user/profile.php',$baseargs));
            } else {
                $usernode->add(get_string('viewprofile'), new moodle_url('/user/view.php',$baseargs));
            }
        }

        if (!empty($CFG->navadduserpostslinks)) {
            // Add nodes for forum posts and discussions if the user can view either or both
            // There are no capability checks here as the content of the page is based
            // purely on the forums the current user has access too.
            $forumtab = $usernode->add(get_string('forumposts', 'forum'));
            $forumtab->add(get_string('posts', 'forum'), new moodle_url('/mod/forum/user.php', $baseargs));
            $forumtab->add(get_string('discussions', 'forum'), new moodle_url('/mod/forum/user.php', array_merge($baseargs, array('mode'=>'discussions'))));
        }

        // Add blog nodes
        if (!empty($CFG->enableblogs)) {
            if (!$this->cache->cached('userblogoptions'.$user->id)) {
                require_once($CFG->dirroot.'/blog/lib.php');
                // Get all options for the user
                $options = blog_get_options_for_user($user);
                $this->cache->set('userblogoptions'.$user->id, $options);
            } else {
                $options = $this->cache->{'userblogoptions'.$user->id};
            }

            if (count($options) > 0) {
                $blogs = $usernode->add(get_string('blogs', 'blog'), null, navigation_node::TYPE_CONTAINER);
                foreach ($options as $type => $option) {
                    if ($type == "rss") {
                        $blogs->add($option['string'], $option['link'], settings_navigation::TYPE_SETTING, null, null, new pix_icon('i/rss', ''));
                    } else {
                        $blogs->add($option['string'], $option['link']);
                    }
                }
            }
        }

        // Add the messages link.
        // It is context based so can appear in "My profile" and in course participants information.
        if (!empty($CFG->messaging)) {
            $messageargs = array('user1' => $USER->id);
            if ($USER->id != $user->id) {
                $messageargs['user2'] = $user->id;
            }
            if ($course->id != $SITE->id) {
                $messageargs['viewing'] = MESSAGE_VIEW_COURSE. $course->id;
            }
            $url = new moodle_url('/message/index.php',$messageargs);
            $usernode->add(get_string('messages', 'message'), $url, self::TYPE_SETTING, null, 'messages');
        }

        // Add the "My private files" link.
        // This link doesn't have a unique display for course context so only display it under "My profile".
        if ($issitecourse && $iscurrentuser && has_capability('moodle/user:manageownfiles', $usercontext)) {
            $url = new moodle_url('/user/files.php');
            $usernode->add(get_string('myfiles'), $url, self::TYPE_SETTING);
        }

        if (!empty($CFG->enablebadges) && $iscurrentuser &&
                has_capability('moodle/badges:manageownbadges', $usercontext)) {
            $url = new moodle_url('/badges/mybadges.php');
            $usernode->add(get_string('mybadges', 'badges'), $url, self::TYPE_SETTING);
        }

        // Add a node to view the users notes if permitted
        if (!empty($CFG->enablenotes) && has_any_capability(array('moodle/notes:manage', 'moodle/notes:view'), $coursecontext)) {
            $url = new moodle_url('/notes/index.php',array('user'=>$user->id));
            if ($coursecontext->instanceid != SITEID) {
                $url->param('course', $coursecontext->instanceid);
            }
            $usernode->add(get_string('notes', 'notes'), $url);
        }

        // If the user is the current user add the repositories for the current user
        $hiddenfields = array_flip(explode(',', $CFG->hiddenuserfields));
        if ($iscurrentuser) {
            if (!$this->cache->cached('contexthasrepos'.$usercontext->id)) {
                require_once($CFG->dirroot . '/repository/lib.php');
                $editabletypes = repository::get_editable_types($usercontext);
                $haseditabletypes = !empty($editabletypes);
                unset($editabletypes);
                $this->cache->set('contexthasrepos'.$usercontext->id, $haseditabletypes);
            } else {
                $haseditabletypes = $this->cache->{'contexthasrepos'.$usercontext->id};
            }
            if ($haseditabletypes) {
                $usernode->add(get_string('repositories', 'repository'), new moodle_url('/repository/manage_instances.php', array('contextid' => $usercontext->id)));
            }
        } else if ($course->id == $SITE->id && has_capability('moodle/user:viewdetails', $usercontext) && (!in_array('mycourses', $hiddenfields) || has_capability('moodle/user:viewhiddendetails', $coursecontext))) {

            // Add view grade report is permitted
            $reports = core_component::get_plugin_list('gradereport');
            arsort($reports); // user is last, we want to test it first

            $userscourses = enrol_get_users_courses($user->id);
            $userscoursesnode = $usernode->add(get_string('courses'));

            $count = 0;
            foreach ($userscourses as $usercourse) {
                if ($count === (int)$CFG->navcourselimit) {
                    $url = new moodle_url('/user/profile.php', array('id' => $user->id, 'showallcourses' => 1));
                    $userscoursesnode->add(get_string('showallcourses'), $url);
                    break;
                }
                $count++;
                $usercoursecontext = context_course::instance($usercourse->id);
                $usercourseshortname = format_string($usercourse->shortname, true, array('context' => $usercoursecontext));
                $usercoursenode = $userscoursesnode->add($usercourseshortname, new moodle_url('/user/view.php', array('id'=>$user->id, 'course'=>$usercourse->id)), self::TYPE_CONTAINER);

                $gradeavailable = has_capability('moodle/grade:viewall', $usercoursecontext);
                if (!$gradeavailable && !empty($usercourse->showgrades) && is_array($reports) && !empty($reports)) {
                    foreach ($reports as $plugin => $plugindir) {
                        if (has_capability('gradereport/'.$plugin.':view', $usercoursecontext)) {
                            //stop when the first visible plugin is found
                            $gradeavailable = true;
                            break;
                        }
                    }
                }

                if ($gradeavailable) {
                    $url = new moodle_url('/grade/report/index.php', array('id'=>$usercourse->id));
                    $usercoursenode->add(get_string('grades'), $url, self::TYPE_SETTING, null, null, new pix_icon('i/grades', ''));
                }

                // Add a node to view the users notes if permitted
                if (!empty($CFG->enablenotes) && has_any_capability(array('moodle/notes:manage', 'moodle/notes:view'), $usercoursecontext)) {
                    $url = new moodle_url('/notes/index.php',array('user'=>$user->id, 'course'=>$usercourse->id));
                    $usercoursenode->add(get_string('notes', 'notes'), $url, self::TYPE_SETTING);
                }

                if (can_access_course($usercourse, $user->id)) {
                    $usercoursenode->add(get_string('entercourse'), new moodle_url('/course/view.php', array('id'=>$usercourse->id)), self::TYPE_SETTING, null, null, new pix_icon('i/course', ''));
                }

                $reporttab = $usercoursenode->add(get_string('activityreports'));

                $reports = get_plugin_list_with_function('report', 'extend_navigation_user', 'lib.php');
                foreach ($reports as $reportfunction) {
                    $reportfunction($reporttab, $user, $usercourse);
                }

                $reporttab->trim_if_empty();
            }
        }
        return true;
    }

    /**
     * This method simply checks to see if a given module can extend the navigation.
     *
     * @todo (MDL-25290) A shared caching solution should be used to save details on what extends navigation.
     *
     * @param string $modname
     * @return bool
     */
    public static function module_extends_navigation($modname) {
        global $CFG;
        static $extendingmodules = array();
        if (!array_key_exists($modname, $extendingmodules)) {
            $extendingmodules[$modname] = false;
            $file = $CFG->dirroot.'/mod/'.$modname.'/lib.php';
            if (file_exists($file)) {
                $function = $modname.'_extend_navigation';
                require_once($file);
                $extendingmodules[$modname] = (function_exists($function));
            }
        }
        return $extendingmodules[$modname];
    }
    /**
     * Extends the navigation for the given user.
     *
     * @param stdClass $user A user from the database
     */
    public function extend_for_user($user) {
        $this->extendforuser[] = $user;
    }

    /**
     * Returns all of the users the navigation is being extended for
     *
     * @return array An array of extending users.
     */
    public function get_extending_users() {
        return $this->extendforuser;
    }
    /**
     * Adds the given course to the navigation structure.
     *
     * @param stdClass $course
     * @param bool $forcegeneric
     * @param bool $ismycourse
     * @return navigation_node
     */
    public function add_course(stdClass $course, $forcegeneric = false, $coursetype = self::COURSE_OTHER) {
        global $CFG, $SITE;

        // We found the course... we can return it now :)
        if (!$forcegeneric && array_key_exists($course->id, $this->addedcourses)) {
            return $this->addedcourses[$course->id];
        }

        $coursecontext = context_course::instance($course->id);

        if ($course->id != $SITE->id && !$course->visible) {
            if (is_role_switched($course->id)) {
                // user has to be able to access course in order to switch, let's skip the visibility test here
            } else if (!has_capability('moodle/course:viewhiddencourses', $coursecontext)) {
                return false;
            }
        }

        $issite = ($course->id == $SITE->id);
        $shortname = format_string($course->shortname, true, array('context' => $coursecontext));
        $fullname = format_string($course->fullname, true, array('context' => $coursecontext));
        // This is the name that will be shown for the course.
        $coursename = empty($CFG->navshowfullcoursenames) ? $shortname : $fullname;

        // Can the user expand the course to see its content.
        $canexpandcourse = true;
        if ($issite) {
            $parent = $this;
            $url = null;
            if (empty($CFG->usesitenameforsitepages)) {
                $coursename = get_string('sitepages');
            }
        } else if ($coursetype == self::COURSE_CURRENT) {
            $parent = $this->rootnodes['currentcourse'];
            $url = new moodle_url('/course/view.php', array('id'=>$course->id));
        } else if ($coursetype == self::COURSE_MY && !$forcegeneric) {
            if (!empty($CFG->navshowmycoursecategories) && ($parent = $this->rootnodes['mycourses']->find($course->category, self::TYPE_MY_CATEGORY))) {
                // Nothing to do here the above statement set $parent to the category within mycourses.
            } else {
                $parent = $this->rootnodes['mycourses'];
            }
            $url = new moodle_url('/course/view.php', array('id'=>$course->id));
        } else {
            $parent = $this->rootnodes['courses'];
            $url = new moodle_url('/course/view.php', array('id'=>$course->id));
            // They can only expand the course if they can access it.
            $canexpandcourse = $this->can_expand_course($course);
            if (!empty($course->category) && $this->show_categories($coursetype == self::COURSE_MY)) {
                if (!$this->is_category_fully_loaded($course->category)) {
                    // We need to load the category structure for this course
                    $this->load_all_categories($course->category, false);
                }
                if (array_key_exists($course->category, $this->addedcategories)) {
                    $parent = $this->addedcategories[$course->category];
                    // This could lead to the course being created so we should check whether it is the case again
                    if (!$forcegeneric && array_key_exists($course->id, $this->addedcourses)) {
                        return $this->addedcourses[$course->id];
                    }
                }
            }
        }

        $coursenode = $parent->add($coursename, $url, self::TYPE_COURSE, $shortname, $course->id);
        $coursenode->hidden = (!$course->visible);
        // We need to decode &amp;'s here as they will have been added by format_string above and attributes will be encoded again
        // later.
        $coursenode->title(str_replace('&amp;', '&', $fullname));
        if ($canexpandcourse) {
            // This course can be expanded by the user, make it a branch to make the system aware that its expandable by ajax.
            $coursenode->nodetype = self::NODETYPE_BRANCH;
            $coursenode->isexpandable = true;
        } else {
            $coursenode->nodetype = self::NODETYPE_LEAF;
            $coursenode->isexpandable = false;
        }
        if (!$forcegeneric) {
            $this->addedcourses[$course->id] = $coursenode;
        }

        return $coursenode;
    }

    /**
     * Returns a cache instance to use for the expand course cache.
     * @return cache_session
     */
    protected function get_expand_course_cache() {
        if ($this->cacheexpandcourse === null) {
            $this->cacheexpandcourse = cache::make('core', 'navigation_expandcourse');
        }
        return $this->cacheexpandcourse;
    }

    /**
     * Checks if a user can expand a course in the navigation.
     *
     * We use a cache here because in order to be accurate we need to call can_access_course which is a costly function.
     * Because this functionality is basic + non-essential and because we lack good event triggering this cache
     * permits stale data.
     * In the situation the user is granted access to a course after we've initialised this session cache the cache
     * will be stale.
     * It is brought up to date in only one of two ways.
     *   1. The user logs out and in again.
     *   2. The user browses to the course they've just being given access to.
     *
     * Really all this controls is whether the node is shown as expandable or not. It is uber un-important.
     *
     * @param stdClass $course
     * @return bool
     */
    protected function can_expand_course($course) {
        $cache = $this->get_expand_course_cache();
        $canexpand = $cache->get($course->id);
        if ($canexpand === false) {
            $canexpand = isloggedin() && can_access_course($course);
            $canexpand = (int)$canexpand;
            $cache->set($course->id, $canexpand);
        }
        return ($canexpand === 1);
    }

    /**
     * Returns true if the category has already been loaded as have any child categories
     *
     * @param int $categoryid
     * @return bool
     */
    protected function is_category_fully_loaded($categoryid) {
        return (array_key_exists($categoryid, $this->addedcategories) && ($this->allcategoriesloaded || $this->addedcategories[$categoryid]->children->count() > 0));
    }

    /**
     * Adds essential course nodes to the navigation for the given course.
     *
     * This method adds nodes such as reports, blogs and participants
     *
     * @param navigation_node $coursenode
     * @param stdClass $course
     * @return bool returns true on successful addition of a node.
     */
    public function add_course_essentials($coursenode, stdClass $course) {
        global $CFG, $SITE;

        if ($course->id == $SITE->id) {
            return $this->add_front_page_course_essentials($coursenode, $course);
        }

        if ($coursenode == false || !($coursenode instanceof navigation_node) || $coursenode->get('participants', navigation_node::TYPE_CONTAINER)) {
            return true;
        }

        //Participants
        if (has_capability('moodle/course:viewparticipants', $this->page->context)) {
            $participants = $coursenode->add(get_string('participants'), new moodle_url('/user/index.php?id='.$course->id), self::TYPE_CONTAINER, get_string('participants'), 'participants');
            if (!empty($CFG->enableblogs)) {
                if (($CFG->bloglevel == BLOG_GLOBAL_LEVEL or ($CFG->bloglevel == BLOG_SITE_LEVEL and (isloggedin() and !isguestuser())))
                   and has_capability('moodle/blog:view', context_system::instance())) {
                    $blogsurls = new moodle_url('/blog/index.php');
                    if ($course->id == $SITE->id) {
                        $blogsurls->param('courseid', 0);
                    } else if ($currentgroup = groups_get_course_group($course, true)) {
                        $blogsurls->param('groupid', $currentgroup);
                    } else {
                        $blogsurls->param('courseid', $course->id);
                    }
                    $participants->add(get_string('blogscourse','blog'), $blogsurls->out());
                }
            }
            if (!empty($CFG->enablenotes) && (has_capability('moodle/notes:manage', $this->page->context) || has_capability('moodle/notes:view', $this->page->context))) {
                $participants->add(get_string('notes','notes'), new moodle_url('/notes/index.php', array('filtertype'=>'course', 'filterselect'=>$course->id)));
            }
        } else if (count($this->extendforuser) > 0 || $this->page->course->id == $course->id) {
            $participants = $coursenode->add(get_string('participants'), null, self::TYPE_CONTAINER, get_string('participants'), 'participants');
        }

        // Badges.
        if (!empty($CFG->enablebadges) && !empty($CFG->badges_allowcoursebadges) &&
            has_capability('moodle/badges:viewbadges', $this->page->context)) {
            $url = new moodle_url('/badges/view.php', array('type' => 2, 'id' => $course->id));

            $coursenode->add(get_string('coursebadges', 'badges'), null,
                    navigation_node::TYPE_CONTAINER, null, 'coursebadges');
            $coursenode->get('coursebadges')->add(get_string('badgesview', 'badges'), $url,
                    navigation_node::TYPE_SETTING, null, 'badgesview',
                    new pix_icon('i/badge', get_string('badgesview', 'badges')));
        }

        return true;
    }
    /**
     * This generates the structure of the course that won't be generated when
     * the modules and sections are added.
     *
     * Things such as the reports branch, the participants branch, blogs... get
     * added to the course node by this method.
     *
     * @param navigation_node $coursenode
     * @param stdClass $course
     * @return bool True for successfull generation
     */
    public function add_front_page_course_essentials(navigation_node $coursenode, stdClass $course) {
        global $CFG;

        if ($coursenode == false || $coursenode->get('frontpageloaded', navigation_node::TYPE_CUSTOM)) {
            return true;
        }

        // Hidden node that we use to determine if the front page navigation is loaded.
        // This required as there are not other guaranteed nodes that may be loaded.
        $coursenode->add('frontpageloaded', null, self::TYPE_CUSTOM, null, 'frontpageloaded')->display = false;

        //Participants
        if (has_capability('moodle/course:viewparticipants',  context_system::instance())) {
            $coursenode->add(get_string('participants'), new moodle_url('/user/index.php?id='.$course->id), self::TYPE_CUSTOM, get_string('participants'), 'participants');
        }

        $filterselect = 0;

        // Blogs
        if (!empty($CFG->enableblogs)
          and ($CFG->bloglevel == BLOG_GLOBAL_LEVEL or ($CFG->bloglevel == BLOG_SITE_LEVEL and (isloggedin() and !isguestuser())))
          and has_capability('moodle/blog:view', context_system::instance())) {
            $blogsurls = new moodle_url('/blog/index.php', array('courseid' => $filterselect));
            $coursenode->add(get_string('blogssite','blog'), $blogsurls->out());
        }

        //Badges
        if (!empty($CFG->enablebadges) && has_capability('moodle/badges:viewbadges', $this->page->context)) {
            $url = new moodle_url($CFG->wwwroot . '/badges/view.php', array('type' => 1));
            $coursenode->add(get_string('sitebadges', 'badges'), $url, navigation_node::TYPE_CUSTOM);
        }

        // Notes
        if (!empty($CFG->enablenotes) && (has_capability('moodle/notes:manage', $this->page->context) || has_capability('moodle/notes:view', $this->page->context))) {
            $coursenode->add(get_string('notes','notes'), new moodle_url('/notes/index.php', array('filtertype'=>'course', 'filterselect'=>$filterselect)));
        }

        // Tags
        if (!empty($CFG->usetags) && isloggedin()) {
            $coursenode->add(get_string('tags', 'tag'), new moodle_url('/tag/search.php'));
        }

        if (isloggedin()) {
            // Calendar
            $calendarurl = new moodle_url('/calendar/view.php', array('view' => 'month'));
            $coursenode->add(get_string('calendar', 'calendar'), $calendarurl, self::TYPE_CUSTOM, null, 'calendar');
        }

        return true;
    }

    /**
     * Clears the navigation cache
     */
    public function clear_cache() {
        $this->cache->clear();
    }

    /**
     * Sets an expansion limit for the navigation
     *
     * The expansion limit is used to prevent the display of content that has a type
     * greater than the provided $type.
     *
     * Can be used to ensure things such as activities or activity content don't get
     * shown on the navigation.
     * They are still generated in order to ensure the navbar still makes sense.
     *
     * @param int $type One of navigation_node::TYPE_*
     * @return bool true when complete.
     */
    public function set_expansion_limit($type) {
        global $SITE;
        $nodes = $this->find_all_of_type($type);

        // We only want to hide specific types of nodes.
        // Only nodes that represent "structure" in the navigation tree should be hidden.
        // If we hide all nodes then we risk hiding vital information.
        $typestohide = array(
            self::TYPE_CATEGORY,
            self::TYPE_COURSE,
            self::TYPE_SECTION,
            self::TYPE_ACTIVITY
        );

        foreach ($nodes as $node) {
            // We need to generate the full site node
            if ($type == self::TYPE_COURSE && $node->key == $SITE->id) {
                continue;
            }
            foreach ($node->children as $child) {
                $child->hide($typestohide);
            }
        }
        return true;
    }
    /**
     * Attempts to get the navigation with the given key from this nodes children.
     *
     * This function only looks at this nodes children, it does NOT look recursivily.
     * If the node can't be found then false is returned.
     *
     * If you need to search recursivily then use the {@link global_navigation::find()} method.
     *
     * Note: If you are trying to set the active node {@link navigation_node::override_active_url()}
     * may be of more use to you.
     *
     * @param string|int $key The key of the node you wish to receive.
     * @param int $type One of navigation_node::TYPE_*
     * @return navigation_node|false
     */
    public function get($key, $type = null) {
        if (!$this->initialised) {
            $this->initialise();
        }
        return parent::get($key, $type);
    }

    /**
     * Searches this nodes children and their children to find a navigation node
     * with the matching key and type.
     *
     * This method is recursive and searches children so until either a node is
     * found or there are no more nodes to search.
     *
     * If you know that the node being searched for is a child of this node
     * then use the {@link global_navigation::get()} method instead.
     *
     * Note: If you are trying to set the active node {@link navigation_node::override_active_url()}
     * may be of more use to you.
     *
     * @param string|int $key The key of the node you wish to receive.
     * @param int $type One of navigation_node::TYPE_*
     * @return navigation_node|false
     */
    public function find($key, $type) {
        if (!$this->initialised) {
            $this->initialise();
        }
        if ($type == self::TYPE_ROOTNODE && array_key_exists($key, $this->rootnodes)) {
            return $this->rootnodes[$key];
        }
        return parent::find($key, $type);
    }

    /**
     * They've expanded the 'my courses' branch.
     */
    protected function load_courses_enrolled() {
        global $CFG, $DB;
        $sortorder = 'visible DESC';
        // Prevent undefined $CFG->navsortmycoursessort errors.
        if (empty($CFG->navsortmycoursessort)) {
            $CFG->navsortmycoursessort = 'sortorder';
        }
        // Append the chosen sortorder.
        $sortorder = $sortorder . ',' . $CFG->navsortmycoursessort . ' ASC';
        $courses = enrol_get_my_courses(null, $sortorder);
        if (count($courses) && $this->show_my_categories()) {
            // OK Actually we are loading categories. We only want to load categories that have a parent of 0.
            // In order to make sure we load everything required we must first find the categories that are not
            // base categories and work out the bottom category in thier path.
            $categoryids = array();
            foreach ($courses as $course) {
                $categoryids[] = $course->category;
            }
            $categoryids = array_unique($categoryids);
            list($sql, $params) = $DB->get_in_or_equal($categoryids);
            $categories = $DB->get_recordset_select('course_categories', 'id '.$sql.' AND parent <> 0', $params, 'sortorder, id', 'id, path');
            foreach ($categories as $category) {
                $bits = explode('/', trim($category->path,'/'));
                $categoryids[] = array_shift($bits);
            }
            $categoryids = array_unique($categoryids);
            $categories->close();

            // Now we load the base categories.
            list($sql, $params) = $DB->get_in_or_equal($categoryids);
            $categories = $DB->get_recordset_select('course_categories', 'id '.$sql.' AND parent = 0', $params, 'sortorder, id');
            foreach ($categories as $category) {
                $this->add_category($category, $this->rootnodes['mycourses'], self::TYPE_MY_CATEGORY);
            }
            $categories->close();
        } else {
            foreach ($courses as $course) {
                $this->add_course($course, false, self::COURSE_MY);
            }
        }
    }
}

/**
 * The global navigation class used especially for AJAX requests.
 *
 * The primary methods that are used in the global navigation class have been overriden
 * to ensure that only the relevant branch is generated at the root of the tree.
 * This can be done because AJAX is only used when the backwards structure for the
 * requested branch exists.
 * This has been done only because it shortens the amounts of information that is generated
 * which of course will speed up the response time.. because no one likes laggy AJAX.
 *
 * @package   core
 * @category  navigation
 * @copyright 2009 Sam Hemelryk
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class global_navigation_for_ajax extends global_navigation {

    /** @var int used for determining what type of navigation_node::TYPE_* is being used */
    protected $branchtype;

    /** @var int the instance id */
    protected $instanceid;

    /** @var array Holds an array of expandable nodes */
    protected $expandable = array();

    /**
     * Constructs the navigation for use in an AJAX request
     *
     * @param moodle_page $page moodle_page object
     * @param int $branchtype
     * @param int $id
     */
    public function __construct($page, $branchtype, $id) {
        $this->page = $page;
        $this->cache = new navigation_cache(NAVIGATION_CACHE_NAME);
        $this->children = new navigation_node_collection();
        $this->branchtype = $branchtype;
        $this->instanceid = $id;
        $this->initialise();
    }
    /**
     * Initialise the navigation given the type and id for the branch to expand.
     *
     * @return array An array of the expandable nodes
     */
    public function initialise() {
        global $DB, $SITE;

        if ($this->initialised || during_initial_install()) {
            return $this->expandable;
        }
        $this->initialised = true;

        $this->rootnodes = array();
        $this->rootnodes['site']    = $this->add_course($SITE);
        $this->rootnodes['mycourses'] = $this->add(get_string('mycourses'), new moodle_url('/my'), self::TYPE_ROOTNODE, null, 'mycourses');
        $this->rootnodes['courses'] = $this->add(get_string('courses'), null, self::TYPE_ROOTNODE, null, 'courses');
        // The courses branch is always displayed, and is always expandable (although may be empty).
        // This mimicks what is done during {@link global_navigation::initialise()}.
        $this->rootnodes['courses']->isexpandable = true;

        // Branchtype will be one of navigation_node::TYPE_*
        switch ($this->branchtype) {
            case 0:
                if ($this->instanceid === 'mycourses') {
                    $this->load_courses_enrolled();
                } else if ($this->instanceid === 'courses') {
                    $this->load_courses_other();
                }
                break;
            case self::TYPE_CATEGORY :
                $this->load_category($this->instanceid);
                break;
            case self::TYPE_MY_CATEGORY :
                $this->load_category($this->instanceid, self::TYPE_MY_CATEGORY);
                break;
            case self::TYPE_COURSE :
                $course = $DB->get_record('course', array('id' => $this->instanceid), '*', MUST_EXIST);
                if (!can_access_course($course)) {
                    // Thats OK all courses are expandable by default. We don't need to actually expand it we can just
                    // add the course node and break. This leads to an empty node.
                    $this->add_course($course);
                    break;
                }
                require_course_login($course, true, null, false, true);
                $this->page->set_context(context_course::instance($course->id));
                $coursenode = $this->add_course($course);
                $this->add_course_essentials($coursenode, $course);
                $this->load_course_sections($course, $coursenode);
                break;
            case self::TYPE_SECTION :
                $sql = 'SELECT c.*, cs.section AS sectionnumber
                        FROM {course} c
                        LEFT JOIN {course_sections} cs ON cs.course = c.id
                        WHERE cs.id = ?';
                $course = $DB->get_record_sql($sql, array($this->instanceid), MUST_EXIST);
                require_course_login($course, true, null, false, true);
                $this->page->set_context(context_course::instance($course->id));
                $coursenode = $this->add_course($course);
                $this->add_course_essentials($coursenode, $course);
                $this->load_course_sections($course, $coursenode, $course->sectionnumber);
                break;
            case self::TYPE_ACTIVITY :
                $sql = "SELECT c.*
                          FROM {course} c
                          JOIN {course_modules} cm ON cm.course = c.id
                         WHERE cm.id = :cmid";
                $params = array('cmid' => $this->instanceid);
                $course = $DB->get_record_sql($sql, $params, MUST_EXIST);
                $modinfo = get_fast_modinfo($course);
                $cm = $modinfo->get_cm($this->instanceid);
                require_course_login($course, true, $cm, false, true);
                $this->page->set_context(context_module::instance($cm->id));
                $coursenode = $this->load_course($course);
                $this->load_course_sections($course, $coursenode, null, $cm);
                $activitynode = $coursenode->find($cm->id, self::TYPE_ACTIVITY);
                if ($activitynode) {
                    $modulenode = $this->load_activity($cm, $course, $activitynode);
                }
                break;
            default:
                throw new Exception('Unknown type');
                return $this->expandable;
        }

        if ($this->page->context->contextlevel == CONTEXT_COURSE && $this->page->context->instanceid != $SITE->id) {
            $this->load_for_user(null, true);
        }

        $this->find_expandable($this->expandable);
        return $this->expandable;
    }

    /**
     * They've expanded the general 'courses' branch.
     */
    protected function load_courses_other() {
        $this->load_all_courses();
    }

    /**
     * Loads a single category into the AJAX navigation.
     *
     * This function is special in that it doesn't concern itself with the parent of
     * the requested category or its siblings.
     * This is because with the AJAX navigation we know exactly what is wanted and only need to
     * request that.
     *
     * @global moodle_database $DB
     * @param int $categoryid id of category to load in navigation.
     * @param int $nodetype type of node, if category is under MyHome then it's TYPE_MY_CATEGORY
     * @return void.
     */
    protected function load_category($categoryid, $nodetype = self::TYPE_CATEGORY) {
        global $CFG, $DB;

        $limit = 20;
        if (!empty($CFG->navcourselimit)) {
            $limit = (int)$CFG->navcourselimit;
        }

        $catcontextsql = context_helper::get_preload_record_columns_sql('ctx');
        $sql = "SELECT cc.*, $catcontextsql
                  FROM {course_categories} cc
                  JOIN {context} ctx ON cc.id = ctx.instanceid
                 WHERE ctx.contextlevel = ".CONTEXT_COURSECAT." AND
                       (cc.id = :categoryid1 OR cc.parent = :categoryid2)
              ORDER BY cc.depth ASC, cc.sortorder ASC, cc.id ASC";
        $params = array('categoryid1' => $categoryid, 'categoryid2' => $categoryid);
        $categories = $DB->get_recordset_sql($sql, $params, 0, $limit);
        $categorylist = array();
        $subcategories = array();
        $basecategory = null;
        foreach ($categories as $category) {
            $categorylist[] = $category->id;
            context_helper::preload_from_record($category);
            if ($category->id == $categoryid) {
                $this->add_category($category, $this, $nodetype);
                $basecategory = $this->addedcategories[$category->id];
            } else {
                $subcategories[$category->id] = $category;
            }
        }
        $categories->close();


        // If category is shown in MyHome then only show enrolled courses and hide empty subcategories,
        // else show all courses.
        if ($nodetype === self::TYPE_MY_CATEGORY) {
            $courses = enrol_get_my_courses();
            $categoryids = array();

            // Only search for categories if basecategory was found.
            if (!is_null($basecategory)) {
                // Get course parent category ids.
                foreach ($courses as $course) {
                    $categoryids[] = $course->category;
                }

                // Get a unique list of category ids which a part of the path
                // to user's courses.
                $coursesubcategories = array();
                $addedsubcategories = array();

                list($sql, $params) = $DB->get_in_or_equal($categoryids);
                $categories = $DB->get_recordset_select('course_categories', 'id '.$sql, $params, 'sortorder, id', 'id, path');

                foreach ($categories as $category){
                    $coursesubcategories = array_merge($coursesubcategories, explode('/', trim($category->path, "/")));
                }
                $coursesubcategories = array_unique($coursesubcategories);

                // Only add a subcategory if it is part of the path to user's course and
                // wasn't already added.
                foreach ($subcategories as $subid => $subcategory) {
                    if (in_array($subid, $coursesubcategories) &&
                            !in_array($subid, $addedsubcategories)) {
                            $this->add_category($subcategory, $basecategory, $nodetype);
                            $addedsubcategories[] = $subid;
                    }
                }
            }

            foreach ($courses as $course) {
                // Add course if it's in category.
                if (in_array($course->category, $categorylist)) {
                    $this->add_course($course, true, self::COURSE_MY);
                }
            }
        } else {
            if (!is_null($basecategory)) {
                foreach ($subcategories as $key=>$category) {
                    $this->add_category($category, $basecategory, $nodetype);
                }
            }
            $courses = $DB->get_recordset('course', array('category' => $categoryid), 'sortorder', '*' , 0, $limit);
            foreach ($courses as $course) {
                $this->add_course($course);
            }
            $courses->close();
        }
    }

    /**
     * Returns an array of expandable nodes
     * @return array
     */
    public function get_expandable() {
        return $this->expandable;
    }
}

/**
 * Navbar class
 *
 * This class is used to manage the navbar, which is initialised from the navigation
 * object held by PAGE
 *
 * @package   core
 * @category  navigation
 * @copyright 2009 Sam Hemelryk
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class navbar extends navigation_node {
    /** @var bool A switch for whether the navbar is initialised or not */
    protected $initialised = false;
    /** @var mixed keys used to reference the nodes on the navbar */
    protected $keys = array();
    /** @var null|string content of the navbar */
    protected $content = null;
    /** @var moodle_page object the moodle page that this navbar belongs to */
    protected $page;
    /** @var bool A switch for whether to ignore the active navigation information */
    protected $ignoreactive = false;
    /** @var bool A switch to let us know if we are in the middle of an install */
    protected $duringinstall = false;
    /** @var bool A switch for whether the navbar has items */
    protected $hasitems = false;
    /** @var array An array of navigation nodes for the navbar */
    protected $items;
    /** @var array An array of child node objects */
    public $children = array();
    /** @var bool A switch for whether we want to include the root node in the navbar */
    public $includesettingsbase = false;
    /** @var navigation_node[] $prependchildren */
    protected $prependchildren = array();
    /**
     * The almighty constructor
     *
     * @param moodle_page $page
     */
    public function __construct(moodle_page $page) {
        global $CFG;
        if (during_initial_install()) {
            $this->duringinstall = true;
            return false;
        }
        $this->page = $page;
        $this->text = get_string('home');
        $this->shorttext = get_string('home');
        $this->action = new moodle_url($CFG->wwwroot);
        $this->nodetype = self::NODETYPE_BRANCH;
        $this->type = self::TYPE_SYSTEM;
    }

    /**
     * Quick check to see if the navbar will have items in.
     *
     * @return bool Returns true if the navbar will have items, false otherwise
     */
    public function has_items() {
        if ($this->duringinstall) {
            return false;
        } else if ($this->hasitems !== false) {
            return true;
        }
        $this->page->navigation->initialise($this->page);

        $activenodefound = ($this->page->navigation->contains_active_node() ||
                            $this->page->settingsnav->contains_active_node());

        $outcome = (count($this->children) > 0 || count($this->prependchildren) || (!$this->ignoreactive && $activenodefound));
        $this->hasitems = $outcome;
        return $outcome;
    }

    /**
     * Turn on/off ignore active
     *
     * @param bool $setting
     */
    public function ignore_active($setting=true) {
        $this->ignoreactive = ($setting);
    }

    /**
     * Gets a navigation node
     *
     * @param string|int $key for referencing the navbar nodes
     * @param int $type navigation_node::TYPE_*
     * @return navigation_node|bool
     */
    public function get($key, $type = null) {
        foreach ($this->children as &$child) {
            if ($child->key === $key && ($type == null || $type == $child->type)) {
                return $child;
            }
        }
        foreach ($this->prependchildren as &$child) {
            if ($child->key === $key && ($type == null || $type == $child->type)) {
                return $child;
            }
        }
        return false;
    }
    /**
     * Returns an array of navigation_node's that make up the navbar.
     *
     * @return array
     */
    public function get_items() {
        global $CFG;
        $items = array();
        // Make sure that navigation is initialised
        if (!$this->has_items()) {
            return $items;
        }
        if ($this->items !== null) {
            return $this->items;
        }

        if (count($this->children) > 0) {
            // Add the custom children.
            $items = array_reverse($this->children);
        }

        $navigationactivenode = $this->page->navigation->find_active_node();
        $settingsactivenode = $this->page->settingsnav->find_active_node();

        // Check if navigation contains the active node
        if (!$this->ignoreactive) {

            if ($navigationactivenode && $settingsactivenode) {
                // Parse a combined navigation tree
                while ($settingsactivenode && $settingsactivenode->parent !== null) {
                    if (!$settingsactivenode->mainnavonly) {
                        $items[] = $settingsactivenode;
                    }
                    $settingsactivenode = $settingsactivenode->parent;
                }
                if (!$this->includesettingsbase) {
                    // Removes the first node from the settings (root node) from the list
                    array_pop($items);
                }
                while ($navigationactivenode && $navigationactivenode->parent !== null) {
                    if (!$navigationactivenode->mainnavonly) {
                        $items[] = $navigationactivenode;
                    }
                    if (!empty($CFG->navshowcategories) &&
                            $navigationactivenode->type === self::TYPE_COURSE &&
                            $navigationactivenode->parent->key === 'currentcourse') {
                        $items = array_merge($items, $this->get_course_categories());
                    }
                    $navigationactivenode = $navigationactivenode->parent;
                }
            } else if ($navigationactivenode) {
                // Parse the navigation tree to get the active node
                while ($navigationactivenode && $navigationactivenode->parent !== null) {
                    if (!$navigationactivenode->mainnavonly) {
                        $items[] = $navigationactivenode;
                    }
                    if (!empty($CFG->navshowcategories) &&
                            $navigationactivenode->type === self::TYPE_COURSE &&
                            $navigationactivenode->parent->key === 'currentcourse') {
                        $items = array_merge($items, $this->get_course_categories());
                    }
                    $navigationactivenode = $navigationactivenode->parent;
                }
            } else if ($settingsactivenode) {
                // Parse the settings navigation to get the active node
                while ($settingsactivenode && $settingsactivenode->parent !== null) {
                    if (!$settingsactivenode->mainnavonly) {
                        $items[] = $settingsactivenode;
                    }
                    $settingsactivenode = $settingsactivenode->parent;
                }
            }
        }

        $items[] = new navigation_node(array(
            'text'=>$this->page->navigation->text,
            'shorttext'=>$this->page->navigation->shorttext,
            'key'=>$this->page->navigation->key,
            'action'=>$this->page->navigation->action
        ));

        if (count($this->prependchildren) > 0) {
            // Add the custom children
            $items = array_merge($items, array_reverse($this->prependchildren));
        }

        $this->items = array_reverse($items);
        return $this->items;
    }

    /**
     * Get the list of categories leading to this course.
     *
     * This function is used by {@link navbar::get_items()} to add back the "courses"
     * node and category chain leading to the current course.  Note that this is only ever
     * called for the current course, so we don't need to bother taking in any parameters.
     *
     * @return array
     */
    private function get_course_categories() {
        global $CFG;

        require_once($CFG->dirroot.'/course/lib.php');
        $categories = array();
        $cap = 'moodle/category:viewhiddencategories';
        foreach ($this->page->categories as $category) {
            if (!$category->visible && !has_capability($cap, get_category_or_system_context($category->parent))) {
                continue;
            }
            $url = new moodle_url('/course/index.php', array('categoryid' => $category->id));
            $name = format_string($category->name, true, array('context' => context_coursecat::instance($category->id)));
            $categorynode = navigation_node::create($name, $url, self::TYPE_CATEGORY, null, $category->id);
            if (!$category->visible) {
                $categorynode->hidden = true;
            }
            $categories[] = $categorynode;
        }
        if (is_enrolled(context_course::instance($this->page->course->id))) {
            $courses = $this->page->navigation->get('mycourses');
        } else {
            $courses = $this->page->navigation->get('courses');
        }
        if (!$courses) {
            // Courses node may not be present.
            $courses = navigation_node::create(
                get_string('courses'),
                new moodle_url('/course/index.php'),
                self::TYPE_CONTAINER
            );
        }
        $categories[] = $courses;
        return $categories;
    }

    /**
     * Add a new navigation_node to the navbar, overrides parent::add
     *
     * This function overrides {@link navigation_node::add()} so that we can change
     * the way nodes get added to allow us to simply call add and have the node added to the
     * end of the navbar
     *
     * @param string $text
     * @param string|moodle_url|action_link $action An action to associate with this node.
     * @param int $type One of navigation_node::TYPE_*
     * @param string $shorttext
     * @param string|int $key A key to identify this node with. Key + type is unique to a parent.
     * @param pix_icon $icon An optional icon to use for this node.
     * @return navigation_node
     */
    public function add($text, $action=null, $type=self::TYPE_CUSTOM, $shorttext=null, $key=null, pix_icon $icon=null) {
        if ($this->content !== null) {
            debugging('Nav bar items must be printed before $OUTPUT->header() has been called', DEBUG_DEVELOPER);
        }

        // Properties array used when creating the new navigation node
        $itemarray = array(
            'text' => $text,
            'type' => $type
        );
        // Set the action if one was provided
        if ($action!==null) {
            $itemarray['action'] = $action;
        }
        // Set the shorttext if one was provided
        if ($shorttext!==null) {
            $itemarray['shorttext'] = $shorttext;
        }
        // Set the icon if one was provided
        if ($icon!==null) {
            $itemarray['icon'] = $icon;
        }
        // Default the key to the number of children if not provided
        if ($key === null) {
            $key = count($this->children);
        }
        // Set the key
        $itemarray['key'] = $key;
        // Set the parent to this node
        $itemarray['parent'] = $this;
        // Add the child using the navigation_node_collections add method
        $this->children[] = new navigation_node($itemarray);
        return $this;
    }

    /**
     * Prepends a new navigation_node to the start of the navbar
     *
     * @param string $text
     * @param string|moodle_url|action_link $action An action to associate with this node.
     * @param int $type One of navigation_node::TYPE_*
     * @param string $shorttext
     * @param string|int $key A key to identify this node with. Key + type is unique to a parent.
     * @param pix_icon $icon An optional icon to use for this node.
     * @return navigation_node
     */
    public function prepend($text, $action=null, $type=self::TYPE_CUSTOM, $shorttext=null, $key=null, pix_icon $icon=null) {
        if ($this->content !== null) {
            debugging('Nav bar items must be printed before $OUTPUT->header() has been called', DEBUG_DEVELOPER);
        }
        // Properties array used when creating the new navigation node.
        $itemarray = array(
            'text' => $text,
            'type' => $type
        );
        // Set the action if one was provided.
        if ($action!==null) {
            $itemarray['action'] = $action;
        }
        // Set the shorttext if one was provided.
        if ($shorttext!==null) {
            $itemarray['shorttext'] = $shorttext;
        }
        // Set the icon if one was provided.
        if ($icon!==null) {
            $itemarray['icon'] = $icon;
        }
        // Default the key to the number of children if not provided.
        if ($key === null) {
            $key = count($this->children);
        }
        // Set the key.
        $itemarray['key'] = $key;
        // Set the parent to this node.
        $itemarray['parent'] = $this;
        // Add the child node to the prepend list.
        $this->prependchildren[] = new navigation_node($itemarray);
        return $this;
    }
}

/**
 * Class used to manage the settings option for the current page
 *
 * This class is used to manage the settings options in a tree format (recursively)
 * and was created initially for use with the settings blocks.
 *
 * @package   core
 * @category  navigation
 * @copyright 2009 Sam Hemelryk
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class settings_navigation extends navigation_node {
    /** @var stdClass the current context */
    protected $context;
    /** @var moodle_page the moodle page that the navigation belongs to */
    protected $page;
    /** @var string contains administration section navigation_nodes */
    protected $adminsection;
    /** @var bool A switch to see if the navigation node is initialised */
    protected $initialised = false;
    /** @var array An array of users that the nodes can extend for. */
    protected $userstoextendfor = array();
    /** @var navigation_cache **/
    protected $cache;

    /**
     * Sets up the object with basic settings and preparse it for use
     *
     * @param moodle_page $page
     */
    public function __construct(moodle_page &$page) {
        if (during_initial_install()) {
            return false;
        }
        $this->page = $page;
        // Initialise the main navigation. It is most important that this is done
        // before we try anything
        $this->page->navigation->initialise();
        // Initialise the navigation cache
        $this->cache = new navigation_cache(NAVIGATION_CACHE_NAME);
        $this->children = new navigation_node_collection();
    }
    /**
     * Initialise the settings navigation based on the current context
     *
     * This function initialises the settings navigation tree for a given context
     * by calling supporting functions to generate major parts of the tree.
     *
     */
    public function initialise() {
        global $DB, $SESSION, $SITE;

        if (during_initial_install()) {
            return false;
        } else if ($this->initialised) {
            return true;
        }
        $this->id = 'settingsnav';
        $this->context = $this->page->context;

        $context = $this->context;
        if ($context->contextlevel == CONTEXT_BLOCK) {
            $this->load_block_settings();
            $context = $context->get_parent_context();
        }
        switch ($context->contextlevel) {
            case CONTEXT_SYSTEM:
                if ($this->page->url->compare(new moodle_url('/admin/settings.php', array('section'=>'frontpagesettings')))) {
                    $this->load_front_page_settings(($context->id == $this->context->id));
                }
                break;
            case CONTEXT_COURSECAT:
                $this->load_category_settings();
                break;
            case CONTEXT_COURSE:
                if ($this->page->course->id != $SITE->id) {
                    $this->load_course_settings(($context->id == $this->context->id));
                } else {
                    $this->load_front_page_settings(($context->id == $this->context->id));
                }
                break;
            case CONTEXT_MODULE:
                $this->load_module_settings();
                $this->load_course_settings();
                break;
            case CONTEXT_USER:
                if ($this->page->course->id != $SITE->id) {
                    $this->load_course_settings();
                }
                break;
        }

        $usersettings = $this->load_user_settings($this->page->course->id);
        /*********** eClass Modification ************
        First Author:  Greg Gibeau
        Initial Date:  May 22 2012
        Last Author:   Trevor Jones
        Date Changed:  May 5, 2014

        Extra Comments: Embed category administration into the settings block
         * June 18, 2012: Added check for login.
         * March 21, 2013: Changed comment style.

         ************/
        if (isloggedin() && !isguestuser()) {
            $this->load_category_administration();
        }
        /*********** End eClass Modification ********/

        $adminsettings = false;
        if (isloggedin() && !isguestuser() && (!isset($SESSION->load_navigation_admin) || $SESSION->load_navigation_admin)) {
            $isadminpage = $this->is_admin_tree_needed();

            if (has_capability('moodle/site:config', context_system::instance())) {
                // Make sure this works even if config capability changes on the fly
                // and also make it fast for admin right after login.
                $SESSION->load_navigation_admin = 1;
                if ($isadminpage) {
                    $adminsettings = $this->load_administration_settings();
                }

            } else if (!isset($SESSION->load_navigation_admin)) {
                $adminsettings = $this->load_administration_settings();
                $SESSION->load_navigation_admin = (int)($adminsettings->children->count() > 0);

            } else if ($SESSION->load_navigation_admin) {
                if ($isadminpage) {
                    $adminsettings = $this->load_administration_settings();
                }
            }

            // Print empty navigation node, if needed.
            if ($SESSION->load_navigation_admin && !$isadminpage) {
                if ($adminsettings) {
                    // Do not print settings tree on pages that do not need it, this helps with performance.
                    $adminsettings->remove();
                    $adminsettings = false;
                }
                $siteadminnode = $this->add(get_string('administrationsite'), new moodle_url('/admin'), self::TYPE_SITE_ADMIN, null, 'siteadministration');
                $siteadminnode->id = 'expandable_branch_'.$siteadminnode->type.'_'.clean_param($siteadminnode->key, PARAM_ALPHANUMEXT);
                $this->page->requires->data_for_js('siteadminexpansion', $siteadminnode);
            }
        }

        if ($context->contextlevel == CONTEXT_SYSTEM && $adminsettings) {
            $adminsettings->force_open();
        } else if ($context->contextlevel == CONTEXT_USER && $usersettings) {
            $usersettings->force_open();
        }

        // Check if the user is currently logged in as another user
        if (\core\session\manager::is_loggedinas()) {
            // Get the actual user, we need this so we can display an informative return link
            $realuser = \core\session\manager::get_realuser();
            // Add the informative return to original user link
            $url = new moodle_url('/course/loginas.php',array('id'=>$this->page->course->id, 'return'=>1,'sesskey'=>sesskey()));
            $this->add(get_string('returntooriginaluser', 'moodle', fullname($realuser, true)), $url, self::TYPE_SETTING, null, null, new pix_icon('t/left', ''));
        }

        // At this point we give any local plugins the ability to extend/tinker with the navigation settings.
        $this->load_local_plugin_settings();

        foreach ($this->children as $key=>$node) {
            if ($node->nodetype != self::NODETYPE_BRANCH || $node->children->count()===0) {
                // Site administration is shown as link.
                if (!empty($SESSION->load_navigation_admin) && ($node->type === self::TYPE_SITE_ADMIN)) {
                    continue;
                }
                $node->remove();
            }
        }
        $this->initialised = true;
    }
    /**
     * Override the parent function so that we can add preceeding hr's and set a
     * root node class against all first level element
     *
     * It does this by first calling the parent's add method {@link navigation_node::add()}
     * and then proceeds to use the key to set class and hr
     *
     * @param string $text text to be used for the link.
     * @param string|moodle_url $url url for the new node
     * @param int $type the type of node navigation_node::TYPE_*
     * @param string $shorttext
     * @param string|int $key a key to access the node by.
     * @param pix_icon $icon An icon that appears next to the node.
     * @return navigation_node with the new node added to it.
     */
    public function add($text, $url=null, $type=null, $shorttext=null, $key=null, pix_icon $icon=null) {
        $node = parent::add($text, $url, $type, $shorttext, $key, $icon);
        $node->add_class('root_node');
        return $node;
    }

    /**
     * This function allows the user to add something to the start of the settings
     * navigation, which means it will be at the top of the settings navigation block
     *
     * @param string $text text to be used for the link.
     * @param string|moodle_url $url url for the new node
     * @param int $type the type of node navigation_node::TYPE_*
     * @param string $shorttext
     * @param string|int $key a key to access the node by.
     * @param pix_icon $icon An icon that appears next to the node.
     * @return navigation_node $node with the new node added to it.
     */
    public function prepend($text, $url=null, $type=null, $shorttext=null, $key=null, pix_icon $icon=null) {
        $children = $this->children;
        $childrenclass = get_class($children);
        $this->children = new $childrenclass;
        $node = $this->add($text, $url, $type, $shorttext, $key, $icon);
        foreach ($children as $child) {
            $this->children->add($child);
        }
        return $node;
    }

    /**
     * Does this page require loading of full admin tree or is
     * it enough rely on AJAX?
     *
     * @return bool
     */
    protected function is_admin_tree_needed() {
        if (self::$loadadmintree) {
            // Usually external admin page or settings page.
            return true;
        }

        if ($this->page->pagelayout === 'admin' or strpos($this->page->pagetype, 'admin-') === 0) {
            // Admin settings tree is intended for system level settings and management only, use navigation for the rest!
            if ($this->page->context->contextlevel != CONTEXT_SYSTEM) {
                return false;
            }
            return true;
        }

        return false;
    }

    /**
     * Load the site administration tree
     *
     * This function loads the site administration tree by using the lib/adminlib library functions
     *
     * @param navigation_node $referencebranch A reference to a branch in the settings
     *      navigation tree
     * @param part_of_admin_tree $adminbranch The branch to add, if null generate the admin
     *      tree and start at the beginning
     * @return mixed A key to access the admin tree by
     */
    protected function load_administration_settings(navigation_node $referencebranch=null, part_of_admin_tree $adminbranch=null) {
        global $CFG;

        // Check if we are just starting to generate this navigation.
        if ($referencebranch === null) {

            // Require the admin lib then get an admin structure
            if (!function_exists('admin_get_root')) {
                require_once($CFG->dirroot.'/lib/adminlib.php');
            }
            $adminroot = admin_get_root(false, false);
            // This is the active section identifier
            $this->adminsection = $this->page->url->param('section');

            // Disable the navigation from automatically finding the active node
            navigation_node::$autofindactive = false;
            $referencebranch = $this->add(get_string('administrationsite'), null, self::TYPE_SITE_ADMIN, null, 'root');
            foreach ($adminroot->children as $adminbranch) {
                $this->load_administration_settings($referencebranch, $adminbranch);
            }
            navigation_node::$autofindactive = true;

            // Use the admin structure to locate the active page
            if (!$this->contains_active_node() && $current = $adminroot->locate($this->adminsection, true)) {
                $currentnode = $this;
                while (($pathkey = array_pop($current->path))!==null && $currentnode) {
                    $currentnode = $currentnode->get($pathkey);
                }
                if ($currentnode) {
                    $currentnode->make_active();
                }
            } else {
                $this->scan_for_active_node($referencebranch);
            }
            return $referencebranch;
        } else if ($adminbranch->check_access()) {
            // We have a reference branch that we can access and is not hidden `hurrah`
            // Now we need to display it and any children it may have
            $url = null;
            $icon = null;
            if ($adminbranch instanceof admin_settingpage) {
                $url = new moodle_url('/'.$CFG->admin.'/settings.php', array('section'=>$adminbranch->name));
            } else if ($adminbranch instanceof admin_externalpage) {
                $url = $adminbranch->url;
            } else if (!empty($CFG->linkadmincategories) && $adminbranch instanceof admin_category) {
                $url = new moodle_url('/'.$CFG->admin.'/category.php', array('category' => $adminbranch->name));
            }

            // Add the branch
            $reference = $referencebranch->add($adminbranch->visiblename, $url, self::TYPE_SETTING, null, $adminbranch->name, $icon);

            if ($adminbranch->is_hidden()) {
                if (($adminbranch instanceof admin_externalpage || $adminbranch instanceof admin_settingpage) && $adminbranch->name == $this->adminsection) {
                    $reference->add_class('hidden');
                } else {
                    $reference->display = false;
                }
            }

            // Check if we are generating the admin notifications and whether notificiations exist
            if ($adminbranch->name === 'adminnotifications' && admin_critical_warnings_present()) {
                $reference->add_class('criticalnotification');
            }
            // Check if this branch has children
            if ($reference && isset($adminbranch->children) && is_array($adminbranch->children) && count($adminbranch->children)>0) {
                foreach ($adminbranch->children as $branch) {
                    // Generate the child branches as well now using this branch as the reference
                    $this->load_administration_settings($reference, $branch);
                }
            } else {
                $reference->icon = new pix_icon('i/settings', '');
            }
        }
    }

    /**
     * This function recursivily scans nodes until it finds the active node or there
     * are no more nodes.
     * @param navigation_node $node
     */
    protected function scan_for_active_node(navigation_node $node) {
        if (!$node->check_if_active() && $node->children->count()>0) {
            foreach ($node->children as &$child) {
                $this->scan_for_active_node($child);
            }
        }
    }

    /**
     * Gets a navigation node given an array of keys that represent the path to
     * the desired node.
     *
     * @param array $path
     * @return navigation_node|false
     */
    protected function get_by_path(array $path) {
        $node = $this->get(array_shift($path));
        foreach ($path as $key) {
            $node->get($key);
        }
        return $node;
    }

    /**
     * This function loads the course settings that are available for the user
     *
     * @param bool $forceopen If set to true the course node will be forced open
     * @return navigation_node|false
     */
    protected function load_course_settings($forceopen = false) {
        global $CFG;

        $course = $this->page->course;
        $coursecontext = context_course::instance($course->id);

        // note: do not test if enrolled or viewing here because we need the enrol link in Course administration section

        $coursenode = $this->add(get_string('courseadministration'), null, self::TYPE_COURSE, null, 'courseadmin');
        if ($forceopen) {
            $coursenode->force_open();
        }

        if ($this->page->user_allowed_editing()) {
            // Add the turn on/off settings

            if ($this->page->url->compare(new moodle_url('/course/view.php'), URL_MATCH_BASE)) {
                // We are on the course page, retain the current page params e.g. section.
                $baseurl = clone($this->page->url);
                $baseurl->param('sesskey', sesskey());
            } else {
                // Edit on the main course page.
                $baseurl = new moodle_url('/course/view.php', array('id'=>$course->id, 'return'=>$this->page->url->out_as_local_url(false), 'sesskey'=>sesskey()));
            }

            $editurl = clone($baseurl);
            if ($this->page->user_is_editing()) {
                $editurl->param('edit', 'off');
                $editstring = get_string('turneditingoff');
            } else {
                $editurl->param('edit', 'on');
                $editstring = get_string('turneditingon');
            }
            $coursenode->add($editstring, $editurl, self::TYPE_SETTING, null, 'turneditingonoff', new pix_icon('i/edit', ''));
        }

        if (has_capability('moodle/course:update', $coursecontext)) {
            // Add the course settings link
            $url = new moodle_url('/course/edit.php', array('id'=>$course->id));
            $coursenode->add(get_string('editsettings'), $url, self::TYPE_SETTING, null, 'editsettings', new pix_icon('i/settings', ''));

            // Add the course completion settings link
            if ($CFG->enablecompletion && $course->enablecompletion) {
                $url = new moodle_url('/course/completion.php', array('id'=>$course->id));
                $coursenode->add(get_string('coursecompletion', 'completion'), $url, self::TYPE_SETTING, null, null, new pix_icon('i/settings', ''));
            }
        }

        // add enrol nodes
        enrol_add_course_navigation($coursenode, $course);

        // Manage filters
        if (has_capability('moodle/filter:manage', $coursecontext) && count(filter_get_available_in_context($coursecontext))>0) {
            $url = new moodle_url('/filter/manage.php', array('contextid'=>$coursecontext->id));
            $coursenode->add(get_string('filters', 'admin'), $url, self::TYPE_SETTING, null, null, new pix_icon('i/filter', ''));
        }

        // View course reports.
        if (has_capability('moodle/site:viewreports', $coursecontext)) { // Basic capability for listing of reports.
            $reportnav = $coursenode->add(get_string('reports'), null, self::TYPE_CONTAINER, null, 'coursereports',
                    new pix_icon('i/stats', ''));
            $coursereports = core_component::get_plugin_list('coursereport');
            foreach ($coursereports as $report => $dir) {
                $libfile = $CFG->dirroot.'/course/report/'.$report.'/lib.php';
                if (file_exists($libfile)) {
                    require_once($libfile);
                    $reportfunction = $report.'_report_extend_navigation';
                    if (function_exists($report.'_report_extend_navigation')) {
                        $reportfunction($reportnav, $course, $coursecontext);
                    }
                }
            }

            $reports = get_plugin_list_with_function('report', 'extend_navigation_course', 'lib.php');
            foreach ($reports as $reportfunction) {
                $reportfunction($reportnav, $course, $coursecontext);
            }
        }

        // Add view grade report is permitted
        $reportavailable = false;
        if (has_capability('moodle/grade:viewall', $coursecontext)) {
            $reportavailable = true;
        } else if (!empty($course->showgrades)) {
            $reports = core_component::get_plugin_list('gradereport');
            if (is_array($reports) && count($reports)>0) {     // Get all installed reports
                arsort($reports); // user is last, we want to test it first
                foreach ($reports as $plugin => $plugindir) {
                    if (has_capability('gradereport/'.$plugin.':view', $coursecontext)) {
                        //stop when the first visible plugin is found
                        $reportavailable = true;
                        break;
                    }
                }
            }
        }
        if ($reportavailable) {
            $url = new moodle_url('/grade/report/index.php', array('id'=>$course->id));
            $gradenode = $coursenode->add(get_string('grades'), $url, self::TYPE_SETTING, null, 'grades', new pix_icon('i/grades', ''));
        }

        //  Add outcome if permitted
        if (!empty($CFG->enableoutcomes) && has_capability('moodle/course:update', $coursecontext)) {
            $url = new moodle_url('/grade/edit/outcome/course.php', array('id'=>$course->id));
            $coursenode->add(get_string('outcomes', 'grades'), $url, self::TYPE_SETTING, null, 'outcomes', new pix_icon('i/outcomes', ''));
        }

        //Add badges navigation
        require_once($CFG->libdir .'/badgeslib.php');
        badges_add_course_navigation($coursenode, $course);

        // Backup this course
        if (has_capability('moodle/backup:backupcourse', $coursecontext)) {
            $url = new moodle_url('/backup/backup.php', array('id'=>$course->id));
            $coursenode->add(get_string('backup'), $url, self::TYPE_SETTING, null, 'backup', new pix_icon('i/backup', ''));
        }

        // Restore to this course
        if (has_capability('moodle/restore:restorecourse', $coursecontext)) {
            $url = new moodle_url('/backup/restorefile.php', array('contextid'=>$coursecontext->id));
            $coursenode->add(get_string('restore'), $url, self::TYPE_SETTING, null, 'restore', new pix_icon('i/restore', ''));
        }

        // Import data from other courses
        if (has_capability('moodle/restore:restoretargetimport', $coursecontext)) {
            $url = new moodle_url('/backup/import.php', array('id'=>$course->id));
            $coursenode->add(get_string('import'), $url, self::TYPE_SETTING, null, 'import', new pix_icon('i/import', ''));
        }

        // Publish course on a hub
        if (has_capability('moodle/course:publish', $coursecontext)) {
            $url = new moodle_url('/course/publish/index.php', array('id'=>$course->id));
            $coursenode->add(get_string('publish'), $url, self::TYPE_SETTING, null, 'publish', new pix_icon('i/publish', ''));
        }

        // Reset this course
        if (has_capability('moodle/course:reset', $coursecontext)) {
            $url = new moodle_url('/course/reset.php', array('id'=>$course->id));
            $coursenode->add(get_string('reset'), $url, self::TYPE_SETTING, null, null, new pix_icon('i/return', ''));
        }

        // Questions
        require_once($CFG->libdir . '/questionlib.php');
        question_extend_settings_navigation($coursenode, $coursecontext)->trim_if_empty();

        if (has_capability('moodle/course:update', $coursecontext)) {
            // Repository Instances
            if (!$this->cache->cached('contexthasrepos'.$coursecontext->id)) {
                require_once($CFG->dirroot . '/repository/lib.php');
                $editabletypes = repository::get_editable_types($coursecontext);
                $haseditabletypes = !empty($editabletypes);
                unset($editabletypes);
                $this->cache->set('contexthasrepos'.$coursecontext->id, $haseditabletypes);
            } else {
                $haseditabletypes = $this->cache->{'contexthasrepos'.$coursecontext->id};
            }
            if ($haseditabletypes) {
                $url = new moodle_url('/repository/manage_instances.php', array('contextid' => $coursecontext->id));
                $coursenode->add(get_string('repositories'), $url, self::TYPE_SETTING, null, null, new pix_icon('i/repository', ''));
            }
        }

        // Manage files
        if ($course->legacyfiles == 2 and has_capability('moodle/course:managefiles', $coursecontext)) {
            // hidden in new courses and courses where legacy files were turned off
            $url = new moodle_url('/files/index.php', array('contextid'=>$coursecontext->id));
            $coursenode->add(get_string('courselegacyfiles'), $url, self::TYPE_SETTING, null, 'coursefiles', new pix_icon('i/folder', ''));

        }

        // Switch roles
        $roles = array();
        $assumedrole = $this->in_alternative_role();
        if ($assumedrole !== false) {
            $roles[0] = get_string('switchrolereturn');
        }
        if (has_capability('moodle/role:switchroles', $coursecontext)) {
            $availableroles = get_switchable_roles($coursecontext);
            if (is_array($availableroles)) {
                foreach ($availableroles as $key=>$role) {
                    if ($assumedrole == (int)$key) {
                        continue;
                    }
                    $roles[$key] = $role;
                }
            }
        }
        if (is_array($roles) && count($roles)>0) {
            $switchroles = $this->add(get_string('switchroleto'));
            if ((count($roles)==1 && array_key_exists(0, $roles))|| $assumedrole!==false) {
                $switchroles->force_open();
            }
            foreach ($roles as $key => $name) {
                $url = new moodle_url('/course/switchrole.php', array('id'=>$course->id, 'sesskey'=>sesskey(), 'switchrole'=>$key, 'returnurl'=>$this->page->url->out_as_local_url(false)));
                $switchroles->add($name, $url, self::TYPE_SETTING, null, $key, new pix_icon('i/switchrole', ''));
            }
        }

        // Let admin tools hook into course navigation.
        $tools = get_plugin_list_with_function('tool', 'extend_navigation_course', 'lib.php');
        foreach ($tools as $toolfunction) {
            $toolfunction($coursenode, $course, $coursecontext);
        }

        // Return we are done
        return $coursenode;
    }

    /**
     * This function calls the module function to inject module settings into the
     * settings navigation tree.
     *
     * This only gets called if there is a corrosponding function in the modules
     * lib file.
     *
     * For examples mod/forum/lib.php {@link forum_extend_settings_navigation()}
     *
     * @return navigation_node|false
     */
    protected function load_module_settings() {
        global $CFG;

        if (!$this->page->cm && $this->context->contextlevel == CONTEXT_MODULE && $this->context->instanceid) {
            $cm = get_coursemodule_from_id(false, $this->context->instanceid, 0, false, MUST_EXIST);
            $this->page->set_cm($cm, $this->page->course);
        }

        $file = $CFG->dirroot.'/mod/'.$this->page->activityname.'/lib.php';
        if (file_exists($file)) {
            require_once($file);
        }

        $modulenode = $this->add(get_string('pluginadministration', $this->page->activityname), null, self::TYPE_SETTING, null, 'modulesettings');
        $modulenode->force_open();

        // Settings for the module
        if (has_capability('moodle/course:manageactivities', $this->page->cm->context)) {
            $url = new moodle_url('/course/modedit.php', array('update' => $this->page->cm->id, 'return' => 1));
            $modulenode->add(get_string('editsettings'), $url, navigation_node::TYPE_SETTING, null, 'modedit');
        }
        // Assign local roles
        if (count(get_assignable_roles($this->page->cm->context))>0) {
            $url = new moodle_url('/'.$CFG->admin.'/roles/assign.php', array('contextid'=>$this->page->cm->context->id));
            $modulenode->add(get_string('localroles', 'role'), $url, self::TYPE_SETTING, null, 'roleassign');
        }
        // Override roles
        if (has_capability('moodle/role:review', $this->page->cm->context) or count(get_overridable_roles($this->page->cm->context))>0) {
            $url = new moodle_url('/'.$CFG->admin.'/roles/permissions.php', array('contextid'=>$this->page->cm->context->id));
            $modulenode->add(get_string('permissions', 'role'), $url, self::TYPE_SETTING, null, 'roleoverride');
        }
        // Check role permissions
        if (has_any_capability(array('moodle/role:assign', 'moodle/role:safeoverride','moodle/role:override', 'moodle/role:assign'), $this->page->cm->context)) {
            $url = new moodle_url('/'.$CFG->admin.'/roles/check.php', array('contextid'=>$this->page->cm->context->id));
            $modulenode->add(get_string('checkpermissions', 'role'), $url, self::TYPE_SETTING, null, 'rolecheck');
        }
        // Manage filters
        if (has_capability('moodle/filter:manage', $this->page->cm->context) && count(filter_get_available_in_context($this->page->cm->context))>0) {
            $url = new moodle_url('/filter/manage.php', array('contextid'=>$this->page->cm->context->id));
            $modulenode->add(get_string('filters', 'admin'), $url, self::TYPE_SETTING, null, 'filtermanage');
        }
        // Add reports
        $reports = get_plugin_list_with_function('report', 'extend_navigation_module', 'lib.php');
        foreach ($reports as $reportfunction) {
            $reportfunction($modulenode, $this->page->cm);
        }
        // Add a backup link
        $featuresfunc = $this->page->activityname.'_supports';
        if (function_exists($featuresfunc) && $featuresfunc(FEATURE_BACKUP_MOODLE2) && has_capability('moodle/backup:backupactivity', $this->page->cm->context)) {
            $url = new moodle_url('/backup/backup.php', array('id'=>$this->page->cm->course, 'cm'=>$this->page->cm->id));
            $modulenode->add(get_string('backup'), $url, self::TYPE_SETTING, null, 'backup');
        }

        // Restore this activity
        $featuresfunc = $this->page->activityname.'_supports';
        if (function_exists($featuresfunc) && $featuresfunc(FEATURE_BACKUP_MOODLE2) && has_capability('moodle/restore:restoreactivity', $this->page->cm->context)) {
            $url = new moodle_url('/backup/restorefile.php', array('contextid'=>$this->page->cm->context->id));
            $modulenode->add(get_string('restore'), $url, self::TYPE_SETTING, null, 'restore');
        }

        // Allow the active advanced grading method plugin to append its settings
        $featuresfunc = $this->page->activityname.'_supports';
        if (function_exists($featuresfunc) && $featuresfunc(FEATURE_ADVANCED_GRADING) && has_capability('moodle/grade:managegradingforms', $this->page->cm->context)) {
            require_once($CFG->dirroot.'/grade/grading/lib.php');
            $gradingman = get_grading_manager($this->page->cm->context, 'mod_'.$this->page->activityname);
            $gradingman->extend_settings_navigation($this, $modulenode);
        }

        $function = $this->page->activityname.'_extend_settings_navigation';
        if (!function_exists($function)) {
            return $modulenode;
        }

        $function($this, $modulenode);

        // Remove the module node if there are no children
        if (empty($modulenode->children)) {
            $modulenode->remove();
        }

        return $modulenode;
    }

    /**
     * Loads the user settings block of the settings nav
     *
     * This function is simply works out the userid and whether we need to load
     * just the current users profile settings, or the current user and the user the
     * current user is viewing.
     *
     * This function has some very ugly code to work out the user, if anyone has
     * any bright ideas please feel free to intervene.
     *
     * @param int $courseid The course id of the current course
     * @return navigation_node|false
     */
    protected function load_user_settings($courseid = SITEID) {
        global $USER, $CFG;

        if (isguestuser() || !isloggedin()) {
            return false;
        }

        $navusers = $this->page->navigation->get_extending_users();

        if (count($this->userstoextendfor) > 0 || count($navusers) > 0) {
            $usernode = null;
            foreach ($this->userstoextendfor as $userid) {
                if ($userid == $USER->id) {
                    continue;
                }
                $node = $this->generate_user_settings($courseid, $userid, 'userviewingsettings');
                if (is_null($usernode)) {
                    $usernode = $node;
                }
            }
            foreach ($navusers as $user) {
                if ($user->id == $USER->id) {
                    continue;
                }
                $node = $this->generate_user_settings($courseid, $user->id, 'userviewingsettings');
                if (is_null($usernode)) {
                    $usernode = $node;
                }
            }
            $this->generate_user_settings($courseid, $USER->id);
        } else {
            $usernode = $this->generate_user_settings($courseid, $USER->id);
        }
        return $usernode;
    }

    /**
     * Extends the settings navigation for the given user.
     *
     * Note: This method gets called automatically if you call
     * $PAGE->navigation->extend_for_user($userid)
     *
     * @param int $userid
     */
    public function extend_for_user($userid) {
        global $CFG;

        if (!in_array($userid, $this->userstoextendfor)) {
            $this->userstoextendfor[] = $userid;
            if ($this->initialised) {
                $this->generate_user_settings($this->page->course->id, $userid, 'userviewingsettings');
                $children = array();
                foreach ($this->children as $child) {
                    $children[] = $child;
                }
                array_unshift($children, array_pop($children));
                $this->children = new navigation_node_collection();
                foreach ($children as $child) {
                    $this->children->add($child);
                }
            }
        }
    }

    /**
     * This function gets called by {@link settings_navigation::load_user_settings()} and actually works out
     * what can be shown/done
     *
     * @param int $courseid The current course' id
     * @param int $userid The user id to load for
     * @param string $gstitle The string to pass to get_string for the branch title
     * @return navigation_node|false
     */
    protected function generate_user_settings($courseid, $userid, $gstitle='usercurrentsettings') {
        global $DB, $CFG, $USER, $SITE;

        if ($courseid != $SITE->id) {
            if (!empty($this->page->course->id) && $this->page->course->id == $courseid) {
                $course = $this->page->course;
            } else {
                $select = context_helper::get_preload_record_columns_sql('ctx');
                $sql = "SELECT c.*, $select
                          FROM {course} c
                          JOIN {context} ctx ON c.id = ctx.instanceid
                         WHERE c.id = :courseid AND ctx.contextlevel = :contextlevel";
                $params = array('courseid' => $courseid, 'contextlevel' => CONTEXT_COURSE);
                $course = $DB->get_record_sql($sql, $params, MUST_EXIST);
                context_helper::preload_from_record($course);
            }
        } else {
            $course = $SITE;
        }

        $coursecontext = context_course::instance($course->id);   // Course context
        $systemcontext   = context_system::instance();
        $currentuser = ($USER->id == $userid);

        if ($currentuser) {
            $user = $USER;
            $usercontext = context_user::instance($user->id);       // User context
        } else {
            $select = context_helper::get_preload_record_columns_sql('ctx');
            $sql = "SELECT u.*, $select
                      FROM {user} u
                      JOIN {context} ctx ON u.id = ctx.instanceid
                     WHERE u.id = :userid AND ctx.contextlevel = :contextlevel";
            $params = array('userid' => $userid, 'contextlevel' => CONTEXT_USER);
            $user = $DB->get_record_sql($sql, $params, IGNORE_MISSING);
            if (!$user) {
                return false;
            }
            context_helper::preload_from_record($user);

            // Check that the user can view the profile
            $usercontext = context_user::instance($user->id); // User context
            $canviewuser = has_capability('moodle/user:viewdetails', $usercontext);

            if ($course->id == $SITE->id) {
                if ($CFG->forceloginforprofiles && !has_coursecontact_role($user->id) && !$canviewuser) {  // Reduce possibility of "browsing" userbase at site level
                    // Teachers can browse and be browsed at site level. If not forceloginforprofiles, allow access (bug #4366)
                    return false;
                }
            } else {
                $canviewusercourse = has_capability('moodle/user:viewdetails', $coursecontext);
                $userisenrolled = is_enrolled($coursecontext, $user->id);
                if ((!$canviewusercourse && !$canviewuser) || !$userisenrolled) {
                    return false;
                }
                $canaccessallgroups = has_capability('moodle/site:accessallgroups', $coursecontext);
                if (!$canaccessallgroups && groups_get_course_groupmode($course) == SEPARATEGROUPS && !$canviewuser) {
                    // If groups are in use, make sure we can see that group (MDL-45874). That does not apply to parents.
                    if ($courseid == $this->page->course->id) {
                        $mygroups = get_fast_modinfo($this->page->course)->groups;
                    } else {
                        $mygroups = groups_get_user_groups($courseid);
                    }
                    $usergroups = groups_get_user_groups($courseid, $userid);
                    if (!array_intersect_key($mygroups[0], $usergroups[0])) {
                        return false;
                    }
                }
            }
        }

        $fullname = fullname($user, has_capability('moodle/site:viewfullnames', $this->page->context));

        $key = $gstitle;
        if ($gstitle != 'usercurrentsettings') {
            $key .= $userid;
        }

        // Add a user setting branch
        $usersetting = $this->add(get_string($gstitle, 'moodle', $fullname), null, self::TYPE_CONTAINER, null, $key);
        $usersetting->id = 'usersettings';
        if ($this->page->context->contextlevel == CONTEXT_USER && $this->page->context->instanceid == $user->id) {
            // Automatically start by making it active
            $usersetting->make_active();
        }

        // Check if the user has been deleted
        if ($user->deleted) {
            if (!has_capability('moodle/user:update', $coursecontext)) {
                // We can't edit the user so just show the user deleted message
                $usersetting->add(get_string('userdeleted'), null, self::TYPE_SETTING);
            } else {
                // We can edit the user so show the user deleted message and link it to the profile
                if ($course->id == $SITE->id) {
                    $profileurl = new moodle_url('/user/profile.php', array('id'=>$user->id));
                } else {
                    $profileurl = new moodle_url('/user/view.php', array('id'=>$user->id, 'course'=>$course->id));
                }
                $usersetting->add(get_string('userdeleted'), $profileurl, self::TYPE_SETTING);
            }
            return true;
        }

        $userauthplugin = false;
        if (!empty($user->auth)) {
            $userauthplugin = get_auth_plugin($user->auth);
        }

        // Add the profile edit link
        if (isloggedin() && !isguestuser($user) && !is_mnet_remote_user($user)) {
            if (($currentuser || is_siteadmin($USER) || !is_siteadmin($user)) && has_capability('moodle/user:update', $systemcontext)) {
                $url = new moodle_url('/user/editadvanced.php', array('id'=>$user->id, 'course'=>$course->id));
                $usersetting->add(get_string('editmyprofile'), $url, self::TYPE_SETTING);
            } else if ((has_capability('moodle/user:editprofile', $usercontext) && !is_siteadmin($user)) || ($currentuser && has_capability('moodle/user:editownprofile', $systemcontext))) {
                if ($userauthplugin && $userauthplugin->can_edit_profile()) {
                    $url = $userauthplugin->edit_profile_url();
                    if (empty($url)) {
                        $url = new moodle_url('/user/edit.php', array('id'=>$user->id, 'course'=>$course->id));
                    }
                    $usersetting->add(get_string('editmyprofile'), $url, self::TYPE_SETTING);
                }
            }
        }

        // Change password link
        if ($userauthplugin && $currentuser && !\core\session\manager::is_loggedinas() && !isguestuser() && has_capability('moodle/user:changeownpassword', $systemcontext) && $userauthplugin->can_change_password()) {
            $passwordchangeurl = $userauthplugin->change_password_url();
            if (empty($passwordchangeurl)) {
                $passwordchangeurl = new moodle_url('/login/change_password.php', array('id'=>$course->id));
            }
            $usersetting->add(get_string("changepassword"), $passwordchangeurl, self::TYPE_SETTING, null, 'changepassword');
        }

        // View the roles settings
        if (has_any_capability(array('moodle/role:assign', 'moodle/role:safeoverride','moodle/role:override', 'moodle/role:manage'), $usercontext)) {
            $roles = $usersetting->add(get_string('roles'), null, self::TYPE_SETTING);

            $url = new moodle_url('/admin/roles/usersroles.php', array('userid'=>$user->id, 'courseid'=>$course->id));
            $roles->add(get_string('thisusersroles', 'role'), $url, self::TYPE_SETTING);

            $assignableroles = get_assignable_roles($usercontext, ROLENAME_BOTH);

            if (!empty($assignableroles)) {
                $url = new moodle_url('/admin/roles/assign.php', array('contextid'=>$usercontext->id,'userid'=>$user->id, 'courseid'=>$course->id));
                $roles->add(get_string('assignrolesrelativetothisuser', 'role'), $url, self::TYPE_SETTING);
            }

            if (has_capability('moodle/role:review', $usercontext) || count(get_overridable_roles($usercontext, ROLENAME_BOTH))>0) {
                $url = new moodle_url('/admin/roles/permissions.php', array('contextid'=>$usercontext->id,'userid'=>$user->id, 'courseid'=>$course->id));
                $roles->add(get_string('permissions', 'role'), $url, self::TYPE_SETTING);
            }

            $url = new moodle_url('/admin/roles/check.php', array('contextid'=>$usercontext->id,'userid'=>$user->id, 'courseid'=>$course->id));
            $roles->add(get_string('checkpermissions', 'role'), $url, self::TYPE_SETTING);
        }

        // Portfolio
        if ($currentuser && !empty($CFG->enableportfolios) && has_capability('moodle/portfolio:export', $systemcontext)) {
            require_once($CFG->libdir . '/portfoliolib.php');
            if (portfolio_has_visible_instances()) {
                $portfolio = $usersetting->add(get_string('portfolios', 'portfolio'), null, self::TYPE_SETTING);

                $url = new moodle_url('/user/portfolio.php', array('courseid'=>$course->id));
                $portfolio->add(get_string('configure', 'portfolio'), $url, self::TYPE_SETTING);

                $url = new moodle_url('/user/portfoliologs.php', array('courseid'=>$course->id));
                $portfolio->add(get_string('logs', 'portfolio'), $url, self::TYPE_SETTING);
            }
        }

        $enablemanagetokens = false;
        if (!empty($CFG->enablerssfeeds)) {
            $enablemanagetokens = true;
        } else if (!is_siteadmin($USER->id)
             && !empty($CFG->enablewebservices)
             && has_capability('moodle/webservice:createtoken', context_system::instance()) ) {
            $enablemanagetokens = true;
        }
        // Security keys
        if ($currentuser && $enablemanagetokens) {
            $url = new moodle_url('/user/managetoken.php', array('sesskey'=>sesskey()));
            $usersetting->add(get_string('securitykeys', 'webservice'), $url, self::TYPE_SETTING);
        }

        // Messaging
        if (($currentuser && has_capability('moodle/user:editownmessageprofile', $systemcontext)) || (!isguestuser($user) && has_capability('moodle/user:editmessageprofile', $usercontext) && !is_primary_admin($user->id))) {
            $url = new moodle_url('/message/edit.php', array('id'=>$user->id));
            $usersetting->add(get_string('messaging', 'message'), $url, self::TYPE_SETTING);
        }

        // Blogs
        if ($currentuser && !empty($CFG->enableblogs)) {
            $blog = $usersetting->add(get_string('blogs', 'blog'), null, navigation_node::TYPE_CONTAINER, null, 'blogs');
            $blog->add(get_string('preferences', 'blog'), new moodle_url('/blog/preferences.php'), navigation_node::TYPE_SETTING);
            if (!empty($CFG->useexternalblogs) && $CFG->maxexternalblogsperuser > 0 && has_capability('moodle/blog:manageexternal', context_system::instance())) {
                $blog->add(get_string('externalblogs', 'blog'), new moodle_url('/blog/external_blogs.php'), navigation_node::TYPE_SETTING);
                $blog->add(get_string('addnewexternalblog', 'blog'), new moodle_url('/blog/external_blog_edit.php'), navigation_node::TYPE_SETTING);
            }
        }

        // Badges.
        if ($currentuser && !empty($CFG->enablebadges)) {
            $badges = $usersetting->add(get_string('badges'), null, navigation_node::TYPE_CONTAINER, null, 'badges');
            $badges->add(get_string('preferences'), new moodle_url('/badges/preferences.php'), navigation_node::TYPE_SETTING);
            if (!empty($CFG->badges_allowexternalbackpack)) {
                $badges->add(get_string('backpackdetails', 'badges'), new moodle_url('/badges/mybackpack.php'), navigation_node::TYPE_SETTING);
            }
        }

        // Add reports node.
        $reporttab = $usersetting->add(get_string('activityreports'));
        $reports = get_plugin_list_with_function('report', 'extend_navigation_user', 'lib.php');
        foreach ($reports as $reportfunction) {
            $reportfunction($reporttab, $user, $course);
        }
        $anyreport = has_capability('moodle/user:viewuseractivitiesreport', $usercontext);
        if ($anyreport || ($course->showreports && $currentuser)) {
            // Add grade hardcoded grade report if necessary.
            $gradeaccess = false;
            if (has_capability('moodle/grade:viewall', $coursecontext)) {
                // Can view all course grades.
                $gradeaccess = true;
            } else if ($course->showgrades) {
                if ($currentuser && has_capability('moodle/grade:view', $coursecontext)) {
                    // Can view own grades.
                    $gradeaccess = true;
                } else if (has_capability('moodle/grade:viewall', $usercontext)) {
                    // Can view grades of this user - parent most probably.
                    $gradeaccess = true;
                } else if ($anyreport) {
                    // Can view grades of this user - parent most probably.
                    $gradeaccess = true;
                }
            }
            if ($gradeaccess) {
                $reporttab->add(get_string('grade'), new moodle_url('/course/user.php', array('mode'=>'grade', 'id'=>$course->id,
                        'user'=>$usercontext->instanceid)));
            }
        }
        // Check the number of nodes in the report node... if there are none remove the node
        $reporttab->trim_if_empty();

        // Login as ...
        if (!$user->deleted and !$currentuser && !\core\session\manager::is_loggedinas() && has_capability('moodle/user:loginas', $coursecontext) && !is_siteadmin($user->id)) {
            $url = new moodle_url('/course/loginas.php', array('id'=>$course->id, 'user'=>$user->id, 'sesskey'=>sesskey()));
            $usersetting->add(get_string('loginas'), $url, self::TYPE_SETTING);
        }

        // Let admin tools hook into user settings navigation.
        $tools = get_plugin_list_with_function('tool', 'extend_navigation_user_settings', 'lib.php');
        foreach ($tools as $toolfunction) {
            $toolfunction($usersetting, $user, $usercontext, $course, $coursecontext);
        }

        return $usersetting;
    }

    /**
     * Loads block specific settings in the navigation
     *
     * @return navigation_node
     */
    protected function load_block_settings() {
        global $CFG;

        $blocknode = $this->add($this->context->get_context_name(), null, self::TYPE_SETTING, null, 'blocksettings');
        $blocknode->force_open();

        // Assign local roles
        $assignurl = new moodle_url('/'.$CFG->admin.'/roles/assign.php', array('contextid'=>$this->context->id));
        $blocknode->add(get_string('assignroles', 'role'), $assignurl, self::TYPE_SETTING);

        // Override roles
        if (has_capability('moodle/role:review', $this->context) or  count(get_overridable_roles($this->context))>0) {
            $url = new moodle_url('/'.$CFG->admin.'/roles/permissions.php', array('contextid'=>$this->context->id));
            $blocknode->add(get_string('permissions', 'role'), $url, self::TYPE_SETTING);
        }
        // Check role permissions
        if (has_any_capability(array('moodle/role:assign', 'moodle/role:safeoverride','moodle/role:override', 'moodle/role:assign'), $this->context)) {
            $url = new moodle_url('/'.$CFG->admin.'/roles/check.php', array('contextid'=>$this->context->id));
            $blocknode->add(get_string('checkpermissions', 'role'), $url, self::TYPE_SETTING);
        }

        return $blocknode;
    }

    /**
     * Loads category specific settings in the navigation
     *
     * @return navigation_node
     */
    protected function load_category_settings() {
        global $CFG;

        // We can land here while being in the context of a block, in which case we
        // should get the parent context which should be the category one. See self::initialise().
        if ($this->context->contextlevel == CONTEXT_BLOCK) {
            $catcontext = $this->context->get_parent_context();
        } else {
            $catcontext = $this->context;
        }

        // Let's make sure that we always have the right context when getting here.
        if ($catcontext->contextlevel != CONTEXT_COURSECAT) {
            throw new coding_exception('Unexpected context while loading category settings.');
        }

        $categorynode = $this->add($catcontext->get_context_name(), null, null, null, 'categorysettings');
        $categorynode->force_open();

        if (can_edit_in_category($catcontext->instanceid)) {
            $url = new moodle_url('/course/management.php', array('categoryid' => $catcontext->instanceid));
            $editstring = get_string('managecategorythis');
            $categorynode->add($editstring, $url, self::TYPE_SETTING, null, null, new pix_icon('i/edit', ''));
        }

        if (has_capability('moodle/category:manage', $catcontext)) {
            $editurl = new moodle_url('/course/editcategory.php', array('id' => $catcontext->instanceid));
            $categorynode->add(get_string('editcategorythis'), $editurl, self::TYPE_SETTING, null, 'edit', new pix_icon('i/edit', ''));

            $addsubcaturl = new moodle_url('/course/editcategory.php', array('parent' => $catcontext->instanceid));
            $categorynode->add(get_string('addsubcategory'), $addsubcaturl, self::TYPE_SETTING, null, 'addsubcat', new pix_icon('i/withsubcat', ''));
        }

        // Assign local roles
        $assignableroles = get_assignable_roles($catcontext);
        if (!empty($assignableroles)) {
            $assignurl = new moodle_url('/'.$CFG->admin.'/roles/assign.php', array('contextid' => $catcontext->id));
            $categorynode->add(get_string('assignroles', 'role'), $assignurl, self::TYPE_SETTING, null, 'roles', new pix_icon('i/assignroles', ''));
        }

        // Override roles
        if (has_capability('moodle/role:review', $catcontext) or count(get_overridable_roles($catcontext)) > 0) {
            $url = new moodle_url('/'.$CFG->admin.'/roles/permissions.php', array('contextid' => $catcontext->id));
            $categorynode->add(get_string('permissions', 'role'), $url, self::TYPE_SETTING, null, 'permissions', new pix_icon('i/permissions', ''));
        }
        // Check role permissions
        if (has_any_capability(array('moodle/role:assign', 'moodle/role:safeoverride',
                'moodle/role:override', 'moodle/role:assign'), $catcontext)) {
            $url = new moodle_url('/'.$CFG->admin.'/roles/check.php', array('contextid' => $catcontext->id));
            $categorynode->add(get_string('checkpermissions', 'role'), $url, self::TYPE_SETTING, null, 'checkpermissions', new pix_icon('i/checkpermissions', ''));
        }

        // Cohorts
        if (has_any_capability(array('moodle/cohort:view', 'moodle/cohort:manage'), $catcontext)) {
            $categorynode->add(get_string('cohorts', 'cohort'), new moodle_url('/cohort/index.php',
                array('contextid' => $catcontext->id)), self::TYPE_SETTING, null, 'cohort', new pix_icon('i/cohort', ''));
        }

        // Manage filters
        if (has_capability('moodle/filter:manage', $catcontext) && count(filter_get_available_in_context($catcontext)) > 0) {
            $url = new moodle_url('/filter/manage.php', array('contextid' => $catcontext->id));
            $categorynode->add(get_string('filters', 'admin'), $url, self::TYPE_SETTING, null, 'filters', new pix_icon('i/filter', ''));
        }

        // Restore.
        if (has_capability('moodle/course:create', $catcontext)) {
            $url = new moodle_url('/backup/restorefile.php', array('contextid' => $catcontext->id));
            $categorynode->add(get_string('restorecourse', 'admin'), $url, self::TYPE_SETTING, null, 'restorecourse', new pix_icon('i/restore', ''));
        }

        return $categorynode;
    }

    /**
     * Determine whether the user is assuming another role
     *
     * This function checks to see if the user is assuming another role by means of
     * role switching. In doing this we compare each RSW key (context path) against
     * the current context path. This ensures that we can provide the switching
     * options against both the course and any page shown under the course.
     *
     * @return bool|int The role(int) if the user is in another role, false otherwise
     */
    protected function in_alternative_role() {
        global $USER;
        if (!empty($USER->access['rsw']) && is_array($USER->access['rsw'])) {
            if (!empty($this->page->context) && !empty($USER->access['rsw'][$this->page->context->path])) {
                return $USER->access['rsw'][$this->page->context->path];
            }
            foreach ($USER->access['rsw'] as $key=>$role) {
                if (strpos($this->context->path,$key)===0) {
                    return $role;
                }
            }
        }
        return false;
    }

    /**
     * This function loads all of the front page settings into the settings navigation.
     * This function is called when the user is on the front page, or $COURSE==$SITE
     * @param bool $forceopen (optional)
     * @return navigation_node
     */
    protected function load_front_page_settings($forceopen = false) {
        global $SITE, $CFG;

        $course = clone($SITE);
        $coursecontext = context_course::instance($course->id);   // Course context

        $frontpage = $this->add(get_string('frontpagesettings'), null, self::TYPE_SETTING, null, 'frontpage');
        if ($forceopen) {
            $frontpage->force_open();
        }
        $frontpage->id = 'frontpagesettings';

        if ($this->page->user_allowed_editing()) {

            // Add the turn on/off settings
            $url = new moodle_url('/course/view.php', array('id'=>$course->id, 'sesskey'=>sesskey()));
            if ($this->page->user_is_editing()) {
                $url->param('edit', 'off');
                $editstring = get_string('turneditingoff');
            } else {
                $url->param('edit', 'on');
                $editstring = get_string('turneditingon');
            }
            $frontpage->add($editstring, $url, self::TYPE_SETTING, null, null, new pix_icon('i/edit', ''));
        }

        if (has_capability('moodle/course:update', $coursecontext)) {
            // Add the course settings link
            $url = new moodle_url('/admin/settings.php', array('section'=>'frontpagesettings'));
            $frontpage->add(get_string('editsettings'), $url, self::TYPE_SETTING, null, null, new pix_icon('i/settings', ''));
        }

        // add enrol nodes
        enrol_add_course_navigation($frontpage, $course);

        // Manage filters
        if (has_capability('moodle/filter:manage', $coursecontext) && count(filter_get_available_in_context($coursecontext))>0) {
            $url = new moodle_url('/filter/manage.php', array('contextid'=>$coursecontext->id));
            $frontpage->add(get_string('filters', 'admin'), $url, self::TYPE_SETTING, null, null, new pix_icon('i/filter', ''));
        }

        // View course reports.
        if (has_capability('moodle/site:viewreports', $coursecontext)) { // Basic capability for listing of reports.
            $frontpagenav = $frontpage->add(get_string('reports'), null, self::TYPE_CONTAINER, null, 'frontpagereports',
                    new pix_icon('i/stats', ''));
            $coursereports = core_component::get_plugin_list('coursereport');
            foreach ($coursereports as $report=>$dir) {
                $libfile = $CFG->dirroot.'/course/report/'.$report.'/lib.php';
                if (file_exists($libfile)) {
                    require_once($libfile);
                    $reportfunction = $report.'_report_extend_navigation';
                    if (function_exists($report.'_report_extend_navigation')) {
                        $reportfunction($frontpagenav, $course, $coursecontext);
                    }
                }
            }

            $reports = get_plugin_list_with_function('report', 'extend_navigation_course', 'lib.php');
            foreach ($reports as $reportfunction) {
                $reportfunction($frontpagenav, $course, $coursecontext);
            }
        }

        // Backup this course
        if (has_capability('moodle/backup:backupcourse', $coursecontext)) {
            $url = new moodle_url('/backup/backup.php', array('id'=>$course->id));
            $frontpage->add(get_string('backup'), $url, self::TYPE_SETTING, null, null, new pix_icon('i/backup', ''));
        }

        // Restore to this course
        if (has_capability('moodle/restore:restorecourse', $coursecontext)) {
            $url = new moodle_url('/backup/restorefile.php', array('contextid'=>$coursecontext->id));
            $frontpage->add(get_string('restore'), $url, self::TYPE_SETTING, null, null, new pix_icon('i/restore', ''));
        }

        // Questions
        require_once($CFG->libdir . '/questionlib.php');
        question_extend_settings_navigation($frontpage, $coursecontext)->trim_if_empty();

        // Manage files
        if ($course->legacyfiles == 2 and has_capability('moodle/course:managefiles', $this->context)) {
            //hiden in new installs
            $url = new moodle_url('/files/index.php', array('contextid'=>$coursecontext->id));
            $frontpage->add(get_string('sitelegacyfiles'), $url, self::TYPE_SETTING, null, null, new pix_icon('i/folder', ''));
        }

        // Let admin tools hook into frontpage navigation.
        $tools = get_plugin_list_with_function('tool', 'extend_navigation_frontpage', 'lib.php');
        foreach ($tools as $toolfunction) {
            $toolfunction($frontpage, $course, $coursecontext);
        }

        return $frontpage;
    }

    /**
     * This function gives local plugins an opportunity to modify the settings navigation.
     */
    protected function load_local_plugin_settings() {
        // Get all local plugins with an extend_settings_navigation function in their lib.php file
        foreach (get_plugin_list_with_function('local', 'extends_settings_navigation') as $function) {
            // Call each function providing this (the settings navigation) and the current context.
            $function($this, $this->context);
        }
    }

    /**
     * This function marks the cache as volatile so it is cleared during shutdown
     */
    public function clear_cache() {
        $this->cache->volatile();
    }

    /*********** local_contextadmin Modification ************
     * Extra Comments: Most of the functionality of this method has been extracted into the local_contextadmin module in the localnav.php file.
     * This should keep the core changes to a minimum.
     ************/
    /**
     * Loads category specific administration in the navigation
     *
     * @return navigation_node
     */
    protected function load_category_administration()
    {
        global $CFG, $USER;
        if (isloggedin() && !isguestuser()) {
            // Check if the local module exists, if not then skip this.
            if (file_exists($CFG->dirroot . '/local/contextadmin/localnav.php')) {
                require_once($CFG->dirroot . '/local/contextadmin/localnav.php');
                require_once($CFG->dirroot . '/local/contextadmin/locallib.php');
                if (has_category_view_capability($USER->id)) {
                    return get_context_nav(category_get_root($this), $this->context); // Create children nodes
                }
            }
        }
        return false;
    }
    /*********** End local_contextadmin Modification ********/
}

/**
 * Class used to populate site admin navigation for ajax.
 *
 * @package   core
 * @category  navigation
 * @copyright 2013 Rajesh Taneja <rajesh@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class settings_navigation_ajax extends settings_navigation {
    /**
     * Constructs the navigation for use in an AJAX request
     *
     * @param moodle_page $page
     */
    public function __construct(moodle_page &$page) {
        $this->page = $page;
        $this->cache = new navigation_cache(NAVIGATION_CACHE_NAME);
        $this->children = new navigation_node_collection();
        $this->initialise();
    }

    /**
     * Initialise the site admin navigation.
     *
     * @return array An array of the expandable nodes
     */
    public function initialise() {
        if ($this->initialised || during_initial_install()) {
            return false;
        }
        $this->context = $this->page->context;
        $this->load_administration_settings();

        // Check if local plugins is adding node to site admin.
        $this->load_local_plugin_settings();

        $this->initialised = true;
    }
}

/**
 * Simple class used to output a navigation branch in XML
 *
 * @package   core
 * @category  navigation
 * @copyright 2009 Sam Hemelryk
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class navigation_json {
    /** @var array An array of different node types */
    protected $nodetype = array('node','branch');
    /** @var array An array of node keys and types */
    protected $expandable = array();
    /**
     * Turns a branch and all of its children into XML
     *
     * @param navigation_node $branch
     * @return string XML string
     */
    public function convert($branch) {
        $xml = $this->convert_child($branch);
        return $xml;
    }
    /**
     * Set the expandable items in the array so that we have enough information
     * to attach AJAX events
     * @param array $expandable
     */
    public function set_expandable($expandable) {
        foreach ($expandable as $node) {
            $this->expandable[$node['key'].':'.$node['type']] = $node;
        }
    }
    /**
     * Recusively converts a child node and its children to XML for output
     *
     * @param navigation_node $child The child to convert
     * @param int $depth Pointlessly used to track the depth of the XML structure
     * @return string JSON
     */
    protected function convert_child($child, $depth=1) {
        if (!$child->display) {
            return '';
        }
        $attributes = array();
        $attributes['id'] = $child->id;
        $attributes['name'] = (string)$child->text; // This can be lang_string object so typecast it.
        $attributes['type'] = $child->type;
        $attributes['key'] = $child->key;
        $attributes['class'] = $child->get_css_type();

        if ($child->icon instanceof pix_icon) {
            $attributes['icon'] = array(
                'component' => $child->icon->component,
                'pix' => $child->icon->pix,
            );
            foreach ($child->icon->attributes as $key=>$value) {
                if ($key == 'class') {
                    $attributes['icon']['classes'] = explode(' ', $value);
                } else if (!array_key_exists($key, $attributes['icon'])) {
                    $attributes['icon'][$key] = $value;
                }

            }
        } else if (!empty($child->icon)) {
            $attributes['icon'] = (string)$child->icon;
        }

        if ($child->forcetitle || $child->title !== $child->text) {
            $attributes['title'] = htmlentities($child->title, ENT_QUOTES, 'UTF-8');
        }
        if (array_key_exists($child->key.':'.$child->type, $this->expandable)) {
            $attributes['expandable'] = $child->key;
            $child->add_class($this->expandable[$child->key.':'.$child->type]['id']);
        }

        if (count($child->classes)>0) {
            $attributes['class'] .= ' '.join(' ',$child->classes);
        }
        if (is_string($child->action)) {
            $attributes['link'] = $child->action;
        } else if ($child->action instanceof moodle_url) {
            $attributes['link'] = $child->action->out();
        } else if ($child->action instanceof action_link) {
            $attributes['link'] = $child->action->url->out();
        }
        $attributes['hidden'] = ($child->hidden);
        $attributes['haschildren'] = ($child->children->count()>0 || $child->type == navigation_node::TYPE_CATEGORY);
        $attributes['haschildren'] = $attributes['haschildren'] || $child->type == navigation_node::TYPE_MY_CATEGORY;

        if ($child->children->count() > 0) {
            $attributes['children'] = array();
            foreach ($child->children as $subchild) {
                $attributes['children'][] = $this->convert_child($subchild, $depth+1);
            }
        }

        if ($depth > 1) {
            return $attributes;
        } else {
            return json_encode($attributes);
        }
    }
}

/**
 * The cache class used by global navigation and settings navigation.
 *
 * It is basically an easy access point to session with a bit of smarts to make
 * sure that the information that is cached is valid still.
 *
 * Example use:
 * <code php>
 * if (!$cache->viewdiscussion()) {
 *     // Code to do stuff and produce cachable content
 *     $cache->viewdiscussion = has_capability('mod/forum:viewdiscussion', $coursecontext);
 * }
 * $content = $cache->viewdiscussion;
 * </code>
 *
 * @package   core
 * @category  navigation
 * @copyright 2009 Sam Hemelryk
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class navigation_cache {
    /** @var int represents the time created */
    protected $creation;
    /** @var array An array of session keys */
    protected $session;
    /**
     * The string to use to segregate this particular cache. It can either be
     * unique to start a fresh cache or if you want to share a cache then make
     * it the string used in the original cache.
     * @var string
     */
    protected $area;
    /** @var int a time that the information will time out */
    protected $timeout;
    /** @var stdClass The current context */
    protected $currentcontext;
    /** @var int cache time information */
    const CACHETIME = 0;
    /** @var int cache user id */
    const CACHEUSERID = 1;
    /** @var int cache value */
    const CACHEVALUE = 2;
    /** @var null|array An array of navigation cache areas to expire on shutdown */
    public static $volatilecaches;

    /**
     * Contructor for the cache. Requires two arguments
     *
     * @param string $area The string to use to segregate this particular cache
     *                it can either be unique to start a fresh cache or if you want
     *                to share a cache then make it the string used in the original
     *                cache
     * @param int $timeout The number of seconds to time the information out after
     */
    public function __construct($area, $timeout=1800) {
        $this->creation = time();
        $this->area = $area;
        $this->timeout = time() - $timeout;
        if (rand(0,100) === 0) {
            $this->garbage_collection();
        }
    }

    /**
     * Used to set up the cache within the SESSION.
     *
     * This is called for each access and ensure that we don't put anything into the session before
     * it is required.
     */
    protected function ensure_session_cache_initialised() {
        global $SESSION;
        if (empty($this->session)) {
            if (!isset($SESSION->navcache)) {
                $SESSION->navcache = new stdClass;
            }
            if (!isset($SESSION->navcache->{$this->area})) {
                $SESSION->navcache->{$this->area} = array();
            }
            $this->session = &$SESSION->navcache->{$this->area}; // pointer to array, =& is correct here
        }
    }

    /**
     * Magic Method to retrieve something by simply calling using = cache->key
     *
     * @param mixed $key The identifier for the information you want out again
     * @return void|mixed Either void or what ever was put in
     */
    public function __get($key) {
        if (!$this->cached($key)) {
            return;
        }
        $information = $this->session[$key][self::CACHEVALUE];
        return unserialize($information);
    }

    /**
     * Magic method that simply uses {@link set();} to store something in the cache
     *
     * @param string|int $key
     * @param mixed $information
     */
    public function __set($key, $information) {
        $this->set($key, $information);
    }

    /**
     * Sets some information against the cache (session) for later retrieval
     *
     * @param string|int $key
     * @param mixed $information
     */
    public function set($key, $information) {
        global $USER;
        $this->ensure_session_cache_initialised();
        $information = serialize($information);
        $this->session[$key]= array(self::CACHETIME=>time(), self::CACHEUSERID=>$USER->id, self::CACHEVALUE=>$information);
    }
    /**
     * Check the existence of the identifier in the cache
     *
     * @param string|int $key
     * @return bool
     */
    public function cached($key) {
        global $USER;
        $this->ensure_session_cache_initialised();
        if (!array_key_exists($key, $this->session) || !is_array($this->session[$key]) || $this->session[$key][self::CACHEUSERID]!=$USER->id || $this->session[$key][self::CACHETIME] < $this->timeout) {
            return false;
        }
        return true;
    }
    /**
     * Compare something to it's equivilant in the cache
     *
     * @param string $key
     * @param mixed $value
     * @param bool $serialise Whether to serialise the value before comparison
     *              this should only be set to false if the value is already
     *              serialised
     * @return bool If the value is the same false if it is not set or doesn't match
     */
    public function compare($key, $value, $serialise = true) {
        if ($this->cached($key)) {
            if ($serialise) {
                $value = serialize($value);
            }
            if ($this->session[$key][self::CACHEVALUE] === $value) {
                return true;
            }
        }
        return false;
    }
    /**
     * Wipes the entire cache, good to force regeneration
     */
    public function clear() {
        global $SESSION;
        unset($SESSION->navcache);
        $this->session = null;
    }
    /**
     * Checks all cache entries and removes any that have expired, good ole cleanup
     */
    protected function garbage_collection() {
        if (empty($this->session)) {
            return true;
        }
        foreach ($this->session as $key=>$cachedinfo) {
            if (is_array($cachedinfo) && $cachedinfo[self::CACHETIME]<$this->timeout) {
                unset($this->session[$key]);
            }
        }
    }

    /**
     * Marks the cache as being volatile (likely to change)
     *
     * Any caches marked as volatile will be destroyed at the on shutdown by
     * {@link navigation_node::destroy_volatile_caches()} which is registered
     * as a shutdown function if any caches are marked as volatile.
     *
     * @param bool $setting True to destroy the cache false not too
     */
    public function volatile($setting = true) {
        if (self::$volatilecaches===null) {
            self::$volatilecaches = array();
            core_shutdown_manager::register_function(array('navigation_cache','destroy_volatile_caches'));
        }

        if ($setting) {
            self::$volatilecaches[$this->area] = $this->area;
        } else if (array_key_exists($this->area, self::$volatilecaches)) {
            unset(self::$volatilecaches[$this->area]);
        }
    }

    /**
     * Destroys all caches marked as volatile
     *
     * This function is static and works in conjunction with the static volatilecaches
     * property of navigation cache.
     * Because this function is static it manually resets the cached areas back to an
     * empty array.
     */
    public static function destroy_volatile_caches() {
        global $SESSION;
        if (is_array(self::$volatilecaches) && count(self::$volatilecaches)>0) {
            foreach (self::$volatilecaches as $area) {
                $SESSION->navcache->{$area} = array();
            }
        } else {
            $SESSION->navcache = new stdClass;
        }
    }
}
