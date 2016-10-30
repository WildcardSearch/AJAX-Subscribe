<?php
/*
 * Plugin Name: AJAX Subscribe for MyBB 1.8.x
 * Copyright 2016 WildcardSearch
 * http://www.rantcentralforums.com
 *
 * the forum-side routines start here
 */

subscribeInitialize();

function subscribeInitialize() {
	global $subscribe_js, $mybb, $plugins;

	switch (THIS_SCRIPT) {
		case 'xmlhttp.php':
			if ($mybb->get_input('action') == 'subscribe') {
				$plugins->add_hook('xmlhttp', 'subscribe_xmlhttp');
			}
			break;
		case 'forumdisplay.php':
		case 'showthread.php':
			$subscribe_js = <<<EOF
<script type="text/javascript" src="{$mybb->asset_url}/jscripts/subscribe/subscribe.js"></script>


EOF;
	}
}

function subscribe_xmlhttp()
{
	global $mybb, $lang;

	require_once MYBB_ROOT . 'inc/functions_user.php';

	$lang->load("usercp");
	$lang->load("subscribe");

	$data = $mybb->input['data'];
	$data['notification'] = $mybb->user['subscriptionmethod'];

	if ($data['type'] == "forum") {
		$lang->load("forumdisplay");

		$forum = get_forum($data['fid']);
		if(!$forum)
		{
			error($lang->error_invalidforum);
		}

		if ($data['action'] == 'removesubscription') {
			remove_subscribed_forum($forum['fid']);

			$returnArray = array(
				"success" => $lang->subscribe_forum_subscription_removed,
				"linkText" => $lang->subscribe_forum,
			);
		} else {
			$forumpermissions = forum_permissions($forum['fid']);
			if($forumpermissions['canview'] == 0 || $forumpermissions['canviewthreads'] == 0)
			{
				error_no_permission();
			}

			add_subscribed_forum($forum['fid']);

			$returnArray = array(
				"success" => $lang->subscribe_forum_subscription_added,
				"linkText" => $lang->unsubscribe_forum,
			);
		}

		// send headers
		header("Content-type: application/json; charset={$lang->settings['charset']}");
		echo json_encode($returnArray);
		exit;
	}

	$lang->load("showthread");

	$thread = get_thread($data['tid']);
	if(!$thread)
	{
		error($lang->error_invalidthread);
	}

	// Is the currently logged in user a moderator of this forum?
	$ismod = is_moderator($thread['fid']);

	// Make sure we are looking at a real thread here.
	if(($thread['visible'] != 1 && $ismod == false) || ($thread['visible'] > 1 && $ismod == true))
	{
		error($lang->error_invalidthread);
	}

	if ($data['action'] == 'removesubscription') {
		remove_subscribed_thread($thread['tid']);

		$returnArray = array(
			"success" => $lang->subscribe_subscription_removed,
			"linkText" => $lang->subscribe_thread,
		);
	} else {
		$forumpermissions = forum_permissions($thread['fid']);
		if($forumpermissions['canview'] == 0 || $forumpermissions['canviewthreads'] == 0 || (isset($forumpermissions['canonlyviewownthreads']) && $forumpermissions['canonlyviewownthreads'] != 0 && $thread['uid'] != $mybb->user['uid']))
		{
			error_no_permission();
		}

		add_subscribed_thread($thread['tid'], $data['notification']);

		$returnArray = array(
			"success" => $lang->subscribe_subscription_added,
			"linkText" => $lang->unsubscribe_thread,
		);
	}

	// send headers
	header("Content-type: application/json; charset={$lang->settings['charset']}");
	echo json_encode($returnArray);
	exit;
}

?>
