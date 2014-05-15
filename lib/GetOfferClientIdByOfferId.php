<?php

include_once('../lib/Globals.php');
include_once('../lib/Print.php');
include_once('../lib/Print2.php');


function GetOfferClientIdByOfferId($Db, $OfferId, $DebugLevel, &$ClientId) {
	global $DEBUG_SQL;
	$Sql = "SELECT `client_id` FROM `offer` WHERE `id`=".$OfferId;
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	$Res = $Db->query($Sql);
	if (!$Res) {
		PrintError2("Error in GetOfferClientIdByOfferId(). Sql=".$Sql, $DebugLevel);
		return false;
	}
	$Row = $Res->fetch_row();
	if (!$Row) {
		PrintError2("Нет заказа OfferId=".$OfferId, $DebugLevel);
		$Res->close();
		return false;
	}
	$ClientId = $Row[0];
	$Res->close();
	return true;
}