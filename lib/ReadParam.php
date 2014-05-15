<?php

/**
 * Получает значение или из GET или из POST.
 * $paramName - имя параметра
 * Возвращает полученное значение значение.
 */
function ReadParam($paramName) {
	$ParamValue = $_GET[$paramName];
	if (!isset($ParamValue)) {
		$ParamValue = $_POST[$paramName];
	}
	return $ParamValue;
}

/**
 * Получает значение или из GET или из POST.
 * $ParamName - имя параметра
 * Возвращает полученное значение значение (true или false).
 */
function ReadParamBool($ParamName) {
	$ParamValue = ReadParam($ParamName);
	if (!isset($ParamValue)) // $ParamValue == 'false' || $ParamValue = '0'
		return false;
	if ($ParamValue == 'true' || $ParamValue == '1')
		return true;
	return false;
}