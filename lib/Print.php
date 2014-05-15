<?php

// Uses: <link rel="stylesheet" type="text/css" href="../css/main.css"/>

/**
 * Выводит комментарий.
 * $message - сообщение об ошибке
 */
function PrintComment($message) {
	echo("<div class=\"logComment\">{$message}</div>");
}

/**
 * Выводит сообщение об ошибке.
 * $message - сообщение об ошибке
 */
function PrintError($message) {
	echo("<div class=\"logError\">{$message}</div>");
}

/**
 * Выводит сообщение с информацией.
 * $message - сообщение
 */
function PrintInfo($message) {
	echo("<div class=\"logInfo\">{$message}</div>");
}

/**
 * Выводит сообщение с предупреждением.
 * $message - сообщение
 */
function PrintWarning($message) {
	echo("<div class=\"logWarning\">{$message}</div>");
}
