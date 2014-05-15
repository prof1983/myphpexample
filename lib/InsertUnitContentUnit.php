<?php

include_once('../lib/ExecSql.php');
include_once('../lib/Globals.php');
include_once('../lib/Print.php');


function InsertUnitContentUnit2($Db, $UnitRevId, $ChildUnitRevId, $Num, $UserId, $DebugLevel, &$Id) {
	global $DEBUG_SQL;
	$Sql = "INSERT INTO `unit_content_unit` (`unit_revision_id`, `value_unit_revision_id`, `number`) VALUES (".$UnitRevId.", ".$ChildUnitRevId.", ".$Num.")";
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	if (!Insert($Db, 'unit_content_unit', $UserId, $Sql, $DebugLevel, $Id))
		return false;
	return true;
}

function InsertUnitContentUnit3($Db, $UnitRevId, $ChildUnitRevId, $Num, $UserId, $OperationName, $DebugLevel, &$Id) {
	global $DEBUG_SQL;
	$Sql = "INSERT INTO `unit_content_unit` (`unit_revision_id`, `value_unit_revision_id`, `number`) VALUES (".$UnitRevId.", ".$ChildUnitRevId.", ".$Num.")";
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	if (!ExecSql($Db, $Sql, $OperationName, 'unit_content_unit', $UserId, $DebugLevel, $Id))
		return false;
	return true;
}
