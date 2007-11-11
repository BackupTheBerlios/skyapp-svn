<?php
class SA_Registry {
	protected static $registry = array();
	
	private function __construct() {}
	
	public static function set($name, &$value) {
		self::$registry[$name] = $value;
	}
	
	public static function &get($name) {
		return self::$registry[$name];
	}
}