<?php

// include ExecSql.php GetMaxUnitRevIdByUnitId.php, GetOperationTypeIdByName.php, GetTableIdByName.php, GetUnitIdByRevId.php
//     Globals.php, InsertJournalItem.php, InsertOperationType.php, InsertTable.php, Print.php, Print2.php

function CopyOfferContent($Db, $ProtOfferRevId, $NewOfferRevId, $DebugLevel) {
	return CopyOfferContent2($Db, $ProtOfferRevId, $NewOfferRevId, true, $DebugLevel);
}

function CopyOfferContent3($Db, $ProtOfferRevId, $NewOfferRevId, $IsUpdateUnitToMax, $UserId, $DebugLevel) {
	global $DEBUG_SQL;
	$Sql = "SELECT `id`, `unit_revision_id`, `number` FROM `offer_content_unit` WHERE `offer_revision_id`=".$ProtOfferRevId;
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	$Res = $Db->query($Sql);
	if (!$Res) {
		PrintError2("Произошла ошибка чтения из offer_content_unit (".$Db->errno.") ".$Db->error, $DebugLevel);
		$Db->close();
		return false;
	}

	$Row = $Res->fetch_assoc();
	while ($Row != false) {
		$cuId = $Row['id'];
		$cuUnitRevId = $Row['unit_revision_id'];
		$cuNumber = $Row['number'];
		PrintComment2("-- id=".$cuId." unitRevisionId=".$cuUnitRevId." number=".$cuNumber." --", $DebugLevel);

		$cuLastUnitRevId = $cuUnitRevId;
		if ($IsUpdateUnitToMax) {
			if (!GetUnitIdByRevId2($Db, $cuUnitRevId, $DebugLevel, $cuUnitId) || !isset($cuUnitId) || $cuUnitId <= 0) {
				PrintError2("Для ревизии id=".$cuUnitRevId." не найдена запись сборочной единицы.", $DebugLevel);
			} else {
				if (!GetMaxUnitRevIdByUnitId3($Db, $cuUnitId, $DebugLevel, $cuLastUnitRevId) || !isset($cuLastUnitRevId) || $cuLastUnitRevId <= 0) {
					PrintError2("Не найдена последняя ревизия для сборочной единицы unit.id=".$cuUnitId, $DebugLevel);
					$cuLastUnitRevId = $cuUnitRevId;
				} else {
					if ($cuUnitRevId != $cuLastUnitRevId) {
						PrintWarning2("Ревизия id=".$cuUnitRevId." для сборочной единицы unit.id=".$cuUnitId." устарела. Заменяем на актуальную ревизию id=".$cuLastUnitRevId.".", $DebugLevel);
					}
				}
			}
		}
		
		$SqlInsert = "INSERT INTO `offer_content_unit` (`offer_revision_id`, `unit_revision_id`, `number`) VALUES (".$NewOfferRevId.", ".$cuLastUnitRevId.", ".$cuNumber.")";
		if ($DebugLevel >= $DEBUG_SQL)
			PrintComment($SqlInsert);
		if (!ExecSql($Db, $SqlInsert, 'copy', 'offer_content_unit', $UserId, $DebugLevel, $Id)) {
			PrintError2("Ошибка при заполнении таблицы offer_content_unit. Sql=".$SqlInsert."(".$Db->errno.") ".$Db->error, $DebugLevel);
		}

		$Row = $Res->fetch_assoc();
	}
	$Res->free();
	return true;
}
