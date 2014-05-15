<?php

include_once('../lib/CheckOfferContentUnitIsPresent.php');
include_once('../lib/GetOfferRevByOfferRevId.php');
include_once('../lib/Globals.php');
include_once('../lib/Print.php');
include_once('../lib/Print2.php');

function WorkUpdateOfferContentUnit($Db, $ContainerOfferRevId, $UnitId, $DebugLevel, &$ChildMaxRevId, &$ChildMaxRevIsFixed) {
	global $DEBUG_SQL;

	// ---- CheckParams ----

	if (!isset($ContainerOfferRevId) || $ContainerOfferRevId <= 0) {
		PrintError2('Не указан параметр ContainerOfferRevId', $DebugLevel);
		return false;
	}

	if (!isset($UnitId) || $UnitId <= 0) {
		PrintError2('Не указан параметр UnitId', $DebugLevel);
		return false;
	}

	// ---- CheckParams ----

	if (!GetOfferRevByOfferRevId($Db, $ContainerOfferRevId, $DebugLevel, $ContOfferId, $ContRev, $ContDateCreate, $ContClientId, $ContRevIsFixed)) {
		PrintError2('Ошибка получения ревизии заказа', $DebugLevel);
		return false;
	}

	if ($ContRevIsFixed != 0) {
		PrintError2('Изменения в зафиксированной ревизии производить не допускается. offer_revision.id='.$ContainerOfferRevId, $DebugLevel);
		return false;
	}

	// ---- Запрашиваем данные ----

	$Sql = 'SELECT
	  ocu.`id` as `ocu_id`,
	  ur.`id` as `unit_rev_id`,
	  ur.`revision` as `unit_rev`,
	  ur.`level` as `unit_rev_level`,
	  ur.`is_fixed`+0 as `unit_rev_is_fixed`,
	  urmax.`id` as `unit_max_rev_id`,
	  urmax.`revision` as `unit_max_rev`,
	  urmax.`level` as `unit_max_rev_level`,
	  urmax.`is_fixed` as `unit_max_rev_is_fixed`
	FROM
	  `offer_content_unit` as ocu,
	  `unit_revision` as ur
	  LEFT JOIN
	    `unit_revision` as urmax
	  ON
	    (
	      urmax.`id` = (
	        SELECT MAX(ur3.`id`)
	        FROM `unit_revision` as ur3
	        WHERE ur3.`unit_id`=ur.`unit_id`
	      )
	    )
	WHERE
	  ur.`id`=ocu.`unit_revision_id`
	AND
	  ocu.`offer_revision_id`='.$ContainerOfferRevId.'
	AND
	  ur.`unit_id`='.$UnitId;

	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	$Res = $Db->query($Sql);
	if (!Res) {
		PrintError2('Ошибка при запросе Sql='.$Sql, $DebugLevel);
		return false;
	}
	$Row = $Res->fetch_assoc();
	if (!Row) {
		PrintError2('У ContainerOfferRevId='.$ContainerOfferRevId.' нет вложенной сборочной единицы UnitId='.$UnitId, $DebugLevel);
		return false;
	}
	if ($Res->num_rows != 1) {
		PrintError2('У ContainerOfferRevId='.$ContainerOfferRevId.' кол-во сборочных единиц UnitId='.$UnitId.' не равно 1', $DebugLevel);
		return false;
	}

	$OcuId = $Row['ocu_id'];
	$ChildRevId = $Row['unit_rev_id'];
	$ChildRev = $Row['unit_rev'];
	$ChildRevLevel = $Row['unit_rev_level'];
	$ChildMaxRevId = $Row['unit_max_rev_id'];
	$ChildMaxRev = $Row['unit_max_rev'];
	$ChildMaxRevLevel = $Row['unit_max_rev_level'];
	$ChildMaxRevIsFixed = $Row['unit_max_rev_is_fixed'];

	// ---- CheckUnitContentUnit ----

	if (CheckOfferContentUnitIsPresent($Db, $ContainerOfferRevId, $ChildMaxRevId, $DebugLevel)) {
		PrintError2("Запись уже существует. OfferRevId=".$ContainerOfferRevId." ChildUnitId=".$UnitId." ChildRevId=".$ChildMaxRevId, $DebugLevel);
		return false;
	}

	// --- Обновляем данные ----

	$SqlUpdate = "UPDATE `offer_content_unit` SET `unit_revision_id`=".$ChildMaxRevId." WHERE `id`=".$OcuId;
	$ResUpdate = $Db->query($SqlUpdate);
	if (!$ResUpdate) {
		PrintError2('Ошибка при обновлении данных. Sql='.$SqlUpdate, $DebugLevel);
		return false;
	}

	PrintInfo2("Запись обновлена. offer_content_unit.id=".$OcuId." ChildUnitId=".$UnitId." Rev: ".$ChildRev." -> ".$ChildMaxRev." Id:".$ChildRevId." -> ".$ChildMaxRevId, $DebugLevel);

	return true;
}
