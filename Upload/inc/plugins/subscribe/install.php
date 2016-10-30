<?php
/*
 * Plugin Name: AJAX Subscribe for MyBB 1.8.x
 * Copyright 2014 WildcardSearch
 * http://www.rantcentralforums.com
 *
 * This file contains the install functions for acp.php
 */

// disallow direct access to this file for security reasons
if(!defined('IN_MYBB'))
{
	die('Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.');
}

/*
 * information about the plugin used by MyBB for display as well as to connect with updates
 *
 * @return array the plugin info
 */
function subscribe_info()
{
	global $mybb, $lang;

	if(!$lang->subscribe)
	{
		$lang->load('subscribe');
	}

	$button_pic = $mybb->settings['bburl'] . '/inc/plugins/subscribe/images/donate.gif';
	$border_pic = $mybb->settings['bburl'] . '/inc/plugins/subscribe/images/pixel.gif';
	$subscribe_description = <<<EOF
<table width="100%">
	<tbody>
		<tr>
			<td>
				{$lang->subscribe_description1}<br/><br/>{$lang->subscribe_description2}{$extra_links}
			</td>
			<td style="text-align: center;">
				<img src="{$mybb->settings['bburl']}/inc/plugins/subscribe/images/subscribe_logo_80.png" alt="{$lang->subscribe_logo}" title="{$lang->subscribe_logo}"/><br /><br />
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
					<input type="hidden" name="cmd" value="_s-xclick">
					<input type="hidden" name="hosted_button_id" value="VA5RFLBUC4XM4">
					<input type="image" src="{$button_pic}" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
					<img alt="" border="0" src="{$border_pic}" width="1" height="1">
				</form>
			</td>
		</tr>
	</tbody>
</table>
EOF;

	$name = <<<EOF
<span style="font-familiy: arial; font-size: 1.5em; color: #686859; text-shadow: 2px 2px 2px #111111;">{$lang->subscribe}</span>
EOF;
	$author = <<<EOF
</a></small></i><a href="http://www.rantcentralforums.com" title="Rant Central"><span style="font-family: Courier New; font-weight: bold; font-size: 1.2em; color: #0e7109;">Wildcard</span></a><i><small><a>
EOF;

	// This array returns information about the plugin, some of which was prefabricated above based on whether the plugin has been installed or not.
	return array(
		"name" => $name,
		"description" => $subscribe_description,
		"website" => '',
		"author" => $author,
		"authorsite" => 'http://www.rantcentralforums.com',
		"version" => '0.0.1',
		"compatibility" => '18*',
		"guid" => '',
	);
}

/*
 * add marker to the header include template
 *
 * @return void
 */
function subscribe_activate()
{
	require_once MYBB_ROOT . '/inc/adminfunctions_templates.php';
	find_replace_templatesets('headerinclude', "#" . preg_quote('{$stylesheets}') . "#i", '{$subscribe_js}{$stylesheets}');
}

/*
 * restore the template
 *
 * @return void
 */
function subscribe_deactivate()
{
	require_once MYBB_ROOT . '/inc/adminfunctions_templates.php';
	find_replace_templatesets('headerinclude', "#" . preg_quote('{$subscribe_js}') . "#i", '');
}

?>
