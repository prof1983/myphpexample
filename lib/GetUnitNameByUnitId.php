<?php

include_once('../lib/Globals.php');
include_once('../lib/Print.php');
include_once('../lib/Print2.php');


function GetUnitNameByUnitId($Db, $UnitId, $DebugLevel, &$UnitName) {
	global $DEBUG_SQL;
	$Sql = "SELECT `name` FROM `unit` WHERE `id`=".$UnitId;
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment();
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
	$res->free();
	return true;
}
