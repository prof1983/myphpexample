<?php

include_once('../lib/Globals.php');
include_once('../lib/Print.php');
include_once('../lib/Print2.php');


function GetMaxOfferRevision($db, $offerId, $debugLevel) {
	return GetMaxOfferRevision2($db, $offerId, 'html', $debugLevel);
}

function GetMaxOfferRevision1($Db, $OfferId, $DebugLevel, &$OfferRev) {
	global $DEBUG_ERROR, $DEBUG_SQL;
	$Sql = "SELECT MAX(`revision`) FROM `offer_revision` WHERE `offer_id`=".$OfferId;
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	$Res = $Db->query($Sql);
	if (!$Res) {
		PrintError2("Error in GetMaxOfferRevision()", $DebugLevel);
		return false;
	}
	$Row = $Res->fetch_row();
	if (!$Row) {
		$Res->close();
		$OfferRev = 0;
		return true;
	}
	$OfferRev = $Row[0];
	$Res->close();
	return true;
}

function GetMaxOfferRevision2($db, $offerId, $ReturnContentType, $debugLevel) {
	global $DEBUG_ERROR, $DEBUG_SQL;
	$sql = "SELECT MAX(`revision`) FROM `offer_revision` WHERE `offer_id`=".$offerId;
	if ($ReturnContentType == 'html' && $debugLevel >= $DEBUG_SQL)
		PrintComment($sql);
	$res = $db->query($sql);
	if (!$res) {
		if ($ReturnContentType == 'html' && $debugLevel >= $DEBUG_ERROR)
			PrintError("Error in GetMaxOfferRevision()");
		return FALSE;
	}
	$row = $res->fetch_row();
	if (!$row) {
		$res->close();
		return 0;
		//PrintError("Error in GetMaxRevision()");
		//return FALSE;
	}
	$rev = $row[0];
	$res->close();
	return $rev;
}
