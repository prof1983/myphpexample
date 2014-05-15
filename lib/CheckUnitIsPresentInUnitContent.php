<?php

include_once('../lib/Globals.php');
include_once('../lib/Print.php');
include_once('../lib/Print2.php');


/**
 * Проверяет на зацикливание вложенностей unit'ов друг в друга
 */
function CheckUnitIsPresentInUnitContent_ByUnitRevId($Db, $UnitRevId, $UnitId, $Iter, $DebugLevel) {
	global $DEBUG_SQL;
	$Sql = "SELECT
			`ucu`.`value_unit_revision_id` AS `ucu_value`,
			`u`.`id` AS `unit_id`
		FROM
			`unit_content_unit` AS `ucu`,
			`unit_revision` AS `ur`,
			`unit` AS `u`
		WHERE
			`ur`.`id`=`ucu`.`value_unit_revision_id`
		AND
			`u`.`id`=`ur`.`unit_id`
		AND
			`unit_revision_id`=".$UnitRevId;

	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	$Res = $Db->query($Sql);
	if (!$Res) {
		PrintError2("Проверка на зацикливание: Ошибка при запросе Sql=".$Sql, $DebugLevel);
		return false;
	}
	$Row = $Res->fetch_row();
	while ($Row != false) {
		$ValueUnitRevId = $Row[0];
		$ValueUnitId = $Row[1];

		if ($ValueUnitId == $UnitId) {
			PrintWarning2("Проверка на зацикливание: Сборочная единица UnitRevId=".$UnitRevId." сорержит в себе сборочную единицу UnitId=".$UnitId, $DebugLevel);
			$Res->free();
			return false;
		}

		if ($Iter <= 0) {
			PrintError2("При проверке на зацикливание сборочных единиц произошло зацикливание программы", $DebugLevel);
			return false;
		}

		// Проверяем вложенную сборочную единицу
		if (!CheckUnitIsPresentInUnitContent_ByUnitRevId($Db, $ValueUnitRevId, $UnitId, $Iter-1, $DebugLevel)) {
			$Res->free();
			return false;
		}

		$Row = $Res->fetch_row();
	}
	$Res->free();
	return true;
}
