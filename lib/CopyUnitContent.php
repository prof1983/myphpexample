<?php

include_once('../lib/CheckUnitIsPresentInUnitContent.php');
include_once('../lib/GetMaxUnitRevIdByUnitRevId.php');
include_once('../lib/Globals.php');
include_once('../lib/InsertUnitContentItem.php');
include_once('../lib/InsertUnitContentUnit.php');
include_once('../lib/Print.php');
include_once('../lib/Print2.php');


function CopyUnitContent2($Db, $UnitRevId, $NewUnitRevId, $UnitId, $UserId, $DebugLevel, &$MaxRevLevel) {
	return CopyUnitContent3($Db, $UnitRevId, $NewUnitRevId, $UnitId, true, $UserId, $DebugLevel, $MaxRevLevel);
}

function CopyUnitContent3($Db, $UnitRevId, $NewUnitRevId, $UnitId, $UpUnitRevToMax, $UserId, $DebugLevel, &$MaxRevLevel, &$IsLoop) {
	if (!CopyUnitContentItem($Db, $UnitRevId, $NewUnitRevId, $UserId, $DebugLevel))
		return false;
	if (!CopyUnitContentUnit2($Db, $UnitRevId, $NewUnitRevId, $UnitId, $UserId, $UpUnitRevToMax, $DebugLevel, $MaxRevLevel, $IsLoop))
		return false;
	return true;
}

function CopyUnitContentItem($Db, $UnitRevId, $NewUnitRevId, $UserId, $DebugLevel) {
	global $DEBUG_SQL;

	$Sql = "SELECT `id`, `item_id`, `number` FROM `unit_content_item` WHERE `unit_revision_id`=".$UnitRevId;
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	$Res = $Db->query($Sql);
	if (!$Res) {
		PrintError2("Произошла ошибка чтения из unit_content_item (".$Db->errno.") ".$Db->error, $DebugLevel);
		return false;
	}

	$Row = $Res->fetch_row();
	while ($Row != false) {
		$ItemId = $Row[1];
		$ItemNum = $Row[2];
		PrintComment2('-- Id='.$Row[0].', ItemId='.$ItemId.', ItemNumber='.$ItemNum.' --', $DebugLevel);
		InsertUnitContentItem2($Db, $NewUnitRevId, $ItemId, $ItemNum, $UserId, 'copy', $DebugLevel, $Id);
		$Row = $Res->fetch_row();
	}
	$Res->free();

	return true;
}

// $UpToMax - true: обновлять сб.единицу до доследней ревизии
function CopyUnitContentUnit($Db, $UnitRevId, $NewUnitRevId, $UnitId, $UserId, $UpToMax, $DebugLevel, &$MaxRevLevel) {

}

// $UpToMax - true: обновлять сб.единицу до доследней ревизии
function CopyUnitContentUnit2($Db, $UnitRevId, $NewUnitRevId, $UnitId, $UserId, $UpToMax, $DebugLevel, &$MaxRevLevel, &$IsLoop) {
	global $DEBUG_SQL;

	$MaxRevLevel = 0;

	$Sql = "SELECT
			`ucu`.`id` AS `ucu_id`,
			`ucu`.`value_unit_revision_id` AS `ucu_unit_rev_id`,
			`ucu`.`number` AS `num`,
			`ur`.`level` AS `ur_level`
		FROM
			`unit_content_unit` AS `ucu`,
			`unit_revision` AS `ur`
		WHERE
			`ur`.`id`=`ucu`.`value_unit_revision_id`
		AND
			`ucu`.`unit_revision_id`=".$UnitRevId;
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	$Res = $Db->query($Sql);
	if (!$Res) {
		PrintError2("Произошла ошибка чтения из unit_content_unit (".$Db->errno.") ".$Db->error, $DebugLevel);
		return false;
	}

	$IsLoop = false;
	PrintComment2("----", $DebugLevel);
	// ---- Перебираем все вложенные сборочные единицы по порядку ----
	$Row = $Res->fetch_row();
	while ($Row != false) {
		$cuId = $Row[0];
		$cuUnitRevId = $Row[1];
		$cuNumber = $Row[2];
		$cuLevel = $Row[3];
		PrintComment2("-- Id=".$cuId." UnitRevisionId=".$cuUnitRevId." Number=".$cuNumber." Level=".$cuLevel." --", $DebugLevel);

		// -- Получаем идентификатор ревизии сб.единицы, которую будем добавлять --
		if ($UpToMax) {
			// Получаем идентификатор максимальной ревизии (LastUnitRevId)
			GetMaxUnitRevIdByUnitRevId($Db, $cuUnitRevId, $DebugLevel, $cuUnitId, $LastUnitRevId);
		} else {
			// Оставляем без изменений
			$LastUnitRevId = $cuUnitRevId;
		}

		// TODO: Сделать проверку на зацикливание вложенностей unit'ов друг в друга
		$IsOk = CheckUnitIsPresentInUnitContent_ByUnitRevId($Db, $LastUnitRevId, $UnitId, 99, $DebugLevel);

		if ($IsOk) {
			if ($MaxRevLevel < $cuLevel)
				$MaxRevLevel = $cuLevel;
			if ($UpToMax)
				InsertUnitContentUnit2($Db, $NewUnitRevId, $LastUnitRevId, $cuNumber, $UserId, $DebugLevel, $Id);
			else
				InsertUnitContentUnit3($Db, $NewUnitRevId, $LastUnitRevId, $cuNumber, $UserId, 'copy', $DebugLevel, $Id);
		} else {
			PrintWarning2("Зацикливание. Сборочная единица UnitRevId=".$LastUnitRevId." имеет в своем составе данную сборочную единицу UnitId=".$UnitId, $DebugLevel);
			$IsLoop = true;
		}

		$Row = $Res->fetch_row();
	}
	$Res->free();
	PrintComment2("----", $DebugLevel);
	return true;
}
