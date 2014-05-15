<?php

include_once('../lib/GetMaxUnitRevIdByUnitId.php');
include_once('../lib/GetUnitIdByRevId.php');
include_once('../lib/Print2.php');


// Возвращает номер максимальной ревизии
function GetMaxUnitRevIdByUnitRevId($Db, $UnitRevId, $DebugLevel, &$UnitId, &$LastUnitRevId) {
	$LastUnitRevId = $UnitRevId;
	$UnitId = GetUnitIdByRevId($Db, $UnitRevId, $DebugLevel);
	if (!$UnitId) {
		PrintError2("Для ревизии id=".$UnitRevId." не найдена запись сборочной единицы.", $DebugLevel);
	} else {
		$LastUnitRevId = GetMaxUnitRevIdByUnitId($Db, $UnitId, $DebugLevel);
		if (!$LastUnitRevId) {
			PrintError2("Не найдена последняя ревизия для сборочной единицы unit.id=".$UnitId, $DebugLevel);
			$LastUnitRevId = $UnitRevId;
		} else {
			if ($UnitRevId != $LastUnitRevId) {
				PrintWarning2("Ревизия id=".$UnitRevId." для сборочной единицы unit.id=".$UnitId." устарела. Заменяем на актуальную ревизию id=".$LastUnitRevId.".", $DebugLevel);
			}
		}
	}
}
