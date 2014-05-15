<?php

include_once('../lib/Globals.php');
include_once('../lib/Print.php');
include_once('../lib/Print2.php');


function UpdateOfferElements($Db, $OfferRevId, $ElementsUnitRevId, $DebugLevel) {
	global $DEBUG_SQL;
	$Sql = 'UPDATE `offer_revision` SET `elements_unit_rev_id`='.$ElementsUnitRevId.' WHERE `id`='.$OfferRevId;
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	$Res = $Db->query($Sql);
	if (!$Res) {
		PrintError2('Ошибка при обновлении offer_revision.elements_unit_rev_id. Sql='.$Sql, $DebugLevel);
		return false;
	}
	return true;
}
