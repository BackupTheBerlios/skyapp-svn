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

abstract class SApplication {
	protected $serverName;
	protected $serverPort;
	protected $serverSecure;
	protected $scriptPath;
	protected $scriptName;
	protected $pathInfo;	
		
	protected $home;
	protected $pageSearchDir;	
	protected $gpPageName = 'p';
	protected $gpEventsName = 'e';
	protected $defaultPageName = 'default';
	protected $currentPage;
	protected $errorPageName = '404';	
	protected $gp;
	
	function __construct() {
		ob_start();
		
		$this->start_time = microtime();
		$this->pageSearchDir = "{$this->home}/Pages";
		
		SALog::open();
		SAUrl::setApplicationObject($this);
		SAPageHandler::setApplicationObject($this);		
		SARequest::setApplicationObject($this);
		SARequest::init();		
		SARequest::detectPageName();
		SARequest::detectGETParameters();
		
		$this->gp = $_GET + $_POST;		
		
		session_name(SA_SESSION_NAME);
		session_start();
				
		try {
			SARequest::checkValidity();
		} catch (URLManipulationException $e) {
			SALog::log($e->getMessage(), SA_LOG_ERROR);
			$this->forward($this->errorPageName);
		} catch (Exception $e) {
			$this->resign($e->getMessage(), 1);
		}
		SALog::log("application initialized successfully", SA_LOG_NOTICE);
	}
	
	function __destruct() {
		print '<p style="font-family:arial,helvetica,sans-serif;font-size:x-small">Powered by <a href="http://www.skyweb.ro/" target="_blank">SA PHP framework</a> - Execution time: ' . (microtime() - $this->start_time) . '</p>';
		SALog::log("application shutdown", SA_LOG_NOTICE);
		SALog::close();
		ob_end_flush();		
	}
	
	function forward($page) {
		SAPageHandler::handle($page);
		$this->resign("forwarded to $page", 0);
	}
	
	public function redirect($url) {
		header("Location: $url");
		exit(0);
	}
	
	public function getServerName() {
		return $this->serverName;
	}
	
	public function setServerName($serverName) {
		$this->serverName = $serverName;
	}
	
	public function getServerPort() {
		return $this->serverPort;
	}
	
	public function setServerPort($serverPort) {
		$this->serverPort = $serverPort;
	}
	
	public function getServerSecure() {
		return $this->serverSecure;
	}
	
	public function setServerSecure($secure) {
		$this->serverSecure = $secure;
	}	
	
	public function getPathInfo() {
		return $this->pathInfo;
	}
	
	public function setPathInfo($pathInfo) {
		$this->pathInfo = $pathInfo;
	}
	
	public function getScriptPath() {
		return $this->scriptPath;
	}
	
	public function setScriptPath($scriptPath) {
		$this->scriptPath = $scriptPath;
	}
	
	public function getScriptName() {
		return $this->scriptName;
	}
	
	public function setScriptName($scriptName) {
		$this->scriptName = $scriptName;
	}	
	
	public function getHomeDirectory() {
		return $this->home;
	}
	
	public function setHomeDirectory($home) {
		$this->home = $home;
	}
	
	public function getPageSearchDirectory() {
		return $this->pageSearchDir;
	}	
	
	public function getGPPageName() {
		return $this->gpPageName;
	}
	
	public function getGPEventsName() {
		return $this->gpEventsName;
	}	
	
	public function getDefaultPageName() {
		return $this->defaultPageName;
	}
	
	public function getErrorPageName() {
		return $this->errorPageName;
	}
	
	public function setCurrentPage(&$page) {
		$this->currentPage = &$page;
	}
	
	public function & getCurrentPage() {
		return $this->currentPage;
	}
	
	public function run() {
		$pageName = $this->GP($this->getGPPageName());
		SAPageHandler::handle(($pageName) ? $pageName : $this->getDefaultPageName());
	}
	
	public function resign($message, $errorCode = 1) {
		SALog::log($message, ($errorCode) ? SA_LOG_ERROR : SA_LOG_NOTICE);
		exit($errorCode);
	}
	
	public function GP() {
		$argc = func_num_args();
		if ($argc == 0) return null;
		$argv = func_get_args();
		if ($argc == 2) {
			$this->gp[$argv[0]] = $argv[1];
		}
		return $this->gp[$argv[0]];
	}
}
