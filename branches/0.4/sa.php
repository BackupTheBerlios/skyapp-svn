<?php
//defines for SA
define('SA_HOME_DIR', dirname(__FILE__) . '/');
define('SA_CORE_DIR', SA_HOME_DIR . 'sa/');
define('SA_SMARTY_DIR', SA_HOME_DIR . '/vendor/Smarty-2.6.18/libs/');
define('SA_SESSION_NAME', 'sid');
define('SA_SESSION_FORCE_COOKIES', true);

//includes for SA
require_once(SA_SMARTY_DIR . '/Smarty.class.php');

function __autoload($className) {
	if (ereg('^SA_', $className)) {
		require_once(SA_CORE_DIR . "/$className.php");
	}
}
