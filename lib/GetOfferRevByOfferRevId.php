<?php

include_once('../lib/Globals.php');
include_once('../lib/Print.php');
include_once('../lib/Print2.php');


function GetOfferRevByOfferRevId($Db, $OfferRevId, $DebugLevel, &$OfferId, &$Revision, &$DateCreate, &$ClientId, &$RevIsFixed) {
	global $DEBUG_SQL;
	$Sql = "SELECT `offer_id`, `revision`, `date_create`, `client_id`, `is_fixed`+0 FROM `offer_revision` WHERE `id`=".$OfferRevId;
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	$Res = $Db->query($Sql);
	if (!$Res) {
		PrintError2("Error in GetOfferRev(). Sql=".$Sql, $DebugLevel);
		return false;
	}
	$Row = $Res->fetch_row();
	if (!$Row) {
		PrintError2("Нет ревизии для OfferRevId=".$OfferRevId, $DebugLevel);
		$Res->close();
		return false;
	}
	$OfferId = $Row[0];
	$Revision = $Row[1];
	$DateCreate = $Row[2];
	$ClientId = $Row[3];
	$RevIsFixed = $Row[4];
	$Res->close();
	return true;
}

function GetOfferRevByOfferRevId2($Db, $OfferRevId, $DebugLevel, &$OfferId, &$Revision, &$DateCreate, &$ClientId, &$ClientName, &$UserId, &$ElementsUnitRevId, &$ProtOfferRevId, &$Price, &$Discount, &$IsFixed, &$IsDel) {
	global $DEBUG_SQL;
	$Sql =
		"SELECT `offer_id`, `revision`, `date_create`, `client_id`, `client_name`, `user_id`, `elements_unit_rev_id`, `prot_offer_rev_id`, `price`, `discount`, `is_fixed`+0, `is_del`".
		" FROM `offer_revision` WHERE `id`=".$OfferRevId;
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	$Res = $Db->query($Sql);
	if (!$Res) {
		PrintError2("Error in GetOfferRev(). Sql=".$Sql, $DebugLevel);
		return false;
	}
	$Row = $Res->fetch_row();
	if (!$Row) {
		PrintError2("Нет ревизии для OfferRevId=".$OfferRevId, $DebugLevel);
		$Res->close();
		return false;
	}
	$OfferId = $Row[0];
	$Revision = $Row[1];
	$DateCreate = $Row[2];
	$ClientId = $Row[3];
	$ClientName = $Row[4];
	$UserId = $Row[5];
	$ElementsUnitRevId = $Row[6];
	$ProtOfferRevId = $Row[7];
	$Price = $Row[8];
	$Discount = $Row[9];
	$IsFixed = $Row[10];
	$IsDel = $Row[11];
	$Res->close();
	return true;
}
