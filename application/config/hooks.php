<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| Auth middleware hook - checks authentication and role on protected routes
*/

$hook['post_controller_constructor'] = array(
    'class'    => 'Auth_hook',
    'function' => 'check_access',
    'filename' => 'Auth_hook.php',
    'filepath' => 'hooks'
);
