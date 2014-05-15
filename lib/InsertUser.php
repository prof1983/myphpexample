<?php

include_once('../lib/Globals.php');
include_once('../lib/Print.php');
include_once('../lib/Print2.php');


function InsertUser($Db, $UserName, $UserTyp, $DebugLevel, &$NewUserId) {
	global $DEBUG_SQL;
	$UserName_Sql = $Db->real_escape_string($UserName);
	$Sql = "INSERT INTO `user` (`name`, `typ`) VALUES ('".$UserName_Sql."', ".$UserTyp.")";
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	$Res = $Db->query($Sql);
	if (!$Res)
		return false;
	$NewUserId = $Db->insert_id;
	PrintComment2("Пользователь добавлен. user.id=".$NewUserId." UserName='".$UserName."' UserTyp=".$UserTyp, $DebugLevel);
	return true;
}
