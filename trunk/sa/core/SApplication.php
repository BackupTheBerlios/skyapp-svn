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

/*! \brief This is the application controller.
 * 
 * This is the controller which initializes the internal variables
 * and calls the SAPageHandler::handle method which will instantiate
 * the page class, run its events and display it
 */

abstract class SApplication {
	protected $serverName;
	protected $serverPort;
	protected $serverSecure;
	protected $scriptPath;
	protected $scriptName;
	protected $pathInfo;	
	
	/**
	 * this is the home dir of the webapp
	 * must be set by the inherited class
	 */	
	protected $home;
	/**
	 * this is the home dir for pages
	 */
	protected $pageSearchDir;
	/**
	 * used internally
	 */
	protected $gpPageName = 'p';
	/**
	 * this is the name of the GET variable which contains the event names
	 */
	protected $gpEventsName = 'e';
	/**
	 * this is the name of the default page which will be instantiate if none is supplied in the URL
	 */
	protected $defaultPageName = 'default';
	/**
	 * used internally
	 */
	protected $currentPage;
	/**
	 * this is the dafault error page name if something goes wrong
	 */
	protected $errorPageName = '404';
	/**
	 * contains the GET & POST variables
	 * @see GP()
	 */
	protected $gp;
	
	/**
	 * default constructor
	 * initializes the page search dir, page name and GET & POST variables
	 * must be called explicitely by the inheriting classes
	 */
	
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
	
	/**
	 * this is the default destructor
	 * can be overriden by creating a destructor in the inherited class and not
	 * calling it explicitely
	 */
	
	function __destruct() {
		print '<p style="font-family:arial,helvetica,sans-serif;font-size:x-small">Powered by <a href="http://www.skyweb.ro/" target="_blank">SA PHP framework</a> - Execution time: ' . (microtime() - $this->start_time) . '</p>';
		SALog::log("application shutdown", SA_LOG_NOTICE);
		SALog::close();
		ob_end_flush();		
	}
	
	/**
	 * method for forwarding the control to the specified page
	 * @param string $page the forward target
	 * @return void
	 */
	
	function forward($page) {
		SAPageHandler::handle($page);
		$this->resign("forwarded to $page", 0);
	}
	
	/**
	 * will send a redirect header to the specified url
	 * @param string $url the redirect target
	 * @return void
	 */
	
	public function redirect($url) {
		header("Location: $url");
		exit(0);
	}
	
	/**
	 * getter
	 * @see $this->serverName
	 */
	
	public function getServerName() {
		return $this->serverName;
	}
	
	/**
	 * setter
	 * @see $this->serverName
	 */	
	
	public function setServerName($serverName) {
		$this->serverName = $serverName;
	}
	
	/**
	 * getter
	 * @see $this->serverPort
	 */	
	
	public function getServerPort() {
		return $this->serverPort;
	}
	
	/**
	 * setter
	 * @see $this->serverPort
	 */	
	
	public function setServerPort($serverPort) {
		$this->serverPort = $serverPort;
	}
	
	/**
	 * getter
	 * @see $this->serverSecure
	 */	
	
	public function getServerSecure() {
		return $this->serverSecure;
	}
	
	/**
	 * setter
	 * @see $this->serverSecure
	 */	
	
	public function setServerSecure($secure) {
		$this->serverSecure = $secure;
	}
	
	/**
	 * getter
	 * @see $this->pathInfo
	 */	
	
	public function getPathInfo() {
		return $this->pathInfo;
	}
	
	/**
	 * setter
	 * @see $this->pathInfo
	 */	
	
	public function setPathInfo($pathInfo) {
		$this->pathInfo = $pathInfo;
	}
	
	/**
	 * getter
	 * @see $this->scriptPath
	 */	
	
	public function getScriptPath() {
		return $this->scriptPath;
	}
	
	/**
	 * setter
	 * @see $this->scriptPath
	 */	
	
	public function setScriptPath($scriptPath) {
		$this->scriptPath = $scriptPath;
	}
	
	/**
	 * getter
	 * @see $this->scriptName
	 */	
	
	public function getScriptName() {
		return $this->scriptName;
	}
	
	/**
	 * setter
	 * @see $this->scriptName
	 */	
	
	public function setScriptName($scriptName) {
		$this->scriptName = $scriptName;
	}
	
	/**
	 * getter
	 * @see $this->home
	 */	
	
	public function getHomeDirectory() {
		return $this->home;
	}
	
	/**
	 * setter
	 * @see $this->home
	 */	
	
	public function setHomeDirectory($home) {
		$this->home = $home;
	}
	
	/**
	 * getter
	 * @see $this->pageSearchDir
	 */	

	public function getPageSearchDirectory() {
		return $this->pageSearchDir;
	}
	
	/**
	 * getter
	 * @see $this->gpPageName
	 */
	
	public function getGPPageName() {
		return $this->gpPageName;
	}
	
	/**
	 * getter
	 * @see $this->gpEventsName
	 */	
	
	public function getGPEventsName() {
		return $this->gpEventsName;
	}
	
	/**
	 * getter
	 * @see $this->defaultPageName
	 */	
	
	public function getDefaultPageName() {
		return $this->defaultPageName;
	}
	
	/**
	 * getter
	 * @see $this->errorPageName
	 */	
	
	public function getErrorPageName() {
		return $this->errorPageName;
	}
	
	/**
	 * sets the current page object
	 * @param SAIPage $page the page which is currently being executed
	 * @see SAPageHandler::handle()
	 */	
	
	public function setCurrentPage(SAIPage &$page) {
		$this->currentPage = &$page;
	}
	
	/**
	 * gets the current page object
	 * @return SAIPage the page which is currently being executed
	 */	
	
	public function & getCurrentPage() {
		return $this->currentPage;
	}
	
	/**
	 * the main method which calls the execution of the page
	 * @return void
	 */
	
	public function run() {
		$pageName = $this->GP($this->getGPPageName());
		SAPageHandler::handle(($pageName) ? $pageName : $this->getDefaultPageName());
	}
	
	/**
	 * quits the application and logs the reason
	 * @param string $message reason
	 * @param int $errorCode error code 
	 */
	
	public function resign($message, $errorCode = 1) {
		SALog::log($message, ($errorCode) ? SA_LOG_ERROR : SA_LOG_NOTICE);
		exit($errorCode);
	}
	
	/**
	 * returns or sets a gp variable depending on the number of the arguments
	 * @param string $name this is the name of gp variable
	 * @param mixed $value this is the value of the gp variable
	 */
	
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
