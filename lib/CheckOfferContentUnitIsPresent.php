<?php

include_once('../lib/Globals.php');
include_once('../lib/Print.php');


function CheckOfferContentUnitIsPresent($Db, $OfferRevId, $UnitRevId, $DebugLevel) {
	global $DEBUG_SQL;
	$Sql = "SELECT * FROM `offer_content_unit` WHERE `offer_revision_id`=".$OfferRevId." AND `unit_revision_id`=".$UnitRevId;
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	$Res = $Db->query($Sql);
	if (!$Res) {
		return false;
	}
	$Row = $Res->fetch_assoc();
	if (!$Row) {
		$Res->free();
		return false;
	}
	$Res->free();
	return true;
}
