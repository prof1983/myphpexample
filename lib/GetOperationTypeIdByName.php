<?php

include_once('../lib/Globals.php');
include_once('../lib/Print.php');
include_once('../lib/Print2.php');


function GetOperationTypeIdByName($Db, $OperationName, $DebugLevel, &$OperationId) {
	global $DEBUG_SQL;
	$OperationName_Sql = $Db->real_escape_string($OperationName);
	$Sql = "SELECT `id` FROM `operation_type` WHERE `name`='".$OperationName_Sql."'";
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	$Res = $Db->query($Sql);
	if (!$Res) {
		PrintError2("Ошибка запроса идентификатора операции. OperationTypeName='".$OperationName."' Sql=".$Sql, $DebugLevel);
		return false;
	}
	$Row = $Res->fetch_row();
	if (!$Row) {
		$Res->free();
		$OperationId = 0;
		return true;
	}
	$OperationId = $Row[0];
	$Res->free();
	return true;
}
