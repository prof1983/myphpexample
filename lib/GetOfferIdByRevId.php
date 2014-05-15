<?php

include_once('../lib/Globals.php');
include_once('../lib/Print.php');
include_once('../lib/Print2.php');


function GetOfferIdByRevId1($Db, $OfferRevId, $UseDeleted, $DebugLevel, &$OfferId) {
	global $DEBUG_SQL;
	if ($UseDeleted)
		$Sql = "SELECT `offer_id` FROM `offer_revision` WHERE `id`=".$OfferRevId;
	else
		$Sql = "SELECT `offer_id` FROM `offer_revision` WHERE `id`=".$OfferRevId." AND `is_del`=0";
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Dql);
	$Res = $Db->query($Sql);
	if (!$Res) {
		PrintError2("Error in GetOfferIdByRevId(). Sql=".$Sql, $DebugLevel);
		return false;
	}
	$Row = $Res->fetch_row();
	if (!$Row) {
		$Res->close();
		return false;
	}
	$OfferId = $Row[0];
	$Res->close();
	return true;
}
