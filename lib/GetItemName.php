<?php

// include Globals.php, Print.php, Print2.php

function GetItemName($Db, $ItemId, $DebugLevel, &$ItemName) {
	global $DEBUG_SQL;
	$Sql = "SELECT `name` FROM `item` WHERE `id`=".$ItemId;
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	$Res = $Db->query($Sql);
	if (!$Res) {
		PrintError2("Ошибка запроса наименования элемента. Sql=".$Sql, $DebugLevel);
		return false;
	}
	$Row = $Res->fetch_row();
	if (!$Row) {
		$Res->free();
		return false;
	}
	$ItemName = $Row[0];
	$Res->free();
	return true;
}
