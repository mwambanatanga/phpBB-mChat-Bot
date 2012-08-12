<?php

/**
*
* @author Pico
* @package mChat Bot
* @version $Id install_mchat_bot.php
* @copyright (c) 2011 Pico  ( http://www.modsteam.tk )
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
define('UMIL_AUTO', true);
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
$user->session_begin();
$auth->acl($user->data);
$user->setup();


if (!file_exists($phpbb_root_path . 'umil/umil_auto.' . $phpEx))
{
	trigger_error('Please download the latest UMIL (Unified MOD Install Library) from: <a href="http://www.phpbb.com/mods/umil/">phpBB.com/mods/umil</a>', E_USER_ERROR);
}

/*
* The language file which will be included when installing
* Language entries that should exist in the language file for UMIL (replace $mod_name with the mod's name you set to $mod_name above)
* $mod_name
* 'INSTALL_' . $mod_name
* 'INSTALL_' . $mod_name . '_CONFIRM'
* 'UPDATE_' . $mod_name
* 'UPDATE_' . $mod_name . '_CONFIRM'
* 'UNINSTALL_' . $mod_name
* 'UNINSTALL_' . $mod_name . '_CONFIRM'
*/
$language_file = 'mods/info_acp_mchat_bot';

// The name of the mod to be displayed during installation.
$mod_name = 'ACP_MCHAT_BOT_TITLE';

/*
* The name of the config variable which will hold the currently installed version
* You do not need to set this yourself, UMIL will handle setting and updating the version itself.
*/
$version_config_name = 'mchat_bot_version';

/*
* The array of versions and actions within each.
* You do not need to order it a specific way (it will be sorted automatically), however, you must enter every version, even if no actions are done for it.
*
* You must use correct version numbering.  Unless you know exactly what you can use, only use X.X.X (replacing X with an integer).
* The version numbering must otherwise be compatible with the version_compare function - http://php.net/manual/en/function.version-compare.php
*/
$versions = array(
	'0.0.1'	=> array(
		'table_add' => array(
			array('phpbb_mchat_bot', array(
					'COLUMNS'		=> array(
						'id'			=> array('USINT', NULL, 'auto_increment'),
						'user_text'		=> array('MTEXT_UNI', ''),
						'bot_text'		=> array('MTEXT_UNI', ''),
					),
					'PRIMARY_KEY'	=> 'id',
				),
			),
			array('phpbb_mchat_bot_randtxt', array(
					'COLUMNS' => array(
						'rand_id' => array('USINT', NULL, 'auto_increment'),
						'cat_id' => array('TINT:3', NULL),
						'rand_text' => array('MTEXT_UNI', ''),
					),
					'PRIMARY_KEY'	=> 'rand_id',
				),
			),		
			array('phpbb_mchat_bot_randtxt_cat', array(
					'COLUMNS'		=> array(
						'cat_id'			=> array('TINT:3', NULL, 'auto_increment'),
						'cat_desc'			=> array('VCHAR:255', ''),
					),
					'PRIMARY_KEY'	=> 'cat_id',
				),
			),			
		),		

		'table_row_insert'	=> array(
			array('phpbb_mchat_config', array(			
				array(			
					'config_name' 	=> 'bot_enable',
					'config_value'	=> '0',
				),
				array(			
					'config_name' 	=> 'bot_id',
					'config_value'	=> '',
				),
			)),			
		),		

		'module_add' => array(
			array('acp', 'ACP_CAT_MCHAT', array(
					'module_basename'	=> 'mchat_bot',
					'module_langname'	=> 'ACP_MCHAT_BOT_CONFIG',
					'module_mode'		=> 'settings',
					'module_auth'		=> 'acl_a_mchat',
				),
			),
			array('acp', 'ACP_CAT_MCHAT', array(
					'module_basename'	=> 'mchat_bot',
					'module_langname'	=> 'ACP_MCHAT_BOT_TEXT',
					'module_mode'		=> 'text',
					'module_auth'		=> 'acl_a_mchat',
				),
			),
			array('acp', 'ACP_CAT_MCHAT', array(
					'module_basename'	=> 'mchat_bot',
					'module_langname'	=> 'ACP_MCHAT_BOT_RANDTXT',
					'module_mode'		=> 'random_text',
					'module_auth'		=> 'acl_a_mchat',
				),
			),
		),		
	),

	'0.0.2'	=> array(),
	
	'0.1.0' => array(
		'table_row_insert'	=> array(
			array('phpbb_mchat_config', array(			
				array(			
					'config_name' 	=> 'bot_warning',
					'config_value'	=> '0',
				),
				array(			
					'config_name' 	=> 'bot_warns',
					'config_value'	=> '1',
				),
			)),			
		),
		'table_column_add'	=> array(
			array('phpbb_users', 'user_mchat_warnings', array('BOOL', 0)),
		),
	),
	
	'1.0.0' => array(
		'config_add' => array(
			array('mchat_bot_welcome', '0', 0),
		),
		'table_row_insert'	=> array(
			array('phpbb_mchat_config', array(			
				array(			
					'config_name' 	=> 'bot_welcome_id',
					'config_value'	=> '0',
				),
			)),			
		),
		'table_column_add'	=> array(
			array('phpbb_mchat_bot', 'pos', array('USINT', 0)),
		),
		
		'custom'	=> 'copy_id_to_pos',
		
		'custom'	=> 'create_bot',
	),
	
	'1.0.1'	=> array(),
);

// Include the UMIF Auto file and everything else will be handled automatically.
include($phpbb_root_path . 'umil/umil_auto.' . $phpEx);

$cache->purge();

function copy_id_to_pos($action, $version)
{
	global $db, $table_prefix, $phpbb_root_path, $phpEx;

	$sql = 'SELECT id FROM ' . $table_prefix . 'mchat_bot';
	$result = $db->sql_query($sql);
	
	while ($row = $db->sql_fetchrow($result))
	{
		$sql = 'UPDATE ' . $table_prefix . 'mchat_bot
			SET pos = ' . (int) $row['id'] . "
			WHERE id = '" . $db->sql_escape($row['id']) . "'";
		$db->sql_query($sql);
	}
	$db->sql_freeresult($result);
	return;
}

function create_bot($action, $version)
{
	global $db, $phpbb_root_path, $phpEx;
	global $cache, $config, $user;
	
	$sql = 'SELECT * FROM ' . MCHAT_CONFIG_TABLE;
	$result = $db->sql_query($sql);
	$mchat_config = array();
	while ($row = $db->sql_fetchrow($result))	
	{
		$mchat_config[$row['config_name']] = $row['config_value'];
	}
	$db->sql_freeresult($result);
	
	if (!$mchat_config['bot_id'])
	{
		include_once($phpbb_root_path . 'includes/functions_user.' . $phpEx);

		$sql = 'SELECT group_id, group_colour
			FROM ' . GROUPS_TABLE . "
			WHERE group_name = 'BOTS'
				AND group_type = " . GROUP_SPECIAL;
		$result = $db->sql_query($sql);
		$group_row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		$mchat_bot_name = $user->lang['MCHAT_BOT_TITLE'];
		$mchat_bot_id = user_add(array(
			'user_type'				=> (int) USER_IGNORE,
			'group_id'				=> (int) $group_row['group_id'],
			'username'				=> (string) $mchat_bot_name,
			'user_regdate'			=> time(),
			'user_password'			=> '',
			'user_email'			=> '',
			'user_lang'				=> (string) $config['default_lang'],
			'user_style'			=> (int) $config['default_style'],
			'user_allow_massemail'	=> 0,
		));
								
		$sql = 'UPDATE ' . MCHAT_CONFIG_TABLE . "
			SET config_value = '" . $db->sql_escape($mchat_bot_id) . "'
			WHERE config_name = '" . $db->sql_escape('bot_id') . "'";
			$db->sql_query($sql);

		$cache->destroy('_mchat_config');
				
		if (!function_exists('mchat_cache'))
		{
			include($phpbb_root_path . 'includes/functions_mchat.' . $phpEx);
		}
		mchat_cache();
	
		return $user->lang['MCHAT_BOT_CREATED'];
	}
}

?>