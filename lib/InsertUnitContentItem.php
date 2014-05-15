<?php

include_once('../lib/ExecSql.php');
include_once('../lib/Insert.php');
include_once('../lib/Print.php');


function InsertUnitContentItem($Db, $UnitRevId, $ItemId, $ItemNum, $UserId, $DebugLevel, &$Id) {
	global $DEBUG_SQL;
	$Sql = "INSERT INTO `unit_content_item` (`unit_revision_id`, `item_id`, `number`) VALUES (".$UnitRevId.", ".$ItemId.", ".$ItemNum.")";
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	if (!Insert($Db, 'unit_content_unit', $UserId, $Sql, $DebugLevel, $Id))
		return false;
	return true;
}

function InsertUnitContentItem2($Db, $UnitRevId, $ItemId, $ItemNum, $UserId, $OperationName, $DebugLevel, &$Id) {
	global $DEBUG_SQL;
	$Sql = "INSERT INTO `unit_content_item` (`unit_revision_id`, `item_id`, `number`) VALUES (".$UnitRevId.", ".$ItemId.", ".$ItemNum.")";
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	if (!ExecSql($Db, $Sql, $OperationName, 'unit_content_unit', $UserId, $DebugLevel, $Id))
		return false;
	return true;
}
