<?php

include_once('../lib/Globals.php');
include_once('../lib/Print.php');
include_once('../lib/Print2.php');


function GetClientNameById($Db, $ClientId, $DebugLevel, &$ClientName) {
	global $DEBUG_SQL;
	$Sql = "SELECT `name` FROM `client` WHERE `id`=".$ClientId;
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	$Res = $Db->query($Sql);
	if (!$Res) {
		PrintError2("Error in GetClientNameById(). Sql=".$Sql, $DebugLevel);
		return false;
	}
	$Row = $Res->fetch_row();
	if (!$Row) {
		PrintError2("Нет заказчика ClientId=".$ClientId, $DebugLevel);
		$Res->close();
		return false;
	}
	$ClientName = $Row[0];
	$Res->close();
	return true;
}
