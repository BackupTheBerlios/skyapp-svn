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

define('CONTENT_FOR_LAYOUT', 'content_for_layout');

abstract class SAPage extends Smarty implements SAIPage {
	protected $app;
	protected $name;
	protected $layout = 'default.php';
	protected $layoutDir;
	protected $regions = array();
	protected $content_type = 'text/html; charset=utf-8';
	protected $headers = array();
	protected $attributes = array();
	
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
	
	function __set($key, $value) {
		$this->attributes[$key] = $value;
		$this->assign($key, $value);
	}
	
	function __get($key) {
		return $this->attributes[$key];
	}
	
	public function setContentType($type) {
		$this->content_type = $type;
	}
	
	public function getContentType() {
		return $this->content_type;
	}
	
	public function setHeader($key, $value) {
		$this->headers[$key] = $value;
	}
	
	public function getHeader($key) {
		return $this->headers[$key];
	}
	
	public function sendHeaders() {
		if (!headers_sent()) {
			$this->headers['Content-type'] = $this->content_type;
			foreach($this->headers as $key => $value) {
				header("$key: $value");
			}
		}
	}
	
	public function setApplicationObject(SApplication &$app) {
		$this->app = &$app;
	}
	
	public function getApplicationObject(&$app) {
		return $this->app;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function setName($name) {
		$this->name = $name;
	}
	
	public function setLayout($layout) {
		$this->layout = $layout;
	}
	
	public function getLayout() {
		return $this->layout;
	}
	
	public function setTemplate($template) {
		$this->template = $template;
	}
	
	public function getTemplate() {
		return $this->template;
	}	
	
	public function setLayoutDir($dir) {
		$this->layoutDir = $dir;
	}
	
	public function getLayoutDir() {
		return $this->layoutDir;
	}
	
	public function setTemplateDir($dir) {
		$this->template_dir = $dir;
	}
	
	public function getTemplateDir() {
		return $this->template_dir;
	}
	
	public function setCompileDir($dir) {
		$this->compile_dir = $dir;
	}
	
	public function getCompileDir() {
		return $this->compile_dir;
	}	
	
	public function hasLayout()
	{
		$layout = "{$this->layoutDir}/{$this->layout}";
		return is_file($layout) && is_readable($layout);
	}	
	
	public function setContents($region, $content) {
		$this->regions[$region] = $content;
	}
	
	public function getContents($region) {
		return $this->regions[$region];
	}
	
	public function fetch() {
		if ($this->template) $this->setContents(CONTENT_FOR_LAYOUT, parent::fetch($this->template));
		return $this->render();
	}
	
	public function display() {
		$this->sendHeaders();		
		print $this->fetch();
	}
	
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