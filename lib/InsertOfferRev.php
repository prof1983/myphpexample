<?php

include_once('../lib/Globals.php');
include_once('../lib/Insert.php');
include_once('../lib/Print.php');
include_once('../lib/Print2.php');


// Добавляет новую ревизию
// deprecated
function InsertOfferRev2($Db, $OfferId, $NewOfferRev, $ClientId, $UserId, $DebugLevel, &$NewOfferRevId) {
	global $DEBUG_SQL;
	$Sql = "INSERT INTO `offer_revision` (`offer_id`, `revision`, `client_id`, `user_id`) VALUES (".$OfferId.", ".$NewOfferRev.", ".$ClientId.", ".$UserId.")";
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	if (!Insert($Db, 'offer_revision', $UserId, $Sql, $DebugLevel, $NewOfferRevId))
		return false;
	PrintComment2("Запись добавлена. offer_revision.id=".$NewOfferRevId, $DebugLevel);
	return true;
}

// Добавляет новую ревизию с параметром is_del=1
// $OfferId - Идентификатор заказа
// $NewOfferRev - Номер создаваемой ревизии (порядковый, предыдущий + 1)
// $ClientId - Идентификатор заказчика
// $ClientName - Текущее наименование клиента (нужно, т.к. в будущем наименование может поменяться)
// $UserId - Идентификатор пользователя
// $ProtOfferRevId - Идентификатор прототипа, который использовался создании этой ревизии (используется только при копировании)
function InsertOfferRev3($Db, $OfferId, $NewOfferRev, $ClientId, $ClientName, $UserId, $ProtOfferRevId, $IsDel, $DebugLevel, &$NewOfferRevId) {
	global $DEBUG_SQL;
	if ($IsDel != 0 && $IsDel != 1) {
		PrintError2('Ошибка вставки ревизии заказа: не правильно указан параметр IsDel. IsDel='.$IsDel, $DebugLevel);
		return false;
	}
	$ClientName_Sql = $Db->real_escape_string($ClientName);
	$Sql =
		"INSERT INTO `offer_revision` (`offer_id`, `revision`, `client_id`, `client_name`, `user_id`, `prot_offer_rev_id`, `is_del`)".
		" VALUES (".$OfferId.", ".$NewOfferRev.", ".$ClientId.", '".$ClientName_Sql."', ".$UserId.", ".$ProtOfferRevId.", ".$IsDel.")";
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	if (!Insert($Db, 'offer_revision', $UserId, $Sql, $DebugLevel, $NewOfferRevId))
		return false;
	PrintComment2("Запись добавлена. offer_revision.id=".$NewOfferRevId." (is_del=1)", $DebugLevel);
	return true;
}

// Добавляет новую ревизию с параметром is_del=1
// deprecated
function InsertOfferRev_Begin2($Db, $OfferId, $NewOfferRev, $ClientId, $UserId, $DebugLevel, &$NewOfferRevId) {
	return InsertOfferRev3($Db, $OfferId, $NewOfferRev, $ClientId, '', $UserId, 0, 1, $DebugLevel, $NewOfferRevId);
}

// Добавляет новую ревизию с параметром is_del=1
// $OfferId - Идентификатор заказа
// $NewOfferRev - Номер создаваемой ревизии (порядковый, предыдущий + 1)
// $ClientId - Идентификатор заказчика
// $ClientName - Текущее наименование клиента (нужно, т.к. в будущем наименование может поменяться)
// $UserId - Идентификатор пользователя
// $ProtOfferRevId - Идентификатор прототипа, который использовался создании этой ревизии (используется только при копировании)
function InsertOfferRev_Begin3($Db, $OfferId, $NewOfferRev, $ClientId, $ClientName, $UserId, $ProtOfferRevId, $DebugLevel, &$NewOfferRevId) {
	return InsertOfferRev3($Db, $OfferId, $NewOfferRev, $ClientId, $ClientName, $UserId, $ProtOfferRevId, 1, $DebugLevel, $NewOfferRevId);
}

// Меняет флаг is_del у созданной ревизии заказа
function InsertOfferRev_Ok($Db, $NewOfferRevId, $DebugLevel) {
	global $DEBUG_SQL;
	$Sql = "UPDATE `offer_revision` SET `is_del`=0 WHERE `id`=".$NewOfferRevId;
	if ($DebugLevel >= $DEBUG_SQL)
		PrintComment($Sql);
	$Res = $Db->query($Sql);
	if (!$Res) {
		PrintError2("Ошибка изменения записи в offer_revision (" . $Db->errno . ") " . $Db->error, $DebugLevel);
		return false;
	}
	PrintComment2("Запись добавлена. offer_revision.id=".$NewOfferRevId." (is_del=0)", $DebugLevel);
	return true;
}
