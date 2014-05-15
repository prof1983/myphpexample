<?php

include_once('../lib/GetOperationTypeIdByName.php');
include_once('../lib/GetTableIdByName.php');
include_once('../lib/InsertJournalItem.php');
include_once('../lib/InsertOperationType.php');
include_once('../lib/InsertTable.php');
include_once('../lib/Print2.php');


// $OperationName - Наименование операции (insert, update, delete, recovery, fix, copy)
function ExecSql($Db, $Sql, $OperationName, $TableName, $UserId, $DebugLevel, &$Id) {
	$Res = $Db->query($Sql);
	if (!$Res) {
		PrintError2('Ошибка выполнения Sql в '.$TableName.' ('.$Db->errno.') '.$Db->error.' Sql='.$Sql, $DebugLevel);
		return false;
	}
	if ($OperationName == 'insert' || $OperationName == 'copy')
		$Id = $Db->insert_id;

	// -- Audit --

	if (!GetTableIdByName($Db, $TableName, $DebugLevel, $TableId)) {
		PrintWarning2('Ошибка добавления записи в журнал: ошибка получения идентификатора таблицы. TableName='.$TableName, $DebugLevel);
		return true;
	}

	// -- Если таблицы нет, то добавляем --
	if ($TableId <= 0) {
		if (!InsertTable($Db, $TableName, $DebugLevel, $TableId)) {
			PrintWarning2('Ошибка добавления записи в журнал: ошибка добавления таблицы. TableName='.$TableName, $DebugLevel);
			return true;
		}
		if ($TableId <= 0) {
			PrintWarning2('Ошибка добавления записи в журнал: идентификатор таблицы не получен. TableName='.$TableName, $DebugLevel);
			return true;
		}
	}

	if (!GetOperationTypeIdByName($Db, $OperationName, $DebugLevel, $OperationId)) {
		PrintWarning2('Ошибка добавления записи в журнал: ошибка получения идентификатора операции. OperationName='.$OperationName, $DebugLevel);
		return true;
	}

	if ($OperationId <= 0) {
		if (!InsertOperationType($Db, $OperationName, $DebugLevel, $OperationId)) {
			PrintWarning2('Ошибка добавления записи в журнал: ошибка добавления операции. OperationName='.$OperationName, $DebugLevel);
			return true;
		}
		if ($OperationId <= 0) {
			PrintWarning2('Ошибка добавления записи в журнал: ошибка добавления операции. Идентификатор не верный. OperationName='.$OperationName, $DebugLevel);
			return true;
		}
	}

	if (!InsertJournalItem($Db, $TableId, $Id, $UserId, $OperationId, $DebugLevel, $JournalItemId)) {
		PrintWarning2('Ошибка добавления записи в журнал: ошибка вставки записи.', $DebugLevel);
		return true;
	}

	return true;
}
