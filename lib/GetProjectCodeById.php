<?php

include_once('../lib/Globals.php');
include_once('../lib/Print.php');


function GetProjectCodeById($Db, $ProjectId, $DebugLevel, &$ProjectCode) {
	global $DEBUG_SQL;
	$Sql = 'SELECT `code` FROM `project` WHERE `id`='.$ProjectId;
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
	$ProjectCode = $Row[0];
	$Res->free();
	return true;
}
