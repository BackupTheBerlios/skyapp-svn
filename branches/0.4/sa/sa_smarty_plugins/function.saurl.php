<?php
function smarty_function_saurl($params, &$smarty) {
	$pageName = $params['page'];
	$port = ($params['port']) ? $params['port'] : 80;
	$secure = ($params['secure']) ? $params['secure'] : false;
	
	$events = $params['events'];
	if ($events) {	
		$app = $smarty->get_template_vars('app');
		$params[$app->getGPEventsName()] = $events;
	}
	
	unset($params['port']);
	unset($params['secure']);	
	unset($params['page']);
	unset($params['events']);
	return SA_Url::url($pageName, $params, $port, $secure);
}