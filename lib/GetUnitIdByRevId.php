<?php

include_once('../lib/Globals.php');
include_once('../lib/Print.php');
include_once('../lib/Print2.php');


function GetUnitIdByRevId($Db, $UnitRevId, $DebugLevel) {
	if (!GetUnitIdByRevId2($Db, $UnitRevId, $DebugLevel, $UnitId))
		return false;
	return $UnitId;
}

function GetUnitIdByRevId1($Db, $UnitRevId, $IsHtml, $DebugLevel) {
	if (!GetUnitIdByRevId2($Db, $UnitRevId, $DebugLevel, $UnitId))
		return false;
	return $UnitId;
}

function GetUnitIdByRevId2($Db, $UnitRevId, $DebugLevel, &$UnitId) {
	global $DEBUG_SQL;
	$Sql = "SELECT `unit_id` FROM `unit_revision` WHERE `id`=".$UnitRevId;
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	$Res = $Db->query($Sql);
	if (!$Res) {
		PrintError2('GetUnitIdByRevId1: Ошибка в запросе Sql='.$Sql, $DebugLevel);
		return false;
	}
	$Row = $Res->fetch_row();
	if (!$Row) {
		PrintError2('GetUnitIdByRevId1: Запрос вернул ноль строк Sql='.$Sql, $DebugLevel);
		return false;
	}
	$Res->free();
	$UnitId = $Row[0];
	return true;
}
