<?php
/*
+-----------------------------------------------------------------------+
| SkyApp, The PHP Application Framework.                                |
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

define('SA_SESSION_FORCE_COOKIES', true);

class SAUrl {
	private static $app;
	
	public static function setApplicationObject(SApplication &$app) {
		self::$app = &$app;
	}
	
	public static function url($page = null, $params = array(), $port = 80, $secure = false) {
		$url = '';
		if (($port != 80) || $secure) {
			$url .= ($secure) ? 'https' : 'http';
			$url .= '://';
			$url .= self::$app->getServerName();
			$url .= ($port == 80) ? '' : ":$port";
		} 
		$url .= self::$app->getScriptPath();
		$pageName = (is_null($page)) ? self::$app->getDefaultPageName() : $page;
		if (ereg("^/", $pageName)) {
			$pageName = substr($pageName, 1);
		} else {
			$currentPage = self::$app->getCurrentPage();
			$pagePath = ($currentPage) ? dirname($currentPage->getName()) : '.';
			$url .= ($pagePath == '.') ? '' : "$pagePath/";
		}
		$url .= $pageName;
		if (is_array($params)) {
			foreach($params as $key => $value) {
				$value = (is_null($value)) ? 'null' : $value;
				$value = self::encodeParam($value);
				$url .= "/$key/$value";
			}
		}
		if (!SA_SESSION_FORCE_COOKIES) {
			$url = self::appendSid($url);
			if (SID) $params[SID] = SID;
		}
		if (count($params)) $url = self::appendCheckValue($url);
		return $url;
	}
	
	private function appendSid($url) {
		$url .= (SID) ? '/' . session_name() . '/' . session_id() : '';
		return $url; 
	}
	
	private function appendCheckValue($url) {
		ereg(self::$app->getScriptPath() . '(.*)', $url, $matches);
		$url .= '/chk/' . md5(SA_SECRET_KEY . $matches[1]);
		return $url;
	}
	
	public static function baseHref($secure = false) {
		$baseHref = '';
		$baseHref .= (self::$app->getServerSecure()) ? 'https' : 'http';
		$baseHref .= '://';
		$baseHref .= self::$app->getServerName();
		$baseHref .= (self::$app->getServerPort() == 80) ? '' : ":$port";
		$baseHref .= self::$app->getScriptPath();		
		return $baseHref;			
	}
	
	public static function encodeParam($value) {
		$encoded = $value;
		//hack for escaping funny characters such as / or %
		//NjQ=NjQ=NjQ= used as base64 encoded strings signature
		//NjQ=NjQ=NjQ= equals base64_encode(64)		
		$encoded = (ereg('[/%]', $encoded)) ? 'NjQ=NjQ=NjQ=' . base64_encode($encoded) : $encoded;
		$encoded = urlencode($encoded);
		return $encoded;
	}
}