<?php

include_once('../lib/Globals.php');
include_once('../lib/Print.php');


/**
 * Проверяет наличие ChildUnitRevId в контексте UnitRevId. Если сборочная единица не найдена, то возвращает true, иначе false.
 */
function CheckUnitContentUnit($Db, $UnitRevId, $ChildUnitRevId, $DebugLevel) {
	global $DEBUG_SQL;
	$Sql = "SELECT * FROM `unit_content_unit` WHERE `unit_revision_id`=".$UnitRevId." AND `value_unit_revision_id`=".$ChildUnitRevId;
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	$Res = $Db->query($Sql);
	if (!$Res) {
		return true;
	}
	$Row = $Res->fetch_assoc();
	if (!$Row) {
		$Res->free();
		return true;
	}
	$Res->free();
	return false;
}
