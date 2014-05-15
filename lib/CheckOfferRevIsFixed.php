<?php

include_once('../lib/GetOfferRevIsFixed.php');
include_once('../lib/Print2.php');


function CheckOfferRevIsFixed($Db, $OfferRevId, $DebugLevel) {
	if (!GetOfferRevIsFixed($Db, $OfferRevId, $DebugLevel, $IsFixed)) {
		return false;
	}
	if ($IsFixed != 0) {
		PrintError2("Ревизия зафиксирована. Изменения в зафиксированной ревизии не допускаются.", $DebugLevel);
		return false;
	}
	return true;
}
