<?php

include_once('../lib/GetMaxOfferRevision.php');
include_once('../lib/Print.php');
include_once('../lib/Print2.php');


function GetMaxOfferRevByOfferId($Db, $OfferId, $DebugLevel, &$OfferRevId, &$OfferRev, &$OfferRevIsFixed) {
	global $DEBUG_SQL;
	$OfferRev = GetMaxOfferRevision($Db, $OfferId, $DebugLevel);
	if (!$OfferRev) {
		PrintError2("Error in GetOfferRevision()");
		return false;
	}
	$Sql = "SELECT `id`, `is_fixed` FROM `offer_revision` WHERE `offer_id`=".$OfferId." AND `revision`=".$OfferRev;
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	$Res = $Db->query($Sql);
	if (!$Res) {
		PrintError2("Ошибка при запросе ревизии заказа. Sql=".$Sql);
		return false;
	}
	$Row = $Res->fetch_row();
	if (!$Row) {
		$Res->close();
		return false;
	}
	$OfferRevId = $Row[0];
	$OfferRevIsFixed = $Row[1];
	$Res->close();
	return true;
}
