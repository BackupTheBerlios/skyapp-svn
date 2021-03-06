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

/*! \brief Class for handling the HTTP request
 * 
 */

final class SARequest {
	private static $app;

	/**
	 * Sets the application controller
	 * @param SApplication $app The application controller instance
	 */
	
	public static function setApplicationObject(SApplication &$app) {
		self::$app = &$app;
	}
	
	/**
	 * Initializes some default attributes for SApplication instance 
	 */
	
	public static function init() {
		self::$app->setServerName($_SERVER['SERVER_NAME']);
		self::$app->setServerPort($_SERVER['SERVER_PORT']);
		self::$app->setServerSecure($_SERVER["HTTPS"] == 'on');
		self::$app->setPathInfo($_SERVER['PATH_INFO']);
		$scriptName = basename($_SERVER['SCRIPT_FILENAME']);
		self::$app->setScriptName($scriptName);
		ereg("^(.*)$scriptName", $_SERVER['REDIRECT_URL'], $matches);
		self::$app->setScriptPath($matches[1]);	
	}
	
	/**
	 * Checks if the user altered the request parameters.
	 * This is done by checking the "chk" GET variable
	 */
	
	public static function checkValidity() {		
		ereg($_GET[self::$app->getGPPageName()] . "(.*)", self::$app->getPathInfo(), $matches);
		if ($matches[1]) {
			$uri = $_SERVER['REQUEST_URI'];
			ereg(self::$app->getScriptPath() . "(.*)/chk", $uri, $matches);
			if (md5(SA_SECRET_KEY . $matches[1]) != $_REQUEST['chk']) {
				throw new URLManipulationException('the client altered the request parameters');
			}
		}
	}
	
	/**
	 * Detects the page name by analyzing the PATH_INFO
	 */
	
	public static function detectPageName() {
		$pathInfo = explode('/', self::$app->getPathInfo());
		$length = count($pathInfo);
		$pageName = ($length == 1) ? self::$app->getDefaultPageName() : null;
		if ($length > 1) {
			for($i = $length; $i > 0; $i--) {
				$page = implode('/', array_slice($pathInfo, 1, $i));
				if (is_null($pathInfo[$i])) $page .= '/' . self::$app->getDefaultPageName();
				$pageFileName = self::$app->getPageSearchDirectory() . "/$page.php";
				if (is_file($pageFileName)) {
					$pageName = $page;
					break;
				}
			}
		}
		$_REQUEST[self::$app->getGPPageName()] = $_GET[self::$app->getGPPageName()] = $pageName;		
	}
	
	/**
	 * Detects the GET parameters by analyzing the PATH_INFO
	 */
	
	public static function detectGETParameters() {		
		ereg($_GET[self::$app->getGPPageName()] . "(.*)", self::$app->getPathInfo(), $matches);
		$params = explode('/', $matches[1]);
		$length = count($params);
		for($i = 1; $i < $length - 1; $i += 2) {
			$_REQUEST[$params[$i]] = $_GET[$params[$i]] = self::decodeParam($params[$i + 1]);
		}
	}
	
	/**
	 * Decodes the GET parameter
	 * @param string $value The value of the GET parameter
	 */
	
	public static function decodeParam($value) {
		$decoded = urldecode($value);
		if (ereg('^NjQ=NjQ=NjQ=([A-Za-z0-9=]+)$', $decoded, $matches)) {
			$decoded = base64_decode($matches[1]);
		}
		return $decoded;
	}
}