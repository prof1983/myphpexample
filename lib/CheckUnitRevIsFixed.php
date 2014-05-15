<?php

include_once('../lib/GetUnitRevByRevId.php');
include_once('../lib/Print2.php');


/**
 * Проверяет зафиксирована ревизия или нет. Если зафиксирована возвращает false, иначе true.
 * UnitRevLevel - возвращает значение unit_revision.level
 */
function CheckUnitRevIsFixed($Db, $UnitRevId, $DebugLevel, &$UnitRevLevel) {

	if (!GetUnitRevByRevId3($Db, $UnitRevId, $DebugLevel, $UnitId, $Rev, $UnitRevLevel, $DateCreate, $RevIsFixed)) {
		return false;
	}

	if ($RevIsFixed != 0) {
		PrintError2("Ревизия зафиксирована. Изменения в зафиксированной ревизии не допускаются. UnitRevId=".$UnitRevId, $DebugLevel);
		return false;
	}

	return true;
}
