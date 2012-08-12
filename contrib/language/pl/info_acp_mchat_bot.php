<?php

/**
*
* @package - mChat Bot
* @version $Id: info_acp_mchat_bot.php, v 1.0.0 10/08/2011 Pico Exp $
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
	'ACP_MCHAT_BOT_CONFIG'			=> 'Ustawienia bota',
	'ACP_MCHAT_BOT_TEXT'			=> 'Tekst',
	'ACP_MCHAT_BOT_RANDTXT'			=> 'Losowy tekst',
	
	//Bot settings
	'MCHAT_BOT_TITLE'				=> 'mChat Bot',
	'MCHAT_BOT'						=> 'Ustawienia bota',
	'MCHAT_BOT_ENABLE'				=> 'Włącz mChat Bota',
	'MCHAT_BOT_ENABLE_EXPLAIN'		=> 'Włącz lub wyłącz bota.',
	'MCHAT_BOT_WARNING'				=> 'Włącz ostrzeżenia',
	'MCHAT_BOT_WARNING_EXPLAIN'		=> 'mChat Bot może przyznawać ostrzeżenia za przeklinanie. Słowa są pobierane z "Cenzury słów".',
	'MCHAT_BOT_WARNS'				=> 'Dozwolone przekleństwa',
	'MCHAT_BOT_WARNS_EXPLAIN'		=> 'Po osiągnięciu tej wartości użytkownik dostanie ostrzeżenie.',
	'MCHAT_BOT_WELCOME'				=> 'Włącz powitania',
	'MCHAT_BOT_WELCOME_EXPLAIN'		=> 'mChat Bot będzie witał użytkowników, którzy zalogują się na forum.',
	'MCHAT_BOT_WELCOME_CAT'			=> 'Kategoria dla powitań',
	'MCHAT_BOT_WELCOME_CAT_EXPLAIN'	=> 'Wybierz kategorię dla powitań.<br />Uwaga: kategoria musi zostać utworzona najpierw.',	
	'MCHAT_BOT_NAME'				=> 'Nazwa bota',
	'MCHAT_BOT_NAME_EXPLAIN'		=> 'Tutaj możesz zmienić nazwę bota i jego kolor.',
	'MCHAT_BOT_COLOUR'				=> 'Kolor bota',
	'MCHAT_BOT_AVATAR'				=> 'Awatar',
	'MCHAT_BOT_CREATED'				=> 'Bot został utworzony pomyślnie.',
	'MCHAT_BOT_UPDATED'				=> 'Bot został zaktualizowany pomyślnie.',
	
	//Errors
	'USERNAME_TAKEN'				=> 'Podana nazwa użytkownika dla mChat Bota jest już używana. Wybierz inną.',
	'NO_MCHAT_BOT'					=> 'mChat Bot nie istnieje.',
	'NO_BOT_TEXT'					=> 'Żaden tekst nie istniej.',
	'NO_BOT_DATA'					=> 'Tekst mChat Bot nie istnieje.', 
	'NO_CATEGORY'					=> 'Kategoria nie istnieje.',
	'NO_CAT'						=> 'Brak kategorii.',
	'NO_BOT_GROUP'					=> 'Nie można znaleźć grupy bota.',
	
	//Text
	'MCHAT_BOT_TEXT_TITLE'			=> 'Tekst mChat Bota',
	'MCHAT_BOT_TEXT_TITLE_EXPLAIN'	=> 'Tutaj możesz dodać nowe teksty dla bota, edytować je oraz usuwać.',
	'USER_TEXT'						=> 'Wypowiedź użytkownika',
	'USER_TEXT_EXPLAIN'				=> 'Tekst/słowo użytkownika na które mChat Bot udzieli odpowiedz.',
	'BOT_TEXT'						=> 'Odpowiedź bota',
	'BOT_TEXT_EXPLAIN'				=> 'Tekst/słowo, które jest odpowiedzią na to co napisał użytkownik.<br />Możesz użyć {$username} w odpowiedzi, aby wyświetlić nazwę użytkownika.',
	'ADD_TEXT'						=> 'Dodaj tekst',
	'SELECT_CATEGORY'				=> 'Wybierz kategorię',
	'SELECT_CATEGORY_EXPLAIN'		=> 'Wybierz kategorię dla losowych tekstów mChat Bota.<br />Wskazówka: utwórz kategorię a potem dodaj tekst.',
	'DELETE_BOT_DATA'				=> 'Usunąć odpowiedź mChat Bota?',
	'BOT_DATA_ADD_SUCCESS'			=> 'Odpowiedź bota została dodana pomyślnie.',
	'BOT_DATA_DELETE_SUCCESS'		=> 'Odpowiedź bota została usunięta pomyślnie.',
	'BOT_DATA_EDIT_SUCCESS'			=> 'Odpowiedź bota została zaktualizowana pomyślnie.',
	'BOT_RANDTEXT'					=> 'Losowy tekst',
	
	//Random text
	'MCHAT_BOT_RANDTXT_TITLE'			=> 'Losowy tekst mChat Bota',
	'MCHAT_BOT_RANDTXT_TITLE_EXPLAIN'	=> 'Tutaj możesz stworzyć nowe kategorie dla losowych tekstów oraz dodawać nowe losowe teksty.',
	'ADD_CAT'							=> 'Dodaj kategorię',
	'ADD_RANDTXT'						=> 'Dodaj losowy tekst',
	'CAT_DESC'							=> 'Nazwa kategorii',
	'CAT_DESC_EXPLAIN'					=> 'Nazwa kategorii, która będzie zawierała losowe teksty',
	'DELETE_CATEGORY'					=> 'Usunąć kategorię?',
	'CAT_ADD_SUCCESS'					=> 'Kategoria została dodana pomyślnie.',
	'CAT_DELETE_SUCCESS'				=> 'Kategoria została usunięta pomyślnie.',
	'CAT_EDIT_SUCCESS'					=> 'Kategoria została zaktualizowana pomyślnie.',
	'DELETE_RANDTXT'					=> 'Usunąć losowy tekst?',
	'RANDTXT_ADD_SUCCESS'				=> 'Losowy tekst został dodany pomyślnie.',
	'RANDTXT_DELETE_SUCCESS'			=> 'Losowy tekst został usunięty pomyślnie.',
	'RANDTXT_EDIT_SUCCESS'				=> 'Losowy tekst został zaktualizowany pomyślnie.',
	
	//Warning
	'BOT_WARN'							=> '{$username}, [b]nie tolerujemy przeklinania[/b] :!:',
	'BOT_GIVE_WARN'						=> '{$username}, [color=red][b]otrzymujesz ostrzeżenie za przeklinanie[/b][/color] :!:',
	'BOT_WARN_INFO'						=> 'Użytkownik otrzymał ostrzeżenie za przeklinanie na czacie: %s',
));

?>