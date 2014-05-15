<?php

include_once('../lib/Globals.php');
include_once('../lib/Print.php');
include_once('../lib/Print2.php');


function GetOfferRevIsFixed($Db, $OfferRevId, $DebugLevel, &$IsFixed) {
	global $DEBUG_SQL;

	$Sql = "SELECT `is_fixed` FROM `offer_revision` WHERE `id`=".$OfferRevId;
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	$Res = $Db->query($Sql);
	if (!$Res) {
		PrintError2("Error in GetOfferRevIsFixed. Sql=".$Sql, $DebugLevel);
		return false;
	}

	$Row = $Res->fetch_row();
	if (!$Row) {
		PrintError2("Error in GetOfferRevIsFixed (2)", $DebugLevel);
		$Res->free();
		return false;
	}

	$IsFixed = $Row[0];
	$Res->free();
	return true;
}
