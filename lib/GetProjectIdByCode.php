<?php

include_once('../lib/Globals.php');
include_once('../lib/Print.php');

function GetProjectIdByCode($Db, $ProjectCode, $DebugLevel, &$ProjectId) {
	global $DEBUG_SQL;
	$Sql = 'SELECT `id` FROM `project` WHERE `code`='.$ProjectCode;
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	$Res = $Db->query($Sql);
	if (!$Res)
		return false;
	$Row = $Res->fetch_row();
	if (!$Row) {
		$Res->free();
		return false;
	}
	$ProjectId = $Row[0];
	$Res->free();
	return true;
}
