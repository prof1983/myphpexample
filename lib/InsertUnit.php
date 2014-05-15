<?php

include_once('../lib/Globals.php');
include_once('../lib/Insert.php');
include_once('../lib/Print.php');
include_once('../lib/Print2.php');


// Создает новую сборочную единицу (is_del=0)
function InsertUnit2($Db, $UnitName, $UnitTypeId, $UserId, $DebugLevel, &$NewUnitId) {
	global $DEBUG_SQL;

	$UnitName = $Db->real_escape_string($UnitName);
	$Sql = "INSERT INTO `unit` (`name`, `unit_type_id`) VALUES ('".$UnitName."', ".$UnitTypeId.")";
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	if (!Insert($Db, 'unit', $UserId, $Sql, $DebugLevel, $NewUnitId))
		return false;
	PrintComment2("Запись добавлена. unit.id=".$NewUnitId, $DebugLevel);
	return true;
}

// Создает новую сборочную единицу (is_del=1)
function InsertUnit_Begin2($Db, $UnitName, $UnitTypeId, $UserId, $DebugLevel, &$NewUnitId) {
	global $DEBUG_SQL;

	$UnitName = $Db->real_escape_string($UnitName);
	$Sql = "INSERT INTO `unit` (`name`, `unit_type_id`, `is_del`) VALUES ('".$UnitName."', ".$UnitTypeId.", 1)";
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	if (!Insert($Db, 'unit', $UserId, $Sql, $DebugLevel, $NewUnitId))
		return false;
	PrintComment2("Запись добавлена. unit.id=".$NewUnitId." (is_del=1)", $DebugLevel);
	return true;
}

// Меняет флаг is_del у созданной сборочной единицы
function InsertUnit_Ok($Db, $NewUnitId, $DebugLevel) {
	global $DEBUG_SQL;

	$UnitName = $Db->real_escape_string($UnitName);
	$Sql = "UPDATE `unit` SET `is_del`=0 WHERE `id`=".$NewUnitId;
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	$Res = $Db->query($Sql);
	if (!$Res) {
		PrintError2("Ошибка изменения записи в unit (" . $Db->errno . ") " . $Db->error, $DebugLevel);
		return false;
	}
	PrintComment2("Запись добавлена. unit.id=".$NewUnitId." (is_del=0)", $DebugLevel);
	return true;
}
