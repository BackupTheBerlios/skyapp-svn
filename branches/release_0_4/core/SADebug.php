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

/*! \brief Used for outputing error messages
 * 
 */
 
class SADebug {
	/**
	 * array with the list of http clients which will be able to see the error messages
	 */
	protected static $displayFor = array('127.0.0.1');
	
	/**
	 * adds a client ip to the  allowed http clients which will be able to see the error messages
	 * @param string $ip the ip address of the http client
	 */
	public static function acceptClient($ip) {
		self::$displayFor[] = $ip;
	}
	
	/**
	 * logs and displays the specified error message
	 * @param string $message error message
	 * @param string $file the file name in which the error occured
	 * @param string $line the line number from $file where the error occured
	 * @param string $method the method name where the error occured
	 */
	
	public static function trace($message, $file = __FILE__, $line = __LINE__, $method = __METHOD__) {
		SALog::log("$file on line $line - $method: $message", SA_LOG_ERROR);
		if (!(in_array($_SERVER['REMOTE_ADDR'], self::$displayFor))) return;
		print "<div style='padding:2px; background-color:lightyellow; border:1px dashed red;'>$file on line $line<br><strong>{$method}</strong>: <span style='color:red'>{$message}</span></div>";
	}	
}