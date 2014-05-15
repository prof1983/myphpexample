<?php

// Uses: <link rel="stylesheet" type="text/css" href="../css/main.css"/>
// Uses: include_once('../lib/Globals.php');

/**
 * Выводит комментарий.
 * $Message - сообщение об ошибке
 */
function PrintComment2($Message, $DebugLevel) {
	global $DEBUG_COMMENT;
	if ($DebugLevel < $DEBUG_COMMENT)
		return;
	print('<div class="logComment">'.$Message.'</div>');
	print("<script>console.log('".$Message."');</script>");
}

/**
 * Выводит сообщение об ошибке.
 * $Message - сообщение об ошибке
 */
function PrintError2($Message, $DebugLevel) {
	global $DEBUG_ERROR;
	if ($DebugLevel >= $DEBUG_ERROR)
		print('<div class="logError">'.$Message.'</div>');
	print("<script>console.error('".$Message."');</script>");
}

/**
 * Выводит сообщение с информацией.
 * $Message - сообщение
 */
function PrintInfo2($Message, $DebugLevel) {
	global $DEBUG_INFO;
	if ($DebugLevel >= $DEBUG_INFO)
		print('<div class="logInfo">'.$Message.'</div>');
	print("<script>console.info('".$Message."');</script>");
}

/**
 * Выводит сообщение с предупреждением.
 * $Message - сообщение
 */
function PrintWarning2($Message, $DebugLevel) {
	global $DEBUG_WARNING;
	if ($DebugLevel >= $DEBUG_WARNING)
		print('<div class="logWarning">'.$Message.'</div>');
	print("<script>console.warn('".$Message."');</script>");
}
