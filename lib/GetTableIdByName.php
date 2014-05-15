<?php

include_once('../lib/Globals.php');
include_once('../lib/Print.php');
include_once('../lib/Print2.php');


function GetTableIdByName($Db, $TableName, $DebugLevel, &$TableId) {
	global $DEBUG_SQL;
	$Sql = "SELECT `id` FROM `table` WHERE `name`='".$TableName."'";
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	$Res = $Db->query($Sql);
	if (!$Res) {
		PrintError2('Ошибка запроса идентификатора таблицы ('.$Db->errno.') '.$Db->error.' Sql='.$Sql, $DebugLevel);
		$Res->free();
		return false;
	}
	$Row = $Res->fetch_row();
	if (!$Row) {
		$Res->free();
		$UserId = 0;
		return true;
	}
	$TableId = $Row[0];
	$Res->free();
	return true;
}
