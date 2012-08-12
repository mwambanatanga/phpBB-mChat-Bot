<?php

/**
*
* @package - mChat Bot
* @version $Id: info_acp_mchat_bot.php, v 1.0.0 26/08/2011 Pico Exp $
* @version $Id: info_acp_mchat.php
* @copyright (c) 2011 Pico ( http://www.modsteam.tk )
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
if (!defined('IN_PHPBB'))
{
	exit;
}
if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters for use
// ’ » “ ” …


$lang = array_merge($lang, array(
	'ACP_MCHAT_BOT_TITLE'			=> 'mChat Bot',

	//General
	'ACP_MCHAT_BOT_CONFIG'			=> 'Bot settings',
	'ACP_MCHAT_BOT_TEXT'			=> 'Text',
	'ACP_MCHAT_BOT_RANDTXT'			=> 'Random text',
	
	//Bot settings
	'MCHAT_BOT_TITLE'				=> 'mChat Bot',
	'MCHAT_BOT'						=> 'Bot settings',
	'MCHAT_BOT_ENABLE'				=> 'Enable mChat Bot',
	'MCHAT_BOT_ENABLE_EXPLAIN'		=> 'Enable or disable the bot.',
	'MCHAT_BOT_WARNING'				=> 'Enable warnings',
	'MCHAT_BOT_WARNING_EXPLAIN'		=> 'mChat Bot can warn users who are swearing in chat. Words are taken from "Word censoring".',
	'MCHAT_BOT_WARNS'				=> 'Allowed swears',
	'MCHAT_BOT_WARNS_EXPLAIN'		=> 'After excessing this value user will be warned.',
	'MCHAT_BOT_WELCOME'				=> 'Enable welcome message',
	'MCHAT_BOT_WELCOME_EXPLAIN'		=> 'mChat Bot will reply if user login on the board.',
	'MCHAT_BOT_WELCOME_CAT'			=> 'Welcome message category',
	'MCHAT_BOT_WELCOME_CAT_EXPLAIN'	=> 'Choose a category for welcome messages.<br />Note: The category must be created firstly.',	
	'MCHAT_BOT_NAME'				=> 'Bot name',
	'MCHAT_BOT_NAME_EXPLAIN'		=> 'Here you can edit bot name and its colour.',
	'MCHAT_BOT_COLOUR'				=> 'Bot colour',
	'MCHAT_BOT_AVATAR'				=> 'Bot avatar',
	'MCHAT_BOT_CREATED'				=> 'Bot created successfully.',
	'MCHAT_BOT_UPDATED'				=> 'Bot updated successfully.',
	
	//Errors
	'USERNAME_TAKEN'				=> 'The username for bot you entered is already in use, please select an alternative.',
	'NO_MCHAT_BOT'					=> 'The bot doesn\'t exist.',
	'NO_BOT_TEXT'					=> 'Any texts don\'t exist.',
	'NO_BOT_DATA'					=> 'mChat Bot text doesn\'t exist.', 
	'NO_CATEGORY'					=> 'Category doesn\'t exist.',
	'NO_CAT'						=> 'No categories.',
	'NO_BOT_GROUP'					=> 'Unable to find special bot group.',
	
	//Text
	'MCHAT_BOT_TEXT_TITLE'			=> 'mChat Bot text',
	'MCHAT_BOT_TEXT_TITLE_EXPLAIN'	=> 'Here you can add new texts for mChat Bot, edit and delete them.',
	'USER_TEXT'						=> 'User say',
	'USER_TEXT_EXPLAIN'				=> 'User text/word for which mChat Bot will reply.',
	'BOT_TEXT'						=> 'Bot reply',
	'BOT_TEXT_EXPLAIN'				=> 'Text/word which is the reply for what user said.<br />You can use {$username} to display username in the bot reply.',
	'ADD_TEXT'						=> 'Add text',
	'SELECT_CATEGORY'				=> 'Select a category',
	'SELECT_CATEGORY_EXPLAIN'		=> 'Choose a category for random text for mChat Bot.<br />Note: The category must be created firstly.',
	'DELETE_BOT_DATA'				=> 'Delete Bot reply?',
	'BOT_DATA_ADD_SUCCESS'			=> 'Bot reply added successfully.',
	'BOT_DATA_DELETE_SUCCESS'		=> 'Bot reply deleted successfully.',
	'BOT_DATA_EDIT_SUCCESS'			=> 'Bot reply edited successfully.',
	'BOT_RANDTEXT'					=> 'Random text',
	
	//Random text
	'MCHAT_BOT_RANDTXT_TITLE'			=> 'mChat Bot random text',
	'MCHAT_BOT_RANDTXT_TITLE_EXPLAIN'	=> 'Here you can create new categories for random text and add a new random reply.',
	'ADD_CAT'							=> 'Add category',
	'ADD_RANDTXT'						=> 'Add random text',
	'CAT_DESC'							=> 'Category description',
	'CAT_DESC_EXPLAIN'					=> 'Category description which will contain random texts',
	'DELETE_CATEGORY'					=> 'Delete category?',
	'CAT_ADD_SUCCESS'					=> 'Category added successfully.',
	'CAT_DELETE_SUCCESS'				=> 'Category deleted successfully.',
	'CAT_EDIT_SUCCESS'					=> 'Category edited successfully.',
	'DELETE_RANDTXT'					=> 'Delete random text?',
	'RANDTXT_ADD_SUCCESS'				=> 'Random text added successfully.',
	'RANDTXT_DELETE_SUCCESS'			=> 'Random text deleted successfully.',
	'RANDTXT_EDIT_SUCCESS'				=> 'Random text edited successfully.',
	
	//Warning
	'BOT_WARN'						=> '{$username}, [b]we do not tolerate swearing[/b] :!:',
	'BOT_GIVE_WARN'					=> '{$username}, [color=red][b]you get a warning for swearing[/b][/color] :!:',
	'BOT_WARN_INFO'					=> 'User received the warning for swearing on the chat: %s',
));

?>