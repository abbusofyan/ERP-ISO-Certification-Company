<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Application Version
|--------------------------------------------------------------------------
*/
define('VERSION', '1.0.3');

/*
|--------------------------------------------------------------------------
| X-API-KEY
|--------------------------------------------------------------------------
*/
define('X_API_KEY', '25affff4b-e87a-8c9d-05e5-d21b6907f07a');

/*
|--------------------------------------------------------------------------
| Gogle Cloud Storage
|--------------------------------------------------------------------------
*/
if (ENVIRONMENT == 'production') {
    define('GS_BUCKET_NAME', "hallowed-oven-372904.appspot.com");
} else {
    define('GS_BUCKET_NAME', "staging.hallowed-oven-372904.appspot.com");
}
define('GS_BUCKET',      "gs://" . GS_BUCKET_NAME);
define('GS_TEMP',        GS_BUCKET . "/temp");
define('GAE_PROJECT_ID', "hallowed-oven-372904");
define('GS_DOMPDF_FONT', GS_BUCKET . "/dompdf/font");
define('GS_DOMPDF_TEMP', GS_BUCKET . "/dompdf/temp");

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code


// quotation
defined('QUOTE_CYCLE_NEW')           		OR define('QUOTE_CYCLE_NEW', 1);
defined('DEFAULT_QUOTE_CYCLE')          	OR define('DEFAULT_QUOTE_CYCLE', 1);

defined('DEFAULT_QUOTATION_STATUS')         OR define('DEFAULT_QUOTATION_STATUS', 'New');

defined('QUOTATAION_STATUS')        		OR define('QUOTATION_STATUS', [
	'New', 'Confirmed', 'Chosen Other CB', 'On-Hold', 'Dropped by ASA', 'Dropped by Client'
]);

defined('QUOTATION_STATUS_ALLOWED_TO_EDIT') OR define('QUOTATION_STATUS_ALLOWED_TO_EDIT', [
	'New', 'On-Hold', 'Non-Active'
]);

defined('CLIENT_STATUS')        			OR define('CLIENT_STATUS', [
	'New', 'Active', 'Past Active', 'Non-Active'
]);

defined('CONTACT_STATUS')        			OR define('CONTACT_STATUS', [
	'Active', 'Past'
]);
