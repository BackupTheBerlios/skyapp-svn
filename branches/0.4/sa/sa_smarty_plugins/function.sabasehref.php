<?php
function smarty_function_sabasehref($params, &$smarty) {
	return SA_Url::baseHref();
}