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

//TODO: add logging facility

class SADebug {
	protected static $displayFor = array('127.0.0.1');
	
	public static function acceptClient($ip) {
		self::$displayFor[] = $ip;
	}
	
	public static function trace($message, $file = __FILE__, $line = __LINE__, $method = __METHOD__) {
		SALog::log("$file on line $line - $method: $message", SA_LOG_ERROR);
		if (!(in_array($_SERVER['REMOTE_ADDR'], self::$displayFor))) return;
		print "<div style='padding:2px; background-color:lightyellow; border:1px dashed red;'>$file on line $line<br><strong>{$method}</strong>: <span style='color:red'>{$message}</span></div>";
	}	
}