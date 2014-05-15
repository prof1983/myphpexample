<?php

include_once('../lib/Globals.php');
include_once('../lib/Print.php');
include_once('../lib/Print2.php');


function GetOfferRev($db, $offerRevId, $debugLevel, &$offerId, &$revision, &$dateCreate, &$revIsFixed) {
	global $DEBUG_ERROR, $DEBUG_SQL;
	$sql = "SELECT `offer_id`, `revision`, `date_create`, `is_fixed`+0 FROM `offer_revision` WHERE `id`=".$offerRevId;
	if ($debugLevel >= $DEBUG_SQL)
		PrintComment($sql);
	$res = $db->query($sql);
	if (!$res) {
		if ($debugLevel >= $DEBUG_ERROR)
			PrintError("Error in GetOfferRev(). sql=".$sql);
		return FALSE;
	}
	$row = $res->fetch_row();
	if (!$row) {
		if ($debugLevel >= $DEBUG_ERROR)
			PrintError("Нет ревизии для offerRevId=".$offerRevId);
		$res->close();
		return FALSE;
	}
	$offerId = $row[0];
	$revision = $row[1];
	$dateCreate = $row[2];
	$revIsFixed = $row[3];
	$res->close();
	return TRUE;
}

function GetOfferRev2($Db, $OfferRevId, $DebugLevel, &$OfferId, &$Rev, &$DateCreate, &$RevIsFixed, &$MaxRevId, &$MaxRev) {
	global $DEBUG_SQL;

	$Sql = "SELECT
	  or1.`offer_id` AS `offer_id`,
	  or1.`revision` AS `revision`,
	  or1.`date_create` AS `date_create`,
	  or1.`is_fixed`+0 AS `is_fixed`,
	  or2.`id` AS `max_rev_id`,
	  or2.`revision` AS `max_rev`
	FROM
	  `offer_revision` AS or1
	  LEFT JOIN
	    `offer_revision` AS or2
	  ON
	    (
	      or2.`id` = (
	        SELECT MAX(`or3`.`id`)
	        FROM `offer_revision` AS `or3`
	        WHERE or3.`offer_id`=or1.`offer_id`
	      )
	    )
	WHERE
	  or1.`id`=".$OfferRevId;

	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	$Res = $Db->query($Sql);
	if (!$Res) {
		PrintError2("Error in GetOfferRev2(). Sql=".$Sql, $DebugLevel);
		return false;
	}
	$Row = $Res->fetch_row();
	if (!$Row) {
		PrintError2("Нет ревизии для OfferRevId=".$OfferRevId, $DebugLevel);
		$res->close();
		return false;
	}
	$OfferId = $Row[0];
	$Rev = $Row[1];
	$DateCreate = $Row[2];
	$RevIsFixed = $Row[3];
	$MaxRevId = $Row[4];
	$MaxRev = $Row[5];
	$Res->close();
	return true;
}

/*
function GetOfferRevByOfferIdAndRev($db, $offerId, $rev, &$revId, &$revIsFixed) {
	global $DEBUG_SQL;
	$sql = "SELECT `id`, `is_fixed`+0 FROM `offer_revision` WHERE `offer_id`=".$offerId." AND `revision`=".$rev;
	if ($debugLevel >= $DEBUG_SQL)
		PrintComment($sql);
	$res = $db->query($sql);
	if (!$res) {
		PrintError("Error in GetRevisionId()");
		return FALSE;
	}
	$row = $res->fetch_row();
	if (!$row) {
		PrintError("Нет ревизии для offerId=".$offerId." revision=".$rev);
		$res->close();
		return FALSE;
	}
	$revId = $row[0];
	$revIsFixed = $row[1];
	$res->close();
	return TRUE;
}
*/