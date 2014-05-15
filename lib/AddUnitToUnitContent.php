<?php

// include CheckUnitContentUnit.php, CheckUnitRevIsFixed.php, ExecSql.php, GetMaxUnitRevIdByUnitId.php, GetOperationTypeIdByName.php, GetTableIdByName.php, GetUnitRevByRevId.php, Globals.php,
//     Insert.php, InsertJournalItem.php, InsertOperationType.php, InsertTable.php, InsertUnitContentUnit.php, Print.php, Print2.php, UpdateUnitRevLevel.php

// Добавляет одну сборочную единицу в состав
function AddUnitToUnitContent2($Db, $UnitId, $UnitRevId, $ChildUnitId, $ChildUnitRevId, $Num, $UserId, $DebugLevel) {
	global $DEBUG_COMMENT;

	// ---- CheckParams ----

	if (!isset($UnitRevId) || $UnitRevId <= 0) {
		if (!isset($UnitId) || $UnitId <= 0) {
			PrintError2("Не указан параметр UnitRevId или UnitId", $DebugLevel);
			return false;
		}
		// -- GetMaxUnitRevId --
		if (!GetMaxUnitRevIdByUnitId3($Db, $UnitId, $DebugLevel, $UnitRevId) || $UnitRevId <= 0) {
			PrintError2("Ошибка при получении id ревизии сборочной единицы", $DebugLevel);
			return false;
		}
	}

	if (!isset($ChildUnitRevId) || $ChildUnitRevId <= 0) {
		if (!isset($ChildUnitId) || $ChildUnitId <= 0) {
			PrintError2("Не указан параметр ChildUnitRevId или ChildUnitId", $DebugLevel);
			return false;
		}
		// -- GetMaxUnitRevId --
		if (!GetMaxUnitRevIdByUnitId3($Db, $ChildUnitId, $DebugLevel, $ChildUnitRevId) || $ChildUnitRevId <= 0) {
			PrintError2("Ошибка при получении id ревизии сборочной единицы", $DebugLevel);
			return false;
		}
	}

	if (!isset($Num)) {
		PrintError2("Не указан параметр Num", $DebugLevel);
		return false;
	}

	if ($DebugLevel >= $DEBUG_COMMENT) {
		PrintInfo("Идентификатор ревизии сборной единицы unit_revision.id = " . $UnitRevId);
		PrintInfo("Идентификатор ревизии добавляемой сборной единицы unit_revision.id = " . $ChildUnitRevId);
		PrintInfo("Кол-во = " . $Num);
	}

	// ---- CheckUnitRevIsFixed ----

	if (!CheckUnitRevIsFixed($Db, $UnitRevId, $DebugLevel, $UnitRevLevel)) {
		return false;
	}

	// ---- GetUnitRevByRevId ----

	if (!GetUnitRevByRevId3($Db, $ChildUnitRevId, $DebugLevel, $ChildUnitId, $ChildRev, $ChildLevel, $ChildDateCreate, $ChildRevIsFixed)) {
		return false;
	}

	// ---- CheckUnitContentUnit ----

	if (!CheckUnitContentUnit($Db, $UnitRevId, $ChildUnitRevId, $DebugLevel)) {
		PrintError2("Запись уже существует. UnitId=".$UnitId." UnitRevId=".$UnitRevId." ChildUnitId=".$ChildUnitId." ChildUnitRevId=".$ChildUnitRevId, $DebugLevel);
		return false;
	}

	// ---- Put data to database ----

	if (!InsertUnitContentUnit2($Db, $UnitRevId, $ChildUnitRevId, $Num, $UserId, $DebugLevel, $Id) || $Id <= 0) {
		PrintError2("Запись не добавлена", $DebugLevel);
		return false;
	}

	PrintInfo2("Запись добавлена. unit_content_unit.id=".$Id, $DebugLevel);

	// -- Update level for container unit --

	if ($UnitRevLevel < $ChildLevel+1) {
		if (!UpdateUnitRevLevel($Db, $UnitRevId, $ChildLevel+1, $DebugLevel)) {
			return false;
		}
	}

	return true;
}
