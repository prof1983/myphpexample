<?php

include_once('../lib/Globals.php');
include_once('../lib/Insert.php');
include_once('../lib/Print.php');
include_once('../lib/Print2.php');


// Создает новую ревизию (is_del=0)
function InsertUnitRev2($Db, $UnitId, $NewRev, $RevLevel, $UserId, $DebugLevel, &$NewUnitRevId) {
	global $DEBUG_SQL;

	$Sql = "INSERT INTO `unit_revision` (`unit_id`, `revision`, `level`) VALUES (".$UnitId.", ".$NewRev.", ".$RevLevel.")";
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	if (!Insert($Db, 'unit_revision', $UserId, $Sql, $DebugLevel, $NewUnitRevId))
		return false;
	PrintComment2("Запись добавлена. unit_revision.id=".$NewUnitRevId, $DebugLevel);

	return true;
}

// Создает новую ревизию сборочной единицы (is_del=1)
function InsertUnitRev_Begin2($Db, $UnitId, $NewRev, $RevLevel, $UserId, $DebugLevel, &$NewUnitRevId) {
	global $DEBUG_SQL;
	$Sql = "INSERT INTO `unit_revision` (`unit_id`, `revision`, `level`, `is_del`) VALUES (".$UnitId.", ".$NewRev.", ".$RevLevel.", 1)";
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	if (!Insert($Db, 'unit_revision', $UserId, $Sql, $DebugLevel, $NewUnitRevId))
		return false;
	PrintComment2("Запись добавлена. unit_revision.id=".$NewUnitRevId." (is_del=1)", $DebugLevel);
	return true;
}

// Меняет флаг is_del=0 у созданной ревизии сборочной единицы
function InsertUnitRev_Ok($Db, $NewUnitRevId, $DebugLevel) {
	global $DEBUG_SQL;
	$Sql = "UPDATE `unit_revision` SET `is_del`=0 WHERE `id`=".$NewUnitRevId;
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	$Res = $Db->query($Sql);
	if (!$Res) {
		PrintError2("Ошибка изменения записи в unit_revision (" . $Db->errno . ") " . $Db->error, $DebugLevel);
		return false;
	}
	$NewUnitRevId = $Db->insert_id;
	PrintComment2("Запись добавлена. unit_revision.id=".$NewUnitRevId." (is_del=0)", $DebugLevel);
	return true;
}
