<?php

/************************************
Entry point of the project.
To be run from the command line.
************************************/

include_once(__DIR__ . '/utils.php');
include_once(__DIR__ . '/config.php');
include_once(__DIR__ . '/DatabaseConnection.php');
include_once(__DIR__ . '/JobRepository.php');


printMessage("Starting...");

/* database connection */
$dbConnection = new DatabaseConnection(SQL_HOST, SQL_USER, SQL_PWD, SQL_DB);

/* JobRepository connection */
$jobRepository = new JobRepository($dbConnection);
/* import jobs from resources */
$jobsImporter = new JobsImporter($jobRepository, getFilesFilterByExtensions(RESSOURCES_DIR, ['xml', 'json'])); //For all permitted extensions, you can use getFilesByExtension(RESSOURCES_DIR) without the second parameter."

$count = $jobsImporter->importJobs();

printMessage("> {count} jobs imported.", ['{count}' => $count]);


/* list jobs */
$jobsLister = new JobsLister($jobRepository);
$jobs = $jobsLister->listJobs();


printMessage("> all jobs ({count}):", ['{count}' => count($jobs)]);
foreach ($jobs as $job) {
    printMessage(" {id}: {reference} - {title} - {publication}", [
    	'{id}' => $job['id'],
    	'{reference}' => $job['reference'],
    	'{title}' => $job['title'],
    	'{publication}' => $job['publication']
    ]);
}
printMessage("Terminating...");
