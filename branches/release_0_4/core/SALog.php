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


define('SA_LOG_NOTICE', 0);
define('SA_LOG_WARNING', 1);
define('SA_LOG_ERROR', 1);

/*! \brief Class used for logging messages to a file
 * 
 */

class SALog {
	protected static $fp;
	protected static $types = array(
								SA_LOG_NOTICE => 'NOTICE',
								SA_LOG_WARNING => 'WARNING',
								SA_LOG_WARNING => 'ERROR'
								);
	/**
	 * Opens the log specified by $name. If no name is given
	 * it will receive a default name
	 * @param string $name the name of the file
	 */
	
	public static function open($name = null) {
		if (is_null($name)) {
			$name = 'sa_' . date('Y-m-d') . '.log';
		}
		self::$fp = @fopen(SA_LOGS_DIR . $name, 'a');
	}
	
	/**
	 * Logs the specified message to the file
	 * @param string $message the log message
	 * @param int $type the type of the message; can be SA_LOG_NOTICE, SA_LOG_WARNING or SA_LOG_ERROR
	 */
	
	public static function log($message, $type = 0) {
		@fwrite(self::$fp, self::$types[$type] . ': ' . date('Y-m-d H:i:s') . ": $message\n");
	}
	
	/**
	 * Closes the logger
	 */
	
	public static function close() {
		@fclose(self::$fp);
	}
}