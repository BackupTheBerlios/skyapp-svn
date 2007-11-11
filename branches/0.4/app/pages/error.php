<?php
class Page_error extends SA_Page {
	public function display() {
		print 'error: ' . $this->_tpl_vars['error']->getMessage();
	}
}