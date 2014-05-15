<?php

include_once('../lib/Globals.php');
include_once('../lib/Print.php');
include_once('../lib/Print2.php');


function InsertOperationType($Db, $OperationTypeName, $DebugLevel, &$OperationId) {
	global $DEBUG_SQL;
	$OperationTypeName_Sql = $Db->real_escape_string($OperationTypeName);
	$Sql = "INSERT INTO `operation_type` (`name`) VALUES ('".$OperationTypeName_Sql."')";
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	$Res = $Db->query($Sql);
	if (!$Res) {
		PrintError2('Ошибка добавления записи в operation_type ('.$Db->errno.') '.$Db->error.' Sql='.$Sql, $DebugLevel);
		return false;
	}
	$OperationId = $Db->insert_id;
	PrintComment2('Запись добавлена. operation_type.id='.$OperationId.' OperationTypeName='.$OperationTypeName, $DebugLevel);
	return true;
}
