<?php
class SA_Url {	
	public static function url($page = null, $params = array(), $port = 80, $secure = false) {
		$url = '';
		$app = & SA_Registry::get('__SA_APPLICATION__');
		if (($port != 80) || $secure) {
			$url .= ($secure) ? 'https' : 'http';
			$url .= '://';
			$url .= $app->getServerName();
			$url .= ($port == 80) ? '' : ":$port";
		} 
		$url .= $app->getScriptPath();
		$url .= (strlen($app->getScriptPath()) == 1) ? '' : '/';
		$pageName = (is_null($page)) ? $app->getDefaultPageName() : $page;
		if (ereg("^/", $pageName)) {
			$pageName = substr($pageName, 1);
		} else {
			$currentPage = $app->getCurrentPageHandler();
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

		return $url;
	}
	
	public static function baseHref() {
		$baseHref = '';
		$app = & SA_Registry::get('__SA_APPLICATION__');		
		$baseHref .= ($app->getServerSecure()) ? 'https' : 'http';
		$baseHref .= '://';
		$baseHref .= $app->getServerName();
		$baseHref .= ($app->getServerPort() == 80) ? '' : ":$port";
		$baseHref .= $app->getScriptPath();		
		return $baseHref;			
	}
	
	protected static function appendSid($url) {
		$url .= (SID) ? '/' . session_name() . '/' . session_id() : '';
		return $url; 
	}	
	
	protected static function encodeParam($value) {
		$encoded = $value;
		//hack for escaping funny characters such as / or %
		//NjQ=NjQ=NjQ= used as base64 encoded strings signature
		//NjQ=NjQ=NjQ= equals base64_encode(64)		
		$encoded = (ereg('[/%]', $encoded)) ? 'NjQ=NjQ=NjQ=' . base64_encode($encoded) : $encoded;
		$encoded = urlencode($encoded);
		return $encoded;
	}
}
