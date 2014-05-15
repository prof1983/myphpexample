<?php

include_once('../lib/CheckOfferContentUnitIsPresent.php');
include_once('../lib/CheckOfferRevIsFixed.php');
include_once('../lib/GetMaxOfferRevIdByOfferId.php');
include_once('../lib/GetMaxUnitRevIdByUnitId.php');
include_once('../lib/Globals.php');
include_once('../lib/InsertOfferContentUnit.php');
include_once('../lib/Print.php');
include_once('../lib/Print2.php');


// Добавляет одну сборочную единицу
function AddUnitToOfferContent2($Db, $OfferId, $OfferRevId, $UnitId, $UnitRevId, $Number, $UserId, $DebugLevel) {
	return AddUnitToOfferContent3($Db, $OfferId, $OfferRevId, $UnitId, $UnitRevId, $Number, $UserId, $DebugLevel, $Id);
}

// Добавляет одну сборочную единицу
function AddUnitToOfferContent3($Db, $OfferId, $OfferRevId, $UnitId, $UnitRevId, $Number, $UserId, $DebugLevel, &$Id) {
	global $DEBUG_COMMENT;

	// ---- CheckParams ----

	if (!isset($OfferRevId) || $OfferRevId <= 0) {
		if (!isset($OfferId) || $OfferId <= 0) {
			PrintError2("Не указан параметр OfferRevId или OfferId", $DebugLevel);
			return false;
		}
		// -- GetMaxOfferRevId --
		$OfferRevId = GetMaxOfferRevIdByOfferId($Db, $OfferId, $DebugLevel);
		if (!$OfferRevId || $OfferRevId <= 0) {
			PrintError2("Ошибка при получении id ревизии заказа", $DebugLevel);
			return false;
		}
	}

	if (!isset($UnitRevId) || $UnitRevId <= 0) {
		if (!isset($UnitId) || $UnitId <= 0) {
			PrintError2("Не указан параметр UnitRevId или UnitId", $DebugLevel);
			return false;
		}
		// -- GetMaxUnitRevId --
		if (!GetMaxUnitRevIdByUnitId3($Db, $UnitId, $DebugLevel, $UnitRevId)) {
			PrintError2("Ошибка при получении id ревизии сборочной единицы", $DebugLevel);
			return false;
		}
	}

	if (!isset($Number)) {
		PrintError2("Не указан параметр Number", $DebugLevel);
		return false;
	}

	if ($DebugLevel >= $DEBUG_COMMENT) {
		PrintComment("Идентификатор ревизии заказа offer_revision.id = " . $OfferRevId);
		PrintComment("Идентификатор ревизии сборной единицы unit_revision.id = " . $UnitRevId);
	}

	// ---- CheckOfferRevIsFixed ----

	if (!CheckOfferRevIsFixed($Db, $OfferRevId, $DebugLevel)) {
		return false;
	}

	// ---- CheckOfferContentUnit ----

	if (CheckOfferContentUnitIsPresent($Db, $OfferRevId, $UnitRevId, $DebugLevel)) {
		PrintError2("Запись уже существует. OfferId=".$OfferId." OfferRevId=".$OfferRevId." UnitId=".$UnitId." UnitRevId=".$UnitRevId, $DebugLevel);
		return false;
	}

	// ---- Put data to database ----

	PrintComment2("OfferId=".$OfferId." OfferRevId=".$OfferRevId." UnitId=".$UnitId." UnitRevId=".$UnitRevId." Num=".$Number." UserId=".$UserId, $DebugLevel);

	if (!InsertOfferContentUnit2($Db, $OfferRevId, $UnitRevId, $Number, $UserId, $DebugLevel, $Id) || $Id <= 0) {
		$S = " OfferId=".$OfferId." OfferRevId=".$OfferRevId." UnitId=".$UnitId." UnitRevId=".$UnitRevId." Num=".$Number." UserId=".$UserId;
		PrintError2("Запись не добавлена".$S, $DebugLevel);
		return false;
	}

	PrintInfo2("Запись добавлена. offer_content_unit.id=".$Id, $DebugLevel);
	return true;
}
