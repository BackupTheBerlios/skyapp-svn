<?php
class SA_PageHandler {
	public static function &handle($name) {
		try {
			$page = & self::factory($name);
			SA_Registry::get('__SA_APPLICATION__')->setCurrentPageHandler($page);
			$page->init();
			$page->run();
			$page->beforeDisplay();
			$page->display();
			$page->afterDisplay();
		} catch (Exception $e) {
			throw $e;		
		}
		
		return $page;
	}
	public static function &factory($name) {
		$page = null;
		$pageFile = SA_Registry::get('__SA_APPLICATION__')->getApplicationDirectory() . "/pages/{$name}.php";
		$className = 'Page_' . basename($name);
		if (file_exists($pageFile)) {
			require_once($pageFile);
			if (class_exists($className)) {
				$page = new $className($name);
				if (!is_a($page, 'SA_IPage')) {
					throw new SA_DoesNotImplementSAIPageException("{$className} does not implement <b>SA_IPage</b>");
				}
			} else {
				throw new Exception("The class <b>{$className}</b> was not found.");
			}
		} else {
			throw new Exception("The file <b>$pageFile</b> was not found.");
		}
		
		return $page;
	}	
}