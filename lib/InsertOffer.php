<?php

include_once('../lib/Globals.php');
include_once('../lib/Insert.php');
include_once('../lib/Print.php');
include_once('../lib/Print2.php');


// Создает новый заказ (is_del=0)
function InsertOffer3($Db, $OfferCode, $ClientId, $ProjectId, $UserId, $DebugLevel, &$NewOfferId) {
	global $DEBUG_SQL;
	$OfferCode_Sql = $Db->real_escape_string($OfferCode);
	$Sql = "INSERT INTO `offer` (`code`, `client_id`, `project_id`, `is_del`) VALUES ('".$OfferCode_Sql."', ".$ClientId.", ".$ProjectId.", 0)";
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	if (!Insert($Db, 'offer', $UserId, $Sql, $DebugLevel, $NewOfferId))
		return false;
	PrintComment2("Запись добавлена. offer.id=".$NewOfferId, $DebugLevel);
	return true;
}

// Создает новый заказ (is_del=1)
function InsertOffer_Begin3($Db, $OfferCode, $ClientId, $ProjectId, $UserId, $DebugLevel, &$NewOfferId) {
	global $DEBUG_SQL;
	$OfferCode_Sql = $Db->real_escape_string($OfferCode);
	$Sql = "INSERT INTO `offer` (`code`, `client_id`, `project_id`, `is_del`) VALUES ('".$OfferCode_Sql."', ".$ClientId.", ".$ProjectId.", 1)";
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	if (!Insert($Db, 'offer', $UserId, $Sql, $DebugLevel, $NewOfferId))
		return false;
	PrintComment2("Запись добавлена. offer.id=".$NewOfferId." (is_del=1)", $DebugLevel);
	return true;
}

// Меняет флаг is_del у созданного заказа
function InsertOffer_Ok($Db, $NewOfferId, $DebugLevel) {
	global $DEBUG_SQL;
	$Sql = "UPDATE `offer` SET `is_del`=0 WHERE `id`=".$NewOfferId;
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	$Res = $Db->query($Sql);
	if (!$Res) {
		PrintError2('Ошибка изменения записи в offer ('.$Db->errno.') '.$Db->error.' Sql='.$Sql, $DebugLevel);
		return false;
	}
	PrintComment2("Запись добавлена. offer.id=".$NewOfferId." (is_del=0)", $DebugLevel);
	return true;
}
