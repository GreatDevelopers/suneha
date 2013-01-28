<?php
/*************************************************************
 *  THE ADDRESS BOOK  :  version 1.04
 *  
 *
 **************************************************************
 *  config.php
 *  Sets configuration variables.
 *
 *************************************************************/

/**************************************************************
**
**  You will have to manually edit this file to set up
**  The Address Book on your server. Please read all the
**  instructions carefully. If values are missing or
**  incorrect The Address Book may not function correctly.
**
**************************************************************/

// MYSQL SERVER HOST NAME
// This is the name of the host machine that the MySQL server 
// is installed on. In many cases it is the same as the web 
// server so "localhost" will suffice. If this is incorrect you
// will need to speak with a server administrator to obtain the
// host name.
// If you leave the variable empty it will default to "localhost"

$db_hostname = "localhost";


// DATABASE NAME (REQUIRED)
// This is the name of the database that you wish to use, which
// you may have either already set up in MySQL, or had one
// assigned to you. The Address Book will be unable to create
// a database if one does not already exist, so make sure you
// have one.

$db_name = "adbook";


// MYSQL USER NAME (REQUIRED)
// This is the user name that you use to log in to MySQL.

$db_username = "username";


// MYSQL PASSWORD
// This is the password corresponding to the above username 
// which you use to log in to MySQL.
// Passwords may be left blank if you do not have one.

$db_password = "password";


// DATABASE TABLE PREFIX
// A database can contain a large number of tables, some of 
// which may be used for other applications than the Address
// Book. If you have only one database to work with you may 
// wish to provide a prefix which would make it easier to find
// and identify tables related to the Address Book. We suggest
// using the prefix "address_" but you may also choose not to
// use a prefix by leaving the variable blank.
// Do NOT use the - (dash) character, or spaces or / (slash)

$db_prefix = "";
// $db_prefix = "";


// TURN OFF LOGIN
// This feature has been removed from config.php and can now
// be set through the script in the options control panel.



// END OF FILE
?>
