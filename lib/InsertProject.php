<?php

include_once('../lib/GetMaxProjectCode.php');
include_once('../lib/Globals.php');
include_once('../lib/Insert.php');
include_once('../lib/Print.php');
include_once('../lib/Print2.php');


function InsertProject3($Db, $ProjectCode, $IsDel, $UserId, $DebugLevel, &$NewProjectId, &$NewProjectCode) {
	global $DEBUG_SQL;

	if ($ProjectCode <= 0) {
		if (!GetMaxProjectCode($Db, $ProjectCode)) {
			PrintError2('Ошибка получения максимального кода проекта', $DebugLevel);
			return false;
		}
		if ($ProjectCode <= 0) {
			PrintError2('Получен не правильный номер проекта. Возможно не создано еще ни одной записи. Обратитесь к администратору.', $DebugLevel);
			return false;
		}
		$ProjectCode = $ProjectCode + 1;
	}

	$Sql = 'INSERT INTO `project` (`code`, `is_del`) VALUES ('.$ProjectCode.', '.$IsDel.')';
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	if (!Insert($Db, 'project', $UserId, $Sql, $DebugLevel, $NewProjectId))
		return false;
	$NewProjectCode = $ProjectCode;
	PrintComment2("Запись добавлена. project.id=".$NewProjectId.' (is_del=1)', $DebugLevel);
	return true;
}

function InsertProject_Begin($Db, $ProjectCode, $UserId, $DebugLevel, &$NewProjectId, &$NewProjectCode) {
	global $DEBUG_SQL;

	if ($ProjectCode <= 0) {
		if (!GetMaxProjectCode($Db, $ProjectCode)) {
			PrintError2('Ошибка получения максимального кода проекта', $DebugLevel);
			return false;
		}
		if ($ProjectCode <= 0) {
			PrintError2('Получен не правильный номер проекта. Возможно не создано еще ни одной записи. Обратитесь к администратору.', $DebugLevel);
			return false;
		}
		$ProjectCode = $ProjectCode + 1;
	}

	$Sql = 'INSERT INTO `project` (`code`) VALUES ('.$ProjectCode.')';
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	if (!Insert($Db, 'project', $UserId, $Sql, $DebugLevel, $NewProjectId))
		return false;
	$NewProjectCode = $ProjectCode;
	PrintComment2("Запись добавлена. project.id=".$NewProjectId, $DebugLevel);
	return true;
}

function InsertProject_Ok($Db, $NewProjectId, $DebugLevel) {
	global $DEBUG_SQL;
	$Sql = 'UPDATE `project` SET `is_del`=0 WHERE `id`='.$NewProjectId;
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	$Res = $Db->query($Sql);
	if (!$Res) {
		PrintError2('Ошибка изменения записи в project ('.$Db->errno.') '.$Db->error.' Sql='.$Sql, $DebugLevel);
		return false;
	}
	PrintComment2("Запись добавлена. project.id=".$NewProjectId.' (is_del=0)', $DebugLevel);
	return true;
}
