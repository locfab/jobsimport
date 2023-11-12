<?php

/************************************
Entry point of the project.
To be run from the command line.
************************************/

include_once(__DIR__.'/utils.php');
include_once(__DIR__.'/config.php');


printMessage("Starting...");


/* import jobs from regionsjob.xml */
$jobsImporter = new JobsImporter(SQL_HOST, SQL_USER, SQL_PWD, SQL_DB, RESSOURCES_DIR . 'regionsjob.xml');
$count = $jobsImporter->importJobs();

printMessage("> {count} jobs imported.", ['{count}' => $count]);


/* list jobs */
$jobsLister = new JobsLister(SQL_HOST, SQL_USER, SQL_PWD, SQL_DB);
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
