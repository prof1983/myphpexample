<?php

include_once('../lib/Globals.php');
include_once('../lib/Print.php');
include_once('../lib/Print2.php');


function GetUnitRevByRevId($Db, $UnitRevId, $IsHtml, $DebugLevel, &$Rev, &$Level, &$RevIsFixed, &$DateCreate) {
	return GetUnitRevByRevId3($Db, $UnitRevId, $DebugLevel, $UnitId, $Rev, $Level, $DateCreate, $RevIsFixed);
}

function GetUnitRevByRevId2($Db, $UnitRevId, $DebugLevel, &$Rev, &$Level, &$RevIsFixed, &$DateCreate) {
	return GetUnitRevByRevId3($Db, $UnitRevId, $DebugLevel, $UnitId, $Rev, $Level, $RevIsFixed, $DateCreate);
}

function GetUnitRevByRevId3($Db, $UnitRevId, $DebugLevel, &$UnitId, &$Rev, &$Level, &$DateCreate, &$RevIsFixed) {
	global $DEBUG_SQL;
	$Sql = "SELECT `unit_id`, `revision`, `level`, `date_create`, `is_fixed`+0 FROM `unit_revision` WHERE `id`=".$UnitRevId;
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	$Res = $Db->query($Sql);
	if (!$Res) {
		PrintError2("Error in GetUnitRevByRevId()", $DebugLevel);
		return false;
	}
	$Row = $Res->fetch_row();
	if (!$Row) {
		PrintError2("Нет ревизии UnitRevId=".$UnitRevId, $DebugLevel);
		$Res->close();
		return false;
	}
	$UnitId = $Row[0];
	$Rev = $Row[1];
	$Level = $Row[2];
	$DateCreate = $Row[3];
	$RevIsFixed = $Row[4];
	$Res->close();
	return true;
}
