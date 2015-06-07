<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

/*using offline settings on localhost
  and using online settings for server*/

$active_group = 'online';
$active_record = TRUE;

$db['offline']['hostname'] = 'localhost';
$db['offline']['username'] = 'root';
$db['offline']['password'] = '';
$db['offline']['database'] = 'artery_db';
$db['offline']['dbdriver'] = 'mysql';
$db['offline']['dbprefix'] = '';
$db['offline']['pconnect'] = TRUE;
$db['offline']['db_debug'] = TRUE;
$db['offline']['cache_on'] = FALSE;
$db['offline']['cachedir'] = '';
$db['offline']['char_set'] = 'utf8';
$db['offline']['dbcollat'] = 'utf8_general_ci';
$db['offline']['swap_pre'] = '';
$db['offline']['autoinit'] = TRUE;
$db['offline']['stricton'] = FALSE;


$db['online']['hostname'] = 'localhost';
$db['online']['username'] = 'rajka381_artery';
$db['online']['password'] = 'ch@ng3m3';
$db['online']['database'] = 'rajka381_artery';
$db['online']['dbdriver'] = 'mysql';
$db['online']['dbprefix'] = '';
$db['online']['pconnect'] = TRUE;
$db['online']['db_debug'] = FALSE;
$db['online']['cache_on'] = FALSE;
$db['online']['cachedir'] = '';
$db['online']['char_set'] = 'utf8';
$db['online']['dbcollat'] = 'utf8_general_ci';
$db['online']['swap_pre'] = '';
$db['online']['autoinit'] = TRUE;
$db['online']['stricton'] = FALSE;


/* End of file database.php */
/* Location: ./application/config/database.php */