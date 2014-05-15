<?php

include_once('../lib/GetMaxOfferRevision.php');
include_once('../lib/Globals.php');
include_once('../lib/Print.php');
include_once('../lib/Print2.php');


function GetMaxOfferRevIdByOfferId($db, $offerId, $debugLevel) {
	return GetMaxOfferRevIdByOfferId2($db, $offerId, 'html', $debugLevel);
}

function GetMaxOfferRevIdByOfferId1($Db, $OfferId, $DebugLevel, &$OfferRevId) {
	global $DEBUG_SQL;
	if (!GetMaxOfferRevision1($Db, $OfferId, $DebugLevel, $OfferRev)) {
		PrintError2("Error in GetOfferRevision()", $DebugLevel);
		return false;
	}
	if ($OfferRev == 0) {
		$OfferRevId = 0;
		return true;
	}
	$Sql = "SELECT `id` FROM `offer_revision` WHERE `offer_id`=".$OfferId." AND `revision`=".$OfferRev;
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	$Res = $Db->query($Sql);
	if (!$Res) {
		PrintError2("Error in GetMaxOfferRevIdByOfferId1(). Sql=".$Sql, $DebugLevel);
		return false;
	}
	$Row = $Res->fetch_row();
	if (!$Row) {
		$Res->close();
		PrintError2("Error in GetMaxOfferRevIdByOfferId1(). Sql=".$Sql, $DebugLevel);
		return false;
	}
	$OfferRevId = $Row[0];
	$Res->close();
	return true;
}

function GetMaxOfferRevIdByOfferId2($db, $offerId, $ReturnContentType, $debugLevel) {
	global $DEBUG_ERROR, $DEBUG_SQL;
	$rev = GetMaxOfferRevision2($db, $offerId, $ReturnContentType, $debugLevel);
	if (!$rev) {
		if ($ReturnContentType == 'html' && $debugLevel >= $DEBUG_ERROR)
			PrintError("Error in GetOfferRevision()");
		return FALSE;
	}
	$sql = "SELECT `id` FROM `offer_revision` WHERE `offer_id`=".$offerId." AND `revision`=".$rev;
	if ($ReturnContentType == 'html' && $debugLevel >= $DEBUG_SQL)
		PrintComment($sql);
	$res = $db->query($sql);
	if (!$res) {
		if ($ReturnContentType == 'html' && $debugLevel >= $DEBUG_ERROR)
			PrintError("Error in GetMaxRevision()");
		return FALSE;
	}
	$row = $res->fetch_row();
	if (!$row) {
		$res->close();
		//PrintError("Error in GetMaxRevision()");
		return FALSE;
	}
	$rev = $row[0];
	$res->close();
	return $rev;
}
