<?php

include_once('../lib/Globals.php');
include_once('../lib/Print.php');


function GetMaxUnitRevIdByUnitId($Db, $UnitId, $DebugLevel) {
	return GetMaxUnitRevIdByUnitId1($Db, $UnitId, true, $DebugLevel);
}

function GetMaxUnitRevIdByUnitId1($Db, $UnitId, $IsHtml, $DebugLevel) {
	if (GetMaxUnitRevIdByUnitId3($Db, $UnitId, $DebugLevel, $UnitRevId))
		return $UnitRevId;
	else
		return false;
}

function GetMaxUnitRevIdByUnitId2($Db, $UnitId, $ReturnContentType, $DebugLevel) {
	return GetMaxUnitRevIdByUnitId1($Db, $UnitId, ($ReturnContentType == 'html'), $DebugLevel);
}

function GetMaxUnitRevIdByUnitId3($Db, $UnitId, $DebugLevel, &$UnitRevId) {
	global $DEBUG_SQL;
	$Sql = "SELECT MAX(`id`) FROM `unit_revision` WHERE `unit_id`=".$UnitId;
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	$Res = $Db->query($Sql);
	if (!$Res) {
		return false;
	}
	$Row = $Res->fetch_row();
	if (!$Row) {
		return false;
	}
	$Res->free();
	if (!isset($Row[0]))
		return false;
	$UnitRevId = $Row[0];
	return true;
}
