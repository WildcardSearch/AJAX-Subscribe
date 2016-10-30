<?php
/*
 * Plugin Name: AJAX Subscribe for MyBB 1.8.x
 * Copyright 2016 WildcardSearch
 * http://www.rantcentralforums.com
 *
 * this is the main plugin file
 */

// disallow direct access to this file for security reasons.
if (!defined('IN_MYBB')) {
    die('Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.');
}

// load the install/admin routines only if in ACP.
if (defined('IN_ADMINCP')) {
    require_once MYBB_ROOT . 'inc/plugins/subscribe/install.php';
} else {
	require_once MYBB_ROOT . 'inc/plugins/subscribe/forum.php';
}

?>
