<?php
/*
+-----------------------------------------------------------------------+
| SkyApp - The PHP Application Framework.                               |
| http://www.skyweb.ro/                                                 |
+-----------------------------------------------------------------------+
| This source file is released under LGPL license, available through    |
| the world wide web at http://www.gnu.org/copyleft/lesser.html.        |
| This library is distributed WITHOUT ANY WARRANTY. Please see the LGPL |
| for more details.                                                     |
+-----------------------------------------------------------------------+
| Authors: Andi Trînculescu <andi@skyweb.ro>                            |
+-----------------------------------------------------------------------+

$Id$
*/

define('SA_CORE_DIR', 'core/');
define('SA_LIB_DIR', 'lib/');
define('SA_SMARTY_DIR', SA_LIB_DIR . 'Smarty-2.6.14/libs/');
define('SA_WEBAPP_DIR', 'webapp/');
define('SA_LOGS_DIR', 'logs/');
define('SA_SECRET_KEY', 'monaco');
define('SA_SESSION_NAME', 'sid');
define('SA_SESSION_FORCE_COOKIES', true);

//includes for SA
require_once(SA_SMARTY_DIR	.	'Smarty.class.php');

require_once(SA_CORE_DIR	.	'SADebug.php');
require_once(SA_CORE_DIR	.	'SAExceptions.php');
require_once(SA_CORE_DIR	.	'SAUrl.php');
require_once(SA_CORE_DIR	.	'SAIPage.php');
require_once(SA_CORE_DIR	.	'SAPage.php');
require_once(SA_CORE_DIR	.	'SAPageHandler.php');
require_once(SA_CORE_DIR	.	'SARequest.php');
require_once(SA_CORE_DIR	.	'SALog.php');
require_once(SA_CORE_DIR	.	'SApplication.php');

//includes for webapp
require_once(SA_WEBAPP_DIR	.	'DemoApplication.php');