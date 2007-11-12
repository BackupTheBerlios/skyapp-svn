<?php
class Page_phpinfo extends SA_Page {
	public function init() {
		//$this->setLayout(null);
		$this->setTemplate(null);
		
		ob_start();
		phpinfo();
		$info = ob_get_contents();
		ob_end_clean();
		$info = '<p><a href="' . SA_Url::url('/default') . '">Back</a></p>' . $info;
		$this->setContents(CONTENT_FOR_LAYOUT, $info);		
	}
}