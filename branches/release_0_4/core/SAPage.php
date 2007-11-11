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

/**
 * The name of the default region which will be rendered to the browser
 */
define('CONTENT_FOR_LAYOUT', 'content_for_layout');

/*! \brief The default page class
 * 
 */

abstract class SAPage extends Smarty implements SAIPage {
	protected $app;
	protected $name;
	protected $layout = 'default.php';
	protected $layoutDir;
	protected $regions = array();
	protected $content_type = 'text/html; charset=utf-8';
	protected $headers = array();
	protected $attributes = array();
	
	/**
	 * The default constructor which must be called explicitely by each subclass
	 */
	
	function __construct(SApplication &$app, $name) {
		SALog::log("in page $name constructor", SA_LOG_NOTICE);		
		parent::Smarty();
		$this->app = &$app;
		$this->assign_by_ref('app', $app);
		$this->name = $name;		
		$this->template = "{$name}.tpl";
		$appHome = $app->getHomeDirectory();
		$this->plugins_dir = array('plugins', SA_CORE_DIR . 'sa_smarty_plugins');
		$this->layoutDir =  "{$appHome}/Layouts";
		$this->template_dir = "{$appHome}/Templates";
		$this->compile_dir = "{$appHome}/Templates_c";
		$this->use_sub_dirs = true;
		$this->headers = array(
							'Expires' => 'Mon, 26 Jul 1997 05:00:00 GMT',
							'Last-Modified' => gmdate('D, d M Y H:i:s') . ' GMT',
							'Cache-Control' => 'no-store, no-cache, must-revalidate',
							'Pragma' => 'no-cache'								
							);
	}
	
	function __destruct() {
		
	}
	
	/**
	 * The default setter
	 * Will set an instance attribute and a Smarty variable as well
	 */
	
	function __set($key, $value) {
		$this->attributes[$key] = $value;
		$this->assign($key, $value);
	}
	
	/**
	 * The default getter
	 */
	
	function __get($key) {
		return $this->attributes[$key];
	}
	
	/**
	 * Sets the mime type of the page.
	 * By default is set to "text/html; charset=utf-8"
	 * @see $this->content_type
	 * @param string $type the mime type of the page
	 */
	
	public function setContentType($type) {
		$this->content_type = $type;
	}
	
	/**
	 * @return string The mime type of the page
	 */
	
	public function getContentType() {
		return $this->content_type;
	}
	
	/**
	 * Sets a HTTP header value
	 * @param string $key The name of the header (eg. Location)
	 * @param string $value The value of the header (eg. http://www.php.net/)
	 */
	
	public function setHeader($key, $value) {
		$this->headers[$key] = $value;
	}
	
	/**
	 * @param string $key The name of the header
	 * @return string The value of the header specified by $key
	 */
	
	public function getHeader($key) {
		return $this->headers[$key];
	}
	
	/**
	 * Sends the page headers
	 */
	
	public function sendHeaders() {
		if (!headers_sent()) {
			$this->headers['Content-type'] = $this->content_type;
			foreach($this->headers as $key => $value) {
				header("$key: $value");
			}
		}
	}
	
	/**
	 * Sets the application controller
	 * @param SApplication $app The application controller instance
	 */
	
	public function setApplicationObject(SApplication &$app) {
		$this->app = &$app;
	}
	
	/**
	 * Gets the application controller
	 * @return string The application controller instance
	 */
	
	public function getApplicationObject() {
		return $this->app;
	}
	
	/**
	 * Getter for page name
	 */
	
	public function getName() {
		return $this->name;
	}
	
	/**
	 * Setter for page name
	 */
	
	public function setName($name) {
		$this->name = $name;
	}
	
	/**
	 * Sets the name of the layout file
	 * @param string $layout The file name of the layout
	 */
	
	public function setLayout($layout) {
		$this->layout = $layout;
	}
	
	/**
	 * Getter for page layout
	 * @return string The file name of the layout
	 */
	
	public function getLayout() {
		return $this->layout;
	}
	
	/**
	 * Setter for the Smarty tempate file name
	 */
	
	public function setTemplate($template) {
		$this->template = $template;
	}
	
	/**
	 * Getter for the Smarty template file name
	 */
	
	public function getTemplate() {
		return $this->template;
	}
	
	/**
	 * Setter for the layout search dir
	 */
	
	public function setLayoutDir($dir) {
		$this->layoutDir = $dir;
	}
	
	/**
	 * Getter for the layout search dir
	 */
	
	public function getLayoutDir() {
		return $this->layoutDir;
	}
	
	/**
	 * Setter for the Smarty template search dir
	 */
	
	public function setTemplateDir($dir) {
		$this->template_dir = $dir;
	}
	
	/**
	 * Getter for the Smarty template search dir
	 */
	
	public function getTemplateDir() {
		return $this->template_dir;
	}
	
	/**
	 * Setter for the Smarty template compile dir
	 */
	
	public function setCompileDir($dir) {
		$this->compile_dir = $dir;
	}
	
	/**
	 * Getter for the Smarty template compile dir
	 */
	
	public function getCompileDir() {
		return $this->compile_dir;
	}
	
	/**
	 * Checks if the specified layout is a valid file
	 */
	
	public function hasLayout()
	{
		$layout = "{$this->layoutDir}/{$this->layout}";
		return is_file($layout) && is_readable($layout);
	}
	
	/**
	 * Sets the content of a layout region
	 * @param string $region The name of the region
	 * @param string $content The content of the region
	 */
	
	public function setContents($region, $content) {
		$this->regions[$region] = $content;
	}
	
	/**
	 * Gets the content of a region
	 * @param string $region The region name
	 */
	
	public function getContents($region) {
		return $this->regions[$region];
	}
	
	/**
	 * Fetches the content of the Smarty template
	 * @see $this->setTemplate()
	 * @return $string The content of the rendered Smarty template
	 */
	
	public function fetch() {
		if ($this->template) $this->setContents(CONTENT_FOR_LAYOUT, parent::fetch($this->template));
		return $this->render();
	}
	
	/**
	 * Sends the HTTP headers and dsiplays the page
	 * @see $this->fetch()
	 */
	
	public function display() {
		$this->sendHeaders();		
		print $this->fetch();
	}
	
	/**
	 * Runs the events of the page
	 */
	
	public function runEvents() {
		$events = explode(':', $this->app->GP($this->app->getGPEventsName()));
		if (is_array($events)) {
			foreach($events as $idx => $event) {
				$method = 'do' . ucfirst($event);
				if (method_exists($this, $method)) {
					call_user_func_array(array(&$this, $method), null);
				}
			}
		}		
	}
	
	/**
	 * Used internally for rendering the content
	 * @see $this->fetch()
	 */	
	
	protected function render() {
		if ($this->hasLayout()) {
			ob_start();
			require("{$this->layoutDir}/{$this->layout}");
			$contents = ob_get_contents();
			ob_end_clean();
		} else {
			$contents = $this->regions[CONTENT_FOR_LAYOUT];
		}
		return $contents;
	}	
}