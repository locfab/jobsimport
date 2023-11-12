<?php

function __autoload(string $classname): void
{
    include_once(__DIR__ . '/' . $classname . '.php');
}

function printMessage(string $message, array $messageParamaters = []): void
{
	echo strtr($message."\n", $messageParamaters);
}

/**
 * Retrieves an array of files with specific extensions from a directory.
 *
 * @param string $directoryPath The path to the directory.
 * @param array $extensions An optional array of file extensions to filter by.
 * @return array An array of file paths matching the specified extensions.
 */
function getFilesFilterByExtensions(string $directoryPath, array $extensions = []): array {
    $matchingFiles = array();

    if (is_dir($directoryPath)) {
        $files = scandir($directoryPath);

        foreach ($files as $file) {
            $fullPath = $directoryPath . '/' . $file;

            if (is_file($fullPath)) {
                $fileExtension = pathinfo($file, PATHINFO_EXTENSION);
                if (empty($extensions) || in_array($fileExtension, $extensions)) {
                    $matchingFiles[] = $fullPath;
                }
            }
        }
    }
    return $matchingFiles;
};