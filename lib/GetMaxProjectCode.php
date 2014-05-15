<?php

include_once('../lib/Globals.php');
include_once('../lib/Print.php');
include_once('../lib/Print2.php');


function GetMaxProjectCode($Db, &$ProjectCode) {
	global $DEBUG_SQL;
	$Sql = "SELECT MAX(`code`) FROM `project`";
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	$Res = $Db->query($Sql);
	if (!$Res) {
		PrintError2('Ошибка запроса максимального кода проекта. Sql='.$Sql, $DebugLevel);
		return false;
	}
	$Row = $Res->fetch_row();
	if (!$Row) {
		$Res->free();
		return false;
	}
	$ProjectCode = $Row[0];
	$Res->free();
	return true;
}
