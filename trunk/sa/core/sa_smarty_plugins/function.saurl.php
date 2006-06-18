<?php
/*
+-----------------------------------------------------------------------+
| SkyApp - The PHP Application Framework.                               |
| http://www.skyweb.ro/                                                 |
+-----------------------------------------------------------------------+
| This source file is released under LGPL license, available through    |
| the world wide web at http://www.gnu.org/copyleft/lesser.html.        |
| This library is distributed WITHOUT ANY WARRANTY. Please see the LGPL |
| for more details.                                                     |
+-----------------------------------------------------------------------+
| Authors: Andi Trînculescu <andi@skyweb.ro>                            |
+-----------------------------------------------------------------------+

$Id$
*/

/*! \brief Smarty plugin for creating valid SA URLs within the template
 * 
 */

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
	return SAUrl::url($pageName, $params, $port, $secure);
}