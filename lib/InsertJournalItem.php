<?php

include_once('../lib/Globals.php');
include_once('../lib/Print.php');
include_once('../lib/Print2.php');


function InsertJournalItem($Db, $TableId, $EntityId, $UserId, $OperationTypeId, $DebugLevel, &$JournalItemId) {
	global $DEBUG_SQL;
	$Sql = "INSERT INTO `journal_item` (`table_id`, `entity_id`, `user_id`, `operation_type_id`) VALUES (".$TableId.", ".$EntityId.", ".$UserId.", ".$OperationTypeId.")";
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	$Res = $Db->query($Sql);
	if (!$Res) {
		PrintError2('Ошибка добавления записи в journal_item ('.$Db->errno.') '.$Db->error.' Sql='.$Sql, $DebugLevel);
		return false;
	}
	$JournalItemId = $Db->insert_id;
	PrintComment2('Запись добавлена. journal_item.id='.$JournalItemId, $DebugLevel);
	return true;
}
