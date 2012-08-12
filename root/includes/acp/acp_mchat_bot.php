<?php

/**
*
* @author Pico
* @package - mChat Bot
* @version $Id: acp_mchat_bot.php, v 1.0.1 09/12/2011 Pico Exp $
* @copyright (c) 2011 Pico ( http://www.modsteam.tk )
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

/**
* @package acp
*/
class acp_mchat_bot
{
	var $u_action;

	function main($id, $mode)
	{
		global $cache, $config, $db, $user, $auth, $template;
		global $phpbb_root_path, $phpEx, $phpbb_admin_path;

		add_form_key('acp_mchat_bot');
		$submit = (isset($_POST['submit'])) ? true : false;
		$action = request_var('action', '');
		$id = request_var('id', 0);
		$cat_id = request_var('cat_id', 0);
		$mchat_bot_id = request_var('mchat_bot_id', '');
		
		$error = array();
		
		$this->tpl_name = 'acp_mchat_bot';
		
		switch ($mode)
		{
			case 'settings':
				$this->page_title = $user->lang['MCHAT_BOT_TITLE'];
				
				$sql = 'SELECT * FROM ' . MCHAT_CONFIG_TABLE;
				$result = $db->sql_query($sql);
				$mchat_config = array();
				while ($row = $db->sql_fetchrow($result))	
				{
					$mchat_config[$row['config_name']] = $row['config_value'];
				}
				$db->sql_freeresult($result);
				
				$sql = 'SELECT user_id, username, user_colour
					FROM ' . USERS_TABLE . '
					WHERE user_id = '. $mchat_config['bot_id'];
				$result = $db->sql_query($sql);
				$user_row = $db->sql_fetchrow($result);

				if ($user_row['user_id'] != $mchat_config['bot_id'])
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
	
					trigger_error($user->lang['MCHAT_BOT_CREATED'] . adm_back_link($this->u_action));
				}
				
				$mchat_bot_row = array(
					'mchat_bot_name'	=> utf8_normalize_nfc(request_var('mchat_bot_name', '', true)),
					'mchat_bot_colour'	=> request_var('mchat_bot_colour', ''),
				);
						
				switch ($action)
				{
					case 'edit_bot' :
						include_once($phpbb_root_path . 'includes/functions_user.' . $phpEx);
						
						if (!$mchat_bot_id)
						{
							trigger_error('NO_MCHAT_BOT');
						}
						$sql = 'SELECT username, user_colour FROM ' . USERS_TABLE . ' WHERE user_id = ' . $mchat_config['bot_id'];
						$result = $db->sql_query($sql);
						$mchat_bot_data = $db->sql_fetchrow($result);
						
						if (!$mchat_bot_data)
						{
                            trigger_error('NO_MCHAT_BOT');
						}
						
						if (!$submit)
						{	
							$mchat_bot_row['mchat_bot_name'] = $mchat_bot_data['username'];
							$mchat_bot_row['mchat_bot_colour'] = $mchat_bot_data['user_colour'];
						}
						
						if ($submit)
						{
							$check_ary = array(
								'mchat_bot_name'			=> array(
									array('string', false, $config['min_name_chars'], $config['max_name_chars']),
									array('username', $user_row['username'])
								),
							);
							
							$error = validate_data($mchat_bot_row, $check_ary);

							if (!check_form_key('acp_mchat_bot'))
							{
								$error[] = 'FORM_INVALID';
							}
							
							// Replace "error" strings with their real, localised form
							$error = preg_replace('#^([A-Z_]+)$#e', "(!empty(\$user->lang['\\1'])) ? \$user->lang['\\1'] : '\\1'", $error);
							
							if (!sizeof($error))
							{						
								$mchat_bot_colour = !empty($mchat_bot_row['mchat_bot_colour']) ? $mchat_bot_row['mchat_bot_colour'] : $group_row['group_colour'];
								
								$sql_ary = array(
									'username'			=> (string) $mchat_bot_row['mchat_bot_name'],
									'username_clean'	=> (string) utf8_clean_string($mchat_bot_row['mchat_bot_name']),
									'user_colour'		=> (string) $mchat_bot_colour,
								);

								$sql = 'UPDATE ' . USERS_TABLE . ' SET ' . $db->sql_build_array('UPDATE', $sql_ary) . " WHERE user_id = '" . $mchat_config['bot_id'] . "'";
								$db->sql_query($sql);
								
								trigger_error($user->lang['MCHAT_BOT_UPDATED'] . adm_back_link($this->u_action));
							}
						}
						
						$template->assign_vars(array(
							'MCHAT_BOT_ERROR'		=> isset($error) ? ((sizeof($error)) ? implode('<br />', $error) : '') : '',
							'U_BOT_ACTION'			=> $this->u_action . '&amp;action=edit_bot&amp;mchat_bot_id=' . $mchat_bot_id,
							'U_SWATCH'				=> append_sid("{$phpbb_admin_path}swatch.$phpEx", 'form=acp_mchat_bot&amp;name=mchat_bot_colour'),
							'U_AVATAR'				=> $this->u_action . '&amp;i=users&amp;mode=avatar&amp;u=' . $mchat_bot_id,
					
							'MCHAT_BOT_NAME'		=> $mchat_bot_row['mchat_bot_name'],
							'MCHAT_BOT_COLOUR'		=> $mchat_bot_row['mchat_bot_colour'],
					
							'S_EDIT_MCHAT_BOT'		=> true,
						));
						return;						
					break;

					default :
						$mchat_row = array(
							'bot_enable'		=> request_var('mchat_bot_enable', 0),
							'bot_warning'		=> request_var('mchat_bot_warning', 0),
							'bot_warns'			=> request_var('mchat_bot_warns', ''),
							'bot_welcome_id'	=> request_var('cat_id', 0),
						);		

						$sql = 'SELECT username, user_colour
								FROM ' . USERS_TABLE . '
								WHERE user_id = '. $mchat_config['bot_id'];
						$result = $db->sql_query($sql);
						$user_row = $db->sql_fetchrow($result);
							
						if ($submit)
						{
							if (!function_exists('validate_data'))
							{
								include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
							}
							
							// validate the entries...most of them anyway
							$mchat_array = array(
								'bot_warns'	=> array('num', false, 1, 100),
							);	
							
							$error = validate_data($mchat_row, $mchat_array);

							if (!check_form_key('acp_mchat_bot'))
							{
								$error[] = 'FORM_INVALID';
							}
							
							// Replace "error" strings with their real, localised form
							$error = preg_replace('#^([A-Z_]+)$#e', "(!empty(\$user->lang['\\1'])) ? \$user->lang['\\1'] : '\\1'", $error);
			
							if (!sizeof($error))
							{
								foreach ($mchat_row as $config_name => $config_value)
								{
									$sql = 'UPDATE ' . MCHAT_CONFIG_TABLE . "
										SET config_value = '" . $db->sql_escape($config_value) . "'
										WHERE config_name = '" . $db->sql_escape($config_name) . "'";
									$db->sql_query($sql);
								}
				
								set_config('mchat_bot_welcome', request_var('mchat_bot_welcome', 0));
								
								$cache->destroy('_mchat_config');
				
								if (!function_exists('mchat_cache'))
								{
									include($phpbb_root_path . 'includes/functions_mchat.' . $phpEx);
								}
								mchat_cache();
							
								trigger_error($user->lang['MCHAT_CONFIG_SAVED'] . adm_back_link($this->u_action));
							}
						}
		
						$mchat_bot_welcome = isset($config['mchat_bot_welcome']) ? $config['mchat_bot_welcome'] : 0;
						
						$template->assign_vars(array(
							'MCHAT_BOT_ERROR'		=> isset($error) ? ((sizeof($error)) ? implode('<br />', $error) : '') : '',
							'MCHAT_BOT_ENABLE'		=> !empty($mchat_row['bot_enable']) ? $mchat_row['bot_enable'] : $mchat_config['bot_enable'],
							'MCHAT_BOT_WARNING'		=> !empty($mchat_row['bot_warning']) ? $mchat_row['bot_warning'] : $mchat_config['bot_warning'],
							'MCHAT_BOT_WARNS'		=> !empty($mchat_row['bot_warns']) ? $mchat_row['bot_warns'] : $mchat_config['bot_warns'],
							'MCHAT_BOT_WELCOME'		=> ($mchat_bot_welcome) ? true : false,
							'CAT_SELECT' 			=> generate_cat_select($mchat_config['bot_welcome_id']),
							'MCHAT_BOT'				=> get_username_string('username', $mchat_config['bot_id'], $user_row['username'], $user_row['user_colour']),
							'MCHAT_BOT_COLOUR'		=> get_username_string('colour', $mchat_config['bot_id'], $user_row['username'], $user_row['user_colour']),
							'U_EDIT_BOT'			=> append_sid($this->u_action . '&amp;action=edit_bot&amp;mchat_bot_id=' . $mchat_config['bot_id']),
							'U_ACTION'				=> $this->u_action,
							
							'S_MCHAT_BOT_CONFIG'	=> true)
						);
					break;
				}
			break;
			
			case 'text' :
				$this->page_title = $user->lang['MCHAT_BOT_TEXT_TITLE'];

				$bot_data = array(
					'user_text'		=> utf8_normalize_nfc(request_var('user_text', '', true)),
					'bot_text'		=> utf8_normalize_nfc(request_var('bot_text', '', true)),
					'pos'			=> request_var('pos', '1'),
				);
				
				switch ($action)
				{
					case 'edit' :
						if (!$id)
						{
							trigger_error('NO_BOT_DATA');
						}
						$sql = 'SELECT * FROM ' . MCHAT_BOT_TABLE . ' WHERE id = ' . $id;
						$result = $db->sql_query($sql);
						$text = $db->sql_fetchrow($result);
						
						if (!$text)
						{
                            trigger_error('NO_BOT_DATA');
						}
						
						if (!$submit)
						{
							if (strstr($text['bot_text'], 'randtxt_'))
							{
								$cat_id = intval(substr($text['bot_text'], 8));
								$text['bot_text'] = '';
							}
							
							$bot_data = $text;
						}
					case 'add' :
						$template->assign_vars(array(
							'USER_TEXT'			=> $bot_data['user_text'],
							'BOT_TEXT'			=> $bot_data['bot_text'],
							'CAT_SELECT' 		=> generate_cat_select($cat_id),
							'S_ADD'				=> ($action == 'add') ? true : false,
							'U_TEXT_ACTION'		=> $this->u_action . '&amp;action=' . $action . (($action == 'edit') ? '&amp;id=' . $id : ''),
						));

						if ($submit)
						{
							if ($cat_id)
							{
								$bot_data['bot_text'] = 'randtxt_' . $cat_id;
							}
							

							if ($action == 'add')
							{
								$sql = 'SELECT pos AS pos FROM ' . MCHAT_BOT_TABLE . '
									ORDER BY pos DESC';
								$result = $db->sql_query_limit($sql, 1);
								$pos = (int) $db->sql_fetchfield('pos');
								$db->sql_freeresult($result);
								$bot_data['pos'] = $pos + 1;
								
								$sql = 'INSERT ' . MCHAT_BOT_TABLE . ' ' . $db->sql_build_array('INSERT', $bot_data);
								$db->sql_query($sql);
								$cache->destroy('_mchat_bot_text');
								trigger_error($user->lang['BOT_DATA_ADD_SUCCESS'] . adm_back_link($this->u_action));
							}
							else
							{
								$sql = 'SELECT pos AS pos FROM ' . MCHAT_BOT_TABLE . '
									WHERE id = ' . $id;
								$result = $db->sql_query_limit($sql, 1);
								$pos = (int) $db->sql_fetchfield('pos');
								$db->sql_freeresult($result);
								$bot_data['pos'] = $pos;
								
								$sql = 'UPDATE ' . MCHAT_BOT_TABLE . ' SET ' . $db->sql_build_array('UPDATE', $bot_data) . " WHERE id = '" . $id . "'";
								$db->sql_query($sql);
								$cache->destroy('_mchat_bot_text');
								trigger_error($user->lang['BOT_DATA_EDIT_SUCCESS'] . adm_back_link($this->u_action));
							}
						}
					break;

					case 'delete' :
						if (!$id)
						{
							trigger_error('NO_BOT_DATA');
						}
						$sql = 'SELECT * FROM ' . MCHAT_BOT_TABLE . ' WHERE id = ' . $id;
						$result = $db->sql_query($sql);
						$text = $db->sql_fetchrow($result);
						if (!$text)
						{
                            trigger_error('NO_BOT_DATA');
						}

						if (confirm_box(true))
						{
							$sql = 'DELETE FROM ' . MCHAT_BOT_TABLE . " WHERE id = '" . $id . "'";
							$db->sql_query($sql);
							$cache->destroy('_mchat_bot_text');
							trigger_error($user->lang['BOT_DATA_DELETE_SUCCESS'] . adm_back_link($this->u_action));
						}
						else
						{
							confirm_box(false, $user->lang['DELETE_BOT_DATA']);
						}
					break;
					
					case 'move_up' :
					case 'move_down' :
						$sql = 'SELECT * FROM ' . MCHAT_BOT_TABLE . ' WHERE id = ' . $id;
						$result = $db->sql_query($sql);
						$row = $db->sql_fetchrow($result);
						$size = sizeof($row);
				
						move_text($action, $size, $row['pos'], $row['id']);
						
						$cache->destroy('_mchat_bot_text');
						
						redirect($this->u_action);
					break;
					
					default :
						$sql = 'SELECT * FROM ' . MCHAT_BOT_RANDTXT_CAT_TABLE . '
							ORDER BY cat_id';
						$result = $db->sql_query($sql);
						
						$randtxt_cats = array();
						
						while ($row = $db->sql_fetchrow($result))
						{
							$randtxt_cats[$row['cat_id']] = $row['cat_desc'];
						}
						$db->sql_freeresult($result);
				
						$sql = 'SELECT * FROM ' . MCHAT_BOT_TABLE . '
							ORDER BY pos ASC';
						$result = $db->sql_query($sql);
						while ($row = $db->sql_fetchrow($result))
						{
							$bot_text = $row['bot_text'];
							if( strstr($bot_text, 'randtxt_') )
							{
								$cat_id = intval(substr($bot_text, 8));
								$bot_text = !empty($randtxt_cats[$cat_id]) ? '<i>' . $user->lang['BOT_RANDTEXT'] . ': "' . $randtxt_cats[$cat_id] . '"</i>' : '<i>' . $user->lang['NO_CATEGORY'] . '</i>';
							}
							
							$template->assign_block_vars('bot_text', array(
								'USER_TEXT'		=> $row['user_text'],
								'BOT_TEXT'		=> $bot_text,
								'U_DELETE'		=> append_sid($this->u_action . '&amp;action=delete&amp;id=' . $row['id']),
								'U_EDIT'		=> append_sid($this->u_action . '&amp;action=edit&amp;id=' . $row['id']),
								'U_MOVE_DOWN'	=> $this->u_action . '&amp;action=move_down&amp;id=' . $row['id'],
								'U_MOVE_UP'		=> $this->u_action . '&amp;action=move_up&amp;id=' . $row['id'],
							));
						}
						$db->sql_freeresult($result);
						$template->assign_var('S_BOT_TEXT_LIST', true);
					break;
				}

				$template->assign_vars(array(
					'S_BOT_TEXT'		=> true,
				));
			break;
			
			case 'random_text' :
				
				$this->page_title = $user->lang['MCHAT_BOT_RANDTXT_TITLE'];

				$cat_data = utf8_normalize_nfc(request_var('cat_desc', '', true));
				$rand_data = utf8_normalize_nfc(request_var('rand_text', '', true));
				$rand_id = request_var('rand_id', 0);

				switch ($action)
				{
					case 'edit_cat' :
						if (!$cat_id)
						{
							trigger_error('NO_CATEGORY');
						}
						$sql = 'SELECT * FROM ' . MCHAT_BOT_RANDTXT_CAT_TABLE . ' WHERE cat_id = ' . $cat_id;
						$result = $db->sql_query($sql);
						$cat = $db->sql_fetchrow($result);
						
						if (!$cat)
						{
                            trigger_error('NO_CAT_DATA');
						}

						if (!$submit)
						{
							$cat_data = $cat['cat_desc'];
						}
					case 'add_cat' :
						$template->assign_vars(array(
							'CAT_DESC'			=> $cat_data,
							'S_ADD'				=> ($action == 'add_cat') ? true : false,
							'U_CAT_ACTION'		=> $this->u_action . '&amp;action=' . $action . (($action == 'edit_cat') ? '&amp;cat_id=' . $cat_id : ''),
							'S_CATEGORY_EDIT'	=> true,
						));

						if ($submit)
						{
							if ($action == 'add_cat')
							{
								$sql = 'INSERT INTO ' . MCHAT_BOT_RANDTXT_CAT_TABLE . ' ' . $db->sql_build_array('INSERT', array('cat_desc' => $cat_data));
								$db->sql_query($sql);
								trigger_error($user->lang['CAT_ADD_SUCCESS'] . adm_back_link($this->u_action));
							}
							else
							{
								$sql = 'UPDATE ' . MCHAT_BOT_RANDTXT_CAT_TABLE . ' SET ' . $db->sql_build_array('UPDATE', array('cat_desc' => $cat_data)) . " WHERE cat_id = '" . $cat_id . "'";
								$db->sql_query($sql);
								trigger_error($user->lang['CAT_EDIT_SUCCESS'] . adm_back_link($this->u_action));
							}
						}
					break;

					case 'delete_cat' :
						if (!$cat_id)
						{
							trigger_error('NO_CAT_DATA');
						}
						$sql = 'SELECT * FROM ' . MCHAT_BOT_RANDTXT_CAT_TABLE . ' WHERE cat_id = ' . $cat_id;
						$result = $db->sql_query($sql);
						$category = $db->sql_fetchrow($result);
						
						if (!$category)
						{
                            trigger_error('NO_CAT_DATA');
						}

						if (confirm_box(true))
						{
							$sql = 'DELETE FROM ' . MCHAT_BOT_RANDTXT_CAT_TABLE . ' WHERE cat_id = ' . $cat_id;
							$db->sql_query($sql);
							$sql = 'DELETE FROM ' . MCHAT_BOT_RANDTXT_TABLE . ' WHERE cat_id = ' . $cat_id;
							$db->sql_query($sql);
							$cache->destroy('_mchat_bot_randtxt');
							trigger_error($user->lang['CAT_DELETE_SUCCESS'] . adm_back_link($this->u_action));
						}
						else
						{
							confirm_box(false, $user->lang['DELETE_CATEGORY']);
						}
					break;
					
					case 'edit_randtxt' :
						if (!$rand_id)
						{
							trigger_error('NO_BOT_DATA');
						}
						$sql = 'SELECT * FROM ' . MCHAT_BOT_RANDTXT_TABLE . ' WHERE rand_id = ' . $rand_id;
						$result = $db->sql_query($sql);
						$rand = $db->sql_fetchrow($result);
						
						if (!$rand)
						{
                            trigger_error('NO_BOT_DATA');
						}

						if (!$submit)
						{

							$cat_id = $rand['cat_id'];
							$rand_data = $rand['rand_text'];
						}
					case 'add_randtxt' :
						$template->assign_vars(array(
							'RAND_TEXT'			=> $rand_data,
							'S_ADD'				=> ($action == 'add_randtxt') ? true : false,
							'CAT_SELECT' 		=> generate_cat_select($cat_id),
							'U_RANDTXT_ACTION'	=> $this->u_action . '&amp;action=' . $action . (($action == 'edit_randtxt') ? '&amp;rand_id=' . $rand_id : ''),
							'S_RANDTXT_EDIT'	=> true,
						));

						if ($submit)
						{
							if ($action == 'add_randtxt')
							{
								$sql = 'INSERT INTO ' . MCHAT_BOT_RANDTXT_TABLE . ' ' . $db->sql_build_array('INSERT', array('cat_id' => $cat_id, 'rand_text' => $rand_data));
								$db->sql_query($sql);
								$cache->destroy('_mchat_bot_randtxt');
								trigger_error($user->lang['RANDTXT_ADD_SUCCESS'] . adm_back_link($this->u_action));
							}
							else
							{
								$sql = 'UPDATE ' . MCHAT_BOT_RANDTXT_TABLE . ' SET ' . $db->sql_build_array('UPDATE', array('cat_id' => $cat_id, 'rand_text' => $rand_data)) . " WHERE rand_id = '" . $rand_id . "'";
								$db->sql_query($sql);
								$cache->destroy('_mchat_bot_randtxt');
								trigger_error($user->lang['RANDTXT_EDIT_SUCCESS'] . adm_back_link($this->u_action));
							}
						}
					break;

					case 'delete_randtxt' :
						if (!$rand_id)
						{
							trigger_error('NO_BOT_DATA');
						}
						$sql = 'SELECT * FROM ' . MCHAT_BOT_RANDTXT_TABLE . ' WHERE rand_id = ' . $rand_id;
						$result = $db->sql_query($sql);
						$category = $db->sql_fetchrow($result);
						
						if (!$rand_id)
						{
                            trigger_error('NO_BOT_DATA');
						}

						if (confirm_box(true))
						{
							$sql = 'DELETE FROM ' . MCHAT_BOT_RANDTXT_TABLE . ' WHERE rand_id = ' . $rand_id;
							$db->sql_query($sql);
							$cache->destroy('_mchat_bot_randtxt');
							trigger_error($user->lang['RANDTXT_DELETE_SUCCESS'] . adm_back_link($this->u_action));
						}
						else
						{
							confirm_box(false, $user->lang['DELETE_RANDTXT']);
						}
					break;

					default :
						$sql = 'SELECT * FROM ' . MCHAT_BOT_RANDTXT_TABLE  . '
							ORDER BY cat_id, rand_id';
						$result = $db->sql_query($sql);
		
						$randtxts = array();
		
						while ($row = $db->sql_fetchrow($result))
						{
							$randtxts[$row['cat_id']][] = array(
								'rand_id' => $row['rand_id'],
								'rand_text' => $row['rand_text']
							);
						}
						$db->sql_freeresult($result);
	
						$sql = 'SELECT * FROM ' . MCHAT_BOT_RANDTXT_CAT_TABLE . '
							ORDER BY cat_id';
						$result = $db->sql_query($sql);
						
						while ($row = $db->sql_fetchrow($result))
						{
							$cat_id = $row['cat_id'];
							$num_cat_texts = !empty($randtxts[$cat_id]) ? sizeof($randtxts[$cat_id]) : 0;
							
							$template->assign_block_vars('randtxt_cat', array(
								'CAT_ID' 		=> $cat_id,
								'CAT_DESC' 		=> $row['cat_desc'],
								'NUM_CAT_TEXTS' => $num_cat_texts,

								'U_CAT_EDIT' 	=> append_sid($this->u_action . '&amp;action=edit_cat&amp;cat_id=' . $cat_id),
								'U_CAT_DELETE'	=> append_sid($this->u_action . '&amp;action=delete_cat&amp;cat_id=' . $cat_id),
								'U_RANDTXT_ADD' => append_sid($this->u_action . '&amp;action=add_randtxt&amp;cat_id=' . $cat_id),
							));

							for ($i = 0; $i < $num_cat_texts; $i++)
							{
								$rand_id = $randtxts[$cat_id][$i]['rand_id'];
								$rand_text = $randtxts[$cat_id][$i]['rand_text'];
				
								$template->assign_block_vars('randtxt_cat.randtxt_word', array(
									'RAND_TEXT' => $rand_text,
					
									'U_RANDTXT_EDIT' => append_sid($this->u_action . '&amp;action=edit_randtxt&amp;rand_id=' . $rand_id),
									'U_RANDTXT_DELETE' => append_sid($this->u_action . '&amp;action=delete_randtxt&amp;rand_id=' . $rand_id),
								));
							}
						}
						
						$template->assign_var('S_RANDTXT_LIST', true);
					break;
				}

				$template->assign_vars(array(
					'S_RANDTXT'		=> true,
				));
			
			break;
		}
	}
}

function generate_cat_select($cat_selected = 0)
{
	global $db, $user;
	
	$sql = 'SELECT * FROM ' . MCHAT_BOT_RANDTXT_CAT_TABLE . '
		ORDER BY cat_id';
	$result = $db->sql_query($sql);
	
	$cat_select = '<select name="cat_id"><option value="0">' . $user->lang['SELECT_CATEGORY'] . '</option>';
	
	while( $row = $db->sql_fetchrow($result) )
	{
		if( $cat_selected != 0 && $cat_selected == $row['cat_id'] )
		{
			$selected = ' selected="selected"';
		}
		else
		{
			$selected = '';
		}
		
		$cat_select .= '<option value="' . $row['cat_id'] . '"' . $selected . '>' . $row['cat_desc'] . '</option>';
	}
	
	$cat_select .= '</select>';
	
	$db->sql_freeresult($result);
	
	return $cat_select;
}

function move_text($mode = 'move_up', $size, $pos, $id)
{
	global $db;
	
	if(($pos == 1 && $mode == 'move_up') || ($pos == $size && $mode == 'move_down'))
	{
		return;
	}
	
	if($mode == 'move_down')
	{
		$new_pos = $pos + 1;
	}
	elseif ($mode == 'move_up')
	{
		$new_pos = $pos - 1;
	}
	
	$sql = 'SELECT id as id FROM ' . MCHAT_BOT_TABLE . ' 
		WHERE pos = ' . (int) $new_pos;
	$result = $db->sql_query($sql);
	$id_pos = $db->sql_fetchfield('id');
	$db->sql_freeresult($result);
	
	$sql = 'UPDATE ' . MCHAT_BOT_TABLE . '
			SET pos = ' . (int) $pos . "
			WHERE id = '" . $db->sql_escape($id_pos) . "'";
	$result = $db->sql_query($sql);
	$db->sql_freeresult($result);
	$sql = 'UPDATE ' . MCHAT_BOT_TABLE . '
			SET pos = ' . (int) $new_pos . "
			WHERE id = '" . $db->sql_escape($id) . "'";
	$result = $db->sql_query($sql);
	$db->sql_freeresult($result);
}

?>