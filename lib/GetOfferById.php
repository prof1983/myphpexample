<?php

include_once('../lib/Globals.php');
include_once('../lib/Print.php');
include_once('../lib/Print2.php');


function GetOfferById($Db, $OfferId, $DebugLevel, &$Code) {
	global $DEBUG_SQL;
	$Sql = "SELECT `code` FROM `offer` WHERE `id`=".$OfferId;
	$Res = $Db->query($Sql);
	if (!$Res) {
		PrintError2("Error query in GetOfferById() Sql=".$Sql, $DebugLevel);
		return false;
	}
	$Row = $Res->fetch_row();
	if (!$Row) {
		$Res->close();
		return false;
	}
	$Code = $Row[0];
	$Res->close();
	return true;
}
