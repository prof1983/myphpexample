<?php

include_once('../lib/Globals.php');
include_once('../lib/Print.php');
include_once('../lib/Print2.php');


function InsertTable($Db, $TableName, $DebugLevel, &$TableId) {
	global $DEBUG_SQL;
	$TableName_Sql = $Db->real_escape_string($TableName);
	$Sql = "INSERT INTO `table` (`name`) VALUES ('".$TableName_Sql."')";
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	$Res = $Db->query($Sql);
	if (!$Res) {
		PrintError2('Ошибка добавления записи в table ('.$Db->errno.') '.$Db->error.' Sql='.$Sql, $DebugLevel);
		return false;
	}
	$TableId = $Db->insert_id;
	PrintComment2('Запись добавлена. table.id='.$TableId.' TableName='.$TableName, $DebugLevel);
	return true;
}
