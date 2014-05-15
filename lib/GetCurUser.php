<?php

include_once('../lib/GetUserIdByName.php');
include_once('../lib/Globals.php');
include_once('../lib/InsertUser.php');
include_once('../lib/Print2.php');


// $Created - True:создавать записть в БД, если такого пользователя не существует
function GetCurUser($Db, $Created, $DebugLevel, &$UserIdOut, &$UserNameOut, &$UserTypOut) {
	global $DEBUG_ERROR;

	// ---- Consts ----

	$AdminUserName = 'admin';
	$RootUserName = 'root';

	// ---- Main ----

	if (!isset($_SESSION['usr_login']))
		return false;

	$UserName = $_SESSION['usr_login'];

	if (!GetUserIdByName2($Db, $UserName, $DebugLevel, $UserId, $UserTyp)) {
		if (!$Created) return false;
		// Добавляем пользователя
		if ($UserName == $RootUserName) {
			$UserTyp = 1; // Полные права
		} else if ($UserName == $AdminUserName) {
			$UserTyp = 2; // Права администратора
		} else {
			$UserTyp = 3; // Права на запись
		}
		if (!InsertUser($Db, $UserName, $UserTyp, $DebugLevel, $UserId)) {
			PrintError2("Ошибка при добавлении пользователя. UserName='".$UserName."'", $DebugLevel);
			return false;
		}
		if ($UserId <= 0) {
			PrintError2("Не удалось добавить пользователя. UserName='".$UserName."'", $DebugLevel);
			return false;
		}
	}

	$UserIdOut = $UserId;
	$UserNameOut = $UserName;
	$UserTypOut = $UserTyp;
	return true;
}
