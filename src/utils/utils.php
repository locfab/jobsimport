<?php

function __autoload(string $classname): void
{
    include_once(__DIR__ . '/../' . $classname . '.php');
}

function printMessage(string $message, array $messageParamaters = []): void
{
	echo strtr($message."\n", $messageParamaters);
}

/**
 * Retrieves an array of files with importJobteaser extensions from a directory.
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

/**
 * Display formatted job details.
 *
 * @param array[] $jobs An array of jobs to be displayed.
 */
function printJobsInfo(array $jobs): void {
    $totalCount = count($jobs);
    printMessage("> all jobs ({$totalCount}):");
    foreach ($jobs as $job) {
        $placeholders = [
            '{id}' => $job['id'],
            '{reference}' => $job['reference'],
            '{title}' => $job['title'],
            '{publication}' => $job['publication']
        ];
        printMessage(" {id}: {reference} - {title} - {publication}", $placeholders);
    }
}

/**
 * Display a message with the imported job count.
 *
 * @param int $count The count of imported jobs to be displayed.
 */
function printImportedJobCount(int $count): void {
    $placeholders = ['{count}' => $count];
    printMessage("> {count} jobs imported.", $placeholders);
}

/**
 * Display a starting message.
 */
function printStartingMessage(): void {
    printMessage("Starting...");
}

/**
 * Display a terminating message.
 */
function printTerminatingMessage(): void {
    printMessage("Terminating...");
}
