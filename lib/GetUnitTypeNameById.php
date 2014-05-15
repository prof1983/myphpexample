<?php

include_once('../lib/Globals.php');
include_once('../lib/Print.php');


function GetUnitTypeNameById($Db, $UnitTypeId, $IsHtml, $DebugLevel) {
	global $DEBUG_ERROR, $DEBUG_SQL;
	$Sql = "SELECT `name` FROM `unit_type` WHERE `id`=".$UnitTypeId;
	if (!$IsHtml && $DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	$Res = $Db->query($Sql);
	if (!$Res) {
		if (!$IsHtml && $DebugLevel >= $DEBUG_ERROR)
			PrintError('Ошибка при запросе UnitType. Sql='.$Sql);
		return false;
	}
	$Row = $Res->fetch_row();
	if (!$Row) {
		if (!$IsHtml && $DebugLevel >= $DEBUG_ERROR)
			PrintError('Не найден UnitType id='.$UnitTypeId);
		return false;
	}
	return $Row[0];
}