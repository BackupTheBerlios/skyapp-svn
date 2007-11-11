<?php
class SA_Application {
	protected $gp;
	protected $appDirectory;
	protected $serverName;
	protected $serverPort;
	protected $serverSecure;
	protected $pathInfo;
	protected $scriptName;
	protected $scriptPath;
	protected $currentPage;
	protected $currentPageHandler;
	protected $defaultPageName = 'default';
	protected $errorPageName = 'error';
	
	public function __construct() {
	}
	
	public function getApplicationDirectory() {
		return $this->appDirectory;
	}
	
	public function setApplicationDirectory($appDirectory) {
		$this->appDirectory = $appDirectory;
	}
	
	public function setServerName($serverName) {
		$this->serverName = $serverName;
	}
	
	public function getServerName() {
		return $this->serverName;
	}
	
	public function setServerPort($port) {
		$this->serverPort = $port;
	}
	
	public function getServerPort() {
		return $this->serverPort;
	}
	
	public function setServerSecure($secure) {
		$this->serverSecure = $secure;
	}
	
	public function getServerSecure() {
		return $this->serverSecure;
	}
	
	public function setPathInfo($pathInfo) {
		$this->pathInfo = $pathInfo;
	}
	
	public function getPathInfo() {
		return $this->pathInfo;
	}
	
	public function setScriptName($scriptName) {
		$this->scriptName = $scriptName;
	}
	
	public function getScriptName() {
		return $this->scriptName;
	}
	
	public function setScriptPath($scriptPath) {
		$this->scriptPath = $scriptPath;
	}
	
	public function getScriptPath() {
		return $this->scriptPath;
	}
	
	public function setCurrentPageName($pageName) {
		$this->currentPageName = $pageName;
	}
	
	public function getCurrentPageName() {
		return $this->currentPageName;
	}
	
	public function setCurrentPageHandler($page) {
		$this->currentPageHandler = $page;
	}
	
	public function getCurrentPageHandler() {
		return $this->currentPageHandler;
	}
	
	public function setErrorPageName($errorPage) {
		$this->errorPageName = $errorPage;
	}
	
	public function getErrorPageName() {
		return $this->errorPageName;
	}
	
	public function run() {
		try {
			SA_PageHandler::handle($this->getCurrentPageName());
		} catch (Exception $e) {
			try {
				$errorPage = & SA_PageHandler::factory($this->getErrorPageName());
				$errorPage->assign('error', $e);
				$errorPage->display();
			} catch (Exception $e) {
				throw $e;
			}
		}
	}
	
	public function init() {
		session_start();
		
		$this->setServerName($_SERVER['SERVER_NAME']);
		$this->setServerPort($_SERVER['SERVER_PORT']);
		$this->setServerSecure($_SERVER["HTTPS"] == 'on');
		$this->setPathInfo($_SERVER['PATH_INFO']);
		$this->setScriptName(basename($_SERVER['SCRIPT_NAME']));
		$this->setScriptPath(dirname($_SERVER['SCRIPT_NAME']));
		$this->detectPageName();
		$this->detectGETParameters();
		$this->gp = $_GET + $_POST;
	}
	
	public function GP() {
		$argc = func_num_args();
		if ($argc == 0) return $this->gp;
		$argv = func_get_args();
		if ($argc == 2) {
			$this->gp[$argv[0]] = $argv[1];
		}
		return $this->gp[$argv[0]];
	}	
	
	protected function detectPageName() {
		$pathInfo = explode('/', $this->getPathInfo());
		$length = count($pathInfo);
		$pageName = ($length == 1) ? $this->defaultPageName : null;
		if ($length > 1) {
			for($i = $length; $i > 0; $i--) {
				$page = implode('/', array_slice($pathInfo, 1, $i));
				if (is_null($pathInfo[$i])) $page .= '/' . $this->defaultPageName;
				$pageFileName = $this->getApplicationDirectory() . "/pages/$page.php";
				if (is_file($pageFileName)) {
					$pageName = $page;
					break;
				}
			}
		}
		$this->setCurrentPageName($pageName);
	}

	protected function detectGETParameters() {
		ereg($this->getCurrentPageName() . "(.*)", $this->getPathInfo(), $matches);
		$params = explode('/', $matches[1]);
		$length = count($params);
		for($i = 1; $i < $length - 1; $i += 2) {
			$_REQUEST[$params[$i]] = $_GET[$params[$i]] = $params[$i + 1];
		}

		$pageName = $this->getCurrentPageName();
		$this->setCurrentPageName(($pageName) ? $pageName : $this->defaultPageName);		
	}	
}