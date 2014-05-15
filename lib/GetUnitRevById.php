<?php

include_once('../lib/Globals.php');
include_once('../lib/Print.php');
include_once('../lib/Print2.php');


function GetUnitRevByUnitIdAndRev($Db, $UnitId, $Rev, $IsHtml, $DebugLevel, &$RevId, &$Level, &$RevIsFixed, &$DateCreate) {
	global $DEBUG_SQL;
	$Sql = "SELECT `id`, `level`, `is_fixed`+0, `date_create` FROM `unit_revision` WHERE `unit_id`=".$UnitId." AND `revision`=".$Rev;
	if ($IsHtml && $DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	$Res = $Db->query($Sql);
	if (!$Res) {
		if ($IsHtml && $DebugLevel >= $DEBUG_ERROR)
			PrintError("Error in GetUnitRevByUnitIdAndRev()");
		return false;
	}
	$Row = $Res->fetch_row();
	if (!$Row) {
		if ($IsHtml && $DebugLevel >= $DEBUG_ERROR)
			PrintError("Нет ревизии для UnitId=".$UnitId." Rev=".$Rev);
		$Res->close();
		return false;
	}
	$RevId = $Row[0];
	$Level = $Row[1];
	$RevIsFixed = $Row[2];
	$DateCreate = $Row[3];
	$Res->close();
	return true;
}
