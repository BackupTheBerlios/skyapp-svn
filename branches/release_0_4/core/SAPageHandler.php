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

/*! \brief Class for handling the pages
 * 
 */

final class SAPageHandler {
	private static $pageObjectCache;
	private static $app;
	
	/**
	 * Sets the application controller
	 * @param SApplication $app The application controller instance
	 */
	
	public static function setApplicationObject(SApplication &$app) {
		self::$app = &$app;
	}
	
	/**
	 * Handles the page specified by $name
	 * @param string $name The page name
	 */
	
	public static function handle($name) {
		$page = null;
		try {
			$page = &self::tryToGetPage($name);
		} catch (Exception $e) {
			try {
				SALog::log($e->getMessage(), SA_LOG_WARNING);				
				$page = &self::tryToGetPage(self::$app->getErrorPageName());
			} catch (Exception $e) {
				SADebug::trace($e->getMessage(), __FILE__, __LINE__, __METHOD__);				
			}
		}
		if (is_null($page)) self::$app->resign("Could not create the page {$name}");
		self::$app->setCurrentPage($page);
		try {
			$page->runEvents();
			$page->display();
		} catch (Exception $e) {
			SADebug::trace($e->getMessage(), __FILE__, __LINE__, __METHOD__);
		}
	}
	
	/**
	 * Factory method for instantiating the page specified by $name
	 * @param string $name The page name
	 */
	
	public static function &factory($name) {
		if (is_a(self::$pageObjectCache[$name], 'SAIPage')) {
			return self::$pageObjectCache[$name];
		}
		$page = null;
		$pageFile = self::$app->getPageSearchDirectory() . "/{$name}.php";
		$className = 'Page_' . str_replace('/', '_', $name);
		if (file_exists($pageFile)) {
			require_once($pageFile);
			if (class_exists($className)) {
				$page = &new $className(self::$app, $name);
				if (!is_a($page, 'SAIPage')) {
					throw new DoesNotImplementSAIPageException("{$className} does not implement SAIPage");
				}
				self::$pageObjectCache[$name] = &$page;
			} else {
				throw new Exception("The class {$className} was not found.");
			}
		} else {
			throw new Exception("The file $pageFile was not found.");
		}
		
		return $page;
	}
	
	/**
	 * Calls the factory method and throws an exception if the page is not valid
	 * @param string $name The page name
	 */
	
	private static function &tryToGetPage($name) {
		$page = null;
		try {
			$page = &self::factory($name);
		} catch (DoesNotImplementSAIPageException $e) {
			SADebug::trace($e->getMessage(), __FILE__, __LINE__, __METHOD__);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
		return $page;
	}
}