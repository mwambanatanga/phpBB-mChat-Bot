<?php
/**
*
* @author Pico
* @package - mChat Bot
* @version $Id acp_mchat_bot.php
* @copyright Pico ( http://www.modsteam.ssl2.pl/ )
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
if (!defined('IN_PHPBB'))
{
	exit;
}
/**
* @package module_install
*/
class acp_mchat_bot_info
{
	function module()
	{
		return array(
			'filename'	=> 'acp_mchat_bot',
			'title'		=> 'ACP_CAT_MCHAT_BOT',
			'version'	=> '1.0.0',
			'modes'		=> array(
				'settings'		=> array('title' => 'ACP_MCHAT_BOT_CONFIG', 'auth' => 'acl_a_mchat', 'cat' => array('ACP_CAT_DOT_MODS')),
				'text'		=> array('title' => 'ACP_MCHAT_BOT_TEXT', 'auth' => 'acl_a_mchat', 'cat' => array('ACP_CAT_DOT_MODS')),
				'random_text'	=> array('title' => 'ACP_MCHAT_BOT_RANDTXT', 'auth' => 'acl_a_mchat', 'cat' => array('ACP_CAT_DOT_MODS')),
			),
		);
	}

	function install()
	{
	}

	function uninstall()
	{
	}
}

?>