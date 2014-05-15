<?php

include_once('../lib/Globals.php');
include_once('../lib/Print.php');
include_once('../lib/Print2.php');


function GetUserIdByName($Db, $UserName, $DebugLevel, &$UserId) {
	global $DEBUG_SQL;
	$UserName_Sql = $Db->real_escape_string($UserName);
	$Sql = "SELECT `id` FROM `user` WHERE `name`='".$UserName_Sql."'";
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	$Res = $Db->query($Sql);
	if (!$Res) {
		PrintError2("Ошибка запроса идентификатора пользователя. UserName='".$UserName."' (".$Db->errno.") ".$Db->error." Sql=".$Sql, $DebugLevel);
		return false;
	}
	$Row = $Res->fetch_row();
	if (!$Row) {
		$Res->free();
		$UserId = 0;
		return false;
	}
	$UserId = $Row[0];
	$Res->free();
	return true;
}

function GetUserIdByName2($Db, $UserName, $DebugLevel, &$UserId, &$UserTyp) {
	global $DEBUG_SQL;
	$UserName_Sql = $Db->real_escape_string($UserName);
	$Sql = "SELECT `id`, `typ` FROM `user` WHERE `name`='".$UserName_Sql."'";
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	$Res = $Db->query($Sql);
	if (!$Res) {
		PrintError2("Ошибка запроса идентификатора пользователя. UserName='".$UserName."' (".$Db->errno.") ".$Db->error."' Sql=".$Sql, $DebugLevel);
		return false;
	}
	$Row = $Res->fetch_row();
	if (!$Row) {
		$Res->free();
		$UserId = 0;
		return false;
	}
	$UserId = $Row[0];
	$UserTyp = $Row[1];
	$Res->free();
	return true;
}
