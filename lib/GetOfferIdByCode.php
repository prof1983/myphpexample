<?php

include_once('../lib/Globals.php');
include_once('../lib/Print.php');
include_once('../lib/Print2.php');


function GetOfferIdByCode($Db, $OfferCode, $DebugLevel, &$OfferId) {
	return GetOfferIdByCode2($Db, $OfferCode, false, $DebugLevel, $OfferId);
}

function GetOfferIdByCode2($Db, $OfferCode, $UseDeleted, $DebugLevel, &$OfferId) {
	global $DEBUG_SQL;
	$OfferCode = $Db->real_escape_string($OfferCode);
	if ($UseDeleted)
		$Sql = "SELECT `id` FROM `offer` WHERE `code`='".$OfferCode;
	else
		$Sql = "SELECT `id` FROM `offer` WHERE `code`='".$OfferCode."' AND `is_del`=0";
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Dql);
	$Res = $Db->query($Sql);
	if (!$Res) {
		PrintError2("Error in GetOfferIdByCode(). Sql=".$Sql, $DebugLevel);
		return false;
	}
	$Row = $Res->fetch_row();
	if (!$Row) {
		//PrintError2("Нет заказа с кодом OfferCode=".$OfferCode, $DebugLevel);
		$Res->close();
		return false;
	}
	$OfferId = $Row[0];
	$Res->close();
	return true;
}
