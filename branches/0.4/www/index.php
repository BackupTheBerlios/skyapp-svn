<?php
ob_start();

require_once('../sa.php');

try {
	SA_Dispatcher::dispatch()->run();
} catch (SA_ApplicationNotFoundException $e) {
	print $e->getMessage();
} catch (Exception $e) {
	print $e->getMessage();
}