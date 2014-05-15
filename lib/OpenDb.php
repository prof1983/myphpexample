<?php

include_once('../lib/Globals.php');
include_once('../lib/Print.php');


/**
 * Открывает БД.
 * Возвращает объект mysqli или FALSE.
 */
function OpenDb($dbHostName, $dbUserName, $dbUserPassword, $dbName, $debugLevel) {
	return OpenDb1($dbHostName, $dbUserName, $dbUserPassword, $dbName, true, $debugLevel);
}

/**
 * Открывает БД.
 * Возвращает объект mysqli или false.
 */
function OpenDb1($DbHostName, $DbUserName, $DbUserPassword, $DbName, $IsHtml, $DebugLevel) {
	global $DEBUG_NONE, $DEBUG_ERROR, $DEBUG_WARNING, $DEBUG_INFO, $DEBUG_COMMENT;

	if ($IsHtml && $debugLevel >= $DEBUG_COMMENT)
		PrintComment("Подключаемся к базе данных");
	$Db = new mysqli($DbHostName, $DbUserName, $DbUserPassword, $DbName);
	if ($Db->connect_errno) {
		if ($IsHtml && $DebugLevel >= $DEBUG_ERROR)
			PrintError("Не удалось подключиться к MySql: (".$Db->connect_errno.") ".$Db->connect_error);
		return false;
	}
	if ($IsHtml && $DebugLevel >= $DEBUG_COMMENT)
		PrintComment("Успешно присоединились к базе данных");

	// ---- Charset ----

	if ($IsHtml && $DebugLevel >= $DEBUG_COMMENT)
		PrintComment("Изменяем набор символов на utf8");
	if (!$Db->set_charset("utf8")) {
		if ($IsHtml && $DebugLevel >= $DEBUG_ERROR)
			PrintError(sprintf("Ошибка при загрузке набора символов utf8: (%d) %s", $Db->errno, $Db->error));
		$Db->close();
		return false;
	} else {
		if ($IsHtml && $DebugLevel >= $DEBUG_COMMENT)
			PrintComment(sprintf("Текущий набор символов: %s", $Db->character_set_name()));
	}
	return $Db;
}

/**
 * Открывает БД.
 * Возвращает объект mysqli или FALSE.
 */
function OpenDb2($dbHostName, $dbUserName, $dbUserPassword, $dbName, $ReturnContentType, $debugLevel) {
	return OpenDb1($dbHostName, $dbUserName, $dbUserPassword, $dbName, ($ReturnContentType == 'html'), $debugLevel);
}
