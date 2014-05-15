<?php

include_once('../lib/Globals.php');
include_once('../lib/Insert.php');
include_once('../lib/Print.php');


function InsertOfferContentUnit2($Db, $OfferRevId, $UnitRevId, $Number, $UserId, $DebugLevel, &$Id) {
	global $DEBUG_SQL;
	$Sql = "INSERT INTO `offer_content_unit` (`offer_revision_id`, `unit_revision_id`, `number`) VALUES (".$OfferRevId.", ".$UnitRevId.", ".$Number.")";
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	if (!Insert($Db, 'offer_content_unit', $UserId, $Sql, $DebugLevel, $Id))
		return false;
	return true;
}
