<?php
/*************************************************************
 *  THE ADDRESS BOOK  :  version 1.04e
 *  
 *
 **************************************************************
 *  constants.inc
 *  Defines constants to be used within other scripts.
 *
 *************************************************************/

// DEFINE PHP EXTENSION
// change the following line if your files end in another extension, ie. "php3".
// in most cases the extension "php" is fine.
$php_ext = "php";

// ** DO NOT MODIFY ANYTHING BELOW THIS LINE
// ***********************************************************/
// ***********************************************************/

// ERROR REPORTING
//error_reporting (E_ALL);
error_reporting (E_ALL ^ E_NOTICE); 

// DEFINE VERSION NUMBER
define('VERSION_NO', '1.04e');

// DEFINE FILE NAMES
define('FILE_ADDRESS', 'address.' . $php_ext);
define('FILE_BIRTHDAY', 'birthday.' . $php_ext);
define('FILE_CELL', 'cell.' . $php_ext);
define('FILE_CLASS_CONTACTLIST', 'lib/class-contactlist.' . $php_ext);
define('FILE_CLASS_OPTIONS', 'lib/class-options.' . $php_ext);
define('FILE_CLASS_BIRTHDAY', 'lib/class-birthday.' . $php_ext);
define('FILE_CLASSES', 'classes.' . $php_ext); // temporary
define('FILE_CONFIG', 'config.' . $php_ext);
define('FILE_EDIT', 'edit.' . $php_ext);
define('FILE_EXPORT', 'export.' . $php_ext);
define('FILE_FUNCTIONS', 'functions.' . $php_ext);
define('FILE_GROUPS', 'groups.' . $php_ext);
define('FILE_INDEX', 'index.' . $php_ext);
define('FILE_INSTALL', 'install.' . $php_ext);
define('FILE_LIB_MAIL', 'lib/phpmailer/class.phpmailer.' . $php_ext);
define('FILE_LIB_UPLOAD', 'lib/fileupload-class.' . $php_ext);
define('FILE_LIB_USER', 'lib/userfunctions.' . $php_ext);
define('FILE_LIST', 'list.' . $php_ext);
define('FILE_MAILSEND', 'mailsend.' . $php_ext);
define('FILE_MAILTO', 'mailto.' . $php_ext);
define('FILE_OPTIONS', 'options.' . $php_ext);
define('FILE_REGISTER', 'register.' . $php_ext);
define('FILE_SAVE', 'save.' . $php_ext);
define('FILE_SCRATCHPAD', 'scratchpad.' . $php_ext);
define('FILE_SEARCH', 'search.' . $php_ext);
define('FILE_UPGRADE', 'upgrade.' . $php_ext);
define('FILE_UPLOAD', 'fileupload.' . $php_ext);
define('FILE_USERS', 'users.' . $php_ext);
//define('FILE_SMS', '.' . $php_ext);
define('PATH_MUGSHOTS', 'mugshots/');
define('PATH_LANGUAGES', 'languages/');

require_once(FILE_CONFIG);

// DEFINE TABLE NAMES
define('TABLE_ADDITIONALDATA', $db_prefix . 'additionaldata');
define('TABLE_ADDRESS', $db_prefix . 'address');
define('TABLE_CONTACT', $db_prefix . 'contact');
define('TABLE_EMAIL', $db_prefix . 'email');
define('TABLE_GROUPS', $db_prefix . 'groups');
define('TABLE_GROUPLIST', $db_prefix . 'grouplist');
define('TABLE_MESSAGING', $db_prefix . 'messaging');
define('TABLE_OPTIONS', $db_prefix . 'options');
define('TABLE_OTHERPHONE', $db_prefix . 'otherphone');
define('TABLE_SCRATCHPAD', $db_prefix . 'scratchpad');
define('TABLE_USERS', $db_prefix . 'users');
define('TABLE_WEBSITES', $db_prefix . 'websites');

// DEFINE WEBSITES
define('URL_HOMEPAGE', 'http://202.164.53.116/sms');
// http://www.corvalis.net/phpBB2/
define('URL_SOURCEFORGE', 'http://www.harbhag.wordpress.com/harbhag/');

// DEFINE USER CAPABILITIES (bitmap)
// -- Originally by Joe Chen
// -- This is experimental and is currently not used in this release of the Address Book.
define('CAP_USER',                  0x00000001);
define('CAP_ADMIN',                 0x00000002);
define('CAP_ADD',                   0x00000004);
define('CAP_EDIT',                  0x00000008);
define('CAP_DELETE',                0x00000010);
define('CAP_MAIL',                  0x00000020);
define('CAP_TABLE_ADDITIONALDATA',  0x00010000);
define('CAP_TABLE_ADDRESS',         0x00020000);
define('CAP_TABLE_EMAIL',           0x00040000);
define('CAP_TABLE_EVENTS',          0x00080000);
define('CAP_TABLE_FAMILY',          0x00100000);
define('CAP_TABLE_GROUPS',          0x00200000);
define('CAP_TABLE_MESSAGING',       0x00400000);
define('CAP_TABLE_NOTES',           0x00800000);
define('CAP_TABLE_OTHERPHONE',      0x01000000);
define('CAP_TABLE_PICTURES',        0x02000000);
define('CAP_TABLE_PRIVATENOTES',    0x04000000);

// END OF FILE
?>
