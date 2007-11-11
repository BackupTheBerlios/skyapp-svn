<?php
class SA_Dispatcher {	
	private function __construct() {		
	}
	
	public function dispatch($appDirectory = null) {
		$appDirectory = (is_null($appDirectory)) ? SA_HOME_DIR . '/app/' : $appDirectory;
		$appFile = $appDirectory . '/Application.php';
		$app = null;
		if (file_exists($appFile)) {
			require_once($appFile);
			$app = new Application();
			SA_Registry::set('__SA_APPLICATION__', $app);
			$app->setApplicationDirectory($appDirectory);
			$app->init();
		} else {
			throw new SA_ApplicationNotFoundException('Application not found in <b>' . $appDirectory . '</b>.');
		}
		return $app;
	}
}
