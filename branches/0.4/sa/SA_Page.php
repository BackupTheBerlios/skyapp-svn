<?php
class SA_Page extends Smarty implements SA_IPage {
	protected $name;
	protected $layout = 'default.php';
	protected $layoutDir;
	protected $regions = array();
	protected $content_type = 'text/html; charset=utf-8';
	protected $headers = array();
	protected $attributes = array();
		
	public function __construct($name) {
		parent::Smarty();
		$this->app = & SA_Registry::get('__SA_APPLICATION__');
		$this->assign_by_ref('app', $this->app);
		$this->setName($name);		
		$this->setTemplate("$name.tpl");
		$appHome = $this->app->getApplicationDirectory();
		$this->plugins_dir = array('plugins', SA_CORE_DIR . 'sa_smarty_plugins');
		$this->setLayoutDir("{$appHome}/layouts");
		$this->setTemplateDir("{$appHome}/templates");
		$this->setCompileDir("{$appHome}/templates_c");
		$this->use_sub_dirs = true;
		$this->headers = array(
							'Expires' => 'Mon, 26 Jul 1997 05:00:00 GMT',
							'Last-Modified' => gmdate('D, d M Y H:i:s') . ' GMT',
							'Cache-Control' => 'no-store, no-cache, must-revalidate',
							'Pragma' => 'no-cache'								
							);
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
	
	public function init() {}
	
	public function run() {
		$events = explode('&', $this->app->GP($this->app->getGPEventsName()));
		if (is_array($events)) {
			foreach($events as $idx => $event) {
				$method = 'do' . ucfirst($event);
				if (method_exists($this, $method)) {
					call_user_func_array(array(&$this, $method), null);
				}
			}
		}		
	}
	
	public function beforeDisplay() {}
	
	public function fetch() {
		if ($this->getTemplate()) $this->setContents(CONTENT_FOR_LAYOUT, parent::fetch($this->getTemplate()));
		return $this->render();
	}	
	
	public function display() {
		$this->sendHeaders();		
		print $this->fetch();
	}
	
	public function afterDisplay() {}
	
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