<?php

/************************************
Entry point of the project.
To be run from the command line.
************************************/

use JobMangement\Database\DatabaseConnection;
use JobMangement\Database\JobRepository;
use JobMangement\Lists\JobsLister;

include_once(__DIR__ . '/utils/utils.php');
include_once(__DIR__ . '/config/config.php');
include_once(__DIR__ . '/database/DatabaseConnection.php');
include_once(__DIR__ . '/database/JobRepository.php');
include_once(__DIR__ . '/lists/JobsLister.php');

include_once(__DIR__ . '/importers/FileImporter.php');
include_once(__DIR__ . '/importers/ImportJobteaser/ImportJobteaser.php');
include_once(__DIR__ . '/importers/ImportRegionsJob/ImportRegionsJob.php');

include_once(__DIR__ . '/entity/Job.php');

printStartingMessage();

/* database connection */
$dbConnection = new DatabaseConnection(SQL_HOST, SQL_USER, SQL_PWD, SQL_DB);

/* JobRepository connection */
$jobRepository = new JobRepository($dbConnection);
/* import jobs from resources */
$jobsImporter = new JobsImporter($jobRepository, getFilesFilterByExtensions(RESSOURCES_DIR, ['xml', 'json'])); //For all permitted extensions, you can use getFilesByExtension(RESSOURCES_DIR) without the second parameter."

$count = $jobsImporter->importJobs();

printImportedJobCount($count);

/* get list jobs */
$jobs = (new JobsLister($jobRepository))->listJobs();

printJobsInfo($jobs);

printImportedJobCount($count);

printTerminatingMessage();
