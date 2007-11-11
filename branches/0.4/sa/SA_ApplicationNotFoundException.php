<?php
class SA_ApplicationNotFoundException extends Exception {
	public function __construct($message = null) {
		$message = (is_null($message)) ? 'Application not found' : $message;
		parent::__construct($message);
	}	
}
