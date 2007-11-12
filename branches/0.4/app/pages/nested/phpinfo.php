<?php
class Page_phpinfo extends SA_Page {
	public function init() {
		$this->setLayout(null);
		$this->setTemplate(null);
	}
	
	public function display() {
		phpinfo();	
	}
}