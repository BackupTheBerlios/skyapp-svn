<?php
class SA_DoesNotImplementSAIPageException extends Exception {
	public function __construct($message) {
		parent::__construct($message);
	}	
}
