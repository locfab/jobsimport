<?php

function __autoload(string $classname): void
{
    include_once(__DIR__ . '/' . $classname . '.php');
}

function printMessage(string $message, array $messageParamaters = []): void
{
	echo strtr($message."\n", $messageParamaters);
}
