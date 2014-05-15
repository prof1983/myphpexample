<?php


include_once('../lib/GetMaxUnitRevIdByUnitId.php');
include_once('../lib/GetUnitRevByRevId.php');
include_once('../lib/Globals.php');
include_once('../lib/Print.php');
include_once('../lib/Print2.php');


function FixUnitRevision($Db, $UnitId, $UnitRevId, $DebugLevel) {
	if (!FixUnitRevision_Check($UnitId, $UnitRevId, $DebugLevel))
		return false;
	if (!FixUnitRevision_Work($Db, $UnitId, $UnitRevId, $DebugLevel))
		return false;
	return true;
}

function FixUnitRevision_Check($UnitId, $UnitRevId, $DebugLevel) {
	$UnitId = (int)$UnitId;
	$UnitRevId = (int)$UnitRevId;

	if (!isset($UnitId))
		$UnitId = 0;

	if (!isset($UnitRevId))
		$UnitRevId = 0;

	if (($UnitId <= 0) && ($UnitRevId <= 0)) {
		PrintError2("Не указан ни параметр UnitId, ни UnitRevId.", $DebugLevel);
		return false;
	}

	return true;
}

function FixUnitRevision_Work($Db, $UnitId, $UnitRevId, $DebugLevel) {
	global $DEBUG_SQL;

	// ---- Получим номер последней ревизии ----

	if ($UnitRevId <= 0) {
		$UnitRevId = GetMaxUnitRevIdByUnitId($Db, $UnitId, $DebugLevel);
		if (!$UnitRevId) {
			PrintError2("Ревизия не найдена", $DebugLevel);
			return false;
		}
		if ($UnitRevId <= 0) {
			PrintError2("Ревизия не найдена", $DebugLevel);
			return false;
		}
		PrintComment2("MaxUnitRevId(UnitId=".$UnitId.")=".$UnitRevId, $DebugLevel);
	}

	// ---- Проверим закрыта ли ревизия ----

	$Rev = 0;
	$RevLevel = 0;
	$RevIsFixed = 0;
	$RevDateCreate = 0;

	if (!GetUnitRevByRevId($Db, $UnitRevId, true, $DebugLevel, $Rev, $RevLevel, $RevIsFixed, $RevDateCreate)) {
		PrintError2("Ошибка при запросе данных из GetUnitRevByRevId()", $DebugLevel);
		return false;
	}
	if ($Rev < 0) $Rev = 0;
	PrintComment2("UnitId=".$UnitId." RevId=".$UnitRevId." Rev=".$Rev." Level=".$RevLevel." RevIsFixed=".$RevIsFixed." DateCreate=".$RevDateCreate, $DebugLevel);
	if ($RevIsFixed == 1) {
		PrintWarning2("Ревизия ".$Rev." у UnitId=".$UnitId." уже закрыта (RevId=".$UnitRevId.")", $DebugLevel);
		return false;
	}

	// ---- Фиксируем запись unit_revision ----

	$Sql = "UPDATE `unit_revision` SET `is_fixed`=1 WHERE `id`=".$UnitRevId;
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	$Res = $Db->query($Sql);
	if (!$Res) {
		PrintError2("Произошла ошибка при фиксировании offer_revision. errno=".$Db->errno." err=".$Db->error." sql=".$Sql, $DebugLevel);
		return false;
	}

	return true;
}
