<?php
$capabilities = array(
    
    'local/metadata:ins_view' => array(
        
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array( // The roles that you want to allow
            'teacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        ),
    ),

    'local/metadata:admin_view' => array(

        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array( // The roles that you want to allow
            'manager' => CAP_ALLOW
        ),
    ),

);

?>
