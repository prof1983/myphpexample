<?php

include_once('../lib/Globals.php');
include_once('../lib/Print.php');
include_once('../lib/Print2.php');


function GetUnitByUnitId($Db, $UnitId, $DebugLevel, &$UnitName, &$UnitTypeId, &$UnitIsDel) {
	global $DEBUG_SQL;
	$Sql = "SELECT `name`, `unit_type_id`, `is_del` FROM `unit` WHERE `id`=".$UnitId;
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	$Res = $Db->query($Sql);
	if (!$Res) {
		PrintError2('Ошибка при получении наименования сб.единицы. UnitId='.$UnitId.' ('.$Db->errno.') '.$Db->error.' Sql='.$Sql, $DebugLevel);
		return false;
	}
	$Row = $Res->fetch_row();
	if (!$Row) {
		PrintError2('Не удалось получить наименование сб.единицы. UnitId='.$UnitId, $DebugLevel);
		return false;
	}
	$UnitName = $Row[0];
	$UnitTypeId = $Row[1];
	$UnitIsDel = $Row[2];
	$Res->free();
	return true;
}
