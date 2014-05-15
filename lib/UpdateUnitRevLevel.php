<?php

include_once('../lib/Globals.php');
include_once('../lib/Print.php');
include_once('../lib/Print2.php');


function UpdateUnitRevLevel($Db, $UnitRevId, $Level, $DebugLevel) {
	global $DEBUG_SQL;

	$Sql = "UPDATE `unit_revision` SET `level`=".$Level." WHERE `id`=".$UnitRevId;
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	$Res = $Db->query($Sql);
	if (!$Res) {
		PrintError2('Ошибка при обновлении unit_revision.level RevId='.$UnitRevId);
		return false;
	}
	return true;
}
