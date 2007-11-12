<?php
class Page_default extends SA_Page {
	public function doInit() {
		print 'Executing init... ';
	}
	
	public function doUpdate() {
		print 'update.';
	}
	
	public function afterDisplay() {
		var_dump($this->app->GP());
	}	
}
